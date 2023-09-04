<?php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'user');
    define('DB_PASS', $_ENV['USER_PASSWORD']);
    define('DB_NAME', 'securitydb');
    define('DB_ADMIN', 'admin');
    define('DB_ADMIN_PASS', $_ENV['ADMIN_PASSWORD']);
    
    try {
        $userconn = new pdo('mysql:host=db;dbname=securitydb', DB_USER, DB_PASS);
        $adminconn = new pdo('mysql:host=db;dbname=securitydb', DB_ADMIN, DB_ADMIN_PASS);

    } catch (PDOException $e) {
        print "Error: " . $e->getMessage();
    }

?>