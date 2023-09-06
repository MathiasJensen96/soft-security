<?php

namespace security;

class AuthenticationManager
{
    const ABSOLUTE_EXPIRATION = 60 * 60 * 4; // 4 hours
    const IDLE_EXPIRATION = 60 * 30; // 30 minutes
    const RENEWAL_INTERVAL = 60 * 60; // 1 hour

    function validateSession(): bool
    {
        if ($this->isExpired()) {
            return false;
        } else if ($this->needsRenewal()) {
            $this->renewSession();
        }
        $this->didActivity();
        return true;
    }

    function isExpired(): bool
    {
        return (isset($_SESSION['expiration']) && $_SESSION['expiration'] <= time())
            || (isset($_SESSION['lastActive']) && $_SESSION['lastActive'] + self::IDLE_EXPIRATION <= time());
    }

    function invalidateSession(): void
    {
        $this->expireSession();
        setcookie(session_name(), "");  // removes session cookie from browser
    }

    function expireSession(): void
    {
        $_SESSION['expiration'] = time() + 10;
    }

    function createSession(): void
    {
        $this->renewSession();
        $_SESSION['expiration'] = time() + self::ABSOLUTE_EXPIRATION;
        $this->didActivity();
    }

    function needsRenewal():bool
    {
        return !isset($_SESSION['lastRenew']) || $_SESSION['lastRenew'] + self::RENEWAL_INTERVAL <= time();
    }

    function renewSession():void
    {
        $old_expiration = $_SESSION['expiration'];
        $this->expireSession();
        session_regenerate_id();
        $_SESSION['expiration'] = $old_expiration;
        $_SESSION['lastRenew'] = time();
    }

    function didActivity(): void
    {
        $_SESSION['lastActive'] = time();
    }
}