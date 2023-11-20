<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Course;


class CheckCourseEnrollment
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
        $user = auth()->user();

        if ($courseSlug) {
            $course = Course::where('slug', $courseSlug)->firstOrFail();
            $courseId = $course->id;
        }

        if (($courseId && $user->subscriptions()->whereHas('course', function ($query) use ($courseId) {
            $query->where('id', $courseId);
        })->exists()) || $course->user_id == $user->id || $user->isAdmin()) {
            return $next($request);
        }

        return redirect()->back();
    }
}
