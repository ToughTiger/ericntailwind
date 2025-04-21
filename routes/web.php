<?php

use App\Http\Controllers\CaseStudyController;
use App\Http\Controllers\FeaturedController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LinkedInController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\TechnologyController;
use App\Models\User;
use App\Services\LinkedInService; // Ensure this is the correct namespace for LinkedInService
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/team', function () {
    return view('about/team');
});
Route::get('/about', function () {
    return view('about/eric');
});

Route::get('/single', function () {
    return view('blog/singlePost');
});
Route::get('/posts', function () {
    return view('blog/blogPost');
});

Route::get('/contact', function () {
    return view('contacts/contact');
});

Route::get('/gdpr', function () {
    return view('legal/gdpr');
});
Route::get('/overview', function () {
    return view('therapeutics/overview');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
// Index controller
// Route::get('/',[IndexController::class,'blog']);
Route::get('/',[FeaturedController::class,'index']);

//Technology routes
route::get('/edc',[TechnologyController::class,'edc']);
Route::get('/etmf_tech',[TechnologyController::class,'etmf_tech']);
Route::get('/ctms',[TechnologyController::class,'ctms']);
Route::get('/irt',[TechnologyController::class,'irt']);

// Services routes

Route::get('/clinical_operation',[ServicesController::class,'clinical_operation']);
Route::get('/biostatistics',[ServicesController::class,'biostatistics']);
Route::get('/clinical_data',[ServicesController::class,'clinical_data']);
Route::get('/data_management',[ServicesController::class,'data_management']);
Route::get('/medical_writing',[ServicesController::class,'medical_writing']);
Route::get('/pharmacovigilance',[ServicesController::class,'pharmacovigilance']);
Route::get('/etmf',[ServicesController::class,'etmf']);

// therapy routes
// Route::get('/oncology', function () {
//     return view('therapeutics/oncology');
// });
// Route::get('/neurology', function () {
//     return view('therapeutics/neurology');
// });
// Route::get('/infectious', function () {
//     return view('therapeutics/infectious');
// });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';

// linkedin call back route
Route::get('/linkedin/auth/{user}', function (User $user) {
    $linkedInService = app(LinkedInService::class);
    return redirect($linkedInService->getAuthUrl($user));
})->name('linkedin.auth');

Route::get('/linkedin/callback', [LinkedInController::class, 'handleCallback'])->name('linkedin.callback');

//download route
Route::get('/case-studies', [CaseStudyController::class, 'index']);

Route::get('/case-studies/{slug}', [CaseStudyController::class, 'show'])
    ->name('case-studies.show');

Route::post('/case-studies/{caseStudy}/download', [CaseStudyController::class, 'download'])
    ->name('case-studies.download');


// Cookeies Routes

Route::post('/set-cookie-preferences', function (Request $request) {
    // Get preferences from the request
    $preferences = $request->input('preferences');

    // Set cookies for 1 year (can be adjusted)
    Cookie::queue('marketing_cookies', $preferences['marketing'], 60 * 24 * 365);
    Cookie::queue('facebook_cookies', $preferences['facebook'], 60 * 24 * 365);
    Cookie::queue('twitter_cookies', $preferences['twitter'], 60 * 24 * 365);
    Cookie::queue('google_analytics_cookies', $preferences['google_analytics'], 60 * 24 * 365);

    return response()->json(['message' => 'Preferences saved successfully']);
});   