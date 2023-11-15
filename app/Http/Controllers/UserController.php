<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function register(Request $request){
        $incomingFields = $request->validate([
            'username' => ['required','min:3','max:10',Rule::unique('users', 'username')],
            'email' => ['required','email',Rule::unique('users', 'email')],
            'password' => ['required','min:8', 'confirmed'],
        ]);

        User::create($incomingFields);
        return "Hello, welcome to our blog";
    }
}