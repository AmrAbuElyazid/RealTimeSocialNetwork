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

Route::get('/home', 'HomeController@index');
Route::get('/search', 'HomeController@search');

Route::get('users/{id}', ['uses' => 'UserController@userProfile']);
Route::get('friends/{id}', ['uses' => 'UserController@getMyFriends']);


Route::group(['prefix' => 'posts'], function() {

	Route::post('new', ['uses' => 'PostController@newPost']);
	Route::get('get', ['uses' => 'PostController@getPosts']);

	Route::post('comment', ['uses' => 'PostController@newComment']);

	Route::post('like', ['uses' => 'PostController@newLike']);
});

Route::group(['prefix' => 'friendships'], function() {
    
	Route::post('checkIfFriends', ['uses' => 'FriendshipController@checkIfFriends']);
	Route::post('checkIfHaveRequestFrom', ['uses' => 'FriendshipController@checkIfHaveRequestFrom']);
	Route::post('checkIfSentRequestTo', ['uses' => 'FriendshipController@checkIfSentRequestTo']);
	Route::post('sendFriendRequestTo', ['uses' => 'FriendshipController@sendFriendRequestTo']);
	Route::post('acceptFriendRequest', ['uses' => 'FriendshipController@acceptFriendRequest']);
	Route::post('denyFriendRequest', ['uses' => 'FriendshipController@denyFriendRequest']);
	Route::post('unfriend', ['uses' => 'FriendshipController@unfriend']);

});

Route::group(['prefix' => 'chat'], function() {
	Route::post('send', ['uses' => 'ChatController@sendMessage']);
	Route::get('get', ['uses' => 'ChatController@getMessages']);
	Route::post('new_thread', ['uses' => 'ChatController@newThread']);
	Route::post('typing', ['uses' => 'ChatController@typing']);
});

Route::get('test', function () {
    // this checks for the event
    return view('chat');
});
