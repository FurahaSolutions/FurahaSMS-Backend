<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ServerRunningTest extends TestCase
{

  /**
   * Test GET '/'
   *
   * @return void
   * @group app
   * @group get-request
   *
   */
  public function testRootRouteStatusOk()
  {
    $response = $this->get('/');
    $response->assertStatus(200);
  }
}


