<?php

namespace App\Http\Controllers;

use App\Models\IceAndFire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HouseController extends Controller
{
    public function getHouse($id){
        return $this->houses('id', $id);
//        return view('character',[
//            'character' => $this->characters('id', $id),
//        ]);
    }

    public function getHouses(Request $request){

            $params = (new IceAndFire)->getParameters($request); // get parameters set by user

            $data = $this->houses($params['identifier'] ?? null, $params['value'] ?? null, $params['page'] ?? null); //get books fitting parameters

            if($request->input('sortBy') !==null){$sort = $request->input('sortBy');}
            $desc = $request->input('order') !==null ?? null;

            $houses = isset($sort) ? $this->order($data, $sort, $desc) : $data;
            $house_data = array();
            foreach ($houses as $house){
                $house['id'] = (new IceAndFire)->getId($house['url']);
                $house_data[] = $house;
            }
            return $house_data;


    }

    public function houses($identifier=NULL, $value=NULL, array $pages=NULL){
        //frontend get all books
        $url = 'houses';

        if (isset($identifier)){
            if (! isset($value)) {
                return ['error' => 'No value set for identifier'];
            }
            $url = $identifier == 'id' ? 'houses/'.$value : 'houses/?'.$identifier.'='.$value;
        }

        if (!empty($pages)){
            $url = $url == 'houses' ? $url . '/?' . http_build_query($pages) : $url . '&' . http_build_query($pages);
        }

        return  Cache::rememberForever($url, function () use ($url) {
            return (new IceAndFire)->getData($url);
        });
    }


//    public function getCharacters(array $filters, $desc = TRUE){  {{-- TODO --}}
//
//    }

    public function getParameters(Request $request){
        if ($request->input('page') !== null){ $page['page'] = $request->input('page'); }
        if($request->input('pageSize') !== null){ $page['pageSize'] = $request->input('pageSize');} else{ $page['pageSize'] = 1000; }
        if ($request->input('id' !== null)){
            return ['error' => 'End point getHouses does not accept id parameter try using getHouse'];
        }elseif ($request->input('name' !== null)){
            $identifier = 'name';
            $value = $request->input('name');
        }elseif ($request->input('region' !== null)){
            $identifier = 'region';
            $value = $request->input('region');
        }elseif ($request->input('words' !== null)){
            $identifier = 'words';
            $value = $request->input('words');
        }elseif ($request->input('hasWords' !== null)){
            $identifier = 'hasWords';
            $value = $request->input('hasWords');
        }elseif ($request->input('hasTitles' !== null)){
            $identifier = 'hasTitles';
            $value = $request->input('hasTitles');
        }elseif ($request->input('hasSeats' !== null)){
            $identifier = 'hasSeats';
            $value = $request->input('hasSeats');
        }elseif ($request->input('hasDiedOut' !== null)){
            $identifier = 'hasDiedOut';
            $value = $request->input('hasDiedOut');
        }elseif ($request->input('hasAncestralWeapons' !== null)){
            $identifier = 'hasAncestralWeapons';
            $value = $request->input('hasAncestralWeapons');
        }

        $data['identifier'] = $identifier ?? null;
        $data['value'] = $value ?? null;
        $data['page'] = $page ?? null;

        return $data;
    }
}
