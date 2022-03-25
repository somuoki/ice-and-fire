<?php
namespace App\Http\Controllers;

use App\Models\Comments;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'body' => 'required|max:500'
        ];
         $validate = Validator::make($request->all(), $rules);

         if ($validate->passes()){
             try {
                 Comments::create([
                     'book_id' => $request->input('id'),
                     'comment_ip' =>request()->ip(),
                     'body' => $request->input('body')
                 ]);
             }catch (QueryException $exception) {
                 return $exception->errorInfo;

             }

         }else{
             return $validate->errors()->all();
         }

    }
}
