<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ConsumedMeal;

class ConsumedMealApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_consumed_meal()
    {
        $consumedMeal = ConsumedMeal::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/consumed_meals', $consumedMeal
        );

        $this->assertApiResponse($consumedMeal);
    }

    /**
     * @test
     */
    public function test_read_consumed_meal()
    {
        $consumedMeal = ConsumedMeal::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/consumed_meals/'.$consumedMeal->id
        );

        $this->assertApiResponse($consumedMeal->toArray());
    }

    /**
     * @test
     */
    public function test_update_consumed_meal()
    {
        $consumedMeal = ConsumedMeal::factory()->create();
        $editedConsumedMeal = ConsumedMeal::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/consumed_meals/'.$consumedMeal->id,
            $editedConsumedMeal
        );

        $this->assertApiResponse($editedConsumedMeal);
    }

    /**
     * @test
     */
    public function test_delete_consumed_meal()
    {
        $consumedMeal = ConsumedMeal::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/consumed_meals/'.$consumedMeal->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/consumed_meals/'.$consumedMeal->id
        );

        $this->response->assertStatus(404);
    }
}
