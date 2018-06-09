<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cities;
use App\Models\Geo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GeoController extends Controller
{

    protected $model;

    public function __construct()
    {
        $this->model = new Geo();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = $this->model->get();
        return view('admin.geo.index', [
            'countries' => $countries
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.geo.add_countries');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->model->saveCountries($request->all());
        return redirect('admin/geo')->with('message', 'New countries has been added');
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
        $country = $this->model->getCountry($id);
        return view('admin.geo.edit_country', [
            'country' => $country
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
        $this->model->updateCountry($id, $request->all());
        return redirect('admin/geo')->with('message', 'Country "' . $request->get('country') . '" has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getCities($request)
    {

        if (!$request->ajax()) {
            return abort(404);
        }
        $city_model = new Cities();
        $cities = $city_model->where('country_id', $request->get('country_id'))->get();

        return view('admin.geo.cities', [
            'cities' => $cities
        ]);

    }


    public function deleteCity($id)
    {
        $city_model = new Cities();
        $city_model->where('id', $id)->delete();
        return back()
            ->with('message', 'City has been deleted');
    }


    public function showCity($id)
    {

        $city = Cities::find($id);
        return view('admin.geo.city_form', compact('city'));

    }


    public function updateCity(Request $request, $id){

        $data = $request->all();
        unset($data['_token']);
        unset($data['files']);
        Cities::where('id', $id)->update($data);

        return redirect('admin/geo')
            ->with('message', 'Город "'.$request->get('city').'" успешно обновлен');

    }

}