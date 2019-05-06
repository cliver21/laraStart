<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return User::latest()->paginate(10);
    }
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string|max:255',
            'email'=> 'required | string | email | max:255 | unique:users',
            'password' => 'required|string|min:8',
            'type'=> 'required'
        ]);
       return User::create([
            'name' => $request['name'],
            'email'=> $request['email'],
            'type' => $request['type'],
            'bio' => $request['bio'],
            'photo' => $request['photo'],
            'password' => Hash::make($request['password'])
       ]);
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
    public function updateProfile(Request $request){
        $user=auth('api')->user();
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email'=> 'required | string | email | max:191 | unique:users,email,'.$user->id,
            'password' => 'sometimes|required|string|min:6',

        ]);
        $currenttPhoto=$user->photo;
        if($request->photo != $currenttPhoto) {


            $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            \Image::make($request->photo)->save(public_path('img/profile/').$name);

            $request->merge(['photo'=> $name]);
            $userPhoto=public_path('img/profile/').$currenttPhoto;
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
        }
        if(!empty($request->password)){

            $request->merge(['password'=> Hash::make($request['password'])]);
        }
        $user->update($request->all());
        return ['message','success'];
    }
    public function profile()
    {
        return auth('api')->user();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user=User::findOrFail($id);
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email'=> 'required | string | email | max:191 | unique:users,email,'.$user->id,
            'password' => 'sometimes|string|min:6',
            'type'=> 'required'
        ]);


         $user->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $user= User::FindOrFail($id);
        $user->delete();
    }
}
