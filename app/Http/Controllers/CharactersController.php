<?php

namespace App\Http\Controllers;

use App\Models\IceAndFire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CharactersController extends Controller
{

    public function getCharacter($id){
        $character = $this->characters('id', $id);
        return (new IceAndFire)->getAge($character)['character'];
//        return view('character',[
//            'character' => $this->characters('id', $id),
//        ]);
    }

    public function getCharacters(Request $request){
            $params = $this->getParameters($request); // get parameters set by user

            $data = $this->characters($params['identifier'] ?? null, $params['value'] ?? null, $params['page'] ?? null); //get books fitting parameters

            if($request->input('sortBy') !==null){$sort = $request->input('sortBy');}
            $desc = $request->input('order') !==null ?? null;

            $characters =  isset($sort) ? $this->order($data, $sort, $desc) : $data;

            $characters_data = array();$total_age = 0;
            foreach ($characters as $character){
                $withAge = (new IceAndFire)->getAge($character,$total_age, $request);
                $characters_data[] = $withAge['character'];
                $total_age = $withAge['total_age'];
            }
        if ($request->input('gender') != null) {
            $characters_data[] = ['total_age_months' => $total_age * 12, 'total_age_years' => $total_age];
        }
            return $characters_data;
    }

    public function characters($identifier=NULL, $value=NULL, array $pages=NULL){
        //frontend get all books
        $url = 'characters';

        if (isset($identifier)){
            if(!isset($value)) {return ['error' => 'No value set for identifier']; }
            $url = $identifier == 'id' ? 'characters/'.$value : 'characters/?'.$identifier.'='.$value;
        }

        if (!empty($pages)){
            $url = $url == 'characters' ? $url . '/?' . http_build_query($pages) : $url . '&' . http_build_query($pages);
        }

        return  Cache::rememberForever($url, function () use ($url) {
            return (new IceAndFire)->getData($url);
        });
    }

    public function getParameters(Request $request){
        if ($request->input('page') !== null){ $page['page'] = $request->input('page'); }
        if($request->input('pageSize') !== null){ $page['pageSize'] = $request->input('pageSize');} else{ $page['pageSize'] = 1000; }
        if ($request->input('id' !== null)){
            return ['error' => 'End point getHouses does not accept id parameter try using getHouse'];
        }elseif ($request->input('name' !== null)){
            $identifier = 'name';
            $value = $request->input('name');
        }elseif ($request->input('gender' !== null)){
            $identifier = 'gender';
            $value = $request->input('gender');
        }elseif ($request->input('culture' !== null)){
            $identifier = 'culture';
            $value = $request->input('culture');
        }elseif ($request->input('born' !== null)){
            $identifier = 'born';
            $value = $request->input('born');
        }elseif ($request->input('died' !== null)){
            $identifier = 'died';
            $value = $request->input('died');
        }elseif ($request->input('isAlive' !== null)){
            $identifier = 'isAlive';
            $value = $request->input('isAlive');
        }
        $data['identifier'] = $identifier ?? null;
        $data['value'] = $value ?? null;
        $data['page'] = $page ?? null;

        return $data;
    }
}
