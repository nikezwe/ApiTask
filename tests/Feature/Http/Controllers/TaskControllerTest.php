<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TaskController
 */
final class TaskControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $tasks = Task::factory()->count(3)->create();

        $response = $this->get(route('tasks.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TaskController::class,
            'store',
            \App\Http\Requests\TaskStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $title = fake()->sentence(4);
        $description = fake()->text();
        $due_date = Carbon::parse(fake()->date());
        $status = fake()->word();

        $response = $this->post(route('tasks.store'), [
            'title' => $title,
            'description' => $description,
            'due_date' => $due_date->toDateString(),
            'status' => $status,
        ]);

        $tasks = Task::query()
            ->where('title', $title)
            ->where('description', $description)
            ->where('due_date', $due_date)
            ->where('status', $status)
            ->get();
        $this->assertCount(1, $tasks);
        $task = $tasks->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $task = Task::factory()->create();

        $response = $this->get(route('tasks.show', $task));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TaskController::class,
            'update',
            \App\Http\Requests\TaskUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $task = Task::factory()->create();
        $title = fake()->sentence(4);
        $description = fake()->text();
        $due_date = Carbon::parse(fake()->date());
        $status = fake()->word();

        $response = $this->put(route('tasks.update', $task), [
            'title' => $title,
            'description' => $description,
            'due_date' => $due_date->toDateString(),
            'status' => $status,
        ]);

        $task->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($title, $task->title);
        $this->assertEquals($description, $task->description);
        $this->assertEquals($due_date, $task->due_date);
        $this->assertEquals($status, $task->status);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $task = Task::factory()->create();

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertNoContent();

        $this->assertModelMissing($task);
    }
}
