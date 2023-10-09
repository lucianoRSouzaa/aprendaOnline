<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;


class ForgotPasswordController extends Controller
{
    // mostra o modal pedindo o email para enviar a senha
    public function showForgotPasswordForm()
    {
        session()->flash('forgotPassword', 'Por favor, insira seu endereço de e-mail no campo abaixo. Enviaremos um link para redefinição de sua senha!');

        return redirect()->route('home');
    }

    // Processa a solicitação de recuperação de senha e envia o email
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email'),
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with('successVerification', 'Foi enviado um email para você, veja ele para alterar sua senha!')
                    : back()->withErrors(['error' => trans($status)]);
    }

    // Mostra modal com o formulário de redefinição de senha
    public function showResetPasswordForm($token)
    {
        session()->flash('token', $token);
        session()->flash('resetPassword', 'Insira sua nova senha');

        return redirect()->route('home');
    }

    // Processa a redefinição de senha
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('home')->with('successVerification', trans($status))
                    : back()->withErrors(['error' => trans($status)]);
    }
}
