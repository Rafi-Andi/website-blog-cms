<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string',
        ]);

        $comment = Comment::create([
            'post_id' => $validatedData['post_id'],
            'user_id' => $request->user()->id,
            'content' => $validatedData['content'],
        ]);

        $responseData = [
            'id' => $comment->id,
            'post_id' => $comment->post_id,
            'user' => ['id' => $comment->user->id, 'name' => $comment->user->name],
            'content' => $comment->content,
            'created_at' => $comment->created_at->toIso8601String(),
        ];

        return response()->json([
            'message' => 'New Comment has been created.',
            'data' => $responseData,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'comment not found'], 404);
        }

        if ($comment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'You are not authorized to update this comment.'], 403);
        }

        $validatedData = $request->validate([
            'content' => 'required|string',
        ]);

        $comment->content = $validatedData['content'];
        $comment->save();

        return response()->json([
            'message' => 'Comment has been updated.',
            'data' => [
                'id' => $comment->id,
                'content' => $comment->content
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from stdorage.
     */
    public function destroy($id, Request $request)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'comment not found'], 404);
        }

        if ($comment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Comment has been deleted.'
        ], 200);
    }
}
