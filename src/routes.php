<?php

require_once __DIR__ . '/router.php';

get('/', 'views/index.php');
get('/login', 'views/login.php');
post('/login', 'services/login.php');
get('/logout', 'services/logout.php');
post('/logout', 'services/logout.php');
get('/register', 'views/register.php');
post('/register', 'views/register.php');
get('/products', 'views/allProducts.php');
get('/products/$id', 'views/oneProduct.php');
get('/products/$id/delete', 'views/deleteProduct.php');
post('/products', 'views/createProduct.php');
post('/products/$id', 'views/updateProduct.php');
get('/orders', 'views/allOrders.php');
post('/orders','views/createOrder.php');
post('/orders/$id', 'views/updateOrder.php');
get('/orders/$id', 'views/oneOrder.php');
get('/orders/$id/delete', 'views/deleteOrder.php');
get('/adminpage', 'controllers/adminPageController.php');
get('/users/$id', 'services/adminResource.php');
post('/update-users/$id','services/adminResource.php');
post('/delete-users/$id', 'services/adminResource.php');
get('/admin', 'services/adminResource.php');
any('/404','views/404.php');