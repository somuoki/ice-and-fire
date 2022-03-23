<?php

namespace App\Http\Controllers;

use App\Models\IceAndFire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CharactersController extends Controller
{

    public function getCharacter($id){
        return $this->characters('id', $id);
//        return view('character',[
//            'character' => $this->characters('id', $id),
//        ]);
    }

    public function getCharacters(Request $request){
        if (!empty($request)){
            $params = (new IceAndFire)->getParameters($request); // get parameters set by user

            $data = $this->characters($params['identifier'] ?? null, $params['value'] ?? null, $params['page'] ?? null); //get books fitting parameters

            if($request->input('sortBy') !==null){$sort = $request->input('sortBy');}
            $desc = $request->input('order') !==null ?? null;

            return isset($sort) ? $this->order($data, $sort, $desc) : $data;

        }
        return $this->characters();
    }

    public function characters($identifier=NULL, $value=NULL, array $pages=NULL){
        //frontend get all books
        $url = 'characters';

        if (isset($identifier)){
            if(!isset($value)) {return ['error' => 'No value set for identifier']; }

            $url = 'characters/?'.$identifier.'='.$value;
        }

        if (!empty($pages)){
            $url = $url == 'characters' ? $url . '/?' . http_build_query($pages) : $url . '&' . http_build_query($pages);
        }

        return  Cache::rememberForever($url, function () use ($url) {
            return (new IceAndFire)->getData($url);
        });
    }
}
