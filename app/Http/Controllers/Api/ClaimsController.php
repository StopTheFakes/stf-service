<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Front\ClaimCreateRequest;
use App\Models\Cities;
use App\Models\File;
use App\Models\Geo;
use App\Models\Objects;
use App\Models\Topics;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Claim;
use Auth;
use Illuminate\Support\Facades\DB;

class ClaimsController extends Controller
{

    protected $model;

    public function __construct()
    {
        $this->model = new Claim();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $claims = Claim::all();

        return response()->json(['requests'=>$claims]);
    }

    /**
     * Posts actions.
     *
     * @return \Illuminate\Http\Response
     */
    public function postAction(ClaimCreateRequest $request, $action)
    {
        $action = camel_case($action);

        if(method_exists($this, $action)){
            return $this->$action($request);
        }

        return response()->json([
            'error' => 'Method not allowed'
        ], 405);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createRequest($request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $data['accepted'] = $data['selected'];
        //$data['city_id'] = implode(',', $data['city_id']);

        $id = Claim::create($data);

        return response()->json(['ok'=>true],200);
    }

    public function copyRequest($request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        $id = Claim::create($data);

        return response()->json([
            'ok'=>true,
            'projects' => Claim::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get()
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $claim = Claim::find($id);

        if($claim){
            return response()->json([
                'ok'=>true,
                'request'=> $claim
                ], 200);
        }

        return response()->json([], 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $claim = Claim::find($id);

        if($claim){
            return response()->json([
                'ok'=>true,
                'request'=> $claim
            ], 200);
        }

        return response()->json([], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateRequest($request)
    {
        $claim = Claim::find($request->id);
        $data = $request->all();

        $data['user_id'] = Auth::user()->id;
        //$data['city_id'] = implode(',', $data['city_id']);
        $data['accepted'] = $data['selected'];

        $claim->fill($data);

        $claim->save();

        return response()->json(['ok'=>true],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $claim = Claim::find($id);

        if($claim){
            $claim->delete();

            return response()->json([
                'ok'=>true,
            ],200);
        }

        return response()->json([], 404);
    }


    /**
     * Get actions.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAction(Request $request, $action)
    {
        $action = camel_case($action);

        if(method_exists($this, $action)){
            return $this->$action($request);
        }

        return response()->json([
            'error' => 'Method not allowed'
        ], 405);
    }

    /**
     * Set posts actions.
     *
     * @return \Illuminate\Http\Response
     */
    public function setPost(Request $request, $action)
    {
        $action = camel_case($action);

        if(method_exists($this, $action)){
            return $this->$action($request);
        }

        return response()->json([
            'error' => 'Method not allowed'
        ], 405);
    }

    public function get($request)
    {
        $projects = Claim::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        $current = $projects->where('current', 1)->count();
        $hold = $projects->where('current', 0)->count();

        return response()->json([
            'projects' => $projects,
            'current' => $current,
            'hold' => $hold,
            'count' => Claim::all()->count()
        ], 200);
    }

    public function guestRequests()
    {
        $projects = Claim::where('status', 1)->get();
        $offline = $projects->where('type', 'offline')->count();
        $online = $projects->where('type', 'online')->count();

        return response()->json([
            'projects' => $projects,
            'offline' => $offline,
            'online' => $online,
            'count' => Claim::where('status', 1)->count()
        ], 200);
    }


    public function search(Request $request)
    {
        $projects = $this->model->getSearch($request->all());

        return response()->json([
            'projects' => $projects,
            'count' => Claim::all()->count()
        ], 200);
    }


    public function doer($request)
    {
        $projects = $this->model->getDoerFilter($request->type);

        return response()->json([
            'projects' => $projects
        ], 200);
    }


    public function countries($request)
    {
        $countries = Geo::all();
        return response()->json([
            'countries' => $countries
        ], 200);
    }


    public function cities($request)
    {
        $cities = Cities::where('country_id', $request->country_id)->get();
        return response()->json([
            'cities' => $cities
        ], 200);
    }


    public function topics($request)
    {
        $topics = Topics::all();
        return response()->json([
            'topics' => $topics
        ], 200);
    }

    public function objects($request)
    {
        $objects = Objects::all();
        return response()->json([
            'objects' => $objects
        ], 200);
    }


    public function setCurrent(Request $request)
    {
        $current = $request->current == 1 ? 0 : 1;
        Claim::where('id', $request->id)->update(['current' => $current]);

        return response()->json(['current' => $current], 200);
    }


    public function getRequest(Request $request)
    {
        $project = Claim::with(['author.profile', 'country', 'cities', 'doer' => function($q){
            $q->where('user_id', Auth::check() ? Auth::user()->id : 0);
        }])->where('id', $request->id)->first();
        if(Auth::check()) {
            $doer = DB::table('claims_doer')
                ->where('claim_id', $project->id)
                ->where('user_id', Auth::user()->id)
                ->first();
        }
        //$project->country_id = $project->country->first()->id;
        $project->rights = empty($project->rights) ? [] : $project->rights;
        $project->selected = $project->accepted;
        if(isset($doer) && !empty($doer) && Auth::user()->type != 1){
            $time = date('Y-m-d H:i:s', strtotime($doer->created_at) + 129600);
        }
        return response()->json([
            'project' => $project,
            'doer' => $doer ?? '',
            'time' => isset($time) ? $time: false,
            'country' => Geo::find($project->country_id),
            'cities' => $project->city_id,
            'objects' => Objects::all(),
            'selected' => $project->accepted
        ], 200);
    }

    public function takeTask($request)
    {
        if(!$request->isMethod('post')){return abort(404);}
        $task = $this->model->takeTask($request->id, Auth::user()->id);
        return response()->json([
            'task' => $task,
        ], 200);
    }

}
