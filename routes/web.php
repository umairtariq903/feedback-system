<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [FeedbackController::class, "index"])->name('dashboard');
    Route::get('/feedback', [FeedbackController::class, "add"])->name("feedback");
    Route::post('/feedback', [FeedbackController::class, "store"])->name("feedback_submit");
    Route::get('/unvoate/{id}', [FeedbackController::class, "unvote"])->name("unvote");
    Route::get('/vote/{id}', [FeedbackController::class, "vote"])->name("vote");

    Route::post("/comment/{id}", [FeedbackController::class, "comment_store"])->name("comment_add");


    Route::get("/my_feedback", [FeedbackController::class, "my_feedback"])->name("my_feedback");
    Route::get("/view_feedback/{id}", [FeedbackController::class, "view_feedback"])->name("view_feedback");

    Route::get('/get-mentions', [FeedbackController::class, 'getMentions'])->name("getMentions");
    Route::get('/my_notification', [FeedbackController::class, 'my_notification'])->name("my_notification");

});

//Admin Auth

Route::group(["prefix" => "admin"], function () {
    Route::get("login", [AdminController::class, 'login'])->name('admin.login');
    Route::post("login", [AdminController::class, 'login_request'])->name('admin.login');

    Route::middleware('admin-auth')->group(function () {
        Route::get("dashboard", [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get("logout", [AdminController::class, 'logout'])->name('admin.logout');
        Route::get('/edit_feedback/{id}', [FeedbackController::class, 'edit'])->name("admin.edit");
        Route::post('/update_feedback/{id}', [FeedbackController::class, 'update'])->name("admin.feedback_update");
        Route::get('/delete_feedback/{id}', [FeedbackController::class, 'delete'])->name("admin.delete");
    });

});


require __DIR__ . '/auth.php';
