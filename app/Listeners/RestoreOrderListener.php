<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\RestoreOrderEvent;

class RestoreOrderListener
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
    public function handle(object $event)
    {
        $model = $event->model;

         // Recalcula a ordem dos registros relacionados
        if ($model->module) {
            $module = $model->module;
            $this->recalculateOrder($module->lessons());
        } elseif ($model->course) {
            $course = $model->course;
            $this->recalculateOrder($course->modules());
        }
    }

    private function recalculateOrder($query)
    {
        $records = $query->orderBy('order')->get();
        $order = 1;
        foreach ($records as $record) {
            $record->order = $order;
            $record->save();
            $order++;
        }
    }
}
