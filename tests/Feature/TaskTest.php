<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Http\Response;
use Tests\TestCase;

class TaskTest extends TestCase
{
    public function test_task_multi(): void
    {
        $tasks = Task::factory()->count(5)->create();
        $this->assertCount(5, $tasks);
        foreach ($tasks as $task) {
            $this->assertNotEmpty($task->title);
            $this->assertTrue(in_array($task->status, ['pendente', 'concluída']));
        }
    }

    public function test_task_creates(): void
    {
        $data = [
            'title' => 'New Task',
            'status' => 'pendente'
        ];
        $response = $this->post(route('task.store'), $data);
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('tasks', $data);
    }

    public function test_task_updates(): void
    {
        $task = Task::factory()->create();
        $data = [
            'title' => 'Task Atualizado',
            'status' => 'concluída'
        ];
        $response = $this->put(route('task.update', $task->id), $data);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('tasks', $data);
    }

    public function test_task_deletes(): void
    {
        $task = Task::factory()->create();
        $response = $this->delete(route('task.destroy', $task->id));
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_task_shows(): void
    {
        $task = Task::factory()->create();
        $response = $this->get(route('task.show', $task->id));
        $response->assertJson([
            'id' => $task->id,
            'title' => $task->title,
            'status' => $task->status
        ]);
    }
}
