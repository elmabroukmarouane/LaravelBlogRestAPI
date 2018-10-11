<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Faker\Factory as Faker;
use Datetime;
use App\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('updated_at', 'desc')->get();
        $users->filter->posts;
        $users->filter->comments;
        //return UserResource::collection($users);
        return response()->json([
            'users'    => $users,
        ], 200);
    }
    
    public function getUsersList() {
        $users = User::select('id', 'name')->orderBy('updated_at', 'desc')->get();
        return response()->json([
            'users'    => $users,
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
            'name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'user_role' => 'required|string|max:255'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $datetime = new Datetime($request->get('birthdate'));
        $faker = Faker::create();
        $user_add = User::create([
            'name' => $request->get('name'),
            'birthdate' => $datetime->format('Y-m-d'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'user_role' => $request->get('user_role'),
            'image' => $faker->imageUrl($width = 640, $height = 480)
        ]);
        $user = User::where('id', '=', $user_add->id)->with('posts')->with('comments')->first();
        return response()->json([
            'user' => $user,
            'msg' => 'User added successfully !'
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
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $datetime = new Datetime($request->get('birthdate'));
        $user->name = $request->get('name');
        $user->birthdate = $datetime->format('Y-m-d');
        $user->email = $request->get('email');
        if($request->get('password') != "" && $request->get('password') != null) {
            $user->password = Hash::make($request->get('password'));
        }
        $user->user_role = $request->get('user_role');
        $result_user = $user->saveOrFail();
        if($result_user){
            $user_json = User::where('id', '=', $user->id)->with('posts')->with('comments')->first();
            return response()->json([
                'user' => $user_json,
                'msg' => 'User updated successfully !'
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
        $result = User::where('id','=', $id)->delete();
        if($result){
            return response()->json([
                'msg' => 'User deleted successfully !'
            ], 200);
        }else{
            return response()->json([
                'msg' => 'Action failed  !'
            ], 500);
        }
    }
}
