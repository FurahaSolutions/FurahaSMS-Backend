<?php


namespace Okotieno\LMS\Tests\Unit;


use App\Models\User;
use Okotieno\LMS\Models\BookIssue;
use Okotieno\LMS\Models\LibraryBookItem;
use Okotieno\LMS\Models\LibraryUser;
use Okotieno\PermissionsAndRoles\Models\Permission;
use Tests\TestCase;

class LibraryBookIssueTest extends TestCase
{
  /**
   * POST /api/library-books/issue
   * @group library
   * @group library-book-issue
   * @test
   */
  public function unauthenticated_users_cannot_issue_library_books()
  {
    $this->postJson('api/library-books/issue', [])
      ->assertStatus(401);
  }

  /**
   * POST /api/library-books/issue
   * @group library
   * @group library-book-issue
   * @test
   */
  public function authenticated_users_without_permission_cannot_issue_library_book()
  {
    $this->actingAs($this->user, 'api')
      ->postJson('api/library-books/issue', [])
      ->assertStatus(403);
  }

  /**
   * POST /api/library-books/issue
   * @group library
   * @group library-book-issue
   * @test
   */
  public function authenticated_users_with_permission_can_issue_library_book()
  {
    Permission::factory()->state(['name' => 'issue library book'])->create();
    $this->user->givePermissionTo('issue library book');
    $libraryUser = LibraryUser::factory()->create();
    $bookItem = LibraryBookItem::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('api/library-books/issue', [
        'user_id' => $libraryUser->user->id,
        'book_item_id' => $bookItem->id
      ])
      ->assertStatus(200);
  }
  /**
   * POST /api/library-books/issue
   * @group library
   * @group library-book-issue-1
   * @test
   */
  public function status_403_when_user_tries_to_issue_library_book_to_suspended_library_user()
  {
    Permission::factory()->state(['name' => 'issue library book'])->create();
    $this->user->givePermissionTo('issue library book');
    $libraryUser = LibraryUser::factory()->suspended()->create();
    $bookItem = LibraryBookItem::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('api/library-books/issue', [
        'user_id' => $libraryUser->user->id,
        'book_item_id' => $bookItem->id
      ])
      ->assertStatus(403);
  }

  /**
   * POST /api/library-books/issue
   * @group library
   * @group library-book-issue
   * @test
   */
  public function status_422_when_user_tries_to_issue_library_book_to_non_library_user()
  {
    Permission::factory()->state(['name' => 'issue library book'])->create();
    $this->user->givePermissionTo('issue library book');
    $user = User::factory()->create();
    $bookItem = LibraryBookItem::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('api/library-books/issue', [
        'user_id' => $user->id,
        'book_item_id' => $bookItem->id
      ])
      ->assertStatus(422);
  }
  /**
   * POST /api/library-books/issue
   * @group library
   * @group library-book-issue
   * @test
   */
  public function error_422_is_thrown_if_user_is_not_provided()
  {
    Permission::factory()->state(['name' => 'issue library book'])->create();
    $this->user->givePermissionTo('issue library book');
    $bookItem = LibraryBookItem::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('api/library-books/issue', [
        'book_item_id' => $bookItem->id
      ])
      ->assertStatus(422);
  }
  /**
   * POST /api/library-books/issue
   * @group library
   * @group library-book-issue
   * @test
   */
  public function error_422_is_thrown_if_book_item_is_not_provided()
  {
    Permission::factory()->state(['name' => 'issue library book'])->create();
    $this->user->givePermissionTo('issue library book');
    $libraryUser = LibraryUser::factory()->create();
    $this->actingAs($this->user, 'api')
      ->postJson('api/library-books/issue', [
        'user_id' => $libraryUser->user->id,
      ])
      ->assertStatus(422);
  }
  /**
   * GET /api/library-books/issue
   * @group library
   * @group library-book-issue
   * @test
   */
  public function unauthenticated_users_cannot_retrieve_library_books()
  {
    $libraryUser = LibraryUser::factory()->create();
    $this->getJson('api/library-books/issue', [
        'user_id' => $libraryUser->user->id,
      ])
      ->assertStatus(401);
  }

  /**
   * GET /api/library-books/issue
   * @group library
   * @group library-book-issue
   * @test
   */
  public function authenticate_users_can_retrieve_library_books()
  {
    $libraryUser = LibraryUser::factory()->create();
    $this->actingAs($this->user, 'api')
      ->getJson('api/library-books/issue', [
        'user_id' => $libraryUser->user->id,
      ])
      ->assertStatus(200);
  }

  /**
   * DELETE /api/library-books/issue
   * @group library
   * @group library-book-issue
   * @test
   */
  public function unauthenticated_users_cannot_mark_library_books_as_returned()
  {
    $libraryBookIssue = BookIssue::factory()->create();
    $this->deleteJson('api/library-books/issue/'.$libraryBookIssue->libraryBookItem->id)
      ->assertStatus(401);
  }
  /**
   * DELETE /api/library-books/issue
   * @group library
   * @group library-book-issue
   * @test
   */
  public function authenticated_users_without_permission_cannot_mark_library_books_as_returned()
  {
    $libraryBookIssue = BookIssue::factory()->create();
    $this->actingAs($this->user, 'api')
      ->deleteJson('api/library-books/issue/'.$libraryBookIssue->libraryBookItem->id)
      ->assertStatus(403);
  }  /**
   * DELETE /api/library-books/issue
   * @group library
   * @group library-book-issue
   * @test
   */
  public function authenticated_users_with_permission_can_mark_library_books_as_returned()
  {
    $libraryBookIssue = BookIssue::factory()->create();
    Permission::factory()->state(['name' => 'mark library book returned'])->create();
    $this->user->givePermissionTo('mark library book returned');
    $this->actingAs($this->user, 'api')
      ->deleteJson('api/library-books/issue/'.$libraryBookIssue->libraryBookItem->id)
      ->assertStatus(200);
  }


}
