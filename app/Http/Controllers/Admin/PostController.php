<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $authors = Post::distinct()->pluck('author');

        return view('admin.post.index',compact('authors'));
    }

    public function blogs()
    {
        $posts = Post::all();  
        return view('admin.post.blog-list', compact('posts'));
    }

    public function blogsList()
    {
        $blogs = Post::all();
        return response()->json($blogs);
    }
    

    public function fetchPost()
    {
        return response()->json(Post::all());
    }

    
    public function store(PostRequest $request)
    {
        // Validation is automatically handled by the PostRequest class.
        // If validation fails, Laravel will automatically redirect back with errors.

        // Handle form data after validation
        $post = new Post();
        $post->name = $request->name;
        $post->date = $request->date;
        $post->author = $request->author;
        $post->content = $request->content;

        // Handle image upload (if present)
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $post->image = $path;
        }

        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }


    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'author' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($validated);
        return response()->json(['message' => 'Post updated successfully']);
    }


    public function edit($id)
    {
        // Retrieve the post by its ID
        $post = Post::find($id);
    
        // Check if the post exists
        if ($post) {
            // Return the post data as JSON
            return response()->json($post);
        } else {
            // If the post doesn't exist, return a 404 error with a message
            return response()->json(['message' => 'Post not found'], 404);
        }
    }
    // PostController.php

public function fetchPosts(Request $request)
{
    $query = $request->get('search', '');
    $author = $request->get('author', '');

    // Build the query
    $posts = Post::query();

    if ($query) {
        $posts->where('name', 'like', '%' . $query . '%');
    }

    if ($author) {
        $posts->where('author', $author);
    }

    // Fetch the posts
    $posts = $posts->get();

    // Return the posts as JSON
    return response()->json($posts);
}

        
    public function destroy(Post $post)
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }
}

