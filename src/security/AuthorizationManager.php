<?php

namespace security;

class AuthorizationManager
{
    function requireRole(string $role): bool
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === $role;
    }
}