<?php

require_once '../config.php';

if (! isset($_SERVER['PHP_AUTH_USER'])
    ||
    ! isset($_SERVER['PHP_AUTH_PW'])
    ||
    strtolower($_SERVER['PHP_AUTH_USER']) != 'admin'
    ||
    $_SERVER['PHP_AUTH_PW'] != PASSWORD)
{
    header('WWW-Authenticate: Basic');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Bejelentkezés szükséges (admin)!';
    exit;
}
