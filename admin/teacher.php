<?php

//    teacher.php
include('header.php');

?>

<body>
    <!-- header top -->
    <div class="jumbotron text-center" style="margin-bottom:0">
        <h1>Hệ thống điểm danh Học Sinh Attendance Pro</h1>
    </div>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <a href="index.php" class="navbar-brand unactive"><i class="fas fa-home"></i></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="$collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="grade.php" class="nav-link">Lớp</a>
                </li>
                <li class="nav-item">
                    <a href="teacher.php" class="nav-link active">Giáo Viên</a>
                </li>
                <li class="nav-item">
                    <a href="student.php" class="nav-link">Học Sinh</a>
                </li>
                <li class="nav-item">
                    <a href="attendance.php" class="nav-link">Chuyên Cần</a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">Đăng Xuất</a>
                </li>
            </ul>

        </div>

    </nav>

    <div class="container" style="margin-top:30px">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-9">
                        <h5>Danh sách Giáo Viên</h5>
                    </div>
                    <div class="class-col-md-3" align="right">
                        <button type="button" id="add_button" class="btn btn-sm btn-info">Thêm Giáo viên</button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <span id="message_operation"></span>
                    <table class="table table-striped table-bordered" id="teacher_table">
                        <thead>
                            <tr>
                                <th>Ảnh</th>
                                <th>Họ Và Tên</th>
                                <th>Email</th>
                                <th>Chủ Nhiệm</th>
                                <th>Chi Tiết</th>
                                <th>Sửa</th>
                                <th>Xóa</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- BS4 nodal form for add/edit teacher -->
    <div class="modal" id="formModal">
        <div class="modal-dialog">
            <form method="POST" id="teacher_form" enctype="multipart/form-data">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal_title">Thêm mới Giáo Viên</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 text-right">Tên Giáo Viên<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="teacher_name" id="teacher_name" class="form-control" />
                                    <span id="error_teacher_name" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 text-right">Địa Chỉ<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    <textarea name="teacher_address" id="teacher_address" class="form-control"></textarea>
                                    <span id="error_teacher_address" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 text-right">Email <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="teacher_email" id="teacher_email" class="form-control" />
                                    <span id="error_teacher_email" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 text-right">Password <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="password" name="teacher_password" id="teacher_password" class="form-control" />
                                    <span id="error_teacher_password" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 text-right">Trình độ<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="teacher_qualification" id="teacher_qualification" class="form-control" />
                                    <span id="error_teacher_qualification" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 text-right">Chủ Nhiệm<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    <select name="teacher_grade_id" id="teacher_grade_id" class="form-control">
                                        <option value="">Chọn Tên Lớp</option>
                                        <?php echo load_grade_list($connection); ?>
                                    </select>
                                    <span id="error_teacher_grade_id" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 text-right">Kí Hợp Đồng <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="teacher_date_of_join" id="teacher_date_of_join" class="form-control" placeholder="yyyy-mm-dd" />
                                    <span id="error_teacher_date_of_join" class="text-danger"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 text-right">Ảnh <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="file" name="teacher_image" id="teacher_image" />
                                    <span class="text-muted">Chỉ định dạng ảnh .jpg và .png</span><br />
                                    <span id="error_teacher_image" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <input type="hidden" name="hidden_teacher_image" id="hidden_teacher_image" />
                        <input type="hidden" name="teacher_id" id="teacher_id" />
                        <input type="hidden" name="action" id="action" value="Add" />
                        <input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" />
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Đóng</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- BS4 modal for view detail a teacher -->
    <div class="modal" id="viewModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Xem chi tiết Giáo Viên</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body" id="teacher_details">

                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Đóng</button>
                </div>

            </div>
        </div>
    </div>

    <!-- BS4 modal for delete a teacher -->
    <div class="modal" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Xác nhận xóa thông tin Giáo Viên</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <h3 align="center">Bạn có chắc muốn xóa thông tin Giáo viên này?</h3>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" name="ok_button" id="ok_button" class="btn btn-primary btn-sm">Có</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Không</button>
                </div>

            </div>
        </div>
    </div>
</body>

</html>

