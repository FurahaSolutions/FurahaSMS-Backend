<?php

namespace Okotieno\Phone;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;

class PhoneServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot()
  {
    $this->loadRoutesFrom(__DIR__ . '/routes.php');
    $this->loadMigrationsFrom(__DIR__ . '/migrations');
    $this->loadViewsFrom(__DIR__ . '/views', 'Phone');
    $this->publishes([
      __DIR__ . '/views' => base_path('resources/views/okotieno/phone'),
    ]);
  }

  /**
   * Register services.
   *
   * @return void
   * @throws BindingResolutionException
   */
  public function register()
  {

  }
}
