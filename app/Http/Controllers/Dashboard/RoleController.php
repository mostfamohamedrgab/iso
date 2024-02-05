<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use  App\Repositories\IRoleRepository;
use  App\Repositories\IPermissionRepository;
use App\Requests\dashboard\CreateUpdateRoleRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Traits\Roles;

class RoleController extends Controller
{
    private $rolesRepository;
    use Roles;
    public function __construct(IRoleRepository $rolesRepository,
                               IPermissionRepository $permissionRepository){

            $this->rolesRepository = $rolesRepository;
            $this->permissionRepository = $permissionRepository;
       
    }

    public function index()
    {
        $roles = $this->rolesRepository->getWhere([['id' ,'>' , 1]]);
        return view('dashboard.roles.index' , compact('roles'));
    }

    public function create()
    {
        $permissions = $this->permissionRepository->getAll();
        $html = $this->addRole();
        return view('dashboard.roles.create' , compact('permissions' , 'html'));
    }

    public function store(CreateUpdateRoleRequest $request)
    {
        $data = $request->except('permissions');
        $data['name'] = str_replace(' ','-',$request->nickname_en);
        $data['nickname_ar'] = $request->nickname_en;

        $role = $this->rolesRepository->create($data);
        $role->syncPermissions($request->permissions);

        return response()->json();
    }


    public function edit($id)
    {
        $role = $this->rolesRepository->findOne($id);
        $html = $this->editRole($id);
        return view('dashboard.roles.edit' , compact('role' , 'html'));
    }

    public function update(CreateUpdateRoleRequest $request , $id)
    {
        $data = $request->validated();
        $data['name'] = str_replace(' ','-',$request->nickname_en);
        $data['nickname_ar'] = $request->nickname_en;

        $this->rolesRepository->update($data , $id);
        $role = $this->rolesRepository->findOne($id);
        $role->syncPermissions($request->permissions);
        return response()->json();
    }


    public function destroy($id)
    {
        $this->rolesRepository->forceDelete($id);
        return response()->json();

    }


    
    public function deleteAll(Request $request) {
        $requestIds = json_decode($request->data);
    
        foreach ($requestIds as $id) {
          $ids[] = $id->id;
        }
        if ($this->rolesRepository->deleteForceWhereIn('id', $ids)) {
          return response()->json('success');
        } else {
          return response()->json('failed');
        }
    }

}