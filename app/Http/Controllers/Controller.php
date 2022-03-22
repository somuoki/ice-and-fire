<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use ApiResponse;

    public function order($data, $sortBy, $desc=FALSE){
        $column = array_column($data, $sortBy);
        $desc == TRUE ? array_multisort($column, SORT_DESC, $data) : array_multisort($column, SORT_ASC, $data);
        return $data;
    }
}
