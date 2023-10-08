<?php

namespace App\Listeners;

use App\Events\LessonCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateTotalLessonsOnLessonCreated
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
    public function handle(LessonCreated $event)
    {
        $course = $event->lesson->module->course;
        $course->updateTotalLessonsCount();
    }
}
