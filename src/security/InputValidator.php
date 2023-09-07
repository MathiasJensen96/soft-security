<?php

namespace security;

class InputValidator
{
    /**
     * Password rules:
     * - 8-64 characters
     * - At least one uppercase letter
     * - At least one lowercase letter
     * - At least one number
     * - At least one special character
     */
    function complexPassword(string $password): bool
    {
        $regex = '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,64}$/';
        return preg_match($regex, $password) === 1;
    }

    function email($email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}