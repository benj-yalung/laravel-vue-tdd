<?php

declare(strict_types=1);

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Utilities\Result;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Website;

class WebsiteController extends Controller
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
     * For Fetching all websites.
     */
    public function index(): JsonResponse
    {
        $websites = Website::with(['user'])->get();

        return $this->result->success($websites);
    }

    /**
     * For Fetching single website.
     */
    public function get($id): JsonResponse
    {
        $website = Website::where('id', $id)->with(['user', 'post', 'subscribers'])->first();
        
        if ( !$website ) {
            return $this->result->notFound();
        }

        return $this->result->success($website);
    }

    /**
     * For Fetching websites by author_id.
     */
    public function fetchByAuthor($id): JsonResponse
    {
        $website = Website::where("author_id", $id)->with(['user'])->get();

        if ( count($website) <= 0 ) {
            return $this->result->notFound();
        }

        return $this->result->success($website);
    }

    /**
     * For Storing new website.
     */
    public function store(Request $request): JsonResponse
    {
        // Note: check user first if allowed to create new website
        if($this->user->role == 1) {
            return $this->result->unauthorized();
        }

        $website = new Website([
            'author_id'     => $this->user->id,
            'title'         => $request->input('title'),
            'description'   => $request->input('description'),
        ]);

        $website->save();

        $website = Website::where("id", $website->id)->with(['user'])->first();

        return $this->result->created($website, 'Website created!');
    }

    /**
     * For Updating single Website.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $website = Website::find($id);

        if ( !$website ) {
            return $this->result->notFound();
        }

        $website->title         = $request->title;
        $website->description   = $request->description;
        $website->save();

        $website = Website::where("id", $website->id)->with(['user'])->first();

        return $this->result->success($website, 'Website updated!');
    }

    /**
     * For Removing single website.
     */
    public function destroy($id): JsonResponse
    {
        $website = Website::find($id);

        if ( !$website ) {
            return $this->result->notFound();
        }

        $website->delete();

        return $this->result->success($website, 'Website deleted!');
    }
}
