<?php

namespace controllers;

use error_handling\ErrorResponse;
use error_handling\InsufficientPermissionsException;
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
    // template string for logging would be nice

    public function __construct()
    {
        $this->authenticationManager = new AuthenticationManager();
        $this->authorizationManager = new AuthorizationManager();
    }

    /**
     * Validate access to a resource.
     * Automatically generates JSON error response and exits script if access is denied.
     *
     * @param string $resourceName used for logging
     * @param string|null $requiredRole optional role required to access resource
     * @param bool $returnBool if true, return bool instead of producing error response
     * @return bool
     */
    public function validateAccess(string $resourceName, string $requiredRole = null, bool $returnBool = false): bool
    {
        try {
            $this->authenticationManager->validateSession();
            $this->authorizationManager->requireRole($requiredRole);
        } catch (NotLoggedInException $e) {
            error_log(date('c') . ": Unauthenticated user " . session_id() . " tried accessing $resourceName\n",
                3, $_ENV['ADMIN_ENDPOINT_LOG']);
            if ($returnBool) {
                return false;
            }
            ErrorResponse::makeErrorResponse(401, 'Not logged in');
            exit;
        } catch (SessionExpiredException $e) {
            error_log(date('c') . " - User: " . $_SESSION['email'] . "; session: ". session_id() . "; Expired session tried accessing $resourceName\n",
                3, $_ENV['ADMIN_ENDPOINT_LOG']);
            if ($returnBool) {
                return false;
            }
            ErrorResponse::makeErrorResponse(401, 'Session expired');
            exit;
        } catch (InsufficientPermissionsException $e) {
            error_log(date('c') . " - User: " . $_SESSION['email'] . "; session: ". session_id() . "; role: " . $_SESSION['role'] . "; Tried accessing $resourceName which requires role: $requiredRole\n",
            3, $_ENV['ADMIN_ENDPOINT_LOG']);
            if ($returnBool) {
                return false;
            }
            ErrorResponse::makeErrorResponse(403, 'Insufficient privileges');
            exit;
        }
        return true;
    }
}