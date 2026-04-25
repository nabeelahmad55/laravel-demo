<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\ContentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/demo', [DemoController::class, 'index']);

Route::get('/create-post', [ContentController::class, 'storePost']);
Route::get('/upload-video', [ContentController::class, 'storeVideo']);

// Middleware test route
Route::get('/restricted-content', function () {
    return 'Welcome to the restricted club! You passed the middleware.';
})->middleware('age');

// Pipeline test route
Route::get('/test-pipeline', [ContentController::class, 'testPipeline']);

// CSRF test routes
Route::get('/csrf-demo', function () {
    return view('csrf-demo');
});

Route::post('/submit-secure-data', function (\Illuminate\Http\Request $request) {
    return back()->with('success', 'Success! You securely submitted: ' . $request->input('name'));
});

// Escaping test route
Route::get('/escaping-demo', function () {
    return view('escaping-demo');
});




