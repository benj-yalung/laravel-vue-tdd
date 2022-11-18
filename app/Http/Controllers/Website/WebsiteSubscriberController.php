<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Utilities\Result;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\WebsiteSubscriber;
use App\Models\User;

class WebsiteSubscriberController extends Controller
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
     * For Fetching all websites with subscriber.
     */
    public function fetchWithSubscriber(): JsonResponse
    {
        $websites = WebsiteSubscriber::with(['website', 'user'])->get();

        return $this->result->success($websites);
    }

    /**
     * For Fetching all websites of single user.
     */
    public function fetchWebsitesOfUser(): JsonResponse
    {
        $websites = WebsiteSubscriber::where('user_id', $this->user->id)->with(['website', 'user'])->get();

        return $this->result->success($websites);
    }

    /**
     * For Fetching all websites of the user.
     */
    public function index(): JsonResponse
    {
        $websites = User::where('id', $this->user->id)->with(['websiteSubscribed'])->first();

        return $this->result->success($websites);
    }

    /**
     * For subscribing to website.
     */
    public function subscribe(Request $request): JsonResponse
    {
        $website_id = $request->input('website_id');

        // Note: check if user is already subscribe to the website
        $website = WebsiteSubscriber::where('website_id', $website_id)->where('user_id', $this->user->id)->first();

        if( $website ) {
            return $this->result->validationError($website, 'You already subscribe to this website!');
        }

        $website = new WebsiteSubscriber([
            'user_id'       => $this->user->id,
            'website_id'    => $website_id,
        ]);

        $website->save();

        return $this->result->created($website, 'You have been subscribed!');
    }

    /**
     * For Unsubscribing to website.
     */
    public function unsubscribe($id): JsonResponse
    {
        // Note: check if user is already subscribe to the website
        $website = WebsiteSubscriber::where('id', $id)->where('user_id', $this->user->id)->first();

        if ( !$website ) {
            return $this->result->notFound();
        }

        $website->delete();

        return $this->result->success($website, 'You have been removed to the website!');
    }
}
