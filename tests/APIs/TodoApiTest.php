<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Todo;

class TodoApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_todo()
    {
        $todo = Todo::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/todos', $todo
        );

        $this->assertApiResponse($todo);
    }

    /**
     * @test
     */
    public function test_read_todo()
    {
        $todo = Todo::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/todos/'.$todo->id
        );

        $this->assertApiResponse($todo->toArray());
    }

    /**
     * @test
     */
    public function test_update_todo()
    {
        $todo = Todo::factory()->create();
        $editedTodo = Todo::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/todos/'.$todo->id,
            $editedTodo
        );

        $this->assertApiResponse($editedTodo);
    }

    /**
     * @test
     */
    public function test_delete_todo()
    {
        $todo = Todo::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/todos/'.$todo->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/todos/'.$todo->id
        );

        $this->response->assertStatus(404);
    }
}
