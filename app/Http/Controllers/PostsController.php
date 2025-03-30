<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth')->except(['showPosts']);
    // }

    public function showPosts()
    {
        $posts = Post::with(['user', 'likes', 'comments.user'])->latest()->paginate(10);

        // Fetch top posts by engagement (likes + comments)
        $topPosts = Post::with(['user', 'likes', 'comments'])
            ->withCount(['likes', 'comments'])
            ->orderByRaw('likes_count + comments_count DESC')
            ->limit(5) // Top 5 posts
            ->get();

        return view('posts', compact('posts', 'topPosts'));
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

    public function likePost($id)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please login to like a post');
        }

        $post = Post::findOrFail($id);
        $userId = Auth::id();

        if ($post->isLikedByUser($userId)) {
            // Unlike
            Like::where('post_id', $id)->where('user_id', $userId)->delete();
            $message = 'Post unliked successfully!';
        } else {
            // Like
            Like::create([
                'post_id' => $id,
                'user_id' => $userId
            ]);
            $message = 'Post liked successfully!';
        }
        return redirect()->back()->with('success', $message);

    }
    public function addComment(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please login to comment on a post');
        }

        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $post = Post::findOrFail($id);

        Comment::create([
            'post_id' => $id,
            'user_id' => Auth::id(),
            'content' => strip_tags($request->input('content')),
        ]);
        return redirect()->back()->with('success', 'Comment added successfully!');
        
    }
}