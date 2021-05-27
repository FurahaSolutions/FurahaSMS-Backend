<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordToken extends Model
{
  use HasFactory;
  protected $fillable = ['token', 'expires_at'];

  public static function getUserForToken($token)
  {
    $tokenValue = self::where('token', $token)->first();
    if ($tokenValue == null) {
      return null;
    }
    return $tokenValue->user;
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
