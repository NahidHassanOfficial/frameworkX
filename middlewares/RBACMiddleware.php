<?php

class RBACMiddleware
{
    public static function allow(Request $request, ...$permissions)
    {
        $allowed = false;
        foreach ($permissions as $permission) {
            if (isNotEmpty($_SESSION, 'PERMISSIONS')) {
                if (in_array($permission, $_SESSION['PERMISSIONS'])) {
                    $allowed = true;
                } else {
                    $allowed = false;
                    break;
                }
            }
        }
        if ($allowed) {
            return true;
        } else {
            Response::failed('You do not have permission to access this resource', 403);
            exit; // prevent further script execution
        }
    }
}
