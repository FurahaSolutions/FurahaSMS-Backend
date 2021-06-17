<?php


namespace Okotieno\Procurement\Models;



use Okotieno\Procurement\Database\Factories\ProcurementVendorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementVendor extends Model
{
  use HasFactory;
  protected static function newFactory()
  {
    return ProcurementVendorFactory::new();
  }

  protected $fillable = [
    'name',
    'description',
    'physical_address',
    'active'
  ];

  public function contacts()
  {
    return $this->hasMany(ProcurementVendorContact::class);
  }

  public function delivers()
  {
    return $this->belongsToMany(ProcurementItemsCategory::class);
  }
}
