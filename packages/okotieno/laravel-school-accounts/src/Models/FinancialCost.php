<?php

namespace Okotieno\SchoolAccounts\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Okotieno\SchoolAccounts\Database\Factories\FinancialCostFactory;

class FinancialCost extends Model
{
  use SoftDeletes, HasFactory;

  protected static function newFactory()
  {
    return FinancialCostFactory::new();
  }

  public $timestamps = false;
  protected $fillable = [
    'name',
  ];

  protected $appends = ['cost_items'];

  public function getCostItemsAttribute()
  {
    return $this->costItemsRelations;
  }

  public static function saveCosts($request)
  {

    foreach ($request->all() as $cost) {

      if (key_exists('id', $cost) && $savedCost = FinancialCost::find($cost['id'])) {
        if ($savedCost == null) {
          $savedCost = self::create([
            'name' => $cost['name']
          ]);
          foreach ($cost['costItems'] as $costItem) {
            $savedCost->costItemsRelations()->create([
              'name' => $costItem['name']
            ]);
          }
        } else {
          $savedCost->update(['name' => $cost['name']]);
          foreach ($cost['costItems'] as $costItem) {
            if (
              key_exists('id', $costItem) &&
              $savedCostItem = $savedCost->costItemsRelations()->find($costItem['id'])
            ) {
              if ($savedCostItem == null) {
                $savedCost->costItemsRelations()->create([
                  'name' => $costItem['name']
                ]);
              } else {
                $savedCostItem->update(['name' => $costItem['name']]);
              }
            } else {
              $savedCostItem = $savedCost->costItemsRelations()->create(['name' => $costItem['name']]);
            }

          }

          $toDeletes = array_diff(
            $savedCost->costItemsRelations->pluck('id')->toArray(),
            collect($cost['costItems'])->pluck('id')->toArray()
          );
          if (sizeof($toDeletes) > 0) {
            foreach ($toDeletes as $toDelete) {
              FinancialCostItem::find($toDelete)->delete();
            }
          }
        }
      } else {

        $savedCost = self::create([
          'name' => $cost['name']
        ]);

        foreach ($cost['costItems'] as $costItem) {
          $savedCost->costItemsRelations()->create([
            'name' => $costItem['name']
          ]);
        }
      }
    }
  }

  public function costItemsRelations()
  {
    return $this->hasMany(FinancialCostItem::class);
  }
}
