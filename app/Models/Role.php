<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  use HasFactory;

  protected $fillable = [
    'name'
  ];

  public function users()
  {
    return $this->hasMany(User::class);
  }

  public function permissions()
  {
    return $this->belongsToMany(Permission::class, 'role_has_permissions', 'role_id', 'permission_id');
  }

  public function olts()
  {
    return $this->belongsToMany(Olt::class);
  }
}
