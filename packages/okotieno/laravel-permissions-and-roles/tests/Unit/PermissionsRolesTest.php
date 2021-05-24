<?php


namespace Okotieno\PermissionsAndRoles\Tests\Unit;


use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Okotieno\LMS\Models\LibraryUser;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\PermissionsAndRoles\Models\Role;
use Tests\TestCase;

class PermissionsRolesTest extends TestCase
{
  use WithFaker;
  use DatabaseTransactions;


  protected function setUp(): void
  {
    parent::setUp();
  }


}

