<?php

// Handle attendance

include('admin/database_connection.php');

session_start();

if (isset($_POST["action"])) {

    //show dataTable in attendance.php
    if ($_POST["action"] == "fetch") {
        
        $query = "SELECT * FROM attendance INNER JOIN student 
		ON student.student_id = attendance.student_id INNER JOIN grade ON grade.grade_id = student.grade_id 
		WHERE attendance.teacher_id = '" . $_SESSION["teacher_id"] . "' AND (";

        if (isset($_POST["search"]["value"])) {
            $query .= 'student.student_name LIKE "%' . $_POST["search"]["value"] . '%" 
			    OR student.student_roll_number LIKE "%' . $_POST["search"]["value"] . '%" 
			    OR attendance.attendance_status LIKE "%' . $_POST["search"]["value"] . '%" 
			    OR attendance.attendance_date LIKE "%' . $_POST["search"]["value"] . '%") 
			';
        }
        if (isset($_POST["order"])) {
            $query .= '
			    ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' 
			';
        } else {
            $query .= '
			    ORDER BY attendance.attendance_id DESC 
			';
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
            $data[] = $sub_array;
        }

        $output = array(
            'draw'                =>    intval($_POST["draw"]),
            "recordsTotal"        =>     $filtered_rows,
            "recordsFiltered"       =>   get_total_records($connection, 'attendance'),
            "data"                =>    $data
        );

        echo json_encode($output);
    }

    if ($_POST["action"] == "Add") {
        $attendance_date = '';
        $error_attendance_date = '';
        $error = 0;
        if (empty($_POST["attendance_date"])) {
            $error_attendance_date = 'Ngày học không được để trống';
            $error++;
        } else {
            $attendance_date = $_POST["attendance_date"];
        }

        if ($error > 0) {
            $output = array(
                'error'                            =>    true,
                'error_attendance_date'            =>    $error_attendance_date
            );
        } else {
            // array student_id 
            $student_id = $_POST["student_id"];

            // Check attendance date is exists
            $query = "SELECT attendance_date FROM attendance WHERE teacher_id = '" . $_SESSION["teacher_id"] . "' 
			            AND attendance_date = '" . $attendance_date . "' ";

            $statement = $connection->prepare($query);
            $statement->execute();
            if ($statement->rowCount() > 0) {
                $output = array(
                    'error'                    =>    true,
                    'error_attendance_date'    =>    'Ngày điểm danh này đã tồn tại'
                );
            } else {
                for ($count = 0; $count < count($student_id); $count++) {
                    $data = array(
                        ':student_id'            =>    $student_id[$count],
                        ':attendance_status'     =>    $_POST["attendance_status" . $student_id[$count] . ""],
                        ':attendance_date'       =>    $attendance_date,
                        ':teacher_id'            =>    $_SESSION["teacher_id"]
                    );

                    $query = "INSERT INTO attendance (student_id, attendance_status, attendance_date, teacher_id) 
					VALUES (:student_id, :attendance_status, :attendance_date, :teacher_id)";
                    $statement = $connection->prepare($query);
                    $statement->execute($data);
                }
                $output = array(
                    'success'        =>    'Thêm dữ liệu mới thành công',
                );
            }
        }
        echo json_encode($output);
    }

    //Show dataTable in teacher/index.php
    if ($_POST["action"] == "index_fetch") {
        $query = "SELECT * FROM attendance INNER JOIN student ON student.student_id = attendance.student_id 
		INNER JOIN grade ON grade.grade_id = student.grade_id 
        WHERE attendance.teacher_id = '" . $_SESSION["teacher_id"] . "' AND ( ";
        if (isset($_POST["search"]["value"])) {
            $query .= 'student.student_name LIKE "%' . $_POST["search"]["value"] . '%" 
			OR student.student_roll_number LIKE "%' . $_POST["search"]["value"] . '%" 
			OR grade.grade_name LIKE "%' . $_POST["search"]["value"] . '%" )
			';
        }
        $query .= 'GROUP BY student.student_id ';
        if (isset($_POST["order"])) {
            $query .= '
			ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' 
			';
        } else {
            $query .= '
			ORDER BY student.student_roll_number ASC 
			';
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
            $sub_array[] = get_attendance_percentage($connection, $row["student_id"]);
            $sub_array[] = '<button type="button" name="report_button" id="' . $row["student_id"] . '" 
            class="btn btn-info btn-sm report_button">Báo Cáo</button>';
            $data[] = $sub_array;
        }
        $output = array(
            'draw'                    =>    intval($_POST["draw"]),
            "recordsTotal"            =>     $filtered_rows,
            "recordsFiltered"         =>    get_total_records($connection, 'attendance'),
            "data"                    =>    $data
        );
        echo json_encode($output);
    }
}
