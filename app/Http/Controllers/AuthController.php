<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

use App\Http\Controllers\EmailController;

use App\Events\LoginSuccessful;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            // personalizando mensagens de erros
            'email.email' => 'O email não é válido',
        ]);

        // Verifique se o email existe no banco de dados
        $userWithEmail = User::where('email', $request->input('email'))->first();

        if ($userWithEmail && $userWithEmail->suspended) {
            $suspensionEndTime = $userWithEmail->suspension_until;
        
            if ($suspensionEndTime === null) {
                // Usuário banido permanentemente
                $message = 'Sua conta foi banida permanentemente devido a violações repetidas de nossas políticas. Agradecemos sua compreensão.';
                
                session()->flash('uAreSuspended', $message);
                return redirect()->back();
            } elseif ($suspensionEndTime < now()) {
                // Se a suspensão expirou, então a removemos
                $userWithEmail->suspended = false;
                $userWithEmail->save();
            } 
            else {
                // Se o usuário ainda está suspenso, calculamos o tempo restante
                $remainingTime = now()->diffForHumans($suspensionEndTime, true);
                $message = 'Você está suspenso. Você será liberado para utilizar a plataforma daqui ' . $remainingTime;

                session()->flash('uAreSuspended', $message);
                return redirect()->back();
            }
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            event(new LoginSuccessful(Auth::user()->id));

            if (auth()->user()->isCreator()) {
                return redirect()->intended(route('courses.creator'));
    
            } elseif (auth()->user()->isViewer()) {
                
                return redirect()->intended(route('courses.viewer'));
            }
        }

        if ($userWithEmail) {
            session()->flash('forgotPasswordText', 'Esqueceu sua senha?');
        }

        return redirect()->back()->withInput()->withErrors([
            'erroLogin' => 'E-mail ou senha errado(s)',
            'modal' => 'login',
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ], [
            // personalizando mensagens de erros
            'email.email' => 'O email não é válido',
            'email.unique' => 'Este email já está em uso',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres',
            'password.confirmed' => 'A confirmação de senha não corresponde',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = [
                'modal' => 'cadastro',
            ];
        
            // Verifica se há erro de email único
            if ($errors->has('email')) {
                $errorMessage = $errors->first('email');
                if ($errorMessage === 'Este email já está em uso') {
                    $errorMessages['email'] = $errorMessage;
                }
            }

            // Verifica se há erro de senha com no mínimo 6 caracteres
            if ($errors->has('password')) {
                $errorMessage = $errors->first('password');
                if ($errorMessage === 'A senha deve ter pelo menos 6 caracteres') {
                    $errorMessages['password'] = $errorMessage;
                }
            }

            // Verifica se há erro de confirmação de senha
            if ($errors->has('password')) {
                $errorMessage = $errors->first('password');
                if ($errorMessage === 'A confirmação de senha não corresponde') {
                    $errorMessages['password_confirmation'] = $errorMessage;
                }
            }
        
            return redirect()->back()->withInput([
                'cad_name' => $request->input('name'),
                'cad_email' => $request->input('email'),
                'cad_password' => $request->input('password'),
            ])->withErrors($errorMessages);
        }

        if ($request->has('termo-criador-input')) {
            $role = 'creator';
        } else {
            $role = 'viewer';
        }
        
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $role,
        ]);

        Auth::login($user);

        $emailController = new EmailController();

        $emailController->sendVerificationEmail($user);

        return redirect()->route('home')->with('verification', 'Um link de verificação foi enviado para o seu email! Clique em confirmar para ter acesso total a plataforma');

        // if ($role == 'creator') {
        //     return redirect()->intended(route('courses.creator'));
        // }
        // return redirect()->intended(route('courses.viewer'));
    }



    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
