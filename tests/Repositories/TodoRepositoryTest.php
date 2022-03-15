<?php namespace Tests\Repositories;

use App\Models\Todo;
use App\Repositories\TodoRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class TodoRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var TodoRepository
     */
    protected $todoRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->todoRepo = \App::make(TodoRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_todo()
    {
        $todo = Todo::factory()->make()->toArray();

        $createdTodo = $this->todoRepo->create($todo);

        $createdTodo = $createdTodo->toArray();
        $this->assertArrayHasKey('id', $createdTodo);
        $this->assertNotNull($createdTodo['id'], 'Created Todo must have id specified');
        $this->assertNotNull(Todo::find($createdTodo['id']), 'Todo with given id must be in DB');
        $this->assertModelData($todo, $createdTodo);
    }

    /**
     * @test read
     */
    public function test_read_todo()
    {
        $todo = Todo::factory()->create();

        $dbTodo = $this->todoRepo->find($todo->id);

        $dbTodo = $dbTodo->toArray();
        $this->assertModelData($todo->toArray(), $dbTodo);
    }

    /**
     * @test update
     */
    public function test_update_todo()
    {
        $todo = Todo::factory()->create();
        $fakeTodo = Todo::factory()->make()->toArray();

        $updatedTodo = $this->todoRepo->update($fakeTodo, $todo->id);

        $this->assertModelData($fakeTodo, $updatedTodo->toArray());
        $dbTodo = $this->todoRepo->find($todo->id);
        $this->assertModelData($fakeTodo, $dbTodo->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_todo()
    {
        $todo = Todo::factory()->create();

        $resp = $this->todoRepo->delete($todo->id);

        $this->assertTrue($resp);
        $this->assertNull(Todo::find($todo->id), 'Todo should not exist in DB');
    }
}
