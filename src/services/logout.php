<?php

require_once __DIR__ . '/../security/AuthenticationManager.php';

use security\AuthenticationManager;

session_start();

$authenticationManager = new AuthenticationManager();
$authenticationManager->invalidateSession();
