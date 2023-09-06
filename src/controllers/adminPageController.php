<?php
require_once __DIR__ . '/../security/AuthorizationManager.php';

use security\AuthorizationManager;

session_start();

$authorizationManager = new AuthorizationManager();
if (!$authorizationManager->requireRole('admin')) {
    header('Location: /login');
} else {
    require_once __DIR__ . '/../views/adminpage.php';
}