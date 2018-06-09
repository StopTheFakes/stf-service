<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Models\Cities;
use App\Models\Geo;
use App\Models\ProfileCommands;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class UsersController extends Controller
{

    // модель пользователей
    private $model;
    private $role;

    public function __construct(){
        $this->model = new \App\User();
        $this->role  = new \App\Role();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->model->getUsers();

        return view('admin.users.users_list', [
            'users' => json_encode($users)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Auth::user()->can(['users'])){
            return abort(404);
        }

        return view('admin.users.form_user', [
            'roles' => $this->role->getRoles(),
            'countries' => [],
            'cities' => []
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $roles = $request->get('roles', []);
        $user  = $this->model->saveUser($request->all());

        $user->attachRoles($roles);

        return redirect('admin/users')->with('message', 'User "'.$request->get('name').'" has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $action = camel_case($id);

        if(method_exists($this, $action)){
            return $this->$action($request);
        }
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!Auth::user()->can(['users'])){
            return abort(404);
        }
        $user = $this->model->getUser($id);
        $user_roles     = $user->roles()->get()->toArray();
        $user_roles_ids = array_column($user_roles, 'id');


        return view('admin.users.form_user', [
            'user'  => $user,
            'roles' => $this->role->getRoles(),
            'user_roles_ids' => $user_roles_ids,
//            'countries' => [],
//            'cities' => []
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->model->updateUser($request->all(), $id);

        $roles = $request->get('roles', []);
        $user  = $this->model->getUser($id);
        $user->detachRoles();
        $user->attachRoles($roles);

        return redirect('admin/users')->with('message', 'User "'.$request->get('name').'" has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!Auth::user()->can(['crm_users_delete', 'crm_users_full'])){
            return abort(404);
        }
        $this->model->deleteUser($id);

        return redirect('admin/users');
    }


    public function reports(){

        if(!Auth::user()->can('reports')){
            return abort(404);
        }

    }


    public function delete($request){

        User::where('id', $request->get('id'))->delete();
        return redirect('admin/users')
                ->with('message', 'Пользователь успешно удален');

    }


    public function commands($request)
    {
        $commands = ProfileCommands::with('user:id,name')->get();
        return view('admin.users.commands', compact('commands'));
    }


    public function addCommand($request)
    {
        $users = User::all();
        return view('admin.users.add_command', compact('users'));
    }


    public function storeCommand(Request $request)
    {
        $data = $request->all();
        $data['preview'] = isset($data['preview']) ? $this->moveImages($data['preview']) : '' ;

        ProfileCommands::create($data);

        return redirect('admin/users/commands');
    }


    public function updateCommand(Request $request, $id)
    {
        $user = ProfileCommands::findOrFail($id);
        $data = $user->fill($request->all());
        $data['preview'] = isset($data['preview']) ? $this->moveImages($data['preview']) : '' ;

        $data->save();

        return redirect('admin/users/commands');
    }


    public function editCommand($id)
    {
        $user = ProfileCommands::findOrFail($id);
        $users = User::all();

        return view('admin.users.add_command',compact('user', 'users'));
    }


    public function deleteCommand(Request $request){

        ProfileCommands::where('id', $request->get('id'))->delete();
        return redirect('admin/users/commands')
            ->with('message', 'Участник комманды успешно удален');

    }


    public function moveImages($images){

        if(is_array($images)) {
            foreach ($images as $image) {
                if(strpos($image, '/uploads/users/') === false) {
                    $img[] = ImageHelper::moveTempFile('/uploads/tmp/' . $image, 'users', config('images.users'));
                }else{
                    $img[] = $image;
                }
            }
        }else{
            if(strpos($images, '/uploads/users/') === false) {
                $img = ImageHelper::moveTempFile('/uploads/tmp/' . $images, 'users', config('images.users'));
            }else{
                $img = $images;
            }
        }

        return isset($img) ? str_replace('/uploads/users/', '', $img) : [];

    }

}
