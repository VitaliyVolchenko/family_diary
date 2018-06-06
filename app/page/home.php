<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/app/data/mysql/class.user.php';

$user = new User();
$uid = $_SESSION['uid'];
if (!$user->get_session()){
    header("location:login.php");
}

if (isset($_GET['q'])){
    $user->user_logout();
    header("location:login.php");
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/app/template/home.phtml';

if(isset($_POST['btn-upload']))
{     
 $max_size = 20971520;    
 $file = rand(1000,100000)."-".$_FILES['file']['name'];
 $file_loc = $_FILES['file']['tmp_name'];
 $file_size = $_FILES['file']['size']; 
    if($file_size > $max_size){
        echo "Flie size is exceeds the limit";
        exit();
    }
 $file_type = $_FILES['file']['type'];
 $folder = $_SERVER['DOCUMENT_ROOT'] .'/app/uploads/';  
 if(move_uploaded_file($file_loc, $folder.$file)){
     //echo "local file successfuly upload in $folder <br>";
     $user->upload_file($file, $file_type, $file_size);
     echo "<br>";
     $file_name = $user->get_file();
     $content = file($_SERVER['DOCUMENT_ROOT'] . '/app/uploads/'. $file_name);
     foreach ($content as $string) {
         '<br>' . $string;
         $user->save_tasks($string);
     }
    } else {
        echo "File $file was not uploaded $folder \n";        
    }
    
    
}



