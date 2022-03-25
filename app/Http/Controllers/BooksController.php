<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\IceAndFire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BooksController  extends Controller {

    public function index(Request $request){
        $data['data'] = $this->order($this->books('','',['pageSize'=>100]), 'name', TRUE);
        return view('home',$data);
    }

    public function getBook(Request $request, $id){
        return $this->books('id', $id);
    }

    public function getBooks(Request $request){
        if (!empty($request)){
            $params = $this->getParameters($request); // get parameters set by user

            $data = $this->books($params['identifier'] ?? null, $params['value'] ?? null, $params['page'] ?? null); //get books fitting parameters

            if($request->input('sortBy') !==null){$sort = $request->input('sortBy');}
            $desc = $request->input('order') !==null ?? null;

            return isset($sort) ? $this->order($data, $sort, $desc) : $data;

        }
        return $this->books();
    }

    public function books($identifier=NULL, $value=NULL, array $pages=NULL){
        //frontend get all books
        $url = 'books';

        if (!empty($identifier)){
            if(empty($value)) {return ['error' => 'No value set for '.$identifier]; }

            $url = $identifier == 'id' ? 'books/'.$value : 'books/?'.$identifier.'='.$value;
        }

        if (!empty($pages)){
            $url = $url == 'books' ? $url . '/?' . http_build_query($pages) : $url . '&' . http_build_query($pages);
        }
        //        $books = (new IceAndFire)->getData($url);



       return Cache::remember($url, 86400, function () use ($url){
           $books =  (new IceAndFire)->getData($url);
           $books_category = array();
           if (is_array($books) && ! array_key_exists('url', $books)){
               foreach ($books as $book) {
                   $books_category[] = $this->getComments($book);
               }
           }else{
               $books_category = $this->getComments($books);
           }
           return $books_category;
       });
    }

    public function getComments($book){

            $id = (new IceAndFire)->getId($book['url']);

            $data = Comments::getComments($id);

            $book['id'] = $id;
            $book['comment_count'] = count($data);
            $book['comments'] = $data;

        return $book;
    }

    public function getParameters(Request $request){
        if ($request->input('page') !== null){ $page['page'] = $request->input('page'); }
        if($request->input('pageSize') !== null){ $page['pageSize'] = $request->input('pageSize');} else{ $page['pageSize'] = 1000; }
        if ($request->input('id' !== null)){
            return ['error' => 'End point getBooks does not accept id parameter try using getBook'];
        }elseif ($request->input('name' !== null)){
            $identifier = 'name';
            $value = $request->input('name');
        }elseif ($request->input('fromReleaseDate' !== null)){
            $identifier = 'fromReleaseDate';
            $value = $request->input('fromReleaseDate');
        }elseif ($request->input('toReleaseDate' !== null)){
            $identifier = 'toReleaseDate';
            $value = $request->input('toReleaseDate');
        }
        $data['identifier'] = $identifier ?? null;
        $data['value'] = $value ?? null;
        $data['page'] = $page ?? null;

        return $data;
    }

}
