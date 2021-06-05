<?php

// student.php

include('header.php');

?>

<body>
    <!-- header top -->
    <div class="jumbotron text-center" style="margin-bottom:0">
        <h1>Hệ thống điểm danh Học Sinh Attendance Pro</h1>
    </div>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <a href="index.php" class="navbar-brand"><i class="fas fa-home"></i></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="$collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="grade.php" class="nav-link">Lớp</a>
                </li>
                <li class="nav-item">
                    <a href="teacher.php" class="nav-link">Giáo Viên</a>
                </li>
                <li class="nav-item">
                    <a href="student.php" class="nav-link active">Học Sinh</a>
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
                    <div class="col-md-9">Danh sách Học Sinh</div>
                    <div class="col-md-3" align="right">
                        <button type="button" id="add_button" class="btn btn-info btn-sm">Thêm Học Sinh</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <span id="message_operation"></span>
                    <table class="table table-striped table-bordered" id="student_table">
                        <thead>
                            <tr>
                                <th>Họ và tên</th>
                                <th>Mã Học Sinh</th>
                                <th>Ngày sinh</th>
                                <th>Lớp</th>
                                <th>Sửa</th>
                                <th>Xóa</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <!-- BS4 modal for add or edit student -->
    <div class="modal" id="formModal">
        <div class="modal-dialog">
            <form method="post" id="student_form">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal_title"></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <label for="student_name" class="col-md-4 text-right">Họ và tên <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="student_name" id="student_name" class="form-control" />
                                    <span id="error_student_name" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 text-right">Mã Học Sinh <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="student_roll_number" id="student_roll_number" class="form-control" />
                                    <span id="error_student_roll_number" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 text-right">Ngày sinh <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="student_dob" id="student_dob" class="form-control" />
                                    <span id="error_student_dob" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 text-right">Lớp <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select name="student_grade_id" id="student_grade_id" class="form-control">
                                        <option value="">Chọn Lớp</option>
                                        <?php echo load_grade_list($connection); ?>

                                    </select>
                                    <span id="error_student_grade_id" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <input type="hidden" name="student_id" id="student_id" />
                        <input type="hidden" name="action" id="action" value="Add" />
                        <input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="" />
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Đóng</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- BS4 modal for delete student -->
    <div class="modal" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Xác nhận xóa thông tin Học Sinh</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <h3 align="center">Bạn có chắc muốn xóa Học sinh này ?</h3>
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

        //DataTable for get information from database and send to webpage
        var dataTable = $('#student_table').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                url: "student_action.php",
                method: "POST",
                data: {
                    action: 'fetch'
                },
            },
            "columnDefs": [{
                "targets": [0, 1, 2, 3, 4, 5],
                "orderable": true
            }, ],
        });

        //Config datapicker
        $('#student_dob').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            container: '#formModal modal-body'
        });

        function clear_field() {
            $('#student_form')[0].reset();
            $('#error_student_name').text('');
            $('#error_student_roll_number').text('');
            $('#error_student_dob').text('');
            $('#error_student_grade_id').text('');
        }

        //When click button add
        $('#add_button').click(function() {
            $('#modal_title').text('Thêm mới Học Sinh');
            $('#button_action').val('Thêm');
            $('#action').val('Add');
            $('#formModal').modal('show');
            clear_field();
        });

        //When submit form
        $('#student_form').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: "student_action.php",
                method: "POST",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function() {
                    $('#button_action').val('Validate...');
                    $('#button_action').attr('disabled', true);
                },
                success: function(data) {
                    $('#button_action').attr('disabled', false);
                    $('#button_action').val($('#action').val());

                    if (data.success) {
                        $('#message_operation').html('<div class="alert alert-success">' + data.success + '</div>');
                        clear_field();
                        $('#formModal').modal('hide');
                        dataTable.ajax.reload();
                    }
                    if (data.error) {
                        $action = $('#action').val();
                        if ($action == "Add") {
                            $('#button_action').val('Thêm');
                        } else {
                            $('#button_action').val('Sửa');
                        }


                        if (data.error_student_name != '') {
                            $('#error_student_name').text(data.error_student_name);
                        } else {
                            $('#error_student_name').text('');
                        }
                        if (data.error_student_roll_number != '') {
                            $('#error_student_roll_number').text(data.error_student_roll_number);
                        } else {
                            $('#error_student_roll_number').text('');
                        }
                        if (data.error_student_dob != '') {
                            $('#error_student_dob').text(data.error_student_dob);
                        } else {
                            $('#error_student_dob').text('');
                        }
                        if (data.error_student_grade_id != '') {
                            $('#error_student_grade_id').text(data.error_student_grade_id);
                        } else {
                            $('#error_student_grade_id').text('');
                        }
                    }
                }
            })
        });

        var student_id = '';
        //When click button edit
        $(document).on('click', '.edit_student', function() {
            student_id = $(this).attr('id');
            clear_field();
            $.ajax({
                url: "student_action.php",
                method: "POST",
                data: {
                    action: 'edit_fetch',
                    student_id: student_id
                },
                dataType: "json",
                success: function(data) {
                    $('#student_name').val(data.student_name);
                    $('#student_roll_number').val(data.student_roll_number);
                    $('#student_dob').val(data.student_dob);
                    $('#student_grade_id').val(data.student_grade_id);
                    $('#student_id').val(data.student_id);
                    $('#modal_title').text('Sửa thông tin Học Sinh');
                    $('#button_action').val('Sửa');
                    $('#action').val('Edit');
                    $('#formModal').modal('show');
                }
            })
        });

        //When click button delete
        $(document).on('click', '.delete_student', function() {
            student_id = $(this).attr('id');
            $('#deleteModal').modal('show');
        });


        //When click button ok in modal delete
        $('#ok_button').click(function() {
            $.ajax({
                url: "student_action.php",
                method: "POST",
                data: {
                    student_id: student_id,
                    action: "delete"
                },
                success: function(data) {
                    $('#message_operation').html('<div class="alert alert-success">' + data + '</div>');
                    $('#deleteModal').modal('hide');
                    dataTable.ajax.reload();
                }
            })
        });
    });
</script>