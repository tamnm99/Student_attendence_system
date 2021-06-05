<?php
// grade.php

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
                    <a href="grade.php" class="nav-link active" >Lớp</a>
                </li>
                <li class="nav-item">
                    <a href="teacher.php" class="nav-link" >Giáo Viên</a>
                </li>
                <li class="nav-item">
                    <a href="student.php" class="nav-link">Học Sinh</a>
                </li>
                <li class="nav-item">
                    <a href="attendance.php" class="nav-link">Chuyên cần</a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">Đăng Xuất</a>
                </li>
            </ul>

        </div>

    </nav>


    <!-- data table of grade -->
    <div class="container" style="margin-top: 30px;">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-9">
                        <h6>Danh sách Lớp</h6>
                    </div>
                    <div class="col-md-3" align="right">
                        <button type="button" id="add_button" class="btn btn-info btn-sm">Thêm Lớp</button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <span id="message_operation"></span>
                    <table class="table table-striped table-bordered" id="grade_table">
                        <thead>
                            <tr>
                                <th>Tên Lớp</th>
                                <th>Sửa</th>
                                <th>Xóa</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>


    <!-- BS4 Modal Add and Edit Grade-->
    <div class="modal" id="formModal">
        <div class="modal-dialog">
            <form method="post" id="grade_form">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal_title">Thêm lớp học</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 text-right">Tên Lớp <span class="text-danger">*</span></label>

                                <div class="col-md-8">
                                    <input type="text" name="grade_name" id="grade_name" class="form-control">
                                    <span id="error_grade_name" class="text-danger"></span>
                                </div>

                            </div>

                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <input type="hidden" name="grade_id" id="grade_id">
                        <input type="hidden" name="action" id="action" value="Add">
                        <input type="submit" name="button_action" id="button_action" class="btn btn-success" value="">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- BS4 Modal Delete Grade-->
    <div class="modal" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Xóa Lớp Học</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <h3 align="center">Bạn muốn xóa Lớp học này ?</h3>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" name="ok_button" id="ok_button" class="btn btn-primary btn-sm">Có</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Không</button>
                </div>
            </div>

        </div>
    </div>

</body>

</html>

<script>
    $(document).ready(function() {

        //use library DataTable to show DataTable about grade
        var dataTable = $('#grade_table').DataTable({
            "processing": true,
            "severSide": true,
            "order": [], // set no order
            "ajax": {
                url: "grade_action.php",
                type: "POST",
                data: {
                    action: 'fetch'
                }
            },
            "columnDefs": [{
                "targets": [0, 1, 2],
                "orderable": true,
            }, ],
        });

        //click add button to add grade
        $('#add_button').click(function() {
            $('#button_action').val('Thêm');
            $("#action").val('Add')
            $('#formModal').modal('show');
            clear_field() // this funtion will clear all form field data
        });

        // reset field
        function clear_field() {
            $('#grade_form')[0].reset();
            $('#error_grade_name').text('');
        }

        //submit form add or edit
        $('#grade_form').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: "grade_action.php",
                method: "POST",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function() {
                    $('#button_action').attr('disabled', true);
                    $('#button_action').val('Validate...');
                },
                success: function(data) {
                    $('#button_action').attr('disabled', false);
                    $('#button_action').val($('#action').val());

                    if (data.success) {
                        $('#message_operation').html('<div class="alert alert-success">' + data.success + '</div>');
                        clear_field();
                        dataTable.ajax.reload();
                        $('#formModal').modal('hide');
                    }

                    // show error add grade
                    if (data.error) {
                        if (data.error_grade_name != '') {
                            $('#error_grade_name').text(data.error_grade_name);
                            
                        } else {
                            $('#error_grade_name').text('');
                        }
                    }
                    
                }
            });
        });

        //click button edit to show get information about grade and show to web page
        var grade_id = '';
        $(document).on('click', '.edit_grade', function() {
            grade_id = $(this).attr('id');
            clear_field();
            $('#action').val('Edit');
            $('#button_action').val('Sửa');
            $.ajax({
                url: "grade_action.php",
                method: "POST",
                data: {
                    grade_id: grade_id,
                    action: 'edit_fetch'
                },
                dataType: "json",
                success: function(data) {
                    $('#grade_name').val(data.grade_name);
                    $('#grade_id').val(data.grade_id);
                    $('#modal_title').text('Sửa tên lớp học');
                    $('#button_action').val('Sửa');
                    $('#action').val('Edit');
                    $('#formModal').modal('show');
                }
            });
        });

        // click button delete grade
        $(document).on('click', '.delete_grade', function() {
            grade_id = $(this).attr('id');
            $('#deleteModal').modal('show');
        });

        // "OK" to delete grade
        $("#ok_button").click(function() {
            $.ajax({
                url: "grade_action.php",
                method: "POST",
                data: {
                    grade_id: grade_id,
                    action: 'delete'
                },
                success: function(data) {
                    $('#message_operation').html('<div class="alert alert-success">' + data + '</div>');
                    $('#deleteModal').modal('hide');
                    dataTable.ajax.reload();
                }
            });
        });
    });
</script>