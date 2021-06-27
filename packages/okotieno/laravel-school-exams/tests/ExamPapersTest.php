<?php

namespace Okotieno\SchoolExams\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\SchoolExams\Models\ExamInstruction;
use Okotieno\SchoolExams\Models\ExamPaper;
use Tests\TestCase;


class ExamPapersTest extends TestCase
{

  protected function setUp(): void
  {
    parent::setUp();
    $this->examPaper = ExamPaper::factory()->make()->toArray();
  }

  private array $examPaper;

  /**
   * GET /api/exam-papers/:exam-paper
   * @group exam-papers
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_exam_paper()
  {
    $examPaper = ExamPaper::factory()->create();
    $this->getJson("api/exam-papers/{$examPaper->id}")
      ->assertUnauthorized();

  }

  /**
   * GET /api/exam-papers/:exam-paper
   * @group exam-papers
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_exam_paper()
  {
    $examPaper = ExamPaper::factory()->create();
    $this->actingAs($this->user, 'api')->getJson("api/exam-papers/{$examPaper->id}")
      ->assertOk();

  }

  /**
   * GET /api/exam-papers/:exam-paper
   * @group exam-papers
   * @group get-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_exam_papers()
  {
    $this->getJson("api/exam-papers")
      ->assertUnauthorized();

  }

  /**
   * GET /api/exam-papers/:exam-paper
   * @group exam-papers
   * @group get-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_self_exam_papers()
  {
    ExamPaper::factory()->state(['created_by' => $this->user->id])->count(4)->create();
    $this->actingAs($this->user, 'api')->getJson('api/exam-papers?self=true&latest=10')
      ->assertOk();

  }

  /**
   * POST /api/exam-papers
   * @group exam-papers
   * @group post-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_create_exam_paper()
  {
    $this->postJson('/api/exam-papers', $this->examPaper)
      ->assertStatus(401);

  }

  /**
   * POST /api/exam-papers
   * @group exam-papers
   * @group post-request
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_create_exam_paper()
  {

    $this->actingAs($this->user, 'api')->postJson('/api/exam-papers', $this->examPaper)
      ->assertStatus(403);
  }

  /**
   * POST /api/exam-papers
   * @group exam-papers
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_create_exam_paper()
  {
    Permission::factory()->state(['name' => 'create exam paper'])->create();
    $this->user->givePermissionTo('create exam paper');
    $instructions = ExamInstruction::factory()->count(3)->make();
    $data = array_merge($this->examPaper, ['instructions' => $instructions]);
    $response = $this->actingAs($this->user, 'api')->postJson('/api/exam-papers', $data);
    $response->assertStatus(201);
  }

  /**
   * POST /api/exam-papers
   * @group exam-papers
   * @group post-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided()
  {
    $this->examPaper['name'] = '';
    Permission::factory()->state(['name' => 'create exam paper'])->create();
    $this->user->givePermissionTo('create exam paper');
    $this->actingAs($this->user, 'api')->postJson('/api/exam-papers', $this->examPaper)
      ->assertStatus(422);
  }



  /**
   * POST /api/exam-papers
   * @group exam-papers
   * @test
   * @group post-request
   * @return void
   */
  public function exam_paper_should_exist_after_successful_call()
  {
    Permission::factory()->state(['name' => 'create exam paper'])->create();
    $this->user->givePermissionTo('create exam paper');
    $this->actingAs($this->user, 'api')->postJson('/api/exam-papers', $this->examPaper)
      ->assertStatus(201)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
    $examPaper = ExamPaper::where('name', $this->examPaper['name'])
      ->where('name', $this->examPaper['name'])->first();
    $this->assertNotNull($examPaper);
  }

