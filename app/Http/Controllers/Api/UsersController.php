<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ImageHelper;
use App\Models\Cities;
use File;
use App\Models\Profile;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class UsersController extends Controller
{

    public function index(Request $request, $action)
    {
        $action = camel_case($action);
        if(method_exists($this, $action)){
            return $this->$action($request);
        }
        return abort(404);
    }

    public function postIndex(Request $request, $action)
    {
        $action = camel_case($action);
        if(method_exists($this, $action)){
            return $this->$action($request);
        }
        return abort(404);
    }


    //GET ==================================
    public function settings($request)
    {
        $user = User::with('profile')->find(Auth::user()->id);
        return response()->json([
            'user' => $user
        ], 200);
    }

    public function getAvatar()
    {
        if(!Auth::guest()) {
            $profile = Profile::where('user_id', Auth::user()->id)->first();
            if (!empty($profile->avatar)) {
                return response()->json([
                    'avatar' => '/uploads/' . $profile->avatar
                ]);
            }
        }
        return response()->json([
            'avatar' => '/uploads/no_avatar.png'
        ]);
    }



    //POST ==================================
    public function saveSettings($request)
    {
        $this->validation($request);

        $data = $request->all();

        $profile = $request->profile;
//        $city = Cities::where('city', $profile['city_id'])->first();
//        $profile['city_id'] = $city->id;
        Profile::updateOrCreate(['user_id' => Auth::user()->id], $profile);

        unset($data['profile']);
        User::where('id', Auth::user()->id)->update($data);

        return response()->json([], 200);
    }

    public function saveAvatar(Request $request)
    {
        $file = $request->file('avatar');
        $ext = $file->getClientOriginalExtension();
        $image_name = md5(time()."-".$file->getClientOriginalName()) . '.' . $ext;
        $path = 'uploads/users/'.Auth::user()->id;
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true, true);
            }
        $file->move($path, $image_name);
        $image = Image::make(sprintf($path . '/%s', $image_name))->save();

        return json_encode([
            'filename' => '/users/'. Auth::user()->id . '/' . $image_name
        ]);
    }

    public function validation($request)
    {
        $tmp = [
            'country_id' => $request->profile['country_id'],
            'city_id' => $request->profile['city_id'],
            'avatar' => $request->profile['avatar'],
            'annotation' => $request->profile['annotation'],
            'wallet_address' => $request->profile['wallet_address'],
            'name' => $request->name,
            'old_password' => $request->old_password,
            'new_password' => $request->new_password
        ];
        $rules = [
            'country_id' => 'required',
            'city_id' => 'required',
        ];
        if(!empty($tmp->new_password)){
            $rules['new_password'] = 'min:6';
            $rules['old_password'] = 'required|old_password:' . Auth::user()->password;
            Validator::extend('old_password', function ($attribute, $value, $parameters, $validator) {
                return Hash::check($value, current($parameters));
            });
        }
        return Validator::make($tmp, $rules)->validate();
    }


}
