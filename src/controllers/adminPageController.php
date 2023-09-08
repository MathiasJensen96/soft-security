<?php

use controllers\AccessController;

require_once 'AccessController.php';

session_start();

$accessControl = new AccessController();
$accessControl->validateAccess('admin page', 'admin');

require_once __DIR__ . '/../views/adminpage.php';