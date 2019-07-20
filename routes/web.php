<?php
use App\Message;

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

Route::get('/settings', function () {
    $user = Auth::user();
    return view('settings.index',compact('user'));
})->name('settings');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/manage', 'AdminController@index');

Route::post('/update-user', 'HomeController@updateUser');

Route::get('/modals/edit-personal-photo','ModalController@editPersonalPhoto');
Route::get('/modals/start-chat/{id}','ModalController@startChat');

Route::post('/user/update-personal-photo','HomeController@updatePersonalPhoto');

Route::get('/hill', function () {
    return view('hill.index');
});

Route::post('/chat/create', 'ChatController@create');
Route::get('/chat/{chat}', 'ChatController@show');

Route::get('/gcd/{a}/{b}', 'ChatController@gcd');

Route::post('/msg/create', 'HillController@createMsg');
Route::post('/msg/img/create', 'HillController@createImgMsg');
Route::post('/msgs/dec', 'HillController@decMsg');

Route::post('/test-load/enc', 'HillController@msgLoadEnc');
Route::post('/test-load/dec', 'HillController@msgLoadDec');


//modals
Route::get('/admin/modal/add-user','ModalController@addUser');

Route::get('/admin/modal/delete-user/{id}','ModalController@deleteUser');

Route::get('/admin/modal/edit-user/{id}','ModalController@editUser');


// Ajax

Route::post('/add-user-ajax','ModalController@addUserAjax');
Route::post('/update-user-ajax','ModalController@updateUserAjax');
Route::post('/remove-user-ajax','ModalController@removeUserAjax');

