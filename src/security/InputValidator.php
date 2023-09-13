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

    public function logInvalidInput(string $thing = "input"): void
    {
        $user = $_SESSION['id'] ?? 'unknown';
        $role = $_SESSION['role'] ?? 'none';
        error_log(date('c') . " - User: [$user]" . "; role: [$role]; Invalid input for [" . $_SERVER['REQUEST_URI'] . "]; Invalid $thing\n", 3, $_ENV['ADMIN_ENDPOINT_LOG']);
        // should not log the input because of injection risk,
        // but we could maybe log the name of the validation rule that failed,
        // if that is possible to get.
        // actually the URI contains whatever the user input in there is, so that's not great.
        // although the data is URL encoded and doesn't get parsed back into plain text.
    }

    function id($id): void
    {
        try {
            $this->idValidator->assert($id);
        } catch (NestedValidationException $e) {
            $this->logInvalidInput("id");
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
            $this->logInvalidInput("credentials");
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
            $this->logInvalidInput("user data");
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
            $this->logInvalidInput("product data");
            ErrorResponse::makeErrorResponse(400, $e->getFullMessage());
            exit;
        }
    }

    function orderline($orderline): void
    {
        try {
            v::key('productId', $this->idValidator)
                ->key('quantity', v::intVal())  //->positive()
                ->setName('orderline')
                ->assert($orderline);
        } catch (NestedValidationException $e) {
            $this->logInvalidInput("orderline data");
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
            $this->logInvalidInput("orderlines data");
            ErrorResponse::makeErrorResponse(400, $e->getFullMessage());
            exit;
        }
    }
}