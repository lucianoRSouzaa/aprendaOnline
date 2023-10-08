<?php

namespace App\Listeners;

use App\Events\LessonDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateTotalLessonsOnLessonDeleted
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
    public function handle(LessonDeleted $event)
    {
        $course = $event->lesson->module->course;
        $course->updateTotalLessonsCount();
    }
}
