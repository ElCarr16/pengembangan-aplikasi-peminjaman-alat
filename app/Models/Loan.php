<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Tool;

class loan extends Model
{
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tool()
    {
        return $this->belongsTo(tool::class);
    }
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
