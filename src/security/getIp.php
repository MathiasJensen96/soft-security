<?php
function getIp(): string
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $user_ip_address = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $user_ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $user_ip_address = $_SERVER['REMOTE_ADDR'];
    }
    return $user_ip_address;
}
