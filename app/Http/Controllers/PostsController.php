<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Faker\Factory as Faker;
use App\Post;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('updated_at', 'desc')->get();
        $posts->filter->user;
        $posts->filter->comments;
        //return PostResource::collection($posts);
        return response()->json([
            'posts'    => $posts,
            'msg' => 'Posts list loaded successfully !'
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'title' => 'required',
            'content' => 'required'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $faker = Faker::create();
        $post_add = Post::create([
            'user_id' => $request->get('user_id'),
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'image' => $faker->imageUrl($width = 640, $height = 480)
        ]);
        $post = Post::where('id', '=', $post_add->id)->with('user')->with('comments')->first();
        return response()->json([
            'post' => $post,
            'msg' => 'Post added successfully !'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::where('id', '=', $id)->with('user')->with('comments')->first();
        /* $post->filter->user;
        $post->filter->comments; */
        return response()->json([
            'post'       => $post,
            'msg' => 'Post loaded successfully !'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $post->title = $request->title;
        $post->content = $request->content;
        $result_post = $post->saveOrFail();
        if($result_post){
            $post_json = Post::where('id', '=', $post->id)->with('user')->with('comments')->first();
            return response()->json([
                'post' => $post_json,
                'msg' => 'Post updated successfully !'
            ], 200);
        }else{
            return response()->json([
                'msg' => 'Something went wrong. Failed action !'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = Post::where('id','=', $id)->delete();
        if($result){
            return response()->json([
                'msg' => 'Post deleted successfully !'
            ], 200);
        }else{
            return response()->json([
                'msg' => 'Action failed  !'
            ], 500);
        }
    }
}
