<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class IceAndFire extends Model {

    public function getData($url){
        $client = new Client([
            // Base URI is used with relative requests
            "base_uri" => "https://www.anapioficeandfire.com/api/",
            // You can set any number of default request options.
            "timeout"  => 20.0,
        ]);
        return $this->jsonResults($client->request("GET", $url));
    }

    public function jsonResults($response)
    {
        // return $response->getBody();
        return $response->getStatusCode() == 200 ? json_decode($response->getBody(), true) : throw new Exception('Server Error');
    }

    public function getId(string $url){
        return (int)basename($url);
    }

    public function getParameters(Request $request){
        if ($request->input('page') !== null){ $page['page'] = $request->input('page'); }
        if($request->input('pageSize') !== null){ $page['pageSize'] = $request->input('pageSize');}
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
