<?php
//teacher login
session_start();
include('admin/database_connection.php');

if (isset($_SESSION["teacher_id"])) {
    header('location:index.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống quản lý học sinh</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="jumbotron text-center" style="margin-bottom:0">
        <h1>Hệ thống quản lý học sinh Attendance Pro</h1>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-4">

            </div>
            <div class="col-md-4" style="margin-top:20px;">
                <div class="card">
                    <div class="card-header"><h5>Form đăng nhập Giáo Viên: </h5></div>
                    <div class="card-body">
                        <form method="post" id="teacher_login_form">
                            <div class="form-group">
                                <label><h6>Nhập địa chỉ email: </h6></label>
                                <input type="text" name="teacher_email" id="teacher_email" class="form-control" />
                                <span id="error_teacher_email" class="text-danger"></span>
                            </div>
                            <div class="form-group">
                                <label><h6>Nhập Password: </h6></label>
                                <input type="password" name="teacher_password" id="teacher_password" class="form-control" />
                                <span id="error_teacher_password" class="text-danger"></span>
                            </div>
                            <div class="form-group">
                                <input type="submit" name="teacher_login" id="teacher_login" class="btn btn-info" value="Đăng nhập" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">

            </div>
        </div>
    </div>


</body>

</html>

<script>
    $(document).ready(function() {
        $('#teacher_login_form').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: "check_teacher_login.php",
                method: "POST",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function() {
                    $('#teacher_login').val('Validate...');
                    $('#teacher_login').attr('disabled', true);
                },
                success: function(data) {
                    if (data.success) {
                        location.href = "index.php";
                    }
                    
                    if (data.error) {
                        $('#teacher_login').val('Login');
                        $('#teacher_login').attr('disabled', false);
                        if (data.error_teacher_email != '') {
                            $('#error_teacher_email').text(data.error_teacher_email);
                        } else {
                            $('#error_teacher_email').text('');
                        }
                        if (data.error_teacher_password != '') {
                            $('#error_teacher_password').text(data.error_teacher_password);
                        } else {
                            $('#error_teacher_password').text('');
                        }
                    }
                }
            })
        });
    });
</script>