  /**
   * PATCH /api/exam-papers/{id}
   * @group exam-papers
   * @group patch-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_update_exam_paper()
  {
    $examPaper = ExamPaper::factory()->create();
    $examPaperUpdate = ExamPaper::factory()->make()->toArray();
    $res = $this->patchJson('/api/exam-papers/' . $examPaper->id, $examPaperUpdate);
    $res->assertStatus(401);

  }

  /**
   * PATCH /api/exam-papers/{id}
   * @group exam-papers
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_update_exam_paper()
  {
    $examPaper = ExamPaper::factory()->create();
    $examPaperUpdate = ExamPaper::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/exam-papers/' . $examPaper->id, $examPaperUpdate)
      ->assertStatus(403);
  }

  /**
   * PATCH /api/exam-papers/{id}
   * @group exam-papers
   * @group patch-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_update_exam_paper()
  {
    Permission::factory()->state(['name' => 'update exam paper'])->create();
    $this->user->givePermissionTo('update exam paper');

    $examPaper = ExamPaper::factory()->create();
    $examPaperUpdate = ExamPaper::factory()->make()->toArray();
    $response = $this->actingAs($this->user, 'api')
      ->patchJson('/api/exam-papers/' . $examPaper->id, $examPaperUpdate);
    $response->assertStatus(200);
  }

  /**
   * PATCH /api/exam-papers/{id}
   * @group exam-papers
   * @group patch-request
   * @test
   * @return void
   */
  public function should_return_error_422_if_name_not_provided_on_update()
  {
    Permission::factory()->state(['name' => 'update exam paper'])->create();
    $this->user->givePermissionTo('update exam paper');
    $examPaper = ExamPaper::factory()->create();
    $examPaperUpdate = ExamPaper::factory()->state(['name' => ''])->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/exam-papers/' . $examPaper->id, $examPaperUpdate)
      ->assertStatus(422);
  }

  /**
   * PATCH /api/exam-papers/{id}
   * @group exam-papers
   * @test
   * @group patch-request
   * @return void
   */
  public function exam_paper_should_be_updated_after_successful_call()
  {
    Permission::factory()->state(['name' => 'update exam paper'])->create();
    $this->user->givePermissionTo('update exam paper');
    $examPaper = ExamPaper::factory()->create();
    $examPaperUpdate = ExamPaper::factory()->make()->toArray();
    $this->actingAs($this->user, 'api')
      ->patchJson('/api/exam-papers/' . $examPaper->id, $examPaperUpdate)
      ->assertStatus(200)
      ->assertJsonStructure(['saved', 'message', 'data' => ['id', 'name']]);
  }

  /**
   * DELETE/api/exam-papers/{id}
   * @group exam-papers
   * @group delete-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_delete_exam_paper()
  {
    $examPaper = ExamPaper::factory()->create();
    $this->deleteJson('/api/exam-papers/' . $examPaper->id)
      ->assertStatus(401);

  }

  /**
   * DELETE/api/exam-papers/{id}
   * @group exam-papers
   * @test
   * @return void
   */
  public function authenticate_users_without_permission_cannot_delete_exam_paper()
  {
    $examPaper = ExamPaper::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/exam-papers/' . $examPaper->id)
      ->assertStatus(403);
  }

  /**
   * DELETE/api/exam-papers/{id}
   * @group exam-papers
   * @group delete-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_delete_exam_paper()
  {
    Permission::factory()->state(['name' => 'delete exam paper'])->create();
    $this->user->givePermissionTo('delete exam paper');
    $examPaper = ExamPaper::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('/api/exam-papers/' . $examPaper->id)
      ->assertStatus(200);
  }

  /**
   * DELETE/api/exam-papers/{id}
   * @group exam-papers
   * @test
   * @group delete-request
   * @return void
   */
  public function exam_paper_should_be_deleted_after_successful_call()
  {
    Permission::factory()->state(['name' => 'delete exam paper'])->create();
    $this->user->givePermissionTo('delete exam paper');
    $examPaper = ExamPaper::factory()->create();
    $res = $this->actingAs($this->user, 'api')
      ->deleteJson('/api/exam-papers/' . $examPaper->id);
    $res->assertStatus(200)
      ->assertJsonStructure(['saved', 'message']);
    $this->assertNull(ExamPaper::find($examPaper->id));
  }
}



