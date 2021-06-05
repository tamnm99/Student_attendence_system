<?php

// Profile of teacher

include('header.php');

$teacher_name = '';
$teacher_address = '';
$teacher_email = '';
$teacher_password = '';
$teacher_grade_id = '';
$teacher_qualification = '';
$teacher_date_of_join = '';
$teacher_image = '';
$error_teacher_name = '';
$error_teacher_address = '';
$error_teacher_email = '';
$error_teacher_grade_id = '';
$error_teacher_qualification = '';
$error_teacher_date_of_join = '';
$error_teacher_image = '';
$error = 0;
$success = '';

// Handle edit profile teacher
if (isset($_POST["button_action"])) {

    $teacher_image = $_POST["hidden_teacher_image"];
    if ($_FILES["teacher_image"]["name"] != '') {
        $file_name = $_FILES["teacher_image"]["name"];
        $tmp_name = $_FILES["teacher_image"]["tmp_name"];
        $extension_array = explode(".", $file_name);
        $extension = strtolower($extension_array[1]);
        $allowed_extension = array('jpg', 'png');
        if (!in_array($extension, $allowed_extension)) {
            $error_teacher_image = "Sai định dạng ảnh";
            $error++;
        } else {
            $teacher_image = uniqid() . '.' . $extension;
            $upload_path = 'admin/teacher_image/' . $teacher_image;
            move_uploaded_file($tmp_name, $upload_path);
        }
    }

    if (empty($_POST["teacher_name"])) {
        $error_teacher_name = "Họ và tên không được để trống";
        $error++;
    } else {
        $teacher_name = $_POST["teacher_name"];
    }

    if (empty($_POST["teacher_address"])) {
        $error_teacher_address = 'Địa chỉ không được để trống';
        $error++;
    } else {
        $teacher_address = $_POST["teacher_address"];
    }

    if (empty($_POST["teacher_email"])) {
        $error_teacher_email = "Địa chỉ email không được để trống";
        $error++;
    } else {
        if (!filter_var($_POST["teacher_email"], FILTER_VALIDATE_EMAIL)) {
            $error_teacher_email = "Sai định dạng email";
            $error;
        } else {
            $teacher_email = $_POST["teacher_email"];
        }
    }

    if (!empty($_POST["teacher_password"])) {
        $teacher_password = $_POST["teacher_password"];
    }

    if (empty($_POST["teacher_grade_id"])) {
        $error_teacher_grade_id = 'Lớp không được để trống';
        $error++;
    } else {
        $teacher_grade_id = $_POST["teacher_grade_id"];
    }

    if (empty($_POST["teacher_qualification"])) {
        $error_teacher_qualification = "Trình độ không được để trống";
        $error++;
    } else {
        $teacher_qualification = $_POST["teacher_qualification"];
    }

    if (empty($_POST["teacher_date_of_join"])) {
        $error_teacher_date_of_join = "Ngày kí hợp đồng không được để trống";
        $error++;
    } else {
        $teacher_date_of_join = $_POST["teacher_date_of_join"];
    }

    if ($error == 0) {
        if ($teacher_password != '') {
            $data = array(
                ':teacher_name'             =>    $teacher_name,
                ':teacher_address'          =>    $teacher_address,
                ':teacher_email'            =>    $teacher_email,
                ':teacher_password'         =>    password_hash($teacher_password, PASSWORD_DEFAULT),
                ':teacher_qualification'    =>    $teacher_qualification,
                ':teacher_date_of_join'     =>    $teacher_date_of_join,
                ':teacher_image'            =>    $teacher_image,
                ':teacher_grade_id'         =>    $teacher_grade_id,
                ':teacher_id'               =>    $_POST["teacher_id"]
            );

            $query = "UPDATE teacher SET teacher_name = :teacher_name, teacher_address = :teacher_address, 
		      teacher_email = :teacher_email, teacher_password = :teacher_password, grade_id = :teacher_grade_id, 
		      teacher_qualification = :teacher_qualification, teacher_date_of_join = :teacher_date_of_join, 
		      teacher_image = :teacher_image WHERE teacher_id = :teacher_id";
        } else {
            $data = array(
                ':teacher_name'             =>    $teacher_name,
                ':teacher_address'          =>    $teacher_address,
                ':teacher_email'            =>    $teacher_email,
                ':teacher_qualification'    =>    $teacher_qualification,
                ':teacher_date_of_join'     =>    $teacher_date_of_join,
                ':teacher_image'            =>    $teacher_image,
                ':teacher_grade_id'         =>    $teacher_grade_id,
                ':teacher_id'               =>    $_POST["teacher_id"]
            );
            $query = "UPDATE teacher SET teacher_name = :teacher_name, teacher_address = :teacher_address, 
		      teacher_email = :teacher_email, grade_id = :teacher_grade_id, teacher_qualification = :teacher_qualification, 
		      teacher_date_of_join = :teacher_date_of_join, teacher_image = :teacher_image WHERE teacher_id = :teacher_id ";
        }

        $statement = $connection->prepare($query);
        if ($statement->execute($data)) {
            $success = '<div class="alert alert-success">Thay đổi profile Giáo Viên thành công</div>';
        }
    }
}


