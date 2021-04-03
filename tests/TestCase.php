<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
  use WithFaker;
  use CreatesApplication;
  use DatabaseTransactions;
//  use DatabaseSetup;

  protected $user;

  protected function setUp(): void
  {
    parent::setUp();
//    $this->setupDatabase();
    $this->user = User::factory()->create();
  }
}
