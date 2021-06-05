<?php
// header.php

include('database_connection.php');
session_start();

if (!isset($_COOKIE["admin_id"]) && !isset($_SESSION["admin_id"])) {
    header('location: login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Pro Admin Side</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/dataTables.bootstrap4.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap4.min.js"></script>
    
    <!-- library datepicker for pick date  -->
    <script type="text/javascript" src="../js/bootstrap-datepicker.js"></script>
    <link rel="stylesheet" href="../css/datepicker.css" />

    <style>
        .datepicker {
            z-index: 1600 !important;
        }
    </style>

</head>