<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Testing\Fluent\Concerns\Has;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function tools()
    {
        return $this->hasMany(Tool::class);
    }
}
