<?php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'user');
    define('DB_PASS', $_ENV['USER_PASSWORD']);
    define('DB_NAME', 'securitydb');
    define('DB_ADMIN', 'admin');
    define('DB_ADMIN_PASS', $_ENV['ADMIN_PASSWORD']);
    
    $userconn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $adminconn = new mysqli(DB_HOST, DB_ADMIN, DB_ADMIN_PASS, DB_NAME);
    
    if($userconn->connect_error){
        die('connection failed' . $userconn->connect_error);
    }

    if($adminconn->connect_error){
        die('connection failed' . $adminconn->connect_error);
    }
?>