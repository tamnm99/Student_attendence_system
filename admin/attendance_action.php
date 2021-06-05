<?php

//student_action.php

include('database_connection.php');

session_start();

if (isset($_POST["action"])) {

	//Data table for admin/attendance.php
	if ($_POST["action"] == "fetch") {
		$query = "SELECT * FROM attendance INNER JOIN student ON student.student_id = attendance.student_id 
			INNER JOIN grade ON grade.grade_id = student.grade_id 
			INNER JOIN teacher ON teacher.teacher_id = attendance.teacher_id ";
		if (isset($_POST["search"]["value"])) {
			$query .= 'WHERE student.student_name LIKE "%' . $_POST["search"]["value"] . '%" 
				OR student.student_roll_number LIKE "%' . $_POST["search"]["value"] . '%" 
				OR attendance.attendance_status LIKE "%' . $_POST["search"]["value"] . '%" 
				OR attendance.attendance_date LIKE "%' . $_POST["search"]["value"] . '%" 
				OR teacher.teacher_name LIKE "%' . $_POST["search"]["value"] . '%" ';
			
		}
		if (isset($_POST["order"])) {
			$query .= ' ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
		} else {
			$query .= ' ORDER BY attendance.attendance_id DESC ';			
		}

		if ($_POST["length"] != -1) {
			$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$statement = $connection->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$data = array();
		$filtered_rows = $statement->rowCount();
		foreach ($result as $row) {
			$sub_array = array();
			$status = '';
			if ($row["attendance_status"] == "Đi Học") {
				$status = '<label class="badge badge-success">Đi Học</label>';
			}
			if ($row["attendance_status"] == "Vắng Mặt") {
				$status = '<label class="badge badge-danger">Vắng Mặt</label>';
			}
			$sub_array[] = $row["student_name"];
			$sub_array[] = $row["student_roll_number"];
			$sub_array[] = $row["grade_name"];
			$sub_array[] = $status;
			$sub_array[] = $row["attendance_date"];
			$sub_array[] = $row["teacher_name"];
			$data[] = $sub_array;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connection, 'attendance'),
			"data"				=>	$data
		);

		echo json_encode($output);
	}

	//Data table for admin/index.php
	if ($_POST["action"] == "index_fetch") {
		$query = "SELECT * FROM student LEFT JOIN attendance ON attendance.student_id = student.student_id 
			INNER JOIN grade ON grade.grade_id = student.grade_id INNER JOIN teacher ON teacher.grade_id = grade.grade_id ";
		if (isset($_POST["search"]["value"])) {
			$query .= 'WHERE student.student_name LIKE "%' . $_POST["search"]["value"] . '%" 
				OR student.student_roll_number LIKE "%' . $_POST["search"]["value"] . '%" 
				OR grade.grade_name LIKE "%' . $_POST["search"]["value"] . '%" 
				OR teacher.teacher_name LIKE "%' . $_POST["search"]["value"] . '%" ';
		}
		$query .= 'GROUP BY student.student_id ';
		if (isset($_POST["order"])) {
			$query .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
		} else {
			$query .= 'ORDER BY student.student_name ASC ';
		}

		if ($_POST["length"] != -1) {
			$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$statement = $connection->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$data = array();
		$filtered_rows = $statement->rowCount();
		foreach ($result as $row) {
			$sub_array = array();
			$sub_array[] = $row["student_name"];
			$sub_array[] = $row["student_roll_number"];
			$sub_array[] = $row["grade_name"];
			$sub_array[] = $row["teacher_name"];
			$sub_array[] = get_attendance_percentage($connection, $row["student_id"]);
			$sub_array[] = '<button type="button" name="report_button" data-student_id="' . $row["student_id"] . '" 
			class="btn btn-info btn-sm report_button">Tạo Báo Cáo</button>';
			$data[] = $sub_array;
		}

		$output = array(
			'draw'				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connection, 'student'),
			"data"				=>	$data
		);

		echo json_encode($output);
	}
}
