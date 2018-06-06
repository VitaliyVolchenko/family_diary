<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/app/data/mysql/class.user.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/app/template/login.phtml';
$user = new User();

if (isset($_REQUEST['submit'])) {
    extract($_REQUEST);
    $login = $user->check_login($name, $password);
    if ($login) {
        // Registration Success
        header("location:home.php");
    } else {
        // Registration Failed
        echo 'Wrong username or password';
    }
}
?>
