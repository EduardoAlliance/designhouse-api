<?php

namespace App\Http\Controllers\Designs;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Design;
use Illuminate\Http\Request;

class CommentsController extends Controller
{

    public function index()
    {
        //
    }

    public function store(Request $request,Design $design)
    {
        $request->validate([
            'body'=>'required'
        ]);

        $comment = $design->comments()->create([
            'body'=>$request->body,
            'user_id'=> auth()->user()->id
        ]);

        return new CommentResource($comment);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $this->authorize('update',$comment);

        $request->validate([
            'body'=>'required'
        ]);
        $comment->update([
            'body'=>$request->body
        ]);

        return new CommentResource($comment);

    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $this->authorize('update',$comment);
        $comment->delete();
        return response()->json(['message'=>'Comment deleted']);

    }
}
