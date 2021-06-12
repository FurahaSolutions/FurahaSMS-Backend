<?php

namespace Okotieno\SchoolExams\Tests\Unit;

use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\SchoolExams\Models\ExamPaper;
use Tests\TestCase;


class ExamPapersQuestionTest extends TestCase
{

  protected function setUp(): void
  {
    parent::setUp();
    $this->examPaper = ExamPaper::factory()->create();
  }

  private $examPaper;

  /**
   * POST /api/exam-papers/:exam-paper/questions
   * @group exam-paper-questions
   * @group post-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_create_exam_paper_questions()
  {
    $this->postJson('/api/exam-papers/' . $this->examPaper->id . '/questions')
      ->assertStatus(401);

  }

  /**
   * POST /api/exam-papers/:exam-paper/questions
   * @group exam-paper-questions
   * @group post-request
   * @test
   * @return void
   */
  public function unauthorised_users_cannot_create_exam_paper_questions()
  {
    $this->actingAs($this->user, 'api')
      ->postJson('/api/exam-papers/' . $this->examPaper->id . '/questions')
      ->assertStatus(403);

  }

  /**
   * POST /api/exam-papers/:exam-paper/questions
   * @group exam-paper-questions
   * @group post-request
   * @test
   * @return void
   */
  public function authorised_users_can_create_exam_paper_questions()
  {
    Permission::factory()->state(['name' => 'create exam paper question'])->create();
    $this->user->givePermissionTo('create exam paper question');
    $this->actingAs($this->user, 'api')
      ->postJson('/api/exam-papers/' . $this->examPaper->id . '/questions')
      ->assertStatus(201);

  }
}



