<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/app/data/mysql/class.user.php';
$user = new User();
$uid = $_SESSION['uid'];
if (!$user->get_session()){
    header("location:login.php");
}

$id = $_GET['id'];

include_once $_SERVER['DOCUMENT_ROOT'] . '/app/template/tasks.phtml';