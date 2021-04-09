<?php

namespace Okotieno\SchoolStreams\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Okotieno\SchoolStreams\Database\Factories\StreamFactory;

class Stream extends Model
{
    use SoftDeletes, HasFactory;
    protected static function newFactory()
    {
      return StreamFactory::new();
    }

  protected $fillable = ['name', 'abbreviation'];
}
