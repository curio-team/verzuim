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

Route::middleware(["auth", "teacher", "password_once"])->group(function () {

    Route::get('/', 'LadderController@home')->name('home');

    Route::view('about', 'about')->name('about');

    Route::prefix('groups')->name('groups.')->group(function () {
        Route::get('/', 'LadderController@index')->name('index');
        Route::get('/{group}', 'LadderController@show')->name('show');
        Route::get('/favorite/{group}', 'LadderController@favorite')->name('favorite');
    });
    
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/{id}', 'StudentsController@show')->name('show');
        Route::get('/handle/{id}/{step}/{reason}', 'StudentsController@handle')->name('handle');
    });

    Route::middleware('import')->prefix('import')->name('import.')->group(function () {

        Route::get('/', 'ImportController@home')->name('home');
        Route::get('{unit}', 'ImportController@show')->name('show');
        Route::post('{unit}', 'ImportController@upload')->name('upload');

    });

    Route::middleware('coord')->prefix('coord')->name('coord.')->group(function () {
       
        Route::redirect('/', '/coord/users')->name('home');
        Route::get('users', 'CoordUserController@index')->name('users.index');
        Route::get('requests', 'CoordUserController@requests')->name('users.requests');
        Route::post('users/units', 'CoordUserController@units')->name('users.units');
        Route::get('users/{user}', 'CoordUserController@edit')->name('users.edit');
        Route::patch('users/{user}', 'CoordUserController@update')->name('users.update');
        Route::get('users/{user}/reset', 'CoordUserController@reset')->name('users.reset');
        Route::get('users/{user}/activate', 'CoordUserController@activate')->name('users.activate');

    });

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {

        Route::redirect('/', '/admin/units')->name('home');
        Route::resource('units', 'UnitController')->except('show');
        Route::post('units/{unit}/users', 'UnitUserController@sync_unit')->name('units.users.sync');
        
        Route::resource('users', 'UserController')->except(['create', 'store', 'show']);
        Route::post('users/{user}/units', 'UnitUserController@sync_user')->name('users.units.sync');
        Route::get('users/{user}/reset', 'UserController@reset')->name('users.reset');

        Route::get('students', 'UserController@students')->name('students.index');

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
