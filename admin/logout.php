<?php
    //logout.php

    session_start();

    session_destroy();

    // xóa cookie admin_id
    setcookie("admin_id", "", time() + (86400 * 90), "/");

    header('location: login.php');
?>