<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Website;
use App\Models\WebsitePost;
use Illuminate\Foundation\Testing\WithFaker;

class WebsitePostTest extends TestCase
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
        $this->website      = Website::factory()->create([
            'author_id' => $this->user->id, 
            'title' => $this->faker->title, 
            'description' => $this->faker->paragraph
        ]);
        $this->websitePost  = WebsitePost::create([
            'user_id'       => $this->user->id,
            'website_id'    => $this->website->id,
            'title'         => $this->faker->text(),
            'description'   => $this->faker->text(),
        ]);
        $this->data         = [
            'user_id'       => $this->user->id,
            'website_id'    => $this->website->id,
            'title'         => $this->faker->text(),
            'description'   => $this->faker->text(),
        ];
        
    }

    /**
     * Can create website post with authenticated user.
     *
     * @test
     */
    public function canCreateWebsitePost()
    {
        $this->actingAs($this->user)
        ->postJson('api/website-post/', $this->data)
        ->assertSuccessful()
        ->assertJsonStructure([
            'success',
            'message',
            'data',
        ])
        ->json();
    }

    /**
     * Can not create website post without authenticated user.
     *
     * @test
     */
    public function canNotCreateWebsitePostIfNotAuthenticated()
    {
        $this->postJson('api/website-post', $this->data)
        ->assertUnauthorized()
        ->assertJsonStructure([
            'message',
        ])
        ->json();
    }

    /**
     * Can not create website post if the user is not admin.
     *
     * @test
     */
    public function canNotCreateWebsitePostIfUserIsNotAdmin()
    {
        $this->user->role = 1;

        $this->actingAs($this->user)
        ->postJson('api/website-post', $this->data)
        ->assertUnauthorized()
        ->assertJsonStructure([
            'message',
        ])
        ->json();
    }

    /**
     * Can fetch website post by user.
     *
     * @test
     */
    public function canFetchWebsitePostByUser()
    {
        $this->actingAs($this->user)
        ->getJson('api/website-post/fetch-by-user/'. $this->user->id)
        ->assertSuccessful()
        ->assertJsonStructure([
            'success',
            'message',
            'data',
        ])
        ->json();
    }

    /**
     * Can not fetch website post by user if unauthorized.
     *
     * @test
     */
    public function canNotFetchWebsitePostByAuthorIfUnAuthorized()
    {
        $this->getJson('api/website-post/fetch-by-user/'. $this->user->id)
        ->assertUnauthorized()
        ->assertJsonStructure([
            'message',
        ])
        ->json();
    }

    /**
     * Can not fetch website post by user if website post is not found.
     *
     * @test
     */
    public function canNotFetchWebsitePostByAuthorIfNotFound()
    {
        $this->actingAs($this->user)
        ->getJson('api/website-post/fetch-by-user/'. 51)
        ->assertNotFound()
        ->assertJsonStructure([
            'message',
        ])
        ->json();
    }

    /**
     * Can update website post with authenticated user.
     *
     * @test
     */
    public function canUpdateWebsitePost()
    {
        $this->actingAs($this->user)
        ->putJson('api/website-post/update/'.$this->websitePost->id, $this->data)
        ->assertSuccessful()
        ->assertJsonStructure([
            'success',
            'message',
            'data',
        ])
        ->json();
    }

    /**
     * Can update website post without authenticated user.
     *
     * @test
     */
    public function canNotUpdateWebsitePostIfUnAuthenticated()
    {
        $this->putJson('api/website-post/update/'.$this->websitePost->id, $this->data)
        ->assertUnauthorized()
        ->assertJsonStructure([
            'message',
        ])
        ->json();
    }

    /**
     * Can update website post if not found.
     *
     * @test
     */
    public function canNotUpdateWebsitePostIfNotFound()
    {
        $this->actingAs($this->user)
        ->putJson('api/website-post/update/'. '5', $this->data)
        ->assertNotFound()
        ->assertJsonStructure([
            'message',
        ])
        ->json();
    }

    /**
     * Can fetch single website post.
     *
     * @test
     */
    public function canFetchSingleWebsitePost()
    {
        $this->actingAs($this->user)
        ->getJson('api/website-post/'. $this->websitePost->id)
        ->assertSuccessful()
        ->assertJsonStructure([
            'success',
            'message',
            'data',
        ])
        ->json();
    }

    /**
     * Can not fetch single website post if id is not found.
     *
     * @test
     */
    public function canNotFetchSingleWebsitePostIfNotFound()
    {
        $this->actingAs($this->user)
        ->getJson('api/website-post/'. 5)
        ->assertNotFound()
        ->assertJsonStructure([
            'message',
        ])
        ->json();
    }

    /**
     * Can remove website post.
     *
     * @test
     */
    public function canRemoveWebsitePost()
    {
        $this->actingAs($this->user)
        ->deleteJson('api/website-post/'. $this->websitePost->id)
        ->assertSuccessful()
        ->assertJsonStructure([
            'success',
            'message',
            'data',
        ])
        ->json();
    }

    /**
     * Can not remove website post if UnAuthenticated.
     *
     * @test
     */
    public function canNotRemoveWebsitePostIfUnauthenticated()
    {
        $this->deleteJson('api/website-post/'. $this->websitePost->id)
        ->assertUnauthorized()
        ->assertJsonStructure([
            'message'
        ])
        ->json();
    }

    /**
     * Can not remove website post if Not found.
     *
     * @test
     */
    public function canNotRemoveWebsitePostIfNotFound()
    {
        $this->actingAs($this->user)
        ->deleteJson('api/website-post/'. 5)
        ->assertNotFound()
        ->assertJsonStructure([
            'message'
        ])
        ->json();
    }

    /**
     * Can fetch all website post with authenticated user.
     *
     * @test
     */
    public function canFetchAllWebsitePost()
    {
        $this->actingAs($this->user)
        ->getJson('api/website-post/')
        ->assertSuccessful()
        ->assertJsonStructure([
            'success',
            'message',
            'data',
        ])
        ->json();
    }

    /**
     * Can not fetch all website post with unauthenticated user.
     *
     * @test
     */
    public function canNotFetchAllWebsitePostIfUnauthenticatedUser()
    {
    
        $this->getJson('api/website-post/')
        ->assertUnauthorized()
        ->assertJsonStructure([
            'message',
        ])
        ->json();
    }
}
