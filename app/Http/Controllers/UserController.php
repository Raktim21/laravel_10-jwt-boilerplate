<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }


    public function index(){

        return response()->json([
            'user' => User::get()
        ],200);
    }


    public function store($request){
        $validation = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            'message' => 'User created successfully',
        ],200);
    }


    public function show($id){
        return response()->json([
            'user' => User::find($id)
        ],200);
    }



    public function update($request, $id){
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,'.$id,
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'message' => 'User updated successfully',
        ],201);
    }



    public function destroy($id){
        $user = User::find($id);
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ],200);
    }




}
