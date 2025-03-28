<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function showPosts()
    {
        return view('posts');
    }
    public function showPostForm()
    {
        return view('components.posts.create_post');
    }

    public function createPost()
    {
        // // Validate the request data
        // $request->validate([
        //     'content' => 'required|string|max:255',
        // ]);

        // // Create a new post
        // $post = new Post();
        // $post->content = $request->input('content');
        // $post->user_id = auth()->id(); // Assuming you have user authentication set up
        // $post->save();

        return redirect('/posts')->with('success', 'Post created successfully!');
    }

    public function deletePost($id)
    {
        // Find the post by ID and delete it
        // $post = Post::findOrFail($id);
        // $post->delete();

        return redirect('/posts')->with('success', 'Post deleted successfully!');
    }
    public function editPost($id)
    {
        // Find the post by ID and return it for editing
        // $post = Post::findOrFail($id);
        // return view('edit_post', compact('post'));

        return redirect('/posts')->with('success', 'Post edited successfully!');
    }
    
}
