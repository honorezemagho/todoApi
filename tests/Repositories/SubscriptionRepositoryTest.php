<?php namespace Tests\Repositories;

use App\Models\Subscription;
use App\Repositories\SubscriptionRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class SubscriptionRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var SubscriptionRepository
     */
    protected $subscriptionRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->subscriptionRepo = \App::make(SubscriptionRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_subscription()
    {
        $subscription = Subscription::factory()->make()->toArray();

        $createdSubscription = $this->subscriptionRepo->create($subscription);

        $createdSubscription = $createdSubscription->toArray();
        $this->assertArrayHasKey('id', $createdSubscription);
        $this->assertNotNull($createdSubscription['id'], 'Created Subscription must have id specified');
        $this->assertNotNull(Subscription::find($createdSubscription['id']), 'Subscription with given id must be in DB');
        $this->assertModelData($subscription, $createdSubscription);
    }

    /**
     * @test read
     */
    public function test_read_subscription()
    {
        $subscription = Subscription::factory()->create();

        $dbSubscription = $this->subscriptionRepo->find($subscription->id);

        $dbSubscription = $dbSubscription->toArray();
        $this->assertModelData($subscription->toArray(), $dbSubscription);
    }

    /**
     * @test update
     */
    public function test_update_subscription()
    {
        $subscription = Subscription::factory()->create();
        $fakeSubscription = Subscription::factory()->make()->toArray();

        $updatedSubscription = $this->subscriptionRepo->update($fakeSubscription, $subscription->id);

        $this->assertModelData($fakeSubscription, $updatedSubscription->toArray());
        $dbSubscription = $this->subscriptionRepo->find($subscription->id);
        $this->assertModelData($fakeSubscription, $dbSubscription->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_subscription()
    {
        $subscription = Subscription::factory()->create();

        $resp = $this->subscriptionRepo->delete($subscription->id);

        $this->assertTrue($resp);
        $this->assertNull(Subscription::find($subscription->id), 'Subscription should not exist in DB');
    }
}
