<?php

namespace Okotieno\Procurement\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\Procurement\Models\ProcurementItemsCategory;
use Okotieno\Procurement\Models\ProcurementVendor;
use Tests\TestCase;


class ProcurementVendorTest extends TestCase
{
  use WithFaker;
  use DatabaseTransactions;

  private $procurementVendor;


  protected function setUp(): void
  {
    parent::setUp();
    $this->procurementVendor = ProcurementVendor::factory()->make()->toArray();
    $this->procurementVendor['procurement_items_categories'] = ProcurementItemsCategory::factory()
      ->count(5)->create()->pluck('id')->toArray();
  }

  /**
   * GET /api/procurements/vendors
   * @group procurement
   * @group procurement-vendor
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_procurement_vendor()
  {
    $this->getJson('/api/procurements/vendors')
      ->assertStatus(401);
  }

  /**
   * GET /api/procurements/vendors
   * @group procurement
   * @group procurement-vendor
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_procurement_vendor()
  {
    ProcurementVendor::factory()->create();
    $this->actingAs($this->user, 'api')
      ->getJson('/api/procurements/vendors')
      ->assertStatus(200)
      ->assertJsonStructure([['id', 'name', 'physical_address']])
    ;
  }

  /**
   * POST /api/procurements/vendors
   * @group procurement
   * @group procurement-vendor
   * @group post-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_create_procurement_vendor()
  {
    $this->postJson('/api/procurements/vendors', $this->procurementVendor)
      ->assertStatus(401);

  }

  /**
   * POST /api/procurements/vendors
   * @group procurement
   * @group procurement-vendor
   * @group post-request
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_create_procurement_vendor()
  {

    $this->actingAs($this->user, 'api')->postJson('/api/procurements/vendors', $this->procurementVendor)
      ->assertStatus(403);
  }

  /**
   * POST /api/procurements/vendors
   * @group procurement
   * @group procurement-vendor
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_create_procurement_vendor()
  {
    Permission::factory()->state(['name' => 'create procurement vendor'])->create();
    $this->user->givePermissionTo('create procurement vendor');
    $response = $this->actingAs($this->user, 'api')->postJson('/api/procurements/vendors', $this->procurementVendor);
    $response->assertStatus(201);
  }

  /**
   * POST /api/procurements/vendors
   * @group procurement
   * @group procurement-vendor
   * @group post-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided()
  {
    $this->procurementVendor['name'] = '';
    Permission::factory()->state(['name' => 'create procurement vendor'])->create();
    $this->user->givePermissionTo('create procurement vendor');
    $this->actingAs($this->user, 'api')->postJson('/api/procurements/vendors', $this->procurementVendor)
      ->assertStatus(422);
  }

  /**
   * POST /api/procurements/vendors
   * @group procurement
   * @group procurement-vendor
   * @group post-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_procurement_items_categories_not_provided()
  {
    $this->procurementVendor['procurement_items_categories'] = '';
    Permission::factory()->state(['name' => 'create procurement vendor'])->create();
    $this->user->givePermissionTo('create procurement vendor');
    $this->actingAs($this->user, 'api')->postJson('/api/procurements/vendors', $this->procurementVendor)
      ->assertStatus(422);
  }


  /**
   * POST /api/procurements/vendors
   * @group procurement
   * @group procurement-vendor
   * @test
   * @group post-request
   * @return void
   */
  public function procurement_vendor_should_exist_after_successful_call()
  {
    Permission::factory()->state(['name' => 'create procurement vendor'])->create();
    $this->user->givePermissionTo('create procurement vendor');
    $this->actingAs($this->user, 'api')->postJson('/api/procurements/vendors', $this->procurementVendor)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
    $procurementVendor = ProcurementVendor::where('name', $this->procurementVendor['name'])
      ->where('name', $this->procurementVendor['name'])->first();
    $this->assertNotNull($procurementVendor);
  }


