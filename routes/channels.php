<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->user_id === (int) $id;
});

Broadcast::channel('notification.{userId}', function ($user, $userId) {
    return (int) $user->user_id === (int) $userId;
});