<?php

namespace App\Listeners;

use App\Events\PodcastProcessed;
use App\Models\User;

class SendPodcastEmailListener
{
    public function __construct()
    {
    }

    public function handle(PodcastProcessed $event): void
    {

        //create a dummy user in the database to test the event listener
        $user = $event->user;


        $newUser = new User([
            'name' => 'test',
            'email' => fake()->email,
            'password' => bcrypt('password')
        ]);
        $newUser->id = User::max('id') + 1;
        $newUser->save();



    }
}

















