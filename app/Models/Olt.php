<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Olt extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'ip',
    'vendor',
    'slots',
    'pons',
    'enabled',
    'username',
    'password',
    'unm',
    'model',
    'port'
  ];

  public function roles()
  {
    return $this->belongsToMany(Role::class);
  }
}
