<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Events\LessonCreated;
use App\Events\LessonDeleted;
use App\Events\CourseDeleted;
use App\Events\RestoreOrderEvent;
use App\Listeners\UpdateTotalLessonsOnLessonCreated;
use App\Listeners\UpdateTotalLessonsOnLessonDeleted;
use App\Listeners\UpdateTotalLessonsOnCourseDeleted;
use App\Listeners\RestoreOrderListener;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        LessonCreated::class => [
            UpdateTotalLessonsOnLessonCreated::class,
        ],
        LessonDeleted::class => [
            UpdateTotalLessonsOnLessonDeleted::class,
        ],
        CourseDeleted::class => [
            UpdateTotalLessonsOnCourseDeleted::class,
        ],
        RestoreOrderEvent::class => [
            RestoreOrderListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
