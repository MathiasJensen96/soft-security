<?php

namespace security;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../error_handling/ErrorResponse.php';

use error_handling\ErrorResponse;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class InputValidator
{
    public const PASSWORD_RULES = "Password must be 8-64 characters long and contain uppercase, lowercase, number, and special characters";
    private $emailValidator;
    private $passwordValidator;


    public function __construct()
    {
        $this->emailValidator = v::email();
        $this->passwordValidator = v::stringType()
            ->length(8, 64)
            ->regex('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-])/');
    }

    function email($email): bool
    {
        return $this->emailValidator->validate($email);
    }

    function credentials($formData): void
    {
        try {
            v::key('email', $this->emailValidator)
                ->key('password', $this->passwordValidator)
                ->setName('credentials')
                ->assert($formData);
        } catch (NestedValidationException $e) {
            // can't set custom message unless we get the array form, which is why we join.
            // sadly, also replaces the message if the field is missing.
            ErrorResponse::makeErrorResponse(400, join(', ', $e->getMessages(['password' => $this::PASSWORD_RULES])));
            exit;
        }
    }
}