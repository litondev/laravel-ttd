<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\UserRequest;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        
        $user = User::query();

        if($request->filled("email")){
            $user = $user->where("email",$request->email);
        }

        if($request->filled("name")){
            $user = $user->where("name",$request->name);
        }

        $user = $user->orderBy('id','desc')->paginate(10);

        return response()->json($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        try{
            DB::beginTransaction();

            User::create($request->validated());

            DB::commit();  

            return response()->json([
                "status" => "Success",
            ],201);      
        }catch(\Exception $e){
            DB::rollback();

            return response()->json([
                "status" => "Failed",
                "message" => "Something Wrong"
            ],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request,User $user)
    {
        try{
            DB::beginTransaction();

            $user->update($request->validated());

            DB::commit();

            return response()->json([
                "status" => "Success"
            ]);
        }catch(\Exception $e){
            DB::rollback();

            return response()->json([
                "status" => "Failed",
                "message" => "Something Wrong"
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try{
            DB::beginTransaction();

            $user->delete();

            DB::commit();

            return response()->json([
                "status" => "Success"
            ]);
        }catch(\Exception $e){
            DB::rollback();

            return response()->json([
                "status" => "Failed",
                "message" => "Something Wrong"
            ],500);
        }    
    }
}
