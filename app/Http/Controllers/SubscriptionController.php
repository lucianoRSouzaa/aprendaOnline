<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    public function subscribe($courseSlug){
        $course = Course::where('slug', $courseSlug)->firstOrFail();

        $user = auth()->user();

        // Verificar se o usuário que quer se inscrever é o criador do curso
        if ($user->id === $course->user_id) {
            return redirect()->route('courses.creator')->with('errorInscri', 'Você não pode se inscrever no seu próprio curso.');
        }

        // verificando se o usuário já está inscrito no curso
        $isSubscribed = $user->subscriptions()->where('course_id', $course->id)->exists();

        if (!$isSubscribed) {
            $user->subscriptions()->create(['course_id' => $course->id]);
        }

        return redirect()->route('courses.viewer');
    }

    public function unsubscribe($courseSlug) 
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $user = auth()->user();
    
        // Verificar se o usuário está inscrito no curso
        $isSubscribed = $user->subscriptions()->where('course_id', $course->id)->exists();
    
        if ($isSubscribed) {
            $user->subscriptions()->where('course_id', $course->id)->delete();
        }
    
        return redirect()->route('courses.viewer');
    }
    
}
