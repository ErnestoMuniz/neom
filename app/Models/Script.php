<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Script extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'protocol',
        'port',
        'steps',
        'script_user_id',
        'vendor'
    ];

    public function scriptUser() {
        return $this->hasMany(ScriptUser::class);
    }
}
