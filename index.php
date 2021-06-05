<?php

//teacher index

include('header.php');

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
                    <a class="nav-link" href="attendance.php">Chuyên Cần</a>
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
                    <div class="col-md-9"><h6>Danh sách Chuyên Cần</h6></div>
                    <div class="col-md-3" align="right">

                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="student_table">
                        <thead>
                            <tr>
                                <th>Tên Học Sinh</th>
                                <th>Mã Học Sinh</th>
                                <th>Lớp</th>
                                <th>Tỷ lệ đi học</th>
                                <th>Báo cáo</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- BS4 modal for create .pdf report -->
    <div class="modal" id="formModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Tạo Báo cáo Chuyên cần của 1 Học Sinh</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <div class="input-daterange">
                            <input type="text" name="from_date" id="from_date" class="form-control" placeholder="Từ ngày" readonly />
                            <span id="error_from_date" class="text-danger"></span>
                            <br />
                            <input type="text" name="to_date" id="to_date" class="form-control" placeholder="Đến ngày" readonly />
                            <span id="error_to_date" class="text-danger"></span>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <input type="hidden" name="student_id" id="student_id" />
                    <button type="button" name="create_report" id="create_report" class="btn btn-success btn-sm">Xuất file .pdf</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Đóng</button>
                </div>

            </div>
        </div>
    </div>
</body>

</html>



<script>
    $(document).ready(function() {

        var dataTable = $('#student_table').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                url: "attendance_action.php",
                type: "POST",
                data: {
                    action: 'index_fetch'
                }
            },
            "columnDefs": [{
                "targets": [0, 1, 2],
                "orderable": false,
            }, ],
        });

        $('.input-daterange').datepicker({
            todayBtn: "linked",
            format: "yyyy-mm-dd",
            autoclose: true,
            container: '#formModal modal-body'
        });

        $(document).on('click', '.report_button', function() {
            var student_id = $(this).attr('id');
            $('#student_id').val(student_id);
            $('#formModal').modal('show');
        });

        $('#create_report').click(function() {
            var student_id = $('#student_id').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var error = 0;
            if (from_date == '') {
                $('#error_from_date').text('"Từ ngày" không được để trống');
                error++;
            } else {
                $('#error_from_date').text('');
            }
            if (to_date == '') {
                $('#error_to_date').text('"Đến ngày" không được để trống');
                error++;
            } else {
                $('#error_to_date').text('');
            }

            if (error == 0) {
                $('#from_date').val('');
                $('#to_date').val('');
                $('#formModal').modal('hide');
                window.open("report.php?action=student_report&student_id=" + student_id + "&from_date=" + from_date + "&to_date=" + to_date);
            }
        });

    });
</script>