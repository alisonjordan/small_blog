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

    public function delete(Post $post){
        if (auth()->user()->cannot('delete',$post)) {
                return redirect('401');
        } 
        $post->delete();
        return redirect('/profile/' . auth()->user()->username)->with('success','Post deleted!');

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

