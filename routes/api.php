<?php

use Illuminate\Http\Request;
use App\Post;
use App\Comment;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */
Route::group(['namespace' => 'Auth'], function() { 
    Route::post('register', 'LoginController@register');
    Route::post('login', 'LoginController@login');
}); 
Route::get('get-users-list', 'UsersController@getUsersList');
Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', 'Auth\LoginController@logout');
    /* Route::get('get-auth-user', 'Auth\LoginController@getAuthenticatedUser'); */
    Route::resource('comments', 'CommentsController');
    Route::get('get-comments-by-post-id/{post_id}', 'CommentsController@getCommentsByPostID');
    Route::resource('posts', 'PostsController');
    Route::resource('users', 'UsersController');
});
