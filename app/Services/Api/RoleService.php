<?php


namespace App\Services\Api;

use App\DTO\Role\RoleDto;
use App\Models\Roles;
use App\Models\Roles as RolesModel;
use Exception;

class RoleService
{

    const USER_ROLE_SLUG = 'user';
    const ADMIN_ROLE_SLUG = 'admin';
    const MODERATOR_ROLE_SLUG = 'moderator';

    /**
     * @param string $slug
     * @return int
     */
    public function getRoleIdBySlug(string $slug): int
    {

        return RolesModel::where('slug', $slug)->first()['id'];
    }

    /**
     * @return RoleDto[]
     */
    public function getAllRoles(): array
    {
        $roles = Roles::all();
        $rolesDTO = [];
        $roles->each(function ($item, $key) use (&$rolesDTO) {
            $rolesDTO[] = RoleDto::fromModel($item);
        });

        return $rolesDTO;
    }

    /**
     * @param array $data
     * @return RoleDto
     */
    public function addRole(array $data): RoleDto
    {
        $role = Roles::create([
            'name' => trim($data['name']),
            'slug' => trim(strtolower($data['slug']))
        ]);

        return RoleDto::fromModel($role);

    }

    /**
     * @param array $data
     * @return RoleDto
     * @throws Exception
     */
    public function updateRole(array $data): RoleDto
    {
        $role = Roles::find($data['id']);

        if (!$role) {
            throw new Exception('No such role');
        }

        $role->name = $data['name'];
        $role->save();

        return RoleDto::fromModel($role);

    }

    /**
     * @param int $roleId
     * @return bool
     * @throws Exception
     */
    public function removeRole(int $roleId): bool
    {
        $role = Roles::find($roleId);

        if (!$role) {
            throw new Exception('No such role');
        }

        return $role->delete();

    }


}
