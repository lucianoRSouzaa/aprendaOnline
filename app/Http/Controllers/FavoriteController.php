<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggleFavorite($courseSlug)
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();

        // Verificar se o curso já está favoritado pelo usuário
        $isFavorite = auth()->user()->favorites()->where('course_id', $course->id)->exists();

        if ($isFavorite) {
            // Desfavoritar o curso
            $course->favorites()->detach(auth()->user()->id);
        } else {
            // Favoritar o curso
            $course->favorites()->syncWithoutDetaching([auth()->user()->id => ['created_at' => now(), 'updated_at' => now()]]);
        }

        return redirect()->route('courses.viewer');
    }
}

