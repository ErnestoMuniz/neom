<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScriptUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'username',
        'password'
    ];

    public function scripts() {
        return $this->hasMany(Script::class);
    }

    protected $hidden = [
        'password'
    ];
}
