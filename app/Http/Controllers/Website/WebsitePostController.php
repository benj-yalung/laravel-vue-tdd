<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Utilities\Result;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\WebsiteSubscriber;
use App\Models\WebsitePost;
use App\Http\Requests\WebsitePostRequest;
use App\Jobs\TestEmailJob;

class WebsitePostController extends Controller
{
    /**
     * @var Authenticatable
     */
    private $user;

    /**
     * @var Result
     */
    private $result;

    public function __construct(
        Result $result
    ) {
        $this->user     = auth()->user();
        $this->result   = $result;
    }

    /**
     * For Fetching all website posts.
     */
    public function index(): JsonResponse
    {
        $website_posts = WebsitePost::all();

        return $this->result->success($website_posts);
    }

    /**
     * For Fetching single website.
     */
    public function get($id): JsonResponse
    {

        $website_posts = WebsitePost::where('id', $id)->with(['user', 'website'])->first();

        if (! $website_posts) {
            return $this->result->notFound();
        }

        return $this->result->success($website_posts);
    }

    /**
     * For Fetching websites by user_id.
     */
    public function fetchByUser($id): JsonResponse
    {
        // Note: check user first if allowed to do the request
        if($this->user->role == 1) {
            return $this->result->unauthorized();
        }

        $website_post = WebsitePost::where("user_id", $id)->with(['user', 'website'])->get();

        if ( count($website_post) <= 0 ) {
            return $this->result->notFound();
        }

        return $this->result->success($website_post);
    }

    /**
     * For Storing new website.
     */
    public function store(WebsitePostRequest $request): JsonResponse
    {
        // Note: check user first if allowed to create new website
        if($this->user->role == 1) {
            return $this->result->unauthorized();
        }

        $website_post = new WebsitePost([
            'user_id'       => $this->user->id,
            'website_id'    => $request->input('website_id'),
            'title'         => $request->input('title'),
            'description'   => $request->input('description'),
        ]);

        $website_post->save();

        // Note: Get all the subscribers of the website
        $subscribers = WebsiteSubscriber::where('website_id', $request->input('website_id'))->with(['user'])->get();
       
        foreach ($subscribers as $key => $subscriber) {
            $details = [
                'email'         => $subscriber->user->email,
                'title'         => $request->input('title'),
                'description'   => $request->input('description'),
            ];

            TestEmailJob::dispatch($details);
        }

        return $this->result->created($subscribers, 'Website post has been saved!');
    }

    /**
     * For Updating single post.
     */
    public function update(Request $request, $id): JsonResponse
    {
        // Note: check user first if allowed to do the request
        if($this->user->role == 1) {
            return $this->result->unauthorized();
        }

        $website = WebsitePost::find($id);

        if ( !$website ) {
            return $this->result->notFound();
        }

        $website->title         = $request->title;
        $website->description   = $request->description;
        $website->save();

        return $this->result->success($website, 'Website post has been updated!');
    }

    /**
     * For Removing single website.
     */
    public function destroy($id): JsonResponse
    {
        // Note: check user first if allowed to do the request
        if($this->user->role == 1) {
            return $this->result->unauthorized();
        }
        
        $website = WebsitePost::find($id);

        if ( !$website ) {
            return $this->result->notFound();
        }

        $website->delete();

        return $this->result->success($website, 'Website post has been deleted!');
    }
}
