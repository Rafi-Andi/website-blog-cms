<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentLikeController extends Controller
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
    public function likeComment($id, Request $request)
    {
        $userId = $request->user()->id;

        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'comment not found.'], 404);
        }

        $existingLike = CommentLike::where('user_id', $userId)
            ->where('comment_id', $id)
            ->first();

        if ($existingLike) {
            return response()->json(['message' => 'already liked this comment.'], 409);
        }

        CommentLike::create([
            'user_id' => $userId,
            'comment_id' => $id,
        ]);

        return response()->json(['message' => 'Success.'], 201);

    }


    public function unlikeComment($id)
    {
        $userId = Auth::user()->id;

        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'comment not found.'], 404);
        }

        $like = CommentLike::where('user_id', $userId)
                      ->where('comment_id', $id)
                      ->first();

        if (!$like) {
            return response()->json(['message' => 'not liked this comment.'], 409);
        }

        $like->delete();

        return response()->json(['message' => 'Like has been deleted.'], 200);
    }
    /**
     * Display the specified resource.
     */
    public function show(CommentLike $commentLike)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CommentLike $commentLike)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CommentLike $commentLike)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommentLike $commentLike)
    {
        //
    }
}
