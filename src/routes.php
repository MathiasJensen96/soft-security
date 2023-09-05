<?php

require_once __DIR__ . '/router.php';

get('/', 'views/index.php');
get('/login', 'views/login.php');
post('/login', 'services/login.php');
get('/register', 'views/register.php');
post('/register', 'views/register.php');
get('/products', 'views/allProducts.php');
get('/products/$id', 'views/oneProduct.php');
post('/products', 'views/createProduct.php');
get('/orders', 'views/allOrders.php');
get('/orders/$id', 'views/oneOrder.php');
get('/admin', 'services/adminResource.php');
any('/404','views/404.php');