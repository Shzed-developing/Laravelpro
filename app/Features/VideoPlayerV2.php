<?php

namespace App\Features;

use App\Models\User;
use Illuminate\Support\Lottery;

class VideoPlayerV2
{
    /**
     * Resolve the feature's initial value.
     */
    public function resolve(User $user): mixed
    {
        return Lottery::odds(1 / 4);
    }
}
