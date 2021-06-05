<?php

// Teacher logout

session_start();

session_destroy();
setcookie("teacher_id", "", time() + (86400 * 90), "/" );

header('location:login.php');

?>