<?php

namespace Okotieno\SchoolCurriculum\Models;

use App\Traits\HasActiveProperty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Okotieno\SchoolCurriculum\Database\Factories\UnitCategoryFactory;
use Okotieno\SchoolCurriculum\Requests\CreateUnitCategoryRequest;

class UnitCategory extends Model
{
    protected $hidden = ['deleted_at'];
    use HasActiveProperty, softDeletes, HasFactory;
    public $timestamps = false;
    protected $fillable = ['name', 'active', 'description'];

    protected static function newFactory()
    {
      return UnitCategoryFactory::new();
    }

  public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public static function createCategory(CreateUnitCategoryRequest $request)
    {
        $unitCategory = UnitCategory::create([
            'name' => $request->name,
            'active' => $request->active,
            'description' => $request->description
        ]);
        if ($request->units) {
            foreach ($request->units as $key => $unitRequest) {
                $unit = $unitCategory->units()->create([
                    'name' => $unitRequest['name'],
                    'abbreviation' => $unitRequest['abbreviation'],
                    'active' => $unitRequest['active'],
                ]);
                if (key_exists('subjectLevels', $unitRequest)) {
                    foreach ($unitRequest['subjectLevels'] as $subjectLevel) {
                        $unit->unitLevels()->create([
                            'name' => $subjectLevel['name']
                        ]);
                    }
                }
                $unitCategory = UnitCategory::find($unitCategory->id);
                $unitCategory->units[$key]->unitLevels;
            }
            $unitCategory->units;

        }
        return $unitCategory;
    }
}
