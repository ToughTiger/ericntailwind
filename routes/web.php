<?php

use App\Http\Controllers\FeaturedController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TechnologyController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LinkedInController;

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

Route::get('/gdpr', function () {
    return view('legal/gdpr');
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
Route::get('/oncology', function () {
    return view('therapeutics/oncology');
});
Route::get('/neurology', function () {
    return view('therapeutics/neurology');
});
Route::get('/infectious', function () {
    return view('therapeutics/infectious');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';

// linkedin call back route
Route::get('/linkedin/auth', [LinkedInController::class, 'redirectToLinkedIn']);
Route::get('/linkedin/callback', [LinkedInController::class, 'handleLinkedInCallback']);
Route::post('/linkedin/post', [LinkedInController::class, 'postToLinkedIn'])->middleware('auth');
