<?php

namespace Okotieno\PermissionsAndRoles\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Okotieno\PermissionsAndRoles\Database\Factories\RoleFactory;

class Role extends \Spatie\Permission\Models\Role
{
  use HasFactory;
  protected static function newFactory()
  {
    return RoleFactory::new();
  }

  public static function scopeStaffs(Builder $query): Builder
  {
    return $query->where('is_staff', true);
  }
}
