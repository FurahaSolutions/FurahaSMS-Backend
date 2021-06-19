<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordToken extends Model
{
  use HasFactory;
  protected $fillable = ['token', 'expires_at'];

  public static function scopeWithToken($query, $token) {
    return $query->where('token', $token);
  }

  public static function getUserForToken($token)
  {
    $tokenValue = self::withToken($token)->first();
    if ($tokenValue == null) {
      return null;
    }
    return $tokenValue->user;
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function revoke() {
    $this->revoked = true;
    $this->save();
  }
}
