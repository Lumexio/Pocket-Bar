<?php

use Illuminate\Support\Facades\Auth;
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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('barra.{id}', function ($user, $id) {
    return (Auth::check()) and (Auth::user()->rol_id == 5);
});

Broadcast::channel('mesero.{id}', function ($user, $id) {
    return (Auth::check()) and (in_array(Auth::user()->rol_id, [4]));
});

Broadcast::channel('tickets.{id}', function ($user, $id) {
    return (Auth::check()) and (Auth::user()->rol_id == 5 or Auth::user()->rol_id == 4 or Auth::user()->rol_id == 3);
});
