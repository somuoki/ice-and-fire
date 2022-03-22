<?php

namespace App\Http\Controllers;

use App\Models\IceAndFire;
use Illuminate\Http\Request;

class HouseController extends Controller
{
    public function getHouse($id){
        return $this->houses('id', $id);
//        return view('character',[
//            'character' => $this->characters('id', $id),
//        ]);
    }

    public function getHouses(Request $request){
        if (!empty($request)){
            $params = (new IceAndFire)->getParameters($request); // get parameters set by user

            $data = $this->houses($params['identifier'] ?? null, $params['value'] ?? null, $params['page'] ?? null); //get books fitting parameters

            if($request->input('sortBy') !==null){$sort = $request->input('sortBy');}
            $desc = $request->input('order') !==null ?? null;

            return isset($sort) ? $this->order($data, $sort, $desc) : $data;

        }
        return $this->houses();
    }

    public function houses($identifier=NULL, $value=NULL, array $pages=NULL){
        //frontend get all books
        $url = 'houses';

        if (isset($identifier)){
            if (! isset($value)) {
                return ['error' => 'No value set for identifier'];
            }

            $url = 'houses/?'.$identifier.'='.$value;
        }

        if (!empty($pages)){
            $url = $url == 'houses' ? $url . '/?' . http_build_query($pages) : $url . '&' . http_build_query($pages);
        }

        return (new IceAndFire)->getData($url);
    }

    public function getCharacters(array $filters, $desc = TRUE){

    }
}
