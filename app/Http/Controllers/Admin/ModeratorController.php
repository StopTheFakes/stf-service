<?php

namespace App\Http\Controllers\Admin;

use App\Models\Messages\ModeratorChannel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ModeratorController extends Controller
{

    public function show($action)
    {
        if(is_numeric($action)){
            return $this->getChannel($action);
        }
        $action = camel_case($action);
        if(method_exists($this, $action)){
            return $this->$action();
        }
        return abort(404);
    }


    public function all()
    {
        $channels = ModeratorChannel::with(['user', 'unread' => function($q){
            $q->where('user_to', 0)->where('status', 0);
        }])->get();

        return view('admin.moderator.index', compact('channels'));
    }


    public function getChannel($id)
    {
        $channel = ModeratorChannel::with(['user', 'messages'])->where('id', $id)->first();
        return view('admin.moderator.channel', compact('channel'));
    }

}
