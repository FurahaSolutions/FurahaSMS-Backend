<?php

namespace Okotieno\PermissionsAndRoles\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Okotieno\PermissionsAndRoles\Database\Factories\PermissionFactory;

class Permission extends \Spatie\Permission\Models\Permission
{
  use HasFactory;

  protected static function newFactory()
  {
    return PermissionFactory::new();
  }
}
