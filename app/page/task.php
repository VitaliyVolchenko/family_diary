<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/app/data/mysql/class.user.php';
$user = new User();
$uid = $_SESSION['uid'];
if (!$user->get_session()){
    header("location:login.php");
}
$id = $_GET['id'];
if (isset($_REQUEST['appoint'])) {
    extract($_REQUEST);
    $name = $_POST['name'];
    $mem = $user->appoint_mem($id, $name);
    if ($mem) {
        //  Success
        header("location:tasks.php");
    } else {
        //  Failed
        echo 'Wrong appoint member family';
    }
}
if (isset($_REQUEST['mark'])) {
    extract($_REQUEST);
    $done = $user->mark_done($id);
    if ($done) {
        //  Success
        header("location:tasks.php");
    } else {
        //  Failed
        echo 'Wrong mark done';
    }
}
if (isset($_REQUEST['delete'] )) {
    extract($_REQUEST);
    $del = $user->task_delete($id);
    if ($del) {
        //  Success
        header("location:tasks.php");
    } else {
        //  Failed
        echo 'Wrong delete done';
    }
}
include_once $_SERVER['DOCUMENT_ROOT'] . '/app/template/task.phtml';

