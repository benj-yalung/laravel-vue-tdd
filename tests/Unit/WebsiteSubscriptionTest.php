<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Website;
use App\Models\WebsiteSubscriber;
use Illuminate\Foundation\Testing\WithFaker;

class WebsiteSubscriptionTest extends TestCase
{
    // AssertCodes
    // 404 = assertNotFound
    // 422 = assertUnprocessable
    // 401 = assertUnauthorized
    // >= 200 and < 300 = assertSuccessful

    use WithFaker;

    /**
     * @var User
     */
    protected $admin;

     /**
     * @var User
     */
    protected $user;

    /**
     * @var Website
     */
    protected $website;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin         = User::factory()->create()->first();
        $this->admin->role   = 2; // set user as admin
        $this->user         = User::factory()->create()->first();
        $this->user->role   = 1; // set user as normal user
        $this->website      = Website::factory()->create(['author_id' => $this->user->id, 'title' => $this->faker->title, 'description' => $this->faker->paragraph]);
        $this->data         = [
            'author_id'     => $this->user->id,
            'title'         => $this->faker->text(),
            'description'   => $this->faker->text(),
        ];
    }

    /**
     * Can fetch yser website with authenticated user.
     *
     * @test
     */
    public function canFetchUserWebsites()
    {
        $this->actingAs($this->user)
        ->getJson('api/website-subscription/')
        ->assertSuccessful()
        ->assertJsonStructure([
            'success',
            'message',
            'data',
        ])
        ->json();
    }

    /**
     * Can not fetch user website if unauthenticated.
     *
     * @test
     */
    public function canNotCreateWebsiteIfNotAuthenticated()
    {
        $this->getJson('api/website-subscription', $this->data)
        ->assertUnauthorized()
        ->assertJsonStructure([
            'message',
        ])
        ->json();
    }

    /**
     * User can subscribe to website.
     *
     * @test
     */
    public function userCanSubscribeToWebsite()
    {
        $tempData = [
            'website_id' => $this->website->id
        ];

        $this->actingAs($this->user)
        ->postJson('api/website-subscription', $tempData)
        ->assertSuccessful()
        ->assertJsonStructure([
            'success',
            'message',
            'data',
        ])
        ->json();
    }

    /**
     * User can not subscribe to website if unauthenticated.
     *
     * @test
     */
    public function userCanNotSubscribeToWebsiteIfUnauthenticated()
    {
        $tempData = [
            'website_id' => $this->website->id
        ];

        $this->postJson('api/website-subscription', $tempData)
        ->assertUnauthorized()
        ->assertJsonStructure([
            'message',
        ])
        ->json();
    }

    /**
     * User can not subscribe to the same website.
     *
     * @test
     */
    public function userCanNotSubscribeToWebsiteOnSameWebsite()
    {
        WebsiteSubscriber::create(['user_id' => $this->user->id, 'website_id' => $this->website->id]);

        $tempData = [
            'website_id' => $this->website->id
        ];

        $this->actingAs($this->user)
        ->postJson('api/website-subscription', $tempData)
        ->assertStatus(422);
    }

    /**
     * User can unsubscribe to website.
     *
     * @test
     */
    public function userCanUnsubscribeToWebsite()
    {
        $temp_website = WebsiteSubscriber::create(['user_id' => $this->user->id, 'website_id' => $this->website->id]);

        $this->actingAs($this->user)
        ->deleteJson('api/website-subscription/unsubscribe/'. $temp_website->id)
        ->assertSuccessful()
        ->assertJsonStructure([
            'success',
            'message',
            'data',
        ])
        ->json();
    }

    /**
     * User can not unsubscribe to the same website.
     *
     * @test
     */
    public function userCanNotUnsubscribeToWebsiteIfAuthenticated()
    {
        $temp_website = WebsiteSubscriber::create(['user_id' => $this->user->id, 'website_id' => $this->website->id]);

        $this->deleteJson('api/website-subscription/unsubscribe/'. $temp_website->id)
        ->assertUnauthorized()
        ->assertJsonStructure([
            'message',
        ])
        ->json();
    }

    /**
     * User can not unsubscribe if website is not found
     *
     * @test
     */
    public function userCanNotUnsubscribeToWebsiteIfNotFound()
    {
        $this->actingAs($this->user)
        ->deleteJson('api/website-subscription/unsubscribe/'. 5)
        ->assertNotFound()
        ->assertJsonStructure([
            'message',
        ])
        ->json();
    }
}