<script>
    $(document).ready(function() {

        //DataTable for get record from database and show to webpage
        var dataTable = $('#teacher_table').DataTable({
            "processing": true,
            "severSide": true,
            "oder": [],
            "ajax": {
                url: "teacher_action.php",
                type: "POST",
                data: {
                    action: 'fetch'
                }
            }
        });

        //initialize Date picker
        $('#teacher_date_of_join').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            container: '#formModal modal-body'
        });

        //clear all field and message error in form
        function clear_field() {
            $('#teacher_form')[0].reset();
            $('#error_teacher_name').text('');
            $('#error_teacher_address').text('');
            $('#error_teacher_email').text('');
            $('#error_teacher_password').text('');
            $('#error_teacher_qualification').text('');
            $('#error_teacher_date_of_join').text('');
            $('#error_teacher_image').text('');
            $('#error_teacher_grade_id').text('');
        }

        //When click button "Thêm Giáo Viên"
        $('#add_button').click(function() {
            $('#button_action').val('Thêm');
            $('#action').val('Add');
            $('#formModal').modal('show');
            $('#teacher_email').attr('readonly', false);
            $('#teacher_password').attr('readonly', false);
            clear_field();
        });

        //When form add or edit submit
        $('#teacher_form').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: "teacher_action.php",
                method: "POST",
                data: new FormData(this), // when form has file, use this line of code
                dataType: "json",
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#button_action').val('Validate'),
                        $('#button_action').attr('disabled', true);
                },
                success: function(data) {
                    $('#button_action').attr('disabled', false);
                    $('#button_action').val($('#action').val());

                    if (data.success) {
                        $('#message_operation').html('<div class="alert alert-success">' + data.success + '</div');
                        clear_field();
                        dataTable.ajax.reload();
                        $('#formModal').modal('hide');
                    }

                    if (data.error) {
                        $action = $('#action').val();
                        if ($action == 'Add') {
                            $('#button_action').val('Thêm');
                        } else {
                            $('#button_action').val('Sửa');
                        }

                        if (data.error_teacher_name != '') {
                            $('#error_teacher_name').text(data.error_teacher_name);
                        } else {
                            $('#error_teacher_name').text('');
                        }

                        if (data.error_teacher_address != '') {
                            $('#error_teacher_address').text(data.error_teacher_address);
                        } else {
                            $('#error_teacher_address').text('');
                        }

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

                        if (data.error_teacher_grade_id != '') {
                            $('#error_teacher_grade_id').text(data.error_teacher_grade_id);
                        } else {
                            $('#error_teacher_grade_id').text('');
                        }

                        if (data.error_teacher_qualification != '') {
                            $('#error_teacher_qualification').text(data.error_teacher_qualification);
                        } else {
                            $('#error_teacher_qualification').text('');
                        }

                        if (data.error_teacher_date_of_join != '') {
                            $('#error_teacher_date_of_join').text(data.error_teacher_date_of_join);
                        } else {
                            $('#error_teacher_date_of_join').text('');
                        }

                        if (data.error_teacher_image != '') {
                            $('#error_teacher_image').text(data.error_teacher_image);
                        } else {
                            $('#error_teacher_image').text('');
                        }
                    }
                }
            });
        });

        // When click button view a teacher
        var teacher_id = '';
        $(document).on('click', '.view_teacher', function() {
            teacher_id = $(this).attr('id');
            $.ajax({
                url: "teacher_action.php",
                method: "POST",
                data: {
                    action: 'single_fetch',
                    teacher_id: teacher_id
                },
                success: function(data) {
                    $('#viewModal').modal('show');
                    $('#teacher_details').html(data);
                }
            });
        });

        // Click button edit teacher, get information about via ajax, show information teacher on modal
        $(document).on('click', '.edit_teacher', function() {
            teacher_id = $(this).attr('id');
            clear_field();
            $.ajax({
                url: "teacher_action.php",
                method: "POST",
                data: {
                    action: 'edit_fetch',
                    teacher_id: teacher_id
                },
                dataType: "json",
                beforeSend: function() {
                    $('#teacher_email').attr('readonly', true);
                    $('#teacher_password').attr('readonly', true);
                },
                success: function(data) {
                    $('#teacher_name').val(data.teacher_name);
                    $('#teacher_address').val(data.teacher_address);
                    $('#teacher_grade_id').val(data.grade_id);
                    $('#teacher_qualification').val(data.teacher_qualification);
                    $('#teacher_date_of_join').val(data.teacher_date_of_join);
                    $('#error_teacher_image').html('<img src="teacher_image/' + data.teacher_image + '" class="img-thumbnail" width="50"/>');
                    $('#hidden_teacher_image').val(data.teacher_image);
                    $('#teacher_id').val(data.teacher_id);
                    $('#modal_title').text('Sửa thông tin Giáo Viên');
                    $('#button_action').val('Sửa');
                    $('#action').val('Edit');
                    $('#formModal').modal('show');
                }
            });
        });

        // When click button delete
        $(document).on('click', '.delete_teacher', function() {
            teacher_id = $(this).attr('id');
            $('#deleteModal').modal('show');
        });

        // When click ok button in modal delete teacher
        $('#ok_button').click(function() {
            $.ajax({
                url: "teacher_action.php",
                method: "POST",
                data: {
                    teacher_id: teacher_id,
                    action: 'delete'
                },
                success: function(data) {
                    $('#message_operation').html('<div class="alert alert-success">' + data + '</div>');
                    dataTable.ajax.reload();
                    $('#deleteModal').modal('hide');
                }
            })
        });
    });
</script>