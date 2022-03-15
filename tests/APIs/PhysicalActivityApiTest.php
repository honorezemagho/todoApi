<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\PhysicalActivity;

class PhysicalActivityApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_physical_activity()
    {
        $physicalActivity = PhysicalActivity::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/physical_activities', $physicalActivity
        );

        $this->assertApiResponse($physicalActivity);
    }

    /**
     * @test
     */
    public function test_read_physical_activity()
    {
        $physicalActivity = PhysicalActivity::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/physical_activities/'.$physicalActivity->id
        );

        $this->assertApiResponse($physicalActivity->toArray());
    }

    /**
     * @test
     */
    public function test_update_physical_activity()
    {
        $physicalActivity = PhysicalActivity::factory()->create();
        $editedPhysicalActivity = PhysicalActivity::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/physical_activities/'.$physicalActivity->id,
            $editedPhysicalActivity
        );

        $this->assertApiResponse($editedPhysicalActivity);
    }

    /**
     * @test
     */
    public function test_delete_physical_activity()
    {
        $physicalActivity = PhysicalActivity::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/physical_activities/'.$physicalActivity->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/physical_activities/'.$physicalActivity->id
        );

        $this->response->assertStatus(404);
    }
}