$query = "SELECT * FROM teacher WHERE teacher_id = '" . $_SESSION["teacher_id"] . "'";
$statement = $connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll();


?>

<body>

    <div class="jumbotron-small text-center" style="margin-bottom:0">
        <h1>Hệ thống điểm danh Học Sinh Attendance Pro</h1>
    </div>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <a class="navbar-brand" href="index.php"><i class="fas fa-home"></i></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="profile.php">Hồ Sơ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="attendance.php">Điểm Danh</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- BS4 card body to show data profile teacher  -->
    <div class="container" style="margin-top:30px">
        <span><?php echo $success; ?></span>
        <div class="card">
            <form method="post" id="profile_form" enctype="multipart/form-data">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-9">
                            <h3>Hồ sơ giáo viên</h3>
                        </div>
                        <div class="col-md-3" align="right">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Họ và Tên <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="teacher_name" id="teacher_name" class="form-control" />
                                <span class="text-danger"><?php echo $error_teacher_name; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Địa chỉ <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <textarea name="teacher_address" id="teacher_address" class="form-control"></textarea>
                                <span class="text-danger"><?php echo $error_teacher_address; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Email <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="teacher_email" id="teacher_email" class="form-control" />
                                <span class="text-danger"><?php echo $error_teacher_email; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Password <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="password" name="teacher_password" id="teacher_password" class="form-control" placeholder="Để trống nếu giữ nguyên password cũ" />
                                <span class="text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Trình độ <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="teacher_qualification" id="teacher_qualification" class="form-control" />
                                <span class="text-danger"><?php echo $error_teacher_qualification; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Chủ nhiệm <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="teacher_grade_id" id="teacher_grade_id" class="form-control">
                                    <option value="">Chọn Lớp</option>
                                    <?php echo load_grade_list($connection); ?>
                                </select>
                                <span class="text-danger"><?php echo $error_teacher_grade_id; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Kí Hợp Đồng <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="teacher_date_of_join" id="teacher_date_of_join" class="form-control" />
                                <span class="text-danger"><?php echo $error_teacher_date_of_join; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Ảnh <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="file" name="teacher_image" id="teacher_image" />
                                <span class="text-muted">Ảnh định dạng .jpg hoặc .png</span><br />
                                <span id="error_teacher_image" class="text-danger"><?php echo $error_teacher_image; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer" align="center">
                    <input type="hidden" name="hidden_teacher_image" id="hidden_teacher_image" />
                    <input type="hidden" name="teacher_id" id="teacher_id" />
                    <input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Lưu" />
                </div>
            </form>
        </div>
    </div>

</body>



<script>
    $(document).ready(function() {

        // fill card body input with data get from database
        <?php foreach ($result as $row) { ?>
            $('#teacher_name').val("<?php echo $row["teacher_name"]; ?>");
            $('#teacher_address').val("<?php echo $row["teacher_address"]; ?>");
            $('#teacher_email').val("<?php echo $row["teacher_email"]; ?>");
            $('#teacher_qualification').val("<?php echo $row["teacher_qualification"]; ?>");
            $('#teacher_grade_id').val("<?php echo $row["grade_id"]; ?>");
            $('#teacher_date_of_join').val("<?php echo $row["teacher_date_of_join"]; ?>");
            $('#error_teacher_image').html("<img src='admin/teacher_image/<?php echo $row['teacher_image']; 
                ?>' class='img-thumbnail' width='100' />");
            $('#hidden_teacher_image').val('<?php echo $row["teacher_image"]; ?>');
            $('#teacher_id').val("<?php echo $row["teacher_id"]; ?>");

        <?php } ?>

        //config datapicker
        $('#teacher_date_of_join').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });

    });
</script>