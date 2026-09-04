<?php

use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\CareerController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\FaqController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsletterController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\ProjectController;
use App\Http\Controllers\Frontend\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
| Admin routes live in routes/admin.php (registered via bootstrap/app.php).
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.submit');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->name('newsletter.subscribe');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service:slug}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/careers', [CareerController::class, 'index'])->name('careers.index');
Route::get('/careers/{jobOpening:slug}', [CareerController::class, 'show'])->name('careers.show');
Route::post('/careers/{jobOpening:slug}/apply', [CareerController::class, 'apply'])->name('careers.apply');

Route::get('/faq', [FaqController::class, 'index'])->name('faq');

Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms-conditions', [PageController::class, 'terms'])->name('terms');
