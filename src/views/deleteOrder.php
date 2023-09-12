<?php

require_once __DIR__ . "/../controllers/AccessController.php";
require_once __DIR__ . "/../db/dbconn.php";
require_once __DIR__ . "/../entities/orders.php";
require_once __DIR__ . "/../entities/orderlines.php";
require_once __DIR__ . "/../error_handling/ErrorResponse.php";
require_once __DIR__ . "/../security/InputValidator.php";

use controllers\AccessController;
use error_handling\ErrorResponse;
use security\InputValidator;

session_start();

$accessControl = new AccessController();
$accessControl->validateAccess('deleteOrder');

$validator = new InputValidator();
$validator->id($id);

if ($userconn) {
    $response = [];

    $stmt = $userconn->prepare("SELECT * FROM securitydb.order WHERE id = ?");
    $stmt->execute([$id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row || ($row['User_email'] !== $_SESSION['email'] && $_SESSION['role'] !== "admin")) {
        error_log(date('c') . " | User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to delete order with id: " . $id . "\n", 3, $_ENV['ADMIN_ENDPOINT_LOG']);
        ErrorResponse::makeErrorResponse(404, "Order not found with id: $id");
        exit;
    }

    $userconn->beginTransaction();
    $stmt = $userconn->prepare("DELETE FROM securitydb.orderline WHERE orderId = ?");
    $stmt->execute([$id]);
    $stmt = $userconn->prepare("DELETE FROM securitydb.order WHERE id = ?");
    $stmt->execute([$id]);
    $userconn->commit();
}