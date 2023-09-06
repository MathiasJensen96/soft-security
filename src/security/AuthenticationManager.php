<?php

namespace security;

class AuthenticationManager
{
    const ABSOLUTE_EXPIRATION = 60 * 60 * 4; // 4 hours
    const IDLE_EXPIRATION = 60 * 30; // 30 minutes

    function validateSession(): bool
    {
        return !$this->isExpired();
    }

    function isExpired(): bool
    {
        return (isset($_SESSION['expiration']) && $_SESSION['expiration'] <= time())
            || (isset($_SESSION['lastActive']) && $_SESSION['lastActive'] + self::IDLE_EXPIRATION <= time());
    }

    function invalidateSession(): void
    {
        $_SESSION['expiration'] = time() + 10;
        setcookie(session_name(), "");
    }

    function regenerateSession(): void
    {
        session_regenerate_id();
        $_SESSION['expiration'] = time() + self::ABSOLUTE_EXPIRATION;
        self::didActivity();
    }

    function didActivity(): void
    {
        $_SESSION['lastActive'] = time();
    }
}