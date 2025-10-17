<?php

namespace App\Traits;

use Illuminate\Support\Facades\Event;

trait TracksLastLogin
{
    public static function bootTracksLastLogin()
    {
        static::updated(function ($user) {
            if ($user->isDirty('remember_token')) {
                $user->last_login_at = now();
                $user->saveQuietly();
            }
        });
    }
} 