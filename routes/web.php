    <?php

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
    return view('welcome');
});

Auth::routes();

Route::get('/redirect', 'SocialAuthFacebookController@redirect');
Route::get('/callback', 'SocialAuthFacebookController@callback');

Route::group(['middleware' => 'auth'], function() {
    Route::get('/home', 'UserController@index')->name('home');

    Route::get('/user/add', function () {
        return view('users.create');
    })->name('addUser');

    Route::get('/user/edit/{id}', 'UserController@edit')->where('id', '[0-9]+')->name('editUser');

    Route::post('/save', 'UserController@store')->name('saveUser');
    Route::post('/update/{id}', 'UserController@update')->name('updateUser');

    Route::get('/user/delete/{id}', 'UserController@destroy')->name('deleteUser');
});
