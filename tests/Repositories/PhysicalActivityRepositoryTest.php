<?php namespace Tests\Repositories;

use App\Models\PhysicalActivity;
use App\Repositories\PhysicalActivityRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class PhysicalActivityRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var PhysicalActivityRepository
     */
    protected $physicalActivityRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->physicalActivityRepo = \App::make(PhysicalActivityRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_physical_activity()
    {
        $physicalActivity = PhysicalActivity::factory()->make()->toArray();

        $createdPhysicalActivity = $this->physicalActivityRepo->create($physicalActivity);

        $createdPhysicalActivity = $createdPhysicalActivity->toArray();
        $this->assertArrayHasKey('id', $createdPhysicalActivity);
        $this->assertNotNull($createdPhysicalActivity['id'], 'Created PhysicalActivity must have id specified');
        $this->assertNotNull(PhysicalActivity::find($createdPhysicalActivity['id']), 'PhysicalActivity with given id must be in DB');
        $this->assertModelData($physicalActivity, $createdPhysicalActivity);
    }

    /**
     * @test read
     */
    public function test_read_physical_activity()
    {
        $physicalActivity = PhysicalActivity::factory()->create();

        $dbPhysicalActivity = $this->physicalActivityRepo->find($physicalActivity->id);

        $dbPhysicalActivity = $dbPhysicalActivity->toArray();
        $this->assertModelData($physicalActivity->toArray(), $dbPhysicalActivity);
    }

    /**
     * @test update
     */
    public function test_update_physical_activity()
    {
        $physicalActivity = PhysicalActivity::factory()->create();
        $fakePhysicalActivity = PhysicalActivity::factory()->make()->toArray();

        $updatedPhysicalActivity = $this->physicalActivityRepo->update($fakePhysicalActivity, $physicalActivity->id);

        $this->assertModelData($fakePhysicalActivity, $updatedPhysicalActivity->toArray());
        $dbPhysicalActivity = $this->physicalActivityRepo->find($physicalActivity->id);
        $this->assertModelData($fakePhysicalActivity, $dbPhysicalActivity->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_physical_activity()
    {
        $physicalActivity = PhysicalActivity::factory()->create();

        $resp = $this->physicalActivityRepo->delete($physicalActivity->id);

        $this->assertTrue($resp);
        $this->assertNull(PhysicalActivity::find($physicalActivity->id), 'PhysicalActivity should not exist in DB');
    }
}
