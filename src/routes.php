<?php

require_once __DIR__ . '/router.php';

get('/', 'views/index.php');
get('/login', 'views/login.php');
get('/register', 'views/register.php');
get('/products', 'views/test.php');
post('/login', 'views/verify.php');
post('/register', 'views/create_user.php');
any('/404','views/404.php');