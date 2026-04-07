<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // fungsi heper
    public static function log($action, $desc    = null)
    {
        self::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $desc
        ]);
    }
}
