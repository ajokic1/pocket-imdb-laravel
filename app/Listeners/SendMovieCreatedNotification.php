<?php

namespace App\Listeners;

use App\Events\MovieCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\MovieCreated as MovieCreatedEmail;

class SendMovieCreatedNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MovieCreated  $event
     * @return void
     */
    public function handle(MovieCreated $event)
    {
        Mail::to(config('app.admin_email'))->send(new MovieCreatedEmail($event->movie));
    }
}
