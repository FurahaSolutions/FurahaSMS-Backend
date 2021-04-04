<?php

namespace Okotieno\AcademicYear\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Okotieno\AcademicYear\Database\Factories\HolidayFactory;

class Holiday extends Model
{
  protected $fillable = ['name', 'occurs_on', 'confirmation_variance'];
  use HasFactory, SoftDeletes;

  protected static function newFactory()
  {
    return HolidayFactory::new();
  }

}
