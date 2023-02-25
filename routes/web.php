<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\UserScoreController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use \Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// require __DIR__.'/botman.php';

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




 Route::redirect('/', 'admin/login-page');

Auth::routes();

Route::group(['prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ] ], function()
{


    Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
        Route::get('login-page', 'AuthController@loginPage')->name('admin.login.page');
        Route::post('login', 'AuthController@login')->name('admin.login');
        Route::get('logout', 'AuthController@logout')->name('admin.logout');

        Route::group(['middleware' => ['admin']], function () {
            Route::get('home', 'HomeController@index')->name('admin.home');

            Route::resource('materials',MaterialController::class);
            Route::resource('sections',SectionController::class);
            Route::resource('courses',CourseController::class);


        });
    });
});
