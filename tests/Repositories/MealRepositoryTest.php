<?php namespace Tests\Repositories;

use App\Models\Meal;
use App\Repositories\MealRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class MealRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var MealRepository
     */
    protected $mealRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->mealRepo = \App::make(MealRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_meal()
    {
        $meal = Meal::factory()->make()->toArray();

        $createdMeal = $this->mealRepo->create($meal);

        $createdMeal = $createdMeal->toArray();
        $this->assertArrayHasKey('id', $createdMeal);
        $this->assertNotNull($createdMeal['id'], 'Created Meal must have id specified');
        $this->assertNotNull(Meal::find($createdMeal['id']), 'Meal with given id must be in DB');
        $this->assertModelData($meal, $createdMeal);
    }

    /**
     * @test read
     */
    public function test_read_meal()
    {
        $meal = Meal::factory()->create();

        $dbMeal = $this->mealRepo->find($meal->id);

        $dbMeal = $dbMeal->toArray();
        $this->assertModelData($meal->toArray(), $dbMeal);
    }

    /**
     * @test update
     */
    public function test_update_meal()
    {
        $meal = Meal::factory()->create();
        $fakeMeal = Meal::factory()->make()->toArray();

        $updatedMeal = $this->mealRepo->update($fakeMeal, $meal->id);

        $this->assertModelData($fakeMeal, $updatedMeal->toArray());
        $dbMeal = $this->mealRepo->find($meal->id);
        $this->assertModelData($fakeMeal, $dbMeal->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_meal()
    {
        $meal = Meal::factory()->create();

        $resp = $this->mealRepo->delete($meal->id);

        $this->assertTrue($resp);
        $this->assertNull(Meal::find($meal->id), 'Meal should not exist in DB');
    }
}
