<?php


use App\Http\Controllers\Frontend\WelcomeController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

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



Route::get('/', function () {
    return view('welcome');
});

// Route::post('/signup', [AuthController::class, 'signup']);
// Route::post('/login', [AuthController::class, 'login']);

// Route::post('api/signup', [AuthController::class, 'signup']);
// Route::post('api/login', [AuthController::class, 'login']);
// Route::get('api/signup', [AuthController::class, 'signup']);
// Route::get('api/login', [AuthController::class, 'login']);

// Route::post('/signup', [AuthController::class, 'signup']);
// Route::post('/login', [AuthController::class, 'login']);



Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('callback', [LoginController::class, 'getCallback']);
Route::post('login', [LoginController::class, 'login']);





Route::middleware('verifyadmin')->group(function () {
    Route::get('/dashboard/index', [WelcomeController::class, 'getIndex']);
    Route::get('/post/create', [WelcomeController::class, 'create']);
    Route::post('/post', [WelcomeController::class, 'store']);
    // Route::post('warnings/create', [WarningsController::class, 'postCreate']);
    // Route::post('warnings/edit/{id}', [WarningsController::class, 'postEdit']);
    // Route::get('warnings/delete/{id}', [WarningsController::class, 'getDelete']);
    // Route::post('warnings/delete/{id}', [WarningsController::class, 'postDelete']);

    // Route::post('settings/ipwhitelist', [SettingsController::class, 'postWhitelist']);
    // Route::post('settings/ipwhitelist/remove', [SettingsController::class, 'removeWhitelist']);
});
// Route::get('/', [WelcomeController::class, 'getIndex']);
 //Route::get('/index', [WelcomeController::class, 'getIndex']);
