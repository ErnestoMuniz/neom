<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class Token extends Model
{
  use HasFactory;

  protected $fillable = [
    'token',
    'user_id'
  ];

  public static function getToken(string $target)
  {
    $tokens = Token::all();
    foreach ($tokens as $token) {
      if (Hash::check($target, $token->token)) {
        return $token;
      }
    }
  }

  public static function checkPermission(Request $request, string $permission) {
    return in_array($permission, Token::getToken($request->header('Token'))->user->role->permissions->pluck('name')->toArray());
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
