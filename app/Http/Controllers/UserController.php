<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function logout(){
        auth()->logout();
        return redirect('/')->with('success','You logged out with success!');
        }


    public function showCorrectPage(){
       if ( auth()->check()) {
        return view('userhomepage');
       } else {
        return view('homepage');
       }
       
    }

    public function showProfile(User $user){
        $posts = $user->posts()->latest()->get();
        return view('profile',['username'=>  $user->username,'posts' => $posts,'postCount'=>$posts->count()]);
        }

    public function showAvatarManageForm(){
        return view('avatar-form');
        }

    public function storeAvatar(Request $request){
        $request->validate([
            'avatar' => 'required|image|max:5000'
        ]);

        $user = auth()->user();

        $filename = $user->id . '-' . uniqid() . '.jpg';
        //still to find a proper way to use intevention image v3
        $imgData = Image::make($request->file('avatar'))->fit(120)->encode('jpg');
        Storage::put('public/avatars/' . $filename, $imgData);
       
        }


    public function register(Request $request){
        $incomingFields = $request->validate([
            'username' => ['required','min:3','max:10',Rule::unique('users', 'username')],
            'email' => ['required','email',Rule::unique('users', 'email')],
            'password' => ['required','min:8', 'confirmed'],
        ]);

        $user = User::create($incomingFields);
        auth()->login($user);
        return redirect('/')->with('success','Thank you for creating an account!');
    }

    public function login(Request $request){
        $incomingFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);

        if(auth()->attempt(['username'=>$incomingFields['loginusername'],'password'=>$incomingFields['loginpassword']])){
        $request->session()->regenerate();
        return redirect('/')->with('success','You logged in with success!');
    } else{
        return redirect('/')->with('failure','Username or Password is invalid!');
    }
}
}
