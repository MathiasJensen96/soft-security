<?php

namespace security;

/**
 * As per PHP documentation, we manage sessions with timestamps.
 *
 * There are 4 ways for a session to expire:
 * 1. Absolute expiration: the session expires after a set amount of time.
 * 2. Idle expiration: the session expires after a set amount of time of inactivity.
 * 3. Renewal: the session is renewed after a set amount of time and the old session is expired and invalidated.
 * 4. Manual logout.
 *
 * Renewal is independent of the other two. It does not extend the absolute expiration time.
 *
 * Renewal narrows the window an attacker has to do something malicious with a stolen session. After a time, the session in invalidated.
 * If the legitimate user uses that invalidated session, that signals an attack, and we log the event.
 * We also want to invalidate all the user's sessions.
 *
 * Manual expiration gives the session a grace period before it expires. This is per the PHP documentation.
 * It prevents race conditions and allows outstanding requests to finish. We log the event just in case.
 */
class AuthenticationManager
{
    const ABSOLUTE_EXPIRATION = 60 * 60 * 4; // 4 hours
    const IDLE_EXPIRATION = 60 * 30; // 30 minutes
    const RENEWAL_INTERVAL = 60 * 60; // 1 hour

    function validateSession(): bool
    {
        if (empty($_SESSION['authenticated'])) {
            return false;
        } else if ($this->isExpired()) {
            if (!empty($_SESSION['invalidated'])) {
                error_log(date('c') . ": CRITICAL! Possible attack! Invalidated session used: " . session_id() . " for user: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . "\n", 3, $_ENV['ADMIN_ENDPOINT_LOG']);
                // TODO: Invalidate all sessions for this user
                //   we can't do this until we track all sessions for a user
                //   which might require a database, e.g. Redis.
            }
            $this->invalidateSession();
            $this->removeToken();
            return false;
        } else if (!empty($_SESSION['invalidated'])) {
            error_log(date('c') . ": Warning! Invalidated session used during grace period before expiration: " . session_id() . " for user: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . "\n", 3, $_ENV['ADMIN_ENDPOINT_LOG']);
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

    function expireSession(): void
    {
        $_SESSION['expiration'] = time() + 10;
    }

    function invalidateSession(): void
    {
        $_SESSION['invalidated'] = true;
    }

    function removeToken(): void
    {
        setcookie(session_name(), "");  // removes session cookie from browser
    }

    function createSession(): void
    {
        $_SESSION['expiration'] = time() + self::ABSOLUTE_EXPIRATION;
        $_SESSION['authenticated'] = true;
        $this->renewSession();
        $this->didActivity();
    }

    function needsRenewal():bool
    {
        // if lastRenewal is not set, it will try to renew.
        return !isset($_SESSION['lastRenewal']) || $_SESSION['lastRenewal'] + self::RENEWAL_INTERVAL <= time();
    }

    function renewSession():void
    {
        $old_expiration = $_SESSION['expiration'];
        $this->expireSession();
        $this->invalidateSession();
        session_regenerate_id();
        $_SESSION['expiration'] = $old_expiration;
        $_SESSION['lastRenewal'] = time();
        unset($_SESSION['invalidated']);
    }

    function closeSession(): void
    {
        if (empty($_SESSION['invalidated']) && !$this->isExpired()) {
            $this->expireSession();
        }
        $this->invalidateSession();
        $this->removeToken();
    }

    function didActivity(): void
    {
        $_SESSION['lastActive'] = time();
    }
}