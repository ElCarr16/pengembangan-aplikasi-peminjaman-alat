<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tool;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class category extends Model
{
    use hasFactory;
    protected $guarded = [];
    public function tools()
    {
        return $this->hasMany(tool::class);
    }
}
