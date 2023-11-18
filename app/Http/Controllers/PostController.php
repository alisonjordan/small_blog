<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function showCreateForm(){
            return view('create-post'); 
    }

    public function showSinglePost(Post $content){
        
            return view('post',['post' => $content]);
           
    }

    public function storeNewPost(Request $request){
      
            $incomingFields = $request->validate([
                'title' => 'required',
                'body' => 'required'
            ]);

            $incomingFields['title'] = strip_tags($incomingFields['title']);
            $incomingFields['body'] = strip_tags($incomingFields['body']);
            $incomingFields['user_id'] = auth()->id();
        
            $post = Post::create($incomingFields);

            return redirect("/post/{$post->id}")->with('success','Post created!');
           
    }
}

