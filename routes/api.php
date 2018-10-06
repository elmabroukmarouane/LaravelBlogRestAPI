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
Route::get('posts', function () {
        $posts = Post::orderBy('updated_at', 'desc')->get();
        $posts->filter->user;
        $posts->filter->comments;
        //return PostResource::collection($posts);
        return response()->json([
            'posts'    => $posts,
        ], 200);
    });
Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', 'Auth\LoginController@logout');
    Route::get('get-auth-user', 'Auth\LoginController@getAuthenticatedUser');
    Route::get('comments', function () {
        $comments = Comment::orderBy('updated_at', 'desc')->get();
        $comments->filter->user;
        $comments->filter->post;
        //return PostResource::collection($comments);
        return response()->json([
            'comments'    => $comments,
        ], 200);
    });
});
