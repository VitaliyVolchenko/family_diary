<?php
include "config.php";

class User{

    public $db;

    public function __construct(){
        $this->db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

        if(mysqli_connect_errno()) {
            echo "Error: Could not connect to database.";
            exit;
        }
        // creating tables
        $table_sql = "CREATE TABLE IF NOT EXISTS dbusers (".
            "uid INT PRIMARY KEY AUTO_INCREMENT,".
            "category VARCHAR(30),".
            "name VARCHAR(100),".
            "upass VARCHAR(50)
        );";

        $table_sql2 = "CREATE TABLE IF NOT EXISTS dbfile (".
            "id INT PRIMARY KEY AUTO_INCREMENT,".
            "file VARCHAR( 100 ) NOT NULL , ".
            "type VARCHAR( 10 ) NOT NULL , ".
            "size INT NOT NULL ) ENGINE = MYISAM ;";

            $table_sql3 = "CREATE TABLE IF NOT EXISTS dbtasks (".
            "id INT PRIMARY KEY AUTO_INCREMENT,".
            "task TEXT,".
            "mem VARCHAR(50), " .
            "mark VARCHAR(50)
        );";

        if (mysqli_query($this->db, $table_sql) && mysqli_query($this->db, $table_sql2) && 
        mysqli_query($this->db, $table_sql3))        
        {
            echo "Table created successfully! \n";
        } else {
            echo 'Error creating table: ' . mysqli_error($table_sql) . "\n";
            echo 'Error creating table: ' . mysqli_error($table_sql2) . "\n";
            echo 'Error creating table: ' . mysqli_error($table_sql3) . "\n";
        }


    }

    /** for registration process **/
    public function reg_user($category,$name,$password){
        $password = md5($password);
        $name = $this->db->real_escape_string($name);
        $category = $this->db->real_escape_string($category);
        $sql="SELECT * FROM `dbusers` WHERE `name` = '$name' OR `category` = '$category'";

        //checking if the name or category is available in db
        $check =  $this->db->query($sql) ;
        $count_row = $check->num_rows;

        //if the name, category is not in db then insert to the table
        if ($count_row == 0){
            $sql1="INSERT INTO `dbusers` SET `category` = '$category', `upass` = '$password', `name` = '$name'";
            $result = mysqli_query($this->db,$sql1) or die(mysqli_connect_errno()."Data cannot inserted");
            return $result;
        }
        else { return false;}
    }

    /** for login process **/
    public function check_login($name, $password){
        $password = md5($password);
        $name = $this->db->real_escape_string($name);
        $sql2="SELECT `uid` from `dbusers` WHERE `name` = '$name' and `upass` = '$password'";

        //checking if the name is available in the table
        $result = mysqli_query($this->db,$sql2);
        $user_data = mysqli_fetch_array($result);
        $count_row = $result->num_rows;

        if ($count_row == 1) {
            // this login var will use for the session thing
            $_SESSION['login'] = true;
            $_SESSION['uid'] = $user_data['uid'];
            return true;
        }
        else{
            return false;
        }
    }

    /** for showing the name **/
    public function get_name($uid){
        $sql3 = sprintf("SELECT `name` FROM `dbusers` WHERE `uid` = %d;",$uid);
        $result = mysqli_query($this->db,$sql3);
        $user_data = mysqli_fetch_array($result);
        echo $user_data['name'];
    }

    /** for showing the category **/
    public function get_category($uid){
        $sql3 = sprintf("SELECT `category` FROM `dbusers` WHERE `uid` = %d;",$uid);
        $result = mysqli_query($this->db,$sql3);
        $user_data = mysqli_fetch_array($result);
        echo $user_data['category'];
    }

    /** starting the session **/
    public function get_session(){
        return $_SESSION['login'];
    }

    public function user_logout() {
        $_SESSION['login'] = FALSE;
        session_destroy();
    }

    /** upload file in db **/
    public function upload_file($file, $type, $size){
        $sql="SELECT * FROM `dbfile` WHERE `file` = '$file'";
        //checking if the name or category is available in db
        $check = mysqli_query($this->db,$sql);
        $count_row = $check->num_rows;
        //if the file is not in db then insert to the table
        if ($count_row == 0){
            $sql1="INSERT INTO `dbfile` SET `file` = '$file', `type` = '$type', `size` = '$size'";
            $result = mysqli_query($this->db,$sql1) or die(mysqli_connect_errno()."Data cannot inserted");
            echo "File successfuly upload in db";
            return $result;
        }
        else { return false;}
    }

    /** get name of the saved file **/
    public function get_file(){
        $sql = "SELECT `file` FROM `dbfile`";
        $result = mysqli_query($this->db,$sql);
        $row = mysqli_fetch_array($result);
        return $row['file'];
    }

    /** for save tasks **/
    public function save_tasks($task){
            $sql1="INSERT INTO `dbtasks` SET `task` = '$task'";
            $result = mysqli_query($this->db,$sql1) or die(mysqli_connect_errno()."Data cannot inserted");
            return $result;
    }

    /*for showing the tasks*/
    public function get_tasks(){
        $sql = "SELECT id, task, mem, mark FROM dbtasks";
        $result = mysqli_query($this->db,$sql);
        while($row = mysqli_fetch_assoc($result)) {
                echo '<a href="task.php?id=' . $row['id'] . ' " >' . $row['task'] .'</a>  |work for: <i>'.
                $row['mem'] .'</i>' . '  |status: <i>'.  $row['mark'] .'</i><br/>';
        }
    }

    /* for showing the task*/
    public function get_task($id){
        $sql = sprintf("SELECT `task` FROM `dbtasks` WHERE `id` = %d;",$id);
        $result = mysqli_query($this->db,$sql);
        $data = mysqli_fetch_array($result);
        echo $data['task'].'<br/>';
    }
    
    /** mark done **/
    public function mark_done($id){
        $mark = "DONE";
        $sql = sprintf("UPDATE `dbtasks` SET `mark` = '$mark' WHERE `id` = %d;",$id);
        $result = mysqli_query($this->db,$sql);
        return $result;
    }

    /** delete task **/
    public function task_delete($id){
        $sql = sprintf("DELETE FROM `dbtasks` WHERE `id` = %d;",$id);
        $result = mysqli_query($this->db,$sql);
        return $result;
    }

    /*appoint a member for task*/
    public function appoint_mem($id, $name){
        $name = $this->db->real_escape_string($name);
        $sql = sprintf("UPDATE `dbtasks` SET `mem` = '$name' WHERE `id` = %d;",$id);
        $result = mysqli_query($this->db,$sql);
        return $result;
    }

}