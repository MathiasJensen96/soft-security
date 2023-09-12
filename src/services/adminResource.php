<?php

require_once __DIR__ . '/../controllers/AccessController.php';
require_once __DIR__ . '/../db/UserDao.php';
require_once __DIR__ . '/../error_handling/ErrorResponse.php';
require_once __DIR__ . '/../security/InputValidator.php';
require_once __DIR__ . '/../security/outputEncoder.php';

use controllers\AccessController;
use error_handling\ErrorResponse;
use security\InputValidator;

session_start();

$accessControl = new AccessController();
$accessControl->validateAccess('admin page', 'admin');