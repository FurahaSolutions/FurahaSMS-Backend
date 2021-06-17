<?php

namespace Okotieno\NamePrefix\Traits;


use Okotieno\NamePrefix\Models\NamePrefix;

trait hasNamePrefix
{
  public function namePrefix()
  {
    return $this->belongsto(NamePrefix::class);
  }
}
