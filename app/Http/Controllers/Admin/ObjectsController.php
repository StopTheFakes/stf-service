<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ObjectsController extends Controller
{

    protected $model;
    public $essence = "object";

    public function __construct()
    {
        $className = 'App\Models\\' . ucfirst(str_plural($this->essence));
        $this->model = new $className();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = $this->model->get();
        return view('admin.' . str_plural($this->essence) . '.index', [
            'items' => $items,
            'essence' => $this->essence,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.' . str_plural($this->essence) . '.form', [
            'essence' => $this->essence,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);
        unset($data['files']);

        $this->model->insert($data);
        return redirect('admin/' . str_plural($this->essence))->with('message', 'New ' . $this->essence . ' has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $action = camel_case($id);
        if (method_exists($this, $action)) {
            return $this->$action($request);
        }
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = $this->model->find($id);
        return view('admin.' . str_plural($this->essence) . '.form', [
            'item' => $item,
            'essence' => $this->essence,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        unset($data['files']);
        unset($data['_method']);

        $this->model->where('id',$id)->update($data);
        return redirect('admin/' . str_plural($this->essence))->with('message', ucfirst(str_plural($this->essence)) .' has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($request)
    {
        $this->model->where('id', $request->id)->delete();
        return redirect('admin/' . str_plural($this->essence) )
            ->with('message', ucfirst(str_plural($this->essence)) . ' successfully deleted');
    }


}