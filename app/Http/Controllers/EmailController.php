<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\VerificationToken;

class EmailController extends Controller
{
    public function sendVerificationEmail(User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return redirect('/home')->with('warning', 'Seu email já foi verificado.');
        }

        $token = Str::random(32);
        $user->verificationTokens()->create(['token' => $token]);

        Mail::to($user->email)->send(new VerifyEmail($user, $token));
    }

    public function verifyEmail(Request $request, $token)
    {
        $verificationToken = VerificationToken::where('token', $token)->first();

        if (!$verificationToken) {
            return redirect('/login')->with('error', 'Link de verificação inválido ou expirado.');
        }

        $user = $verificationToken->user;
        $user->markEmailAsVerified();
        $verificationToken->delete();

        return redirect()->route('home')->with('successVerification', 'Seu email foi verificado com sucesso!');
    }
}
