<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    public function showPosts()
    {
        $posts = Post::with('user')->latest()->get();
        return view('posts', ['posts' => $posts]);
    }

    public function showPostForm()
    {
        return view('components.posts.create_post');
    }

    public function createPost(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = new Post();
        $post->title = strip_tags($validatedData['title']);
        $post->content = strip_tags($validatedData['content']);
        $post->user_id = Auth::id();
        $post->save();

        return redirect('/posts')->with('success', 'Post created successfully!');
    }

    public function deletePost($id)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please login to delete a post');
        }

        $post = Post::findOrFail($id);
        
        if ($post->user_id !== Auth::id()) {
            return redirect('/posts')->with('error', 'Unauthorized action');
        }
        
        $post->delete();
        return redirect('/posts')->with('success', 'Post deleted successfully!');
    }

    public function editPost($id)
    {
        $post = Post::findOrFail($id);
        return view('components.posts.edit_post', ['post' => $post]);
    }
    public function updatePost(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = Post::findOrFail($id);
        
        if ($post->user_id !== Auth::id()) {
            return redirect('/posts')->with('error', 'Unauthorized action');
        }

        $post->title = strip_tags($validatedData['title']);
        $post->content = strip_tags($validatedData['content']);
        $post->save();

        return redirect('/posts')->with('success', 'Post updated successfully!');
    }
}