<?php

include("header.php");

// Get information about grade which this teacher teach

$query = "SELECT * FROM grade WHERE grade_id = (SELECT grade_id FROM teacher WHERE 
teacher_id = '" . $_SESSION["teacher_id"] . "')";

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
                    <a class="nav-link" href="profile.php">Hồ Sơ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="attendance.php">Điểm danh</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container" style="margin-top:30px">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-9"><h6>Danh sách chuyên cần</h6></div>
                    <div class="col-md-3" align="right">
                        <button type="button" id="report_button" class="btn btn-danger btn-sm">Báo cáo</button>
                        <button type="button" id="add_button" class="btn btn-info btn-sm">Thêm</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <span id="message_operation"></span>
                    <table class="table table-striped table-bordered" id="attendance_table">
                        <thead>
                            <tr>
                                <th>Tên Học Sinh</th>
                                <th>Mã Học Sinh</th>
                                <th>Lớp</th>
                                <th>Trạng thái</th>
                                <th>Ngày học</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- BS4 modal form for add attendance -->
    <div class="modal" id="formModal">
        <div class="modal-dialog">
            <form method="post" id="attendance_form">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal_title"></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <?php
                        foreach ($result as $row) {
                        ?>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-4 text-right">Lớp <span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <?php echo '<label>' . $row["grade_name"] . '</label>'; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-4 text-right">Ngày Học <span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <input type="text" name="attendance_date" id="attendance_date" class="form-control"/>
                                        <span id="error_attendance_date" class="text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="student_details">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Mã Học Sinh</th>
                                                <th>Tên Học Sinh</th>
                                                <th>Đi Học</th>
                                                <th>Vắng Mặt</th>
                                            </tr>
                                        </thead>
                                       
                                       <!-- get list student which this teacher teach -->
                                        <?php
                                            $sub_query = "SELECT * FROM student WHERE grade_id = '" . $row["grade_id"] . "'  ";
                                            $statement = $connection->prepare($sub_query);
                                            $statement->execute();
                                            $student_result = $statement->fetchAll();
                                            foreach ($student_result as $student) {
                                        ?>
                                            <tr>
                                                <td><?php echo $student["student_roll_number"]; ?></td>
                                                <td>
                                                    <?php echo $student["student_name"]; ?>
                                                    <!-- student_id is array  list id of student -->
                                                    <input type="hidden" name="student_id[]" 
                                                        value="<?php echo $student["student_id"]; ?>" />
                                                </td>
                                                <td>
                                                    <input type="radio" name="attendance_status<?php echo $student["student_id"]; ?>" 
                                                        value="Đi Học" />
                                                </td>
                                                <td>
                                                    <input type="radio" name="attendance_status<?php echo $student["student_id"]; ?>" 
                                                        checked value="Vắng Mặt" />
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <input type="hidden" name="action" id="action" value="Add" />
                        <input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Thêm" />
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Đóng</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- BS4 modal for export pdf -->
    <div class="modal" id="reportModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Tạo Báo cáo Điểm danh</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <div class="input-daterange">
                            <input type="text" name="from_date" id="from_date" class="form-control" placeholder="Từ Ngày" readonly />
                            <span id="error_from_date" class="text-danger"></span>
                            <br />
                            <input type="text" name="to_date" id="to_date" class="form-control" placeholder="Đến Ngày" readonly />
                            <span id="error_to_date" class="text-danger"></span>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" name="create_report" id="create_report" class="btn btn-success btn-sm">Xuất file pdf</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Đóng</button>
                </div>

            </div>
        </div>
    </div>


</body>



<script>
    $(document).ready(function() {
        var dataTable = $('#attendance_table').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                url: "attendance_action.php",
                method: "POST",
                data: {
                    action: "fetch"
                }
            },
            "columnDefs": [{
                "targets": [0, 1, 2, 3, 4],
                "orderable": false,
            }, ],
        });

        $('#attendance_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            container: '#formModal modal-body'
        });

        function clear_field() {
            $('#attendance_form')[0].reset();
            $('#error_attendance_date').text('');
        }

        $('#add_button').click(function() {
            $('#modal_title').text("Thêm việc Đi Học");
            $('#button_action').val("Thêm");
            $('#formModal').modal('show');
            clear_field();
        });

        $('#attendance_form').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: "attendance_action.php",
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
                        if (data.error_attendance_date != '') {
                            $('#error_attendance_date').text(data.error_attendance_date);
                        } else {
                            $('#error_attendance_date').text('');
                        }
                    }
                }
            })
        });

        $('.input-daterange').datepicker({
            todayBtn: "linked", // always pick current day 
            format: "yyyy-mm-dd",
            autoclose: true,
            container: '#formModal modal-body'
        });

        $(document).on('click', '#report_button', function() {
            $('#reportModal').modal('show');
        });

        $('#create_report').click(function() {
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var error = 0;
            if (from_date == '') {
                $('#error_from_date').text('"Từ Ngày" không được để trống');
                error++;
            } else {
                $('#error_from_date').text('');
            }

            if (to_date == '') {
                $('#error_to_date').text('"Đến Ngày" không được để trống');
                error++;
            } else {
                $('#error_to_date').text('');
            }

            if (error == 0) {
                $('#from_date').val('');
                $('#to_date').val('');
                $('#formModal').modal('hide');

                //dircet to file repot.php and use $_GET
                window.open("report.php?action=attendance_report&from_date=" + from_date + "&to_date=" + to_date);
            }

        });

    });
</script>