<?php
namespace App\Http\Controllers;

use App\Models\Comments;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function store(Request $request)
    {
        request()->validate([
            'body' => 'required|max:500'
        ]);

        Comments::create([
            'book_id' => $request->input('book'),
            'comment_ip' =>request()->ip(),
            'body' => $request->input('body')
        ]);

        return back();
    }
}
