<?php

require_once __DIR__ . '/router.php';

get('/', 'views/index.php');
get('/login', 'views/login.php');
get('/register', 'views/register.php');
get('/products', 'views/allProducts.php');
get('/products/$id', 'views/oneProduct.php');
get('/orders', 'views/allOrders.php');
get('/orders/$id', 'views/oneOrder.php');
post('/login', 'views/login.php');
post('/register', 'views/create_user.php');
post('/products', 'views/createProduct.php');
any('/404','views/404.php');