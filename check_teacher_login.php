<?php

// check teacher login

include('admin/database_connection.php');

session_start();

$teacher_email = '';
$teacher_password = '';
$error_teacher_email = '';
$error_teacher_password = '';
$error = 0;

if (empty($_POST["teacher_email"])) {
    $error_teacher_email= 'Địa chỉ email không được để trống';
    $error++;
} else {
    $teacher_email = $_POST["teacher_email"];
}

if (empty($_POST["teacher_password"])) {
    $error_teacher_password = 'Password không được để trống';
    $error++;
} else {
    $teacher_password = $_POST["teacher_password"];
}

if ($error == 0) {
    $query = "SELECT * FROM teacher WHERE teacher_email = '" . $teacher_email . "' ";

    $statement = $connection->prepare($query);
    if ($statement->execute()) {
        $total_row = $statement->rowCount();
        if ($total_row > 0) {
            $result = $statement->fetchAll();
            foreach ($result as $row) {
                if (password_verify($teacher_password, $row["teacher_password"])) {
                    $_SESSION["teacher_id"] = $row["teacher_id"];
                    setcookie("teacher_id", $row["teacher_id"], time() + (86400 * 90), "/" );
                } else {
                    $error_teacher_password = "Sai Password";
                    $error++;
                }
            }
        } else {
            $error_teacher_email = "Sai địa chỉ email";
            $error++;
        }
    }
}

if ($error > 0) {
    $output = array(
        'error'                     =>    true,
        'error_teacher_email'       =>    $error_teacher_email,
        'error_teacher_password'    =>    $error_teacher_password
    );
} else {
    $output = array(
        'success'        =>    true
    );
}

echo json_encode($output);
