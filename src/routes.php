<?php

require_once __DIR__ . '/router.php';

post('/login', 'views/login.php'); //endpoint er /login og den tager dig til login.php siden
post('/register', 'views/register.php');
any('/404','views/404.php');
