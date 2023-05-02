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
    'superuser',
    'superpass',
    'model',
    'port',
    'snmp',
    'community'
  ];

  protected $hidden = [
    'username',
    'password',
    'superuser',
    'superpass'
  ];

  public function roles()
  {
    return $this->belongsToMany(Role::class);
  }
}
