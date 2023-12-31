<?php

require_once __DIR__ . '/router.php';
require_once __DIR__ . '/error_handling/exceptionHandler.php';

set_exception_handler('exceptionHandler');

session_start();

get('/', 'views/index.php');
get('/login', 'views/login.php');
post('/login', 'services/login.php');
get('/logout', 'services/logout.php');
post('/logout', 'services/logout.php');
get('/register', 'views/register.php');
post('/register', 'services/signup.serv.php');
get('/products-page', 'views/products-page.php');
get('/products', 'views/allProducts.php');
get('/products/$id', 'views/oneProduct.php');
post('/products/$id/delete', 'views/deleteProduct.php');
post('/products', 'views/createProduct.php');
post('/products/$id', 'views/updateProduct.php');
get('/orders', 'views/allOrders.php');
get('/orders-page', 'views/orders-page.php');
post('/orders','views/createOrder.php');
post('/orders/$id', 'views/updateOrder.php');
get('/orders/$id', 'views/oneOrder.php');
post('/orders/$id/delete', 'views/deleteOrder.php');
get('/admin-page', 'controllers/adminPageController.php');
get('/users', 'services/getUsers.php');
get('/users/$id', 'services/getUser.php');
post('/users/$id','services/updateUser.php');
post('/users/$id/delete', 'services/deleteUser.php');
get('/admin', 'services/adminResource.php');

// frontend redirects
// 307 and 308 don't change the request method, just resubmits the request
get('/getUser', function() {
    $id = $_GET['id'];
    header("Location: /users/$id");
});
post('/updateUser', function() {
    $id = $_POST['id'];
    header("Location: /users/$id", true, 307);
});
post('/deleteUser', function() {
    $id = $_POST['id'];
    header("Location: /users/$id/delete", true, 307);
});
get('/getProduct', function() {
    $id = $_GET['id'];
    header("Location: /products/$id");
});
post('/updateProduct', function() {
    $id = $_POST['id'];
    header("Location: /products/$id", true, 307);
});
post('/deleteProduct', function() {
    $id = $_POST['id'];
    header("Location: /products/$id/delete", true, 307);
});
get('/getOrder', function() {
    $id = $_GET['id'];
    header("Location: /orders/$id");
});
post('/deleteOrder', function() {
    $id = $_POST['id'];
    header("Location: /orders/$id/delete", true, 307);
});

// not found catch-all
any('/404','views/404.php');