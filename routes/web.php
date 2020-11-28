<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportController;

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

Route::middleware(["auth", "teacher"])->group(function () {

    Route::get('/', 'GroupsController@home')->name('home');

    Route::view('about', 'about')->name('about');

    Route::prefix('groups')->name('groups.')->group(function () {
        Route::get('/', 'GroupsController@index')->name('index');
        Route::get('/{group}', 'GroupsController@show')->name('show');
        Route::get('/favorite/{group}', 'GroupsController@favorite')->name('favorite');
    });
    
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/{id}', 'StudentsController@show')->name('show');
        Route::get('/handle/{id}/{step}/{reason}', 'StudentsController@handle')->name('handle');
    });

    // Route::middleware('admin')->group(function () {

    //     Route::prefix('import')->name('import.')->group(function () {
    //         Route::get('/', 'ImportController@show')->name('show');
    //         Route::post('/', 'ImportController@upload')->name('upload');
    //     });
    
    //     Route::prefix('admins')->name('admins.')->group(function () {
    //         Route::get('/', 'AdminsController@show')->name('show');
    //         Route::post('/', 'AdminsController@save')->name('save');
    //         Route::delete('/{code}', 'AdminsController@delete')->name('delete');
    //     });

    // });

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {

        Route::redirect('/', '/admin/units')->name('home');
        Route::resource('units', 'UnitController')->except('show');
        // Route::prefix('units')->name('units.')->group(function () {
        //     Route::get('/', 'UnitController@index')->name('index');
        // });

    });

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', 'SettingsController@show')->name('show');
        Route::post('/', 'SettingsController@save')->name('save');
    });

    Route::get('logout', 'LoginController@logout')->name('logout');

});

Route::middleware("auth")->group(function () {
    Route::get('/me', 'StudentsController@me')->name('home.student');
});

Route::get('/login', 'LoginController@form')->name('login');
Route::redirect('/login/amoclient', '/amoclient/redirect')->name('login.do.amoclient');
Route::post('/login/internal', 'LoginController@do')->name('login.do.internal');
Route::get('/register', 'RegisterController@form')->name('register');
Route::post('/register', 'RegisterController@do')->name('register.do');

Route::get('/amoclient/ready', function(){
	return redirect()->route('home');
});