  /**
   * PATCH /api/procurements/vendors/{id}
   * @group procurement
   * @group procurement-vendor
   * @group patch-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_update_procurement_vendor()
  {
    $procurementVendor = ProcurementVendor::factory()->create();
    $procurementVendorUpdate = ProcurementVendor::factory()->make()->toArray();
    $res = $this->patchJson('/api/procurements/vendors/' . $procurementVendor->id, $procurementVendorUpdate);
    $res->assertStatus(401);

  }

  /**
   * PATCH /api/procurements/vendors/{id}
   * @group procurement
   * @group procurement-vendor
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_update_procurement_vendor()
  {
    $procurementVendor = ProcurementVendor::factory()->create();

    $this->actingAs($this->user, 'api')
      ->patchJson('/api/procurements/vendors/' . $procurementVendor->id, $this->procurementVendor)
      ->assertStatus(403);
  }

  /**
   * PATCH /api/procurements/vendors/{id}
   * @group procurement
   * @group procurement-vendor
   * @group patch-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_update_procurement_vendor()
  {
    Permission::factory()->state(['name' => 'update procurement vendor'])->create();
    $this->user->givePermissionTo('update procurement vendor');

    $procurementVendor = ProcurementVendor::factory()->create();
    $response = $this->actingAs($this->user, 'api')
      ->patchJson('/api/procurements/vendors/' . $procurementVendor->id,  $this->procurementVendor);
    $response->assertStatus(200);
  }

  /**
   * PATCH /api/procurements/vendors/{id}
   * @group procurement
   * @group procurement-vendor
   * @group patch-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided_on_update()
  {
    Permission::factory()->state(['name' => 'update procurement vendor'])->create();
    $this->user->givePermissionTo('update procurement vendor');
    $procurementVendor = ProcurementVendor::factory()->create();
    $procurementVendorUpdate = ProcurementVendor::factory()->state(['name' => ''])->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/procurements/vendors/' . $procurementVendor->id, $procurementVendorUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /api/procurements/vendors/{id}
   * @group procurement
   * @group procurement-vendor
   * @group patch-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_procurement_items_categories_not_provided_on_update()
  {
    Permission::factory()->state(['name' => 'update procurement vendor'])->create();
    $this->user->givePermissionTo('update procurement vendor');
    $procurementVendor = ProcurementVendor::factory()->create();
    $procurementVendorUpdate = ProcurementVendor::factory()->state(['procurement_items_categories' => ''])->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/procurements/vendors/' . $procurementVendor->id, $procurementVendorUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /api/procurements/vendors/{id}
   * @group procurement
   * @group procurement-vendor
   * @test
   * @group patch-request
   * @return void
   */
  public function procurement_vendor_should_be_updated_after_successful_call()
  {
    Permission::factory()->state(['name' => 'update procurement vendor'])->create();
    $this->user->givePermissionTo('update procurement vendor');
    $procurementVendor = ProcurementVendor::factory()->create();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/procurements/vendors/' . $procurementVendor->id, $this->procurementVendor)
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
  }

  /**
   * DELETE /api/procurements/vendors/{id}
   * @group procurement
   * @group procurement-vendor
   * @group delete-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_delete_procurement_vendor()
  {
    $procurementVendor = ProcurementVendor::factory()->create();
    $this->deleteJson('/api/procurements/vendors/' . $procurementVendor->id)
      ->assertStatus(401);

  }

  /**
   * DELETE /api/procurements/vendors/{id}
   * @group procurement
   * @group procurement-vendor
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_delete_procurement_vendor()
  {
    $procurementVendor = ProcurementVendor::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/procurements/vendors/' . $procurementVendor->id)
      ->assertStatus(403);
  }

  /**
   * DELETE /api/procurements/vendors/{id}
   * @group procurement
   * @group procurement-vendor
   * @group delete-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_delete_procurement_vendor()
  {
    Permission::factory()->state(['name' => 'delete procurement vendor'])->create();
    $this->user->givePermissionTo('delete procurement vendor');
    $procurementVendor = ProcurementVendor::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/procurements/vendors/' . $procurementVendor->id)
      ->assertStatus(200);
  }

  /**
   * DELETE /api/procurements/vendors/{id}
   * @group procurement
   * @group procurement-vendor
   * @test
   * @group delete-request
   * @return void
   */
  public function procurement_vendor_should_be_deleted_after_successful_call()
  {
    Permission::factory()->state(['name' => 'delete procurement vendor'])->create();
    $this->user->givePermissionTo('delete procurement vendor');
    $procurementVendor = ProcurementVendor::factory()->create();
    $res = $this->actingAs($this->user, 'api')
      ->deleteJson('/api/procurements/vendors/' . $procurementVendor->id);
    $res->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
    $this->assertNull(ProcurementVendor::find($procurementVendor->id));
  }
}



