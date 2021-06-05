<?php
    //login.php
    include('database_connection.php');

    session_start();

    if(isset($_COOKIE["admin_id"]) && isset($_SESSION["admin_id"])){
        header('location: index.php');
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Pro Admin Login</title>

    <!-- Latest compiled and minified CSS --> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

    <div class="jumbotron text-center" style="margin-bottom:0">
        <h1>Hệ thống điểm danh Học Sinh Attendance Pro</h1>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-4">

            </div>

            <div class="col-md-4" style="margin-top:20px">
                <div class="card">
                    <div class="card-header"><h1>Admin Login</h1></div>
                    <div class="card-body">
                        <form method="post" id="admin_login_form">
                            <div class="form-group">
                              <label for="admin_user_name"><h6>Nhập UserName:</h6> </label>
                              <input type="text" name="admin_user_name" id="admin_user_name" class="form-control" 
                                placeholder="Enter UserName" />
                              <span id="error_admin_user_name" class="text-danger"></span>
                            </div>

                            <div class="form-group">
                              <label for="admin_password"><h6>Nhập Password: </h6></label>
                              <input type="password" name="admin_password" id="admin_password" class="form-control" 
                                placeholder="Enter Password" />
                              <span id="error_admin_password" class="text-danger"></span>
                            </div>
                            
                            <div class="form-group">
                                <input type="submit" value="Đăng nhập" name="admin_login" id="admin_login" class="btn btn-info">
                            </div>
                        </form>
                    
                    </div>
                
                </div>
            </div>
        </div>
    </div>
    
</body>
<script>
    //jquery
    $(document).ready(function(){
        $('#admin_login_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                url: "check_admin_login.php",
                method: "POST",
                data: $(this).serialize(),// convert current data html form to json
                dataType: "json", 
                beforeSend: function(){
                    $('#admin_login').val('Validate....');
                    $('#admin_login').attr('disabled', true);
                },
                success: function(data){
                    if(data.success){
                        location.href = "<?php echo $base_url; ?>/admin";
                    }

                    if(data.error){
                        $('#admin_login').val('Đăng nhập');
                        $('#admin_login').attr('disabled', false);
                        
                        if(data.error_admin_user_name != ''){
                            $('#error_admin_user_name').text(data.error_admin_user_name);
                        }else{
                            $('#error_admin_user_name').text('');
                        }

                        if(data.error_admin_password != ''){
                            $('#error_admin_password').text(data.error_admin_password);
                        } else{
                            $('#error_admin_password').text('');
                        }
                    }
                }
            });
        });
    });
</script>
</html>
