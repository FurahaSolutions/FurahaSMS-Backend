<?php

namespace Okotieno\Teachers;

use Illuminate\Support\ServiceProvider;

class TeachersServiceProvider extends ServiceProvider
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
    $this->loadViewsFrom(__DIR__ . '/views', 'Teachers');
    $this->publishes([
      __DIR__ . '/views' => base_path('resources/views/okotieno/teachers'),
    ]);
  }

  /**
   * Register services.
   *
   * @return void
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   */
  public function register()
  {
    $this->app->make('Okotieno\\Teachers\\Controllers\\TeachersController');
  }
}
