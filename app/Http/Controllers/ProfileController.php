<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Hash;

class ProfileController extends Controller
{
    function __construct(
        User $user
    ){
        $this->user = $user;
    }

    public function profile()
    {
        $data['user'] = $this->user->find(auth()->user()->id);
        return view('profile.index',$data);
    }

    public function update(Request $request)
    {
        // dd($request->all());
        // $rules = [
        //     'password'  => 'required',
        // ];

        // $messages = [
        //     'password.required'  => 'Password wajib diisi.',
        // ];

        // $validator = Validator::make($request->all(), $rules, $messages);
        // if ($validator->passes()) {
        //     $users = $this->user->find(auth()->user()->id);
        //     $users->password = Hash::make($request->password);
        //     $users->update();

        //     if($users){
        //         return redirect()->route('profile')
        //         ->with('success','Password Profile '.$users->name.' update successfully');
        //     }
        // }

        // return redirect()->route('profile')
        //     ->with(['error' => $validator->errors()->all()]);
        $users = $this->user->find(auth()->user()->id);
        if ($request->password) {
            $users->password = Hash::make($request->password);
        }
        $users->telegram_id = $request->telegram_id;
        $users->update();

        if($users){
            return redirect()->route('profile')
            ->with('success',$users->name.' update successfully');
        }else{
            return redirect()->route('profile')
            ->with(['error' => 'Data belum disimpan']);
        }
    }
}
