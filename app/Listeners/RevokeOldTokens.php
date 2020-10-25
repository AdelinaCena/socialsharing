<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RevokeOldTokens
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $userId = $event->userId;
        $newTokenId = $event->tokenId;
        $user = User::find($userId);

        $user->tokens()->where('id', '<>', $newTokenId)->get()->each(function($token){
          $token->delete();  
        });

    }
}
