<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordsReset extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'recover_token',
    'valid'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
