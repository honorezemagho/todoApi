<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Subscription;

class SubscriptionApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_subscription()
    {
        $subscription = Subscription::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/subscriptions', $subscription
        );

        $this->assertApiResponse($subscription);
    }

    /**
     * @test
     */
    public function test_read_subscription()
    {
        $subscription = Subscription::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/subscriptions/'.$subscription->id
        );

        $this->assertApiResponse($subscription->toArray());
    }

    /**
     * @test
     */
    public function test_update_subscription()
    {
        $subscription = Subscription::factory()->create();
        $editedSubscription = Subscription::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/subscriptions/'.$subscription->id,
            $editedSubscription
        );

        $this->assertApiResponse($editedSubscription);
    }

    /**
     * @test
     */
    public function test_delete_subscription()
    {
        $subscription = Subscription::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/subscriptions/'.$subscription->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/subscriptions/'.$subscription->id
        );

        $this->response->assertStatus(404);
    }
}
