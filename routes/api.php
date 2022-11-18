<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;

/**
 * Controllers.
 */
use App\Http\Controllers\Website\WebsiteController;
use App\Http\Controllers\Website\WebsiteSubscriberController;
use App\Http\Controllers\Website\WebsitePostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [LoginController::class, 'logout']);

    Route::get('user', [UserController::class, 'current']);

    // Settings
    Route::name('settings.')
    ->prefix('settings')
    ->group(function () {
        Route::patch('/profile', [ProfileController::class, 'update'])->name('update.profile');
        Route::patch('/password', [PasswordController::class, 'update'])->name('update.password');
    });

    /**
     * Websites Modules.
     */
    Route::name('websites.')
    ->prefix('websites')
    ->group(function () {
        Route::get('/', [WebsiteController::class, 'index'])->name('list');
        Route::get('/{id}', [WebsiteController::class, 'get'])->name('single');
        Route::get('/fetch-by-author/{id}', [WebsiteController::class, 'fetchByAuthor'])->name('fetch-by-author');
        Route::post('/', [WebsiteController::class, 'store'])->name('store');
        Route::put('/update/{id}', [WebsiteController::class, 'update'])->name('update');
        Route::delete('/{id}', [WebsiteController::class, 'destroy'])->name('delete');
    });

    /**
     * Website Subscription Modules.
     */
    Route::name('website-subscription.')
    ->prefix('website-subscription')
    ->group(function () {
        Route::get('/', [WebsiteSubscriberController::class, 'index'])->name('list');
        Route::get('/fetch-with-subscriber', [WebsiteSubscriberController::class, 'fetchWithSubscriber'])->name('fetch-with-subscriber');
        Route::get('/fetch-user-websites', [WebsiteSubscriberController::class, 'fetchWebsitesOfUser'])->name('fetch-user-websites');
        Route::post('/', [WebsiteSubscriberController::class, 'subscribe'])->name('subscribe');
        Route::delete('/unsubscribe/{id}', [WebsiteSubscriberController::class, 'unsubscribe'])->name('unsubscribe');
    });

    /**
     * Website Posts Modules.
     */
    Route::name('website-post.')
    ->prefix('website-post')
    ->group(function () {
        Route::get('/', [WebsitePostController::class, 'index'])->name('list');
        Route::get('/{id}', [WebsitePostController::class, 'get'])->name('single');
        Route::get('/fetch-by-user/{id}', [WebsitePostController::class, 'fetchByUser'])->name('fetch-by-author');
        Route::post('/', [WebsitePostController::class, 'store'])->name('store');
        Route::put('/update/{id}', [WebsitePostController::class, 'update'])->name('update');
        Route::delete('/{id}', [WebsitePostController::class, 'destroy'])->name('delete');
    });
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);

    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);

    Route::post('email/verify/{user}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('email/resend', [VerificationController::class, 'resend']);

    Route::post('oauth/{driver}', [OAuthController::class, 'redirect']);
    Route::get('oauth/{driver}/callback', [OAuthController::class, 'handleCallback'])->name('oauth.callback');
});
