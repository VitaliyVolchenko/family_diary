<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/app/data/mysql/class.user.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/app/template/registration.phtml';
$user = new User(); // Checking for user logged in or not

if (isset($_REQUEST['submit'])){
    extract($_REQUEST);
    $register = $user->reg_user($category, $name, $upass);
    if ($register) {
        // Registration Success
        echo 'Registration successful <a href="login.php">Click here</a> to login';
    } else {
        // Registration Failed
        echo 'Registration failed. Username or category already exits please try again';
    }
}
?>
