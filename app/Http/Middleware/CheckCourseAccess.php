<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCourseAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->isAdmin() || auth()->user()->isCreator()) {
            return $next($request);
        }

        session()->flash('creator', 'Você precisa ser um criador de conteúdo para acessar esta página.');

        return redirect()->route('courses.viewer');
    }
}
