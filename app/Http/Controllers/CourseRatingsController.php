<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseRating;

class CourseRatingsController extends Controller
{
    public function rateCourse(Request $request, $courseSlug)
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        
        // Verificar se o usuário está inscrito no curso
        if (!$course->isUserSubscribed(auth()->user())) {
            return redirect()->back()->with('error', 'Você deve estar inscrito no curso para avaliá-lo.');
        }

        // Verificar se o usuário já classificou o curso
        if ($course->hasUserRated(auth()->user())) {
            return redirect()->back()->with('error', 'Você já classificou este curso.');
        }
        
        // Validar os dados recebidos do formulário
        $validatedData = $request->validate([
            'score' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $rating = new CourseRating();
        $rating->rating = $validatedData['score'];
        $rating->user_id = auth()->user()->id;
        $rating->course_id = $course->id;

        if (array_key_exists('comment', $validatedData)) {
            $rating->comment = $validatedData['comment'];
        }

        $rating->save();

        $courseRatings = CourseRating::where('course_id', $course->id)->get();
        $averageRating = $courseRatings->avg('rating');
        
        $course->average_rating = $averageRating;
        $course->save();

        return redirect()->back()->with('success', 'Avaliação realizada com sucesso.');
    }
}
