<?php

//attendance_chart.php

include('header.php');

$present_percentage = 0;
$absent_percentage = 0;
$total_present = 0;
$total_absent = 0;
$output = "";

$query = "SELECT * FROM attendance INNER JOIN student  ON student.student_id = attendance.student_id 
INNER JOIN grade ON grade.grade_id = student.grade_id 
WHERE student.grade_id = '" . $_GET['grade_id'] . "' AND attendance.attendance_date = '" . $_GET["date"] . "'";

//echo $query;
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
			<td>' . $row["student_name"] . '</td>
			<td>' . $status . '</td>
		</tr>
	';
}

if ($total_row > 0) {
  $present_percentage = ($total_present / $total_row) * 100;
  $absent_percentage = ($total_absent / $total_row) * 100;
}

?>


<!-- Card store information about google chart -->
<div class="container" style="margin-top:30px">
  <div class="card">
    <div class="card-header"><b>Biểu đồ chuyên cần trong ngày của 1 Lớp</b></div>
    <div class="card-body">
      <div class="table-responsive">

        <table class="table table-bordered table-striped">
          <tr>
            <th>Tên Lớp</th>
            <td><?php echo Get_grade_name($connection, $_GET["grade_id"]); ?></td>
          </tr>
          <tr>
            <th>Ngày Học:</th>
            <td><?php echo $_GET["date"]; ?></td>
          </tr>
        </table>

      </div>
      <div id="attendance_pie_chart" style="width: 100%; height: 400px;">

      </div>

      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <tr>
            <th>Tên Học Sinh</th>
            <th>Điểm danh</th>
          </tr>
          <?php echo $output;  ?>
        </table>
      </div>
    </div>
  </div>
</div>

</body>

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
      title: 'Dữ liệu Chuyên cần trong ngày của 1 Lớp',
      hAxis: {
        title: '%',
        minValue: 0,
        maxValue: 100
      },
      vAxis: {
        title: 'Điểm danh'
      }
    };

    var chart = new google.visualization.PieChart(document.getElementById('attendance_pie_chart'));
    chart.draw(data, options);
  }
</script>