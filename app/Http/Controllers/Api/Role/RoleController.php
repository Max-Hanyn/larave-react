<?php

namespace App\Http\Controllers\Api\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Role\CreateRoleRequest;
use App\Http\Requests\Api\Role\DeleteRoleRequest;
use App\Http\Requests\Api\Role\UpdateRoleRequest;
use App\Models\Roles;
use App\Services\Api\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function getAllRoles(): JsonResponse
    {
        $roles = $this->roleService->getAllRoles();

        return response()->json(['roles' => $roles]);
    }

    public function addRole(CreateRoleRequest $request): JsonResponse
    {
        $data = $request->validated();

        $role = $this->roleService->addRole($data);

        return response()->json(['role' => $role]);

    }

    public function updateRole(UpdateRoleRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $role = $this->roleService->updateRole($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json(['role' => $role]);

    }

    public function removeRole(DeleteRoleRequest $request): JsonResponse
    {
        $roleId = $request->get('id');
        try {
            $deleted = $this->roleService->removeRole($roleId);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json(['deleted' => $deleted]);
    }
}
