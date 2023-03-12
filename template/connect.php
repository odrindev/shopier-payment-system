<?php
ob_start();
session_start();
date_default_timezone_set('Europe/Berlin');

try {

    $db = new PDO('mysql:host=localhost;dbname=dbname;charset=UTF8', 'root', 'password');

} catch (Exception $e) {

    exit($e->getMessage());

}
?>