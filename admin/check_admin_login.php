<?php

    //check_admin_login.php
    include('database_connection.php');
    session_start();

    $admin_user_name = '';
    $admin_password = '';
    $error_admin_user_name = '';
    $error_admin_password = '';
    $error = 0;

    if(empty($_POST["admin_user_name"])){
        $error_admin_user_name = 'Phải nhập username';
        $error++;
    } else{
        // avoid SQL Injection
        $admin_user_name = strip_tags($_POST["admin_user_name"]);//  strips a string from HTML, XML, and PHP tags
        $admin_user_name = addslashes($_POST["admin_user_name"]);// Add a backslash in front of each double quote (")
    }

    if(empty($_POST["admin_password"])){
        $error_admin_password = 'Phải nhập password';
        $error++;
    } else{
        $admin_password = strip_tags($_POST["admin_password"]);
        $admin_password = addslashes($_POST["admin_password"]);
        $admin_password = md5(($_POST["admin_password"]));// generate md5 hash password
    }

    if($error == 0){
        $query = "SELECT * FROM admin where admin_user_name ='".$admin_user_name."'";

        $statement = $connection -> prepare($query);
        
        if($statement -> execute()){
            $total_row = $statement -> rowCount();
            if($total_row > 0){
                $result = $statement -> fetchAll();
                foreach($result as $row){
                    if($admin_password == $row["admin_password"]){
                        $_SESSION["admin_id"] = $row["admin_id"];
                        // set cookie for admin_id with time 90 day in entire website with "/"
                        setcookie("admin_id", $row["admin_id"], time() + (86400 * 90), "/");      
                    } else{
                        $error_admin_password = "Sai Password";
                        $error++;
                    }
                }
            } else{
                $error_admin_user_name = "Sai username";
                $error++;
            }
        }
    }

    if($error > 0){
        //PHP Associative Arrays (=>)
        //Associative arrays are arrays that use named keys that you assign to them
        $output = array(
            'error' => true,
            'error_admin_user_name' => $error_admin_user_name,
            'error_admin_password' => $error_admin_password 
        );
    } else{
        $output = array(
            'success' => true   
        );
    }

    //Objects in PHP can be converted into JSON by using the PHP function json_encode()
    echo json_encode($output);

?>