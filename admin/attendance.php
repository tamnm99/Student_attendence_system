<?php

//attendance.php

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
                    <a href="student.php" class="nav-link">Học Sinh</a>
                </li>
                <li class="nav-item">
                    <a href="attendance.php" class="nav-link active">Chuyên cần</a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">Đăng Xuất</a>
                </li>
            </ul>

        </div>

    </nav>

   <!-- Card Information -->
    <div class="container" style="margin-top:30px">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-9">Danh sách Chuyên Cần</div>
                    <div class="col-md-3" align="right">
                        <button type="button" id="chart_button" class="btn btn-primary btn-sm">Biểu Đồ</button>
                        <button type="button" id="report_button" class="btn btn-danger btn-sm">Báo Cáo</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="attendance_table">
                        <thead>
                            <tr>
                                <th>Tên Học Sinh</th>
                                <th>Mã Học Sinh</th>
                                <th>Lớp</th>
                                <th>Điểm danh</th>
                                <th>Ngày</th>
                                <th>Giáo Viên</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- BS4 modal for create file pdf of a grade -->
    <div class="modal" id="reportModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title"><h6>In Báo cáo Chuyên Cần của 1 Lớp</h6></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <select name="grade_id" id="grade_id" class="form-control">
                            <option value="">Chọn Lớp</option>
                            <?php echo load_grade_list($connection); ?>
                        </select>
                        <span id="error_grade_id" class="text-danger"></span>
                    </div>
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
                    <button type="button" name="create_report" id="create_report" class="btn btn-success btn-sm">Xuất file .pdf</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Đóng</button>
                </div>

            </div>
        </div>
    </div>


    <div class="modal" id="chartModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title"><h6>Biểu đồ chuyên cần trong 1 ngày của 1 lớp</h6></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <select name="chart_grade_id" id="chart_grade_id" class="form-control">
                            <option value="">Chọn Lớp</option>
                            <?php  echo load_grade_list($connection);  ?>
                        </select>
                        <span id="error_chart_grade_id" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <div class="input-daterange">
                            <input type="text" name="attendance_date" id="attendance_date" class="form-control" placeholder="Chọn Ngày" readonly />
                            <span id="error_attendance_date" class="text-danger"></span>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" name="create_chart" id="create_chart" class="btn btn-success btn-sm">Tạo Biểu đồ</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Đóng</button>
                </div>

            </div>
        </div>
    </div>
</body>

</html>



<script>
    $(document).ready(function() {

        var dataTable = $('#attendance_table').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                url: "attendance_action.php",
                type: "POST",
                data: {
                    action: 'fetch'
                }
            },
            "columnDefs": [{
                "targets": [0, 1, 2, 3, 4, 5],
                "orderable": false,
            }, ],
        });


        $('.input-daterange').datepicker({
            todayBtn: "linked",
            format: "yyyy-mm-dd",
            autoclose: true,
            container: '#formModal modal-body'
        });

        $(document).on('click', '#report_button', function() {
            $('#reportModal').modal('show');
        });

        $('#create_report').click(function() {
            var grade_id = $('#grade_id').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var error = 0;

            if (grade_id == '') {
                $('#error_grade_id').text('Lớp không được để trống');
                error++;
            } else {
                $('#error_grade_id').text('');
            }

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
                window.open("report.php?action=attendance_report&grade_id=" + grade_id + "&from_date=" + from_date + "&to_date=" + to_date);
            }

        });

        $('#chart_button').click(function() {
            $('#chart_grade_id').val('');
            $('#attendance_date').val('');
            $('#chartModal').modal('show');
        });

        $('#create_chart').click(function() {
            var grade_id = $('#chart_grade_id').val();
            var attendance_date = $('#attendance_date').val();
            var error = 0;
            if (grade_id == '') {
                $('#error_chart_grade_id').text('Grade is Required');
                error++;
            } else {
                $('#error_chart_grade_id').text('');
            }
            if (attendance_date == '') {
                $('#error_attendance_date').text('Date is Required');
                $error++;
            } else {
                $('#error_attendance_date').text('');
            }

            if (error == 0) {
                $('#attendance_date').val('');
                $('#chart_grade_id').val('');
                $('#chartModal').modal('show');
                window.open("attendance_chart.php?action=attendance_report&grade_id=" + grade_id + "&date=" + attendance_date);
            }

        });
    });
</script>