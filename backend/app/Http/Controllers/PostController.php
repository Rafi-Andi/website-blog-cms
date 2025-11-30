<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts',
            'content' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $post = Post::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'content' => $validated['content'] ?? null,
            'category_id' => $validated['category_id'],
            'user_id' => $user->id,
        ]);

        $post->load('user:id,name', 'category:id,name');

        return response()->json([
            'message' => 'New Post has been created.',
            'data' => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'content' => $post->content,
                'user' => [
                    'id' => $post->user->id,
                    'name' => $post->user->name
                ],
                'category' => [
                    'id' => $post->category->id,
                    'name' => $post->category->name
                ],
                'created_at' => $post->created_at->toIso8601String(),
            ],
        ], 201);
    }

    public function index(Request $request)
    {
        $posts = Post::select('id', 'title', 'slug', 'created_at', 'content', 'user_id', 'category_id')
            ->with('user:id,name', 'category:id,name')
            ->get()
            ->makeHidden(['content']);

        return response()->json([
            "data" => $posts
        ], 200);
    }
    public function MyPosts(Request $request)
    {
        $posts = Post::select('id', 'title', 'slug', 'created_at', 'content', 'user_id', 'category_id')
            ->with('user:id,name', 'category:id,name')->where('user_id', $request->user()->id)
            ->get()
            ->makeHidden(['content']);

        return response()->json([
            "data" => $posts
        ], 200);
    }

    public function show(string $id)
    {
        $userId = Auth::id();

        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                "message" => "Post not found"
            ], 400);
        }

        $post->load([
            'user:id,name',
            'category:id,name',
        ]);


        $post->load([
            'comments' => function ($query) use ($userId) {
                $query->select('id', 'post_id', 'user_id', 'content', 'created_at')
                    ->withCount('comment_likes as likes_count')
                    ->withExists([
                        'comment_likes' => function ($subQuery) use ($userId) {
                            $subQuery->where('user_id', $userId);
                        }
                    ]);
            },
            'comments.user:id,name'
        ]);

        if ($post->comments) {
            $post->comments->each(function ($comment) {
                $comment->setAttribute('is_liked', $comment->comment_likes_exists ?? false);
                unset($comment->comment_likes_exists);
            });
        }


        return response()->json([
            "data" => $post
        ], 200);
    }

 
    public function update(Request $request, string $id)
    {
        $post = Post::with('user:id')->find($id);
        if (!$post) {
            return response()->json([
                "message" => "Post not found"
            ], 400);
        }

        $user = Auth::user();

        if ($user->id !== $post->user->id) {
            return response()->json([
                "message" => "You are not authorized to update this post."
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts',
            'content' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $post->update($validated);

        return response()->json([
            "message" => "Post has been updated.",
            "data" => [
                "id" => $post['id'],
                "title" => $post['title'],
                "slug" => $post['slug']
            ]
        ]);
    }

    public function destroy(string $id)
    {
        $post = Post::with('user:id')->find($id);
        $user = Auth::user();
        if (!$post) {
            return response()->json([
                "message" => "Post not found"
            ], 400);
        }
        if ($user->id !== $post->user->id) {
            return response()->json([
                "message" => "You are not authorized to delete this post."
            ], 403);
        }


        $post->delete();

        return response()->json(['message' => "Post has been deleted"], 200);
    }
}
