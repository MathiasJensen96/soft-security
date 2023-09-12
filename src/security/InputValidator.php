<?php

namespace security;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../error_handling/ErrorResponse.php';

use error_handling\ErrorResponse;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

/**
 * Also sets the response code and sends the response because that's common for lots of resources.
 */
class InputValidator
{
    public const PASSWORD_RULES = "Password must be 8-64 characters long and contain uppercase, lowercase, number, and special characters";
    private $emailValidator;
    private $passwordValidator;
    private $idValidator;


    public function __construct()
    {
        $this->emailValidator = v::email();
        $this->passwordValidator = v::stringType()
            ->length(8, 64)
            ->regex('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-])/');
        $this->idValidator = v::notOptional()->intVal()->positive();
    }

    function id($id): void
    {
        try {
            $this->idValidator->assert($id);
        } catch (NestedValidationException $e) {
            ErrorResponse::makeErrorResponse(400, $e->getFullMessage());
            exit;
        }
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

    function user($formData): void
    {
        try {
            v::key('email', $this->emailValidator)
                ->key('role', v::stringType()->in(['user', 'admin']))
                ->setName('user')
                ->assert($formData);
        } catch (NestedValidationException $e) {
            ErrorResponse::makeErrorResponse(400, $e->getFullMessage());
            exit;
        }
    }

    function product($formData): void
    {
        try {
            v::key('name', v::stringType()->length(1, 45))
                ->key('price', v::floatVal()->positive())
                ->key('description', v::stringType()->length(1, 255))
                ->setName('product')
                ->assert($formData);
        } catch (NestedValidationException $e) {
            ErrorResponse::makeErrorResponse(400, $e->getFullMessage());
            exit;
        }
    }

    function orderline($orderline): void
    {
        try {
            v::key('productId', $this->idValidator)
                ->key('quantity', v::intVal()->positive())
                ->setName('orderline')
                ->assert($orderline);
        } catch (NestedValidationException $e) {
            ErrorResponse::makeErrorResponse(400, $e->getFullMessage());
            exit;
        }
    }

    function orderlines($formData): void
    {
        try {
            v::key('orderlines', v::arrayType())->assert($formData);

            foreach($formData['orderlines'] as $orderline) {
                $this->orderline($orderline);
            }
        } catch (NestedValidationException $e) {
            ErrorResponse::makeErrorResponse(400, $e->getFullMessage());
            exit;
        }
    }
}