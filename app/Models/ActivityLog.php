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
    // fungsi helper
    public static function record($action,$desc = null)
    {
        $user = auth()->user();
        self::create([
            'user_id' => $user->id,
            'action' => $action,
            'description' => $desc
        ]);
    }
}
