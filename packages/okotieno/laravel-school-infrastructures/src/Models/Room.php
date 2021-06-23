<?php

namespace Okotieno\SchoolInfrastructure\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Okotieno\SchoolInfrastructure\Database\Factories\RoomFactory;

class Room extends Model
{
  use softDeletes, HasFactory;

  protected $fillable = ['name', 'abbreviation', 'width', 'length', 'students_capacity'];

  protected static function newFactory()
  {
    return RoomFactory::new();
  }

}
