<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo "You are not authorised!";
    return;
}
echo "Welcome, admin.";
