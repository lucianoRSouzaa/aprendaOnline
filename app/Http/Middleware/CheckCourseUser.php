<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Course;
use App\Models\Module;

class CheckCourseUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Obtendo a slug do curso da rota 
        $courseSlug = $request->route('courseSlug');

        if ($courseSlug) {
            $course = Course::where('slug', $courseSlug)->firstOrFail();
        } else{
            $moduleSlug = $request->route('moduleSlug');
            $module = Module::where('slug', $moduleSlug)->firstOrFail();

            $course = $module->course;
        }
        

        if ($course && $course->user_id == auth()->user()->id) {
            return $next($request);
        }

        session()->flash('creator', 'Desculpe, parece que você está tentando acessar um curso que não é seu. Por favor, verifique se você selecionou o curso correto.');
        return redirect()->route('courses.creator');
    }
}
