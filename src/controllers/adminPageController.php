<?php

use controllers\AccessController;

require_once 'AccessController.php';

session_start();

$accessControl = new AccessController();
if (!$accessControl->validateAccess('admin page', 'admin', returnBool: true)) {
    header('Location: /login');
} else {
    require_once __DIR__ . '/../views/adminpage.php';
}