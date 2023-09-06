<?php

require_once __DIR__ . '/../security/AuthorizationManager.php';

use security\AuthorizationManager;

session_start();

$authorizationManager = new AuthorizationManager();
if (!$authorizationManager->requireRole('admin')) {
    echo "You are not authorised!";
    return http_response_code(403);
} else {
    echo "Welcome, admin.";
}
