<?php

namespace Okotieno\Students;

use Illuminate\Support\ServiceProvider;

class StudentsServiceProvider extends ServiceProvider
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
    $this->loadViewsFrom(__DIR__ . '/views', 'Students');
    $this->publishes([
      __DIR__ . '/views' => base_path('resources/views/okotieno/students'),
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
    $this->app->make('Okotieno\\Students\\Controllers\\StudentsController');
  }
}
