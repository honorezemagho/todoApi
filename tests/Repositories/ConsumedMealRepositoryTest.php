<?php namespace Tests\Repositories;

use App\Models\ConsumedMeal;
use App\Repositories\ConsumedMealRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ConsumedMealRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ConsumedMealRepository
     */
    protected $consumedMealRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->consumedMealRepo = \App::make(ConsumedMealRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_consumed_meal()
    {
        $consumedMeal = ConsumedMeal::factory()->make()->toArray();

        $createdConsumedMeal = $this->consumedMealRepo->create($consumedMeal);

        $createdConsumedMeal = $createdConsumedMeal->toArray();
        $this->assertArrayHasKey('id', $createdConsumedMeal);
        $this->assertNotNull($createdConsumedMeal['id'], 'Created ConsumedMeal must have id specified');
        $this->assertNotNull(ConsumedMeal::find($createdConsumedMeal['id']), 'ConsumedMeal with given id must be in DB');
        $this->assertModelData($consumedMeal, $createdConsumedMeal);
    }

    /**
     * @test read
     */
    public function test_read_consumed_meal()
    {
        $consumedMeal = ConsumedMeal::factory()->create();

        $dbConsumedMeal = $this->consumedMealRepo->find($consumedMeal->id);

        $dbConsumedMeal = $dbConsumedMeal->toArray();
        $this->assertModelData($consumedMeal->toArray(), $dbConsumedMeal);
    }

    /**
     * @test update
     */
    public function test_update_consumed_meal()
    {
        $consumedMeal = ConsumedMeal::factory()->create();
        $fakeConsumedMeal = ConsumedMeal::factory()->make()->toArray();

        $updatedConsumedMeal = $this->consumedMealRepo->update($fakeConsumedMeal, $consumedMeal->id);

        $this->assertModelData($fakeConsumedMeal, $updatedConsumedMeal->toArray());
        $dbConsumedMeal = $this->consumedMealRepo->find($consumedMeal->id);
        $this->assertModelData($fakeConsumedMeal, $dbConsumedMeal->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_consumed_meal()
    {
        $consumedMeal = ConsumedMeal::factory()->create();

        $resp = $this->consumedMealRepo->delete($consumedMeal->id);

        $this->assertTrue($resp);
        $this->assertNull(ConsumedMeal::find($consumedMeal->id), 'ConsumedMeal should not exist in DB');
    }
}
