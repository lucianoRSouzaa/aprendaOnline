<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Models\CourseView;
use App\Events\CourseViewed;


class CourseViewedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $user = $event->user;
        $course = $event->course;

        if (!$this->recentlyViewed($user, $course)) {
            CourseView::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
            ]);
            $course->increment('views');
        }
    }

    protected function recentlyViewed($user, $course)
    {
        return CourseView::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->exists();
    }
}
