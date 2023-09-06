<?php
session_start();

$_SESSION['expiration'] = time() + 10;
setcookie(session_name(), "");
