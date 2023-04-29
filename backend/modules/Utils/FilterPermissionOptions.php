<?php

namespace Utils;

use Permissions\Permission;

/**
 * Class FilterPermissionOptions
 * @package Utils
 */
class FilterPermissionOptions
{
    public static function unsetPermissions(string $permissionToUnset, string $roleToUnset)
    {
        $filteredPermissions = Permission::PERMISSIONS;
        $loggedUser = auth()->user();

        if ($loggedUser->role == $roleToUnset) {
            $index = array_search($permissionToUnset, $filteredPermissions);

            unset($filteredPermissions[$index]);
        }

        return $filteredPermissions;
    }
}
