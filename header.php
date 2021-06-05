<?php

// header of teacher front end
session_start();
include('admin/database_connection.php');


if (!isset($_SESSION["teacher_id"]) && empty($_COOKIE["teacher_id"])) {
    header('location:login.php');
} else {
    // Store value in $_COOKIE["teacher_id"] for $_SESSION["teacher_id"]
    $_SESSION["teacher_id"] = $_COOKIE["teacher_id"];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Attendance Pro</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="css/datepicker.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
    
    <style>
        .datepicker {
            z-index: 1600 !important;
            /* has to be larger than 1050 */
        }
    </style>

</head>


