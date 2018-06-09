<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RequestsController extends Controller
{

    public function show(Request $request, $action = false)
    {
        if ($action) {
            $action = camel_case($action);
            if (method_exists($this, $action)) {
                return $this->$action($request);
            }
            return abort(404);
        }
        return abort(404);
    }

    public function create()
    {
        return view('front.requests.create');
    }


    public function getRequest($id)
    {
        return view('front.requests.show', compact('id'));
    }

    public function edit($id)
    {
        return view('front.requests.create', [
            'id' => $id
        ]);
    }

}
