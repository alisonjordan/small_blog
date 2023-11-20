<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function showCreateForm(){
            return view('create-post'); 
    }

    public function showEditForm(Post $post){
        return view('edit-post',['post'=>$post]); 
}

    public function showSinglePost(Post $content){
        
            return view('post',['post' => $content]);
           
    }

    public function delete(Post $post){
        $post->delete();
        return redirect('/profile/' . auth()->user()->username)->with('success','Post deleted!');

}

public function update(Post $post, Request $request){
        
        $incomingFields = $request->validate([
                'title' => 'required',
                'body' => 'required'
            ]);

            $incomingFields['title'] = strip_tags($incomingFields['title']);
            $incomingFields['body'] = strip_tags($incomingFields['body']);

            $post->update($incomingFields);
            return redirect("/post/{$post->id}")->with('success','Post updated!');

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

