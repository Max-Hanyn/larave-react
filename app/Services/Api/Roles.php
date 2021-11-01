<?php


namespace App\Services\Api;

use App\Models\Roles as RolesModel;

class Roles
{

    const USER_ROLE_SLUG = 'user';
    const ADMIN_ROLE_SLUG = 'admin';
    const MODERATOR_ROLE_SLUG = 'moderator';

    /**
     * @param string $slug
     * @return int
     */
    public function getRoleIdBySlug(string $slug): int{

        return RolesModel::where('slug', $slug)->first()['id'];
    }



}
