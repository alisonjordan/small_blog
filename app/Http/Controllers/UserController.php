<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
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
        return view('userhomepage',['posts' => auth()->user()->feedPosts()->latest()->paginate(5)]);
       } else {
        return view('homepage');
       }
       
    }

    private function getSharedData($user) {
        $currentlyFollowing = 0;

        if (auth()->check()) {
            $currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        }

        View::share('sharedData', ['currentlyFollowing' => $currentlyFollowing, 'avatar' => $user->avatar, 'username' => $user->username, 'postCount' => $user->posts()->count(), 'followersCount' => $user->followers()->count(), 'followingCount' => $user->following()->count()]);
    }

    public function showProfile(User $user){
        $this->getSharedData($user);
        return view('profile', ['posts' => $user->posts()->latest()->get()]);
    }


        public function showFollowers(User $user){
        $this->getSharedData($user);
        return view('profile-followers', ['followers' => $user->followers()->latest()->get()]);
    }
        
        
        public function showFollowing(User $user){
        $this->getSharedData($user);
        return view('profile-following', ['following' => $user->following()->latest()->get()]);
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

        $oldAvatar = $user->avatar; 

        $user->avatar = $filename;
        $user->save();

        if($oldAvatar != "/fallback-avatar.jpg"){
            Storage::delete(str_replace("/storage/","public/",$oldAvatar));
        }

        return redirect('/')->with('success','Avatar image updated!');
       
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
