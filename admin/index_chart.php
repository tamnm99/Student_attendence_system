<?php

//chart.php

include('header.php');

$present_percentage = 0;
$absent_percentage = 0;
$total_present = 0;
$total_absent = 0;
$output = "";

$query = "SELECT * FROM attendance WHERE student_id = '" . $_GET['student_id'] . "' 
AND attendance_date >= '" . $_GET["from_date"] . "' AND attendance_date <= '" . $_GET["to_date"] . "'";

$statement = $connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();

foreach ($result as $row) {
    $status = '';
    if ($row["attendance_status"] == "Đi Học") {
        $total_present++;
        $status = '<span class="badge badge-success">Đi Học</span>';
    }

    if ($row["attendance_status"] == "Vắng Mặt") {
        $total_absent++;
        $status = '<span class="badge badge-danger">Vắng Mặt</span>';
    }

    $output .= '
		<tr>
			<td>' . $row["attendance_date"] . '</td>
			<td>' . $status . '</td>
		</tr>
	';

    $present_percentage = ($total_present / $total_row) * 100;
    $absent_percentage = ($total_absent / $total_row) * 100;
}

?>


<!-- Card store information about google chart -->
<div class="container" style="margin-top:30px">
    <div class="card">
        <div class="card-header"><b>Biểu đồ Chuyên Cần</b></div>
        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered table-striped">
                    <tr>
                        <th>Tên Học Sinh</th>
                        <td><?php echo Get_student_name($connection, $_GET["student_id"]); ?></td>
                    </tr>
                    <tr>
                        <th>Lớp</th>
                        <td><?php echo Get_student_grade_name($connection, $_GET["student_id"]); ?></td>
                    </tr>
                    <tr>
                        <th>Giáo Viên chủ nhiệm</th>
                        <td><?php echo Get_student_teacher_name($connection, $_GET["student_id"]); ?></td>
                    </tr>
                    <tr>
                        <th>Thời gian</th>
                        <td><?php echo ' Từ ' . $_GET["from_date"] . ' đến ' . $_GET["to_date"]; ?></td>
                    </tr>
                </table>

                <div id="attendance_pie_chart" style="width: 100%; height: 400px;">

                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th>Khoảng thời gian</th>
                            <th>Điểm Danh</th>
                        </tr>
                        <?php echo $output; ?>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>



</html>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<!-- Set up chart -->
<script type="text/javascript">
    google.charts.load('current', {
        'packages': ['corechart']
    });

    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Điểm Danh', '%'],
            ['Đi Học', <?php echo $present_percentage; ?>],
            ['Vắng Mặt', <?php echo $absent_percentage; ?>]
        ]);

        var options = {
            title: 'Dữ liệu Chuyên cần của 1 học sinh',
            hAxis: {
                title: '%',
                minValue: 0,
                maxValue: 100
            },
            vAxis: {
                title: 'Điểm Danh'
            }
        };

        var chart = new google.visualization.PieChart(document.getElementById('attendance_pie_chart'));

        chart.draw(data, options);
    }
</script>