<?php

namespace App\Http\Controllers\Api;

use App\Models\Messages\Moderator;
use App\Models\Messages\ModeratorChannel;
use App\Models\Messages\Support;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;

class MessagesController extends Controller
{

    public function sendMessage(Request $request)
    {
        Validator::make($request->all(), [
            'message' => 'required|string'
        ])->validate();

        $channel = ModeratorChannel::firstOrCreate(['client' => Auth::user()->id]);

        $data = [
            'user_from' => Auth::user()->id,
            'user_to' => 0,
            'channel_id' => $channel->id,
            'message' => $request->message
        ];

        if($request->type == 'moderator') {
            Moderator::create($data);
        }
        if($request->type == 'support') {
            Support::create($data);
        }

        return response()->json([]);
    }


    public function getMessages($type)
    {
        if($type == 'moderator'){
            $messages = Moderator::with(['from.profile', 'to.profile'])
                        ->where('user_from', Auth::user()->id)
                        ->orWhere('user_to', Auth::user()->id)
                        ->get();

            Moderator::where('user_to', Auth::user()->id)
                        ->where('status', 0)
                        ->update(['status' => 1]);

            return response()->json([
                'messages' => $messages,
                'user_id' => Auth::user()->id,
                'moderator' => $messages->isNotEmpty() && $messages->last()->from->id != Auth::user()->id ? $messages->last()->from : []
            ], 200);
        }

        if($type == 'support'){
            $messages = Support::with(['from.profile', 'to.profile'])
                ->where('user_from', Auth::user()->id)
                ->orWhere('user_to', Auth::user()->id)
                ->get();

            Support::where('user_to', Auth::user()->id)
                ->where('status', 0)
                ->update(['status' => 1]);

            return response()->json([
                'messages' => $messages,
                'user_id' => Auth::user()->id,
                'support' => $messages->isNotEmpty() && $messages->last()->from->id != Auth::user()->id ? $messages->last()->from : []
            ], 200);
        }

        return abort(404);
    }


    public function unread()
    {
        $count = Moderator::where('user_to', Auth::user()->id)->where('status', 0)->count();
        return response()->json([
            'count' => $count
        ], 200);
    }

}
