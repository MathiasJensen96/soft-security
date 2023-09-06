<?php
require_once __DIR__ . '/../security/AuthenticationManager.php';
require_once __DIR__ . '/../security/AuthorizationManager.php';

use security\AuthenticationManager;
use security\AuthorizationManager;

session_start();

$authenticationManager = new AuthenticationManager();
$authorizationManager = new AuthorizationManager();

if (!$authenticationManager->validateSession() || !$authorizationManager->requireRole('admin')) {
    header('Location: /login');
} else {
    require_once __DIR__ . '/../views/adminpage.php';
}