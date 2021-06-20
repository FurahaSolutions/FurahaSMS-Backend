<?php

namespace Okotieno\LMS\Tests\Unit;


use Tests\TestCase;

class LibraryClassesTest extends TestCase
{
  /**
   * GET /api/library-classes
   * @test
   * @group library
   * @group library-classes
   */
  public function unauthenticated_users_cannot_retrieve_library_classes()
  {
    $this->getJson('api/library-classes')
      ->assertUnauthorized();
  }

  /**
   * GET /api/library-classes
   * @test
   * @group library
   * @group library-classes
   */
  public function authenticated_users_can_retrieve_library_classes()
  {
    $this->actingAs($this->user, 'api')->getJson('api/library-classes?classification=DDC')
      ->assertOk()
      ->assertJsonStructure([['id', 'class', 'name']]);
  }


}
