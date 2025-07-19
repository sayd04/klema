<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

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

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Activities
    Route::resource('activities', ActivityController::class);

    // Weather
    Route::get('/weather', [WeatherController::class, 'index'])->name('weather');
    Route::post('/weather/get-weather', [WeatherController::class, 'getWeather'])->name('weather.get');
    Route::post('/weather/set-default-location', [WeatherController::class, 'setDefaultLocation'])->name('weather.set-default');
    Route::post('/weather/add-location', [WeatherController::class, 'addLocation'])->name('weather.add-location');
    Route::get('/weather/current/{locationId}', [WeatherController::class, 'getCurrentWeather'])->name('weather.current');
    Route::get('/weather/forecast/{locationId}', [WeatherController::class, 'getWeatherForecast'])->name('weather.forecast');
    Route::get('/weather/dashboard', [WeatherController::class, 'getDashboardWeather'])->name('weather.dashboard');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/reports/activities', [ReportController::class, 'activities'])->name('reports.activities');
    Route::get('/reports/weather', [ReportController::class, 'weather'])->name('reports.weather');
    Route::post('/reports/export/activities', [ReportController::class, 'exportActivities'])->name('reports.export.activities');
    Route::post('/reports/export/weather', [ReportController::class, 'exportWeather'])->name('reports.export.weather');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    
    // System statistics
    Route::get('/stats', [AdminController::class, 'systemStats'])->name('stats');
});