<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Meal;

class MealApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_meal()
    {
        $meal = Meal::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/meals', $meal
        );

        $this->assertApiResponse($meal);
    }

    /**
     * @test
     */
    public function test_read_meal()
    {
        $meal = Meal::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/meals/'.$meal->id
        );

        $this->assertApiResponse($meal->toArray());
    }

    /**
     * @test
     */
    public function test_update_meal()
    {
        $meal = Meal::factory()->create();
        $editedMeal = Meal::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/meals/'.$meal->id,
            $editedMeal
        );

        $this->assertApiResponse($editedMeal);
    }

    /**
     * @test
     */
    public function test_delete_meal()
    {
        $meal = Meal::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/meals/'.$meal->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/meals/'.$meal->id
        );

        $this->response->assertStatus(404);
    }
}
