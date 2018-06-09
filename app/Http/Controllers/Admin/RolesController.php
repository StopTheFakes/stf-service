<?php

namespace App\Http\Controllers\Admin;

use App\Role;
use App\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class RolesController extends Controller
{
    private $model;
    private $permission;
    public function __construct()
    {

        $this->model = new Role();
        $this->permission  = new Permission();

        $this->middleware('Roles');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()    {
        $roles = $this->model->getRoles(true);

        return view('admin.users.roles_list', [
            'roles' => json_encode($roles)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.form_role', [
            'permissions' => $this->permission->getPermissions(),
            'user' => Auth::user()
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
        $this->model->saveRole($request->all());
        return redirect('admin/roles')->with('message', 'Role "'.$request->get('display_name').'" has ben created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role           = $this->model->getRole($id);
        $role_perms     = $role->perms()->get()->toArray();
        $role_perms_ids = array_column($role_perms, 'id');

        return view('admin.users.form_role', [
            'role'           => $role,
            'permissions'    => $this->permission->getPermissions(),
            'role_perms_ids' => $role_perms_ids,
            'user' => Auth::user()
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
        $this->model->updateRole($request->all(), $id);

        $permissions = $request->get('permissions', []);
        $role        = $this->model->getRole($id);

        $role->detachPermissions();
        $role->attachPermissions($permissions);

        return redirect('admin/roles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

        $this->model->deleteRole($id);

        if ($request->ajax()) {
            return json_encode(['done' => true]);
        }
        return redirect('admin/roles');
    }
}
