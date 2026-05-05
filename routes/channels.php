<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{id}', function (User $user, int $id) {
    // Log wela inna user ge ID eka channel ID ekata samana nam vitharayi permission denne
    return (int) $user->id === (int) $id;
});
