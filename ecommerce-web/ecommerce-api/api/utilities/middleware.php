<?php
function checkPermission($role, $requiredRole){
    return $role === $requiredRole;
}
?>