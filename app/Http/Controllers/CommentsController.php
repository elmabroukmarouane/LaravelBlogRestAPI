<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Comment;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::orderBy('updated_at', 'desc')->get();
        $comments->filter->user;
        $comments->filter->post;
        //return PostResource::collection($comments);
        return response()->json([
            'comments'    => $comments,
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
                'comment' => 'required'
            ]);
            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }
            
            $comment = Comment::create([
                'user_id' => $request->get('user_id'),
                'post_id' => $request->get('post_id'),
                'comment' => $request->get('comment')
            ]);
            return response()->json([
                'comment' => $comment,
                'msg' => 'Comment added successfully !'
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
        //
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
     * @param  Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $comment->comment = $request->comment;
        $result_comment = $comment->saveOrFail();
        if($result_comment){
            return response()->json([
                'comment' => $comment,
                'msg' => 'Comment updated successfully !'
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
        $result = Comment::where('id','=', $id)->delete();
        if($result){
            return response()->json([
                'msg' => 'Comment deleted successfully !'
            ], 200);
        }else{
            return response()->json([
                'msg' => 'Action failed  !'
            ], 500);
        }
    }
}
