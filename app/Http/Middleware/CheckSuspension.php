<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSuspension
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
    
            if ($user->suspended) {
                $suspensionEndTime = $user->suspension_until;
                if ($suspensionEndTime === null){
                    $message = 'Sua conta foi banida permanentemente devido a violações repetidas de nossas políticas. Agradecemos sua compreensão.';
                }
                else{
                    $remainingTime = now()->diffForHumans($suspensionEndTime, true);
                    $message = 'Você está suspenso. Você será liberado para utilizar a plataforma daqui ' . $remainingTime;
                }
                return redirect()->route('home')->with('uAreSuspended', $message);
            }
        }

        return $next($request);
    }
}
