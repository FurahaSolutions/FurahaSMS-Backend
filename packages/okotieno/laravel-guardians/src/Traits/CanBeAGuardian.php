<?php
/**
 * Created by IntelliJ IDEA.
 * User: oko
 * Date: 9/13/2019
 * Time: 10:13 PM
 */

namespace Okotieno\Guardians\Traits;


use Okotieno\Guardians\Models\Guardian;

trait CanBeAGuardian
{
  public function guardian()
  {
    return $this->hasOne(Guardian::class);
  }
}
