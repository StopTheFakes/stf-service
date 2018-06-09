<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class TestController extends Controller
{
    public function show($method){

        if(method_exists($this, $method)){
            return $this->$method();
        }

        abort(404);
    }

    public function confirm(){
        echo ' confirm';

        $client = new Client();

        $res = $client->request('POST', 'http://stf/api/auth/confirmation', [
            'auth' => ['a@a.aa', '123123'],
            'form_params' => [
                'code' => '2296'            ]
        ]);
//dd($res);
        echo $res->getBody();
    }
}
