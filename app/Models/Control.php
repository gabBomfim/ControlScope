<?php

namespace App\Models;

use App\Enums\ControlStatus;
use Illuminate\Database\Eloquent\Model;

class Control extends Model
{
    protected $fillable = ['code', 'function', 'category', 'title', 'description', 'recommendation', 'status'];

    protected function casts(): array
    {
        return ['status' => ControlStatus::class];
    }
}
