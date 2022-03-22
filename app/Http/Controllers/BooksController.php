<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\IceAndFire;
use Illuminate\Http\Request;

class BooksController  extends Controller {

    public function index(Request $request){
        $data['data'] = $this->order($this->books('','',['pageSize'=>100]), 'name', TRUE);
        return view('home',$data);
    }

    public function getBook($id){
        return json_encode($this->books('id', $id));
    }

    public function getBooks(Request $request){
        if (!empty($request)){
            $params = (new IceAndFire)->getParameters($request); // get parameters set by user

            $data = $this->books($params['identifier'] ?? null, $params['value'] ?? null, $params['page'] ?? null); //get books fitting parameters

            if($request->input('sortBy') !==null){$sort = $request->input('sortBy');}
            $desc = $request->input('order') !==null ?? null;

            return isset($sort) ? $this->order($data, $sort, $desc) : $data;

        }
        return json_encode($this->books());
    }

    public function books($identifier=NULL, $value=NULL, array $pages=NULL){
        //frontend get all books
        $url = 'books';

        if (!empty($identifier)){
            if(empty($value)) {return ['error' => 'No value set for '.$identifier]; }

            $url = $identifier == 'id' ? 'books/'.$value : 'books/?'.$identifier.'='.$value;
        }

        if (!empty($pages)){
            echo $url;
            $url = $url == 'books' ? $url . '/?' . http_build_query($pages) : $url . '&' . http_build_query($pages);
        }

        $books = (new IceAndFire)->getData($url);
        if (is_array($books)){
            foreach ($books as $book) {
                $this->getComments($book);
            }
        }else{
            $this->getComments($books);
        }


       return $books;
    }

    public function getComments($book){
            $id = (new IceAndFire)->getId($book->url);

            $data = Comments::getComments($id);

            $book->id = $id;
            $book->comment_count = count($data);
            $book->comments = $data;

        return $book;
    }

}
