<?php

namespace controllers;

use error_handling\ErrorResponse;
use error_handling\NotLoggedInException;
use error_handling\SessionExpiredException;
use security\AuthenticationManager;
use security\AuthorizationManager;

require_once __DIR__ . '/../security/AuthorizationManager.php';
require_once __DIR__ . '/../security/AuthenticationManager.php';
require_once __DIR__ . '/../error_handling/NotLoggedInException.php';
require_once __DIR__ . '/../error_handling/SessionExpiredException.php';

class AccessController
{
    private AuthenticationManager $authenticationManager;
    private AuthorizationManager $authorizationManager;

    public function __construct()
    {
        $this->authenticationManager = new AuthenticationManager();
        $this->authorizationManager = new AuthorizationManager();
    }

    // template string for logging would be nice
    public function validateAccess(string $resourceName, string $requiredRole = null): void
    {
        try {
            $this->authenticationManager->validateSession();
        } catch (NotLoggedInException $e) {
            error_log(date('c') . ": Unauthenticated user " . session_id() . " tried accessing $resourceName\n",
                3, $_ENV['ADMIN_ENDPOINT_LOG']);
            ErrorResponse::makeErrorResponse(401, 'Not logged in');
            exit;
        } catch (SessionExpiredException $e) {
            error_log(date('c') . " - User: " . $_SESSION['email'] . "; session: ". session_id() . "; Expired session tried accessing $resourceName\n",
                3, $_ENV['ADMIN_ENDPOINT_LOG']);
            ErrorResponse::makeErrorResponse(401, 'Session expired');
            exit;
        }
        if (!empty($requiredRole) && !$this->authorizationManager->requireRole($requiredRole)) {
            error_log(date('c') . " - User: " . $_SESSION['email'] . "; session: ". session_id() . "; role: " . $_SESSION['role'] . "; Tried accessing $resourceName which requires role: $requiredRole\n",
                3, $_ENV['ADMIN_ENDPOINT_LOG']);
            ErrorResponse::makeErrorResponse(403, 'Insufficient privileges');
            exit;
        }
    }
}