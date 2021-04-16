<?php

namespace Okotieno\AcademicYear\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Okotieno\AcademicYear\Database\Factories\ArchivableItemFactory;
use Okotieno\AcademicYear\Traits\HasAcademicYear;
use Okotieno\PermissionsAndRoles\Models\Permission;

/**
 * @property mixed name
 */
class ArchivableItem extends Model
{
  protected $appends = ['permissionName', 'openPermissionName'];
  protected $hidden = ['permission', 'open_permission'];
  use HasFactory, HasAcademicYear;

  protected static function newFactory()
  {
    return ArchivableItemFactory::new();
  }

  public function permission()
  {
    return $this->belongsTo(Permission::class);
  }

  public function openPermission()
  {
    return $this->belongsTo(Permission::class, 'reopen_permission_id');
  }

  public function getPermissionNameAttribute()
  {
    return $this->permission->name;
  }

  public function getOpenPermissionNameAttribute()
  {
    return $this->openPermission->name;
  }

  public function scopeAdmissions($query)
  {
    return $query->where('slug', 'admissions');
  }

  public function scopeFinancialPlans($query)
  {
    return $query->where('slug', 'financial-plan');
  }

  public function scopeSubjectCreations($query)
  {
    return $query->where('slug', 'subject-creation');
  }

  public function scopeScoreAmendments($query)
  {
    return $query->where('slug', 'score-amendment');
  }

  public function scopeTimetableAmendments($query)
  {
    return $query->where('slug', 'timetable-amendment');
  }
}
