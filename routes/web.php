<?php

use App\Http\Controllers\CaseStudyController;
use App\Http\Controllers\FeaturedController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LinkedInController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\TechnologyController;
use App\Models\User;

use App\Services\LinkedInService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Routes loaded by RouteServiceProvider within "web" middleware group.
*/

/**
 * Static pages
 */
Route::view('/team', 'about/team');
Route::view('/about', 'about/eric');
Route::view('/contact', 'contacts/contact');
Route::view('/gdpr', 'legal/gdpr');
Route::view('/overview', 'therapeutics/overview');

/**
 * Home / Dashboard
 */
Route::get('/', [FeaturedController::class, 'index']);
Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/**
 * Blog / Posts
 */
Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index']);
    Route::get('{slug}', [PostController::class, 'singlePost']);
});

/**
 * Technology pages
 */
Route::controller(TechnologyController::class)->group(function () {
    Route::get('/edc', 'edc');
    Route::get('/etmf_tech', 'etmf_tech');
    Route::get('/ctms', 'ctms');
    Route::get('/irt', 'irt');
});

/**
 * Services pages
 */
Route::controller(ServicesController::class)->group(function () {
    Route::get('/clinical_operation', 'clinical_operation');
    Route::get('/biostatistics', 'biostatistics');
    Route::get('/clinical_data', 'clinical_data');
    Route::get('/data_management', 'data_management');
    Route::get('/medical_writing', 'medical_writing');
    Route::get('/pharmacovigilance', 'pharmacovigilance');
    Route::get('/etmf', 'etmf');
});

/**
 * Profile (auth only)
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/**
 * Auth scaffolding routes
 */
require __DIR__ . '/auth.php';

/**
 * LinkedIn OAuth
 */
Route::get('/linkedin/auth/{user}', function (User $user) {
    $linkedInService = app(LinkedInService::class);
    return redirect($linkedInService->getAuthUrl($user));
})->name('linkedin.auth');

Route::get('/linkedin/callback', [LinkedInController::class, 'handleCallback'])
    ->name('linkedin.callback');

/**
 * Case Studies (downloads)
 */
Route::get('/case-studies', [CaseStudyController::class, 'index']);
Route::get('/case-studies/{slug}', [CaseStudyController::class, 'show'])
    ->name('case-studies.show');
Route::post('/case-studies/{caseStudy}/download', [CaseStudyController::class, 'download'])
    ->name('case-studies.download');

/**
 * Misc pages
 */
Route::get('/why_eric', [IndexController::class, 'why_eric']);

/**
 * Cookie preferences
 */
Route::post('/set-cookie-preferences', function (Request $request) {
    $preferences = $request->input('preferences');

    // Set cookies for 1 year
    Cookie::queue('marketing_cookies', $preferences['marketing'] ?? null, 60 * 24 * 365);
    Cookie::queue('facebook_cookies', $preferences['facebook'] ?? null, 60 * 24 * 365);
    Cookie::queue('twitter_cookies', $preferences['twitter'] ?? null, 60 * 24 * 365);
    Cookie::queue('google_analytics_cookies', $preferences['google_analytics'] ?? null, 60 * 24 * 365);

    return response()->json(['message' => 'Preferences saved successfully']);
});
