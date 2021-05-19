<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Route;

class AuthServiceProvider extends ServiceProvider
{
  /**
   * The policy mappings for the application.
   *
   * @var array
   */
  protected $policies = [
    // 'App\Model' => 'App\Policies\ModelPolicy',
  ];

  /**
   * Register any authentication / authorization services.
   *
   * @return void
   */
  public function boot()
  {
    $this->registerPolicies();

    Gate::before(function ($user, $ability) {
      return $user->hasRole('super admin') ? true : null;
    });

    Passport::routes(null, ['prefix' => 'api/oauth']);

    Route::post('/api/oauth/token', [
      'uses' => '\App\Http\Controllers\Auth\AuthController@login',
      'as' => 'passport.token',
      'middleware' => 'throttle',
    ]);


  }
}
