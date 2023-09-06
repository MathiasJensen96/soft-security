<?php

namespace security;

class AuthenticationManager
{
    function validateSession(): bool
    {
        return !$this->isExpired();
    }

    function isExpired(): bool
    {
        return isset($_SESSION['expiration']) && $_SESSION['expiration'] <= time();
    }

    function invalidateSession(): void
    {
        $_SESSION['expiration'] = time() + 10;
        setcookie(session_name(), "");
    }
}