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



    public function getAge($character, $total_age=0, Request $request = null)
    {

        $character['id'] = $this->getId($character['url']);
        $born = preg_replace('/\D/', '', $character['born']);
        $died = preg_replace('/\D/', '', $character['died']);
        if ($died != '' && $born != '') {
            $character['age'] = $died - $born;
        } elseif ($born != '' && $died == '') {
            $possible_age = 305 - $born;
            $character['age'] = $possible_age > 104 ? 'Time of death Unknown' : $possible_age;
        } else {
            $character['age'] = 'Unknown';
        }
        if($request != null){
            if ($request->input('gender') != null) {
                $age = is_numeric($character['age']) ? $character['age'] : 0;
                $total_age += $age;
            }
        }
        return ['character' => $character, 'total_age' => $total_age];
    }
}
