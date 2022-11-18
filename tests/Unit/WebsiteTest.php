<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Website;
use Illuminate\Foundation\Testing\WithFaker;

class WebsiteTest extends TestCase
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
    protected $user;

    /**
     * @var Website
     */
    protected $website;

    public function setUp(): void
    {
        parent::setUp();

        $this->user         = User::factory()->create()->first();
        $this->user->role   = 2; // set user as admin
        $this->website      = Website::factory()->create(['author_id' => $this->user->id, 'title' => $this->faker->title, 'description' => $this->faker->paragraph]);
        $this->data         = [
            'author_id'     => $this->user->id,
            'title'         => $this->faker->text(),
            'description'   => $this->faker->text(),
        ];
    }

    /**
     * Can create website with authenticated user.
     *
     * @test
     */
    public function canCreateWebsite()
    {
        $this->actingAs($this->user)
        ->postJson('api/websites/', $this->data)
        ->assertSuccessful()
        ->assertJsonStructure([
            'success',
            'message',
            'data',
        ])
        ->json();
    }

    /**
     * Can not create website without authenticated user.
     *
     * @test
     */
    public function canNotCreateWebsiteIfNotAuthenticated()
    {
        $this->postJson('api/websites', $this->data)
        ->assertUnauthorized()
        ->assertJsonStructure([
            'message',
        ])
        ->json();
    }

    /**
     * Can not create website if the user is not admin.
     *
     * @test
     */
    public function canNotCreateWebsiteIfUserIsNotAdmin()
    {
        $this->user->role = 1;

        $this->actingAs($this->user)
        ->postJson('api/websites', $this->data)
        ->assertUnauthorized()
        ->assertJsonStructure([
            'message',
        ])
        ->json();
    }

    /**
     * Can fetch website by author id.
     *
     * @test
     */
    public function canFetchWebsiteByAuthor()
    {
        $this->actingAs($this->user)
        ->getJson('api/websites/fetch-by-author/'. $this->user->id)
        ->assertSuccessful()
        ->assertJsonStructure([
            'success',
            'message',
            'data',
        ])
        ->json();
    }

    /**
     * Can not fetch website by author id if unauthorized.
     *
     * @test
     */
    public function canNotFetchWebsiteByAuthorIfUnAuthorized()
    {
        $this->getJson('api/websites/fetch-by-author/'. $this->user->id)
        ->assertUnauthorized()
        ->assertJsonStructure([
            'message',
        ])
        ->json();
    }

    /**
     * Can not fetch website by author id if website id is not found.
     *
     * @test
     */
    public function canNotFetchWebsiteByAuthorIfNotFound()
    {
        $this->actingAs($this->user)
        ->getJson('api/websites/fetch-by-author/'. 5)
        ->assertNotFound()
        ->assertJsonStructure([
            'message',
        ])
        ->json();
    }

    /**
     * Can update website with authenticated user.
     *
     * @test
     */
    public function canUpdateWebsite()
    {
        $this->actingAs($this->user)
        ->putJson('api/websites/update/'.$this->website->id, $this->data)
        ->assertSuccessful()
        ->assertJsonStructure([
            'success',
            'message',
            'data',
        ])
        ->json();
    }

    /**
     * Can update website without authenticated user.
     *
     * @test
     */
    public function canNotUpdateWebsiteIfUnAuthenticated()
    {
        $this->putJson('api/websites/update/'.$this->website->id, $this->data)
        ->assertUnauthorized()
        ->assertJsonStructure([
            'message',
        ])
        ->json();
    }

    /**
     * Can update website if not found.
     *
     * @test
     */
    public function canNotUpdateWebsiteIfNotFound()
    {
        $this->actingAs($this->user)
        ->putJson('api/websites/update/'. '5', $this->data)
        ->assertNotFound()
        ->assertJsonStructure([
            'message',
        ])
        ->json();
    }

    /**
     * Can fetch single website.
     *
     * @test
     */
    public function canFetchSingleWebsite()
    {
        $this->actingAs($this->user)
        ->getJson('api/websites/'. $this->website->id)
        ->assertSuccessful()
        ->assertJsonStructure([
            'success',
            'message',
            'data',
        ])
        ->json();
    }

    /**
     * Can not fetch single website if id is not found.
     *
     * @test
     */
    public function canNotFetchSingleWebsiteIfNotFound()
    {
        $this->actingAs($this->user)
        ->getJson('api/websites/'. 5)
        ->assertNotFound()
        ->assertJsonStructure([
            'message',
        ])
        ->json();
    }

    /**
     * Can remove website.
     *
     * @test
     */
    public function canRemoveWebsite()
    {
        $this->actingAs($this->user)
        ->deleteJson('api/websites/'. $this->website->id)
        ->assertSuccessful()
        ->assertJsonStructure([
            'success',
            'message',
            'data',
        ])
        ->json();
    }

    /**
     * Can not remove website if UnAuthenticated.
     *
     * @test
     */
    public function canNotRemoveWebsiteIfUnauthenticated()
    {
        $this->deleteJson('api/websites/'. $this->website->id)
        ->assertUnauthorized()
        ->assertJsonStructure([
            'message'
        ])
        ->json();
    }

    /**
     * Can not remove website if Not found.
     *
     * @test
     */
    public function canNotRemoveWebsiteIfNotFound()
    {
        $this->actingAs($this->user)
        ->deleteJson('api/websites/'. 5)
        ->assertNotFound()
        ->assertJsonStructure([
            'message'
        ])
        ->json();
    }
}
