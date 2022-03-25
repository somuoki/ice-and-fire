<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Comments extends Model {
    use HasFactory;

    protected $fillable = [
        'book_id', 'comment_ip', 'body'
    ];

    public static function getComments(int $id)    {
        return DB::table('comments')->where('book_id', $id)->orderBy('created_at', 'desc')->get();

//        where('book_id', $id)
//            ->orderBy('created_at','desc')
//            ->get()
    }
}
