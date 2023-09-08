<?php

namespace security;

require_once __DIR__ . '/../error_handling/InsufficientPermissionsException.php';

use error_handling\InsufficientPermissionsException;

class AuthorizationManager
{
    /**
     * @throws InsufficientPermissionsException
     */
    function requireRole(string $role): void
    {
        if (empty($_SESSION['role']) || $_SESSION['role'] !== $role) {
            throw new InsufficientPermissionsException();
        }
    }
}