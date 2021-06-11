<?php

namespace Okotieno\SchoolInfrastructure\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use softDeletes;
    protected $fillable = [
        'name'
    ];

}
