<?php

namespace Okotieno\Religion;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;

class ReligionServiceProvider extends ServiceProvider
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
    $this->loadViewsFrom(__DIR__ . '/views', 'Gender');
    $this->publishes([
      __DIR__ . '/views' => base_path('resources/views/okotieno/religions'),
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
    $this->app->make('Okotieno\\Religion\\Controllers\\ReligionController');
  }
}
