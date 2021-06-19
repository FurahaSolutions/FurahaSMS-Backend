<?php

namespace App\Traits;

use App\Models\PasswordToken;

trait HasPasswordToken
{
  public function passwordToken()
  {
    return $this->hasMany(PasswordToken::class);
  }
}
