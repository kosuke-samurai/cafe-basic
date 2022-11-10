<?php

use App\Http\Controllers\Admin\AdminBlogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

//お問い合わせフォーム
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'sendMail']);
Route::get('/contact/complete', [ContactController::class, 'complete'])->name('contact.complete');



//管理画面（最後にまとめる用）
Route::prefix('/admin')
    ->name('admin.')
    //->middleware('auth')
    ->group(function () {
        //Route::get('/blogs', [AdminBlogController::class, 'index'])->name('blogs.index');
        //Route::get('/blogs/create', [AdminBlogController::class, 'create'])->name('blogs.create');
        //Route::post('/blogs', [AdminBlogController::class, 'store'])->name('blogs.store');
        //Route::get('/blogs/{blog}', [AdminBlogController::class, 'edit'])->name('blogs.edit');
        //Route::put('/blogs/{blog}', [AdminBlogController::class, 'update'])->name('blogs.update');
        //Route::delete('/blogs/{blog}', [AdminBlogController::class, 'destroy'])->name('blogs.destroy');


        //ログイン時のみアクセス可能なルート
        Route::middleware('auth')
            ->group(function () {
                //ブログ
                Route::resource('/blogs', AdminBlogController::class)->except('show');

                Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
            });

        //未ログイン時のみアクセス可能なルート
        Route::middleware('guest')
            ->group(function () {
                //認証
                Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
                Route::post('/login', [AuthController::class, 'login']);
            });
    });


//ブログ
//Route::get('/admin/blogs', [AdminBlogController::class, 'index'])->name('admin.blogs.index')->middleware('auth');
//Route::get('/admin/blogs/create', [AdminBlogController::class, 'create'])->name('admin.blogs.create')->middleware('auth');
//Route::post('/admin/blogs', [AdminBlogController::class, 'store'])->name('admin.blogs.store')->middleware('auth');
//Route::get('/admin/blogs/{blog}', [AdminBlogController::class, 'edit'])->name('admin.blogs.edit')->middleware('auth');
//Route::put('/admin/blogs/{blog}', [AdminBlogController::class, 'update'])->name('admin.blogs.update')->middleware('auth');
//Route::delete('/admin/blogs/{blog}', [AdminBlogController::class, 'destroy'])->name('admin.blogs.destroy')->middleware('auth');


//ユーザー管理
Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
Route::post('/admin/users/create', [UserController::class, 'store'])->name('admin.users.store');

//認証
//Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login')->middleware('guest');
//Route::post('/admin/login', [AuthController::class, 'login']);
//Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout')->middleware('auth');