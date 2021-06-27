<?php

namespace Okotieno\SchoolExams\Tests\Unit;

use Okotieno\PermissionsAndRoles\Models\Permission;
use Okotieno\SchoolExams\Models\ExamPaper;
use Okotieno\SchoolExams\Models\ExamPaperQuestion;
use Okotieno\SchoolExams\Models\ExamPaperQuestionAnswer;
use Okotieno\SchoolExams\Models\ExamPaperQuestionTag;
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
   * GET /api/exam-papers/:exam-paper/questions/:question
   * @group exam-paper-questions
   * @group post-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_retrieve_exam_paper_question()
  {
    $question = ExamPaperQuestion::factory()->state(['exam_paper_id' => $this->examPaper->id])->create();
    $this->getJson("api/exam-papers/{$this->examPaper->id}/questions/{$question->id}")
      ->assertStatus(401);

  }

  /**
   * GET /api/exam-papers/:exam-paper/questions/:question
   * @group exam-paper-questions
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_can_retrieve_exam_paper_question()
  {
    $question = ExamPaperQuestion::factory()->state(['exam_paper_id' => $this->examPaper->id])->create();
    $this->actingAs($this->user, 'api')
      ->getJson("api/exam-papers/{$this->examPaper->id}/questions/{$question->id}")
      ->assertOk();

  }

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
  public function error_422_if_no_questions_provided()
  {
    Permission::factory()->state(['name' => 'create exam paper question'])->create();
    $this->user->givePermissionTo('create exam paper question');
    $this->actingAs($this->user, 'api')
      ->postJson('/api/exam-papers/' . $this->examPaper->id . '/questions', [])
      ->assertStatus(422);

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
    $question = ExamPaperQuestion::factory()->state(['exam_paper_id' => $this->examPaper->id])->create();
    $questions = [
      'questions' => [
        [
          'description' => $this->faker->sentence(5),
          'correctAnswerDescription' => $this->faker->sentence(5),
          'multipleAnswers' => $this->faker->boolean,
          'multipleChoices' => $this->faker->boolean,
          'points' => $this->faker->numberBetween(2, 10),
          'answers' => [
            ['description' => $this->faker->sentence(5), 'isCorrect' => $this->faker->boolean]
          ],
          'tags' => ExamPaperQuestionTag::factory()->count(3)->create()->toArray()
        ],
        [
          'id' => $question->id,
          'description' => $this->faker->sentence(5),
          'correctAnswerDescription' => $this->faker->sentence(5),
          'multipleAnswers' => $this->faker->boolean,
          'multipleChoices' => $this->faker->boolean,
          'points' => $this->faker->numberBetween(2, 10),
          'answers' => [
            ['id' => ExamPaperQuestionAnswer::factory()->state(['exam_paper_question_id' => $question->id])->create()->id, 'description' => $this->faker->sentence(5), 'isCorrect' => $this->faker->boolean],
            ['description' => $this->faker->sentence(5), 'isCorrect' => $this->faker->boolean]
          ],
          'tags' => ExamPaperQuestionTag::factory()->count(3)->create()->toArray()
        ]
      ]
    ];

    Permission::factory()->state(['name' => 'create exam paper question'])->create();
    $this->user->givePermissionTo('create exam paper question');

    $this->actingAs($this->user, 'api')
      ->postJson("/api/exam-papers/{$this->examPaper->id}/questions", $questions)
      ->assertStatus(201);

  }

  /**
   * DELETE /api/exam-papers/:exam-paper/questions
   * @group exam-paper-questions
   * @group post-request
   * @test
   * @return void
   */
  public function unauthenticated_users_cannot_delete_exam_paper_questions()
  {
    $question = ExamPaperQuestion::factory()->create();
    $this->deleteJson("/api/exam-papers/{$this->examPaper->id}/questions/{$question->id}")
      ->assertUnauthorized();

  }

  /**
   * DELETE /api/exam-papers/:exam-paper/questions
   * @group exam-paper-questions
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_without_permission_cannot_delete_exam_paper_questions()
  {
    $question = ExamPaperQuestion::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson("/api/exam-papers/{$this->examPaper->id}/questions/{$question->id}")
      ->assertForbidden();

  }

  /**
   * DELETE /api/exam-papers/:exam-paper/questions
   * @group exam-paper-questions
   * @group post-request
   * @test
   * @return void
   */
  public function authenticated_users_with_permission_can_delete_exam_paper_questions()
  {
    Permission::factory()->state(['name' => 'delete exam paper question'])->create();
    $this->user->givePermissionTo('delete exam paper question');
    $question = ExamPaperQuestion::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson("/api/exam-papers/{$this->examPaper->id}/questions/{$question->id}")
      ->assertOk();

  }
}



