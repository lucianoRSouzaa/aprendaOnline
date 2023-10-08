<?php

namespace App\Listeners;

use App\Events\CourseDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateTotalLessonsOnCourseDeleted
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
    public function handle(CourseDeleted $event)
    {
        $course = $event->course;
        $course->updateTotalLessonsCount();
    }
}
