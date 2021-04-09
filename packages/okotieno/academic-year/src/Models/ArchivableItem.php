<?php

namespace Okotieno\AcademicYear\Models;

use Illuminate\Database\Eloquent\Model;
use Okotieno\PermissionsAndRoles\Models\Permission;

/**
 * @property mixed name
 */
class ArchivableItem extends Model
{
  public function permission() {
    return $this->belongsTo(Permission::class);
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
