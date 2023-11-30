<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::routes(['middleware' => ['jwt']]);

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('message.{user_id}', function (User $user, $user_id) {
    // return true;
    return (int) $user->id === (int) $user_id;
});

Broadcast::channel('alert.{user_id}', function (User $user, $user_id) {
    return (int) $user->id === (int) $user_id;
});