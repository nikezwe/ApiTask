<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\UserTask;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\UserTaskController
 */
final class UserTaskControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $userTasks = UserTask::factory()->count(3)->create();

        $response = $this->get(route('user-tasks.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\UserTaskController::class,
            'store',
            \App\Http\Requests\UserTaskStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();
        $assigned_at = Carbon::parse(fake()->dateTime());

        $response = $this->post(route('user-tasks.store'), [
            'user_id' => $user->id,
            'task_id' => $task->id,
            'assigned_at' => $assigned_at->toDateTimeString(),
        ]);

        $userTasks = UserTask::query()
            ->where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->where('assigned_at', $assigned_at)
            ->get();
        $this->assertCount(1, $userTasks);
        $userTask = $userTasks->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $userTask = UserTask::factory()->create();

        $response = $this->get(route('user-tasks.show', $userTask));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\UserTaskController::class,
            'update',
            \App\Http\Requests\UserTaskUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $userTask = UserTask::factory()->create();
        $user = User::factory()->create();
        $task = Task::factory()->create();
        $assigned_at = Carbon::parse(fake()->dateTime());

        $response = $this->put(route('user-tasks.update', $userTask), [
            'user_id' => $user->id,
            'task_id' => $task->id,
            'assigned_at' => $assigned_at->toDateTimeString(),
        ]);

        $userTask->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($user->id, $userTask->user_id);
        $this->assertEquals($task->id, $userTask->task_id);
        $this->assertEquals($assigned_at, $userTask->assigned_at);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $userTask = UserTask::factory()->create();

        $response = $this->delete(route('user-tasks.destroy', $userTask));

        $response->assertNoContent();

        $this->assertModelMissing($userTask);
    }
}
