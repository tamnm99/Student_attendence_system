<?php

//report.php

if (isset($_GET["action"])) {
    include('database_connection.php');
    require_once 'pdf.php';
    session_start();
    $output = '';

	// Creat file report .pdf in attendance.php
    if ($_GET["action"] == 'attendance_report') {
        if (isset($_GET["grade_id"], $_GET["from_date"], $_GET["to_date"])) {
            $pdf = new Pdf();
            $query = "SELECT attendance.attendance_date FROM attendance 
				INNER JOIN student ON student.student_id = attendance.student_id WHERE student.grade_id = '" . $_GET["grade_id"] . "' 
				AND (attendance.attendance_date BETWEEN '" . $_GET["from_date"] . "' AND '" . $_GET["to_date"] . "')
				GROUP BY attendance.attendance_date ORDER BY attendance.attendance_date ASC";
            $statement = $connection->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            $output .= '
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <style>
                    @page { margin: 20px; }
                    *{
                        font-family: DejaVu Sans, sans-serif;
                    }
                </style>
				<p>&nbsp;</p>
				<h3 align="center">Báo cáo Điểm danh của 1 Lớp</h3><br />';
            foreach ($result as $row) {
                $output .= '
				<table width="100%" border="0" cellpadding="5" cellspacing="0">
			        <tr>
			        	<td><b>Ngày - ' . $row["attendance_date"] . '</b></td>
			        </tr>
			        <tr>
			        	<td>
			        		<table width="100%" border="1" cellpadding="5" cellspacing="0">
			        			<tr>
			        				<td><b>Tên Học Sinh</b></td>
			        				<td><b>Mã Sinh Viên</b></td>
			        				<td><b>Lớp</b></td>
			        				<td><b>Giáo Viên</b></td>
			        				<td><b>Điểm danh</b></td>
			        			</tr>
				';

                $sub_query = "SELECT * FROM attendance INNER JOIN student ON student.student_id = attendance.student_id 
			    	INNER JOIN grade ON grade.grade_id = student.grade_id INNER JOIN teacher ON teacher.grade_id = grade.grade_id 
			    	WHERE student.grade_id = '" . $_GET["grade_id"] . "' 
					AND attendance.attendance_date = '" . $row["attendance_date"] . "'";

                $statement = $connection->prepare($sub_query);
                $statement->execute();
                $sub_result = $statement->fetchAll();
                foreach ($sub_result as $sub_row) {
                    $output .= '
					<tr>
						<td>' . $sub_row["student_name"] . '</td>
						<td>' . $sub_row["student_roll_number"] . '</td>
						<td>' . $sub_row["grade_name"] . '</td>
						<td>' . $sub_row["teacher_name"] . '</td>
						<td>' . $sub_row["attendance_status"] . '</td>
					</tr>
					';
                }
                $output .=
                    '</table>
					</td>
					</tr>
				</table><br />';
            }
            $file_name = 'Bao_Cao_diem_danh_cua_mot_lop.pdf';
            $pdf->loadHtml($output);
            $pdf->render();
            $pdf->stream($file_name, array("Attachment" => false));
            exit(0);
        }
    }

	// Creat file report .pdf in index.php
    if ($_GET["action"] == "student_report") {
        if (isset($_GET["student_id"], $_GET["from_date"], $_GET["to_date"])) {
            $pdf = new Pdf();
            $query = "SELECT * FROM student 
			INNER JOIN grade ON grade.grade_id = student.grade_id WHERE student.student_id = '" . $_GET["student_id"] . "' ";

            $statement = $connection->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            foreach ($result as $row) {
                $output .= '
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <style>
                    @page { margin: 20px; }
                    *{
                        font-family: DejaVu Sans, sans-serif;
                    }
                </style>

				<p>&nbsp;</p>
				<h3 align="center">Báo cáo Chuyên Cần</h3><br /><br />
				<table width="100%" border="0" cellpadding="5" cellspacing="0">
			        <tr>
			            <td width="25%"><b>Tên Học Sinh</b></td>
			            <td width="75%">' . $row["student_name"] . '</td>
			        </tr>
			        <tr>
			            <td width="25%"><b>Mã Học Sinh</b></td>
			            <td width="75%">' . $row["student_roll_number"] . '</td>
			        </tr>
			        <tr>
			            <td width="25%"><b>Lớp</b></td>
			            <td width="75%">' . $row["grade_name"] . '</td>
			        </tr>
			        <tr>
			        	<td colspan="2" height="5">
			        		<h3 align="center">Báo cáo Chi tiết</h3>
			        	</td>
			        </tr>
			        <tr>
			        	<td colspan="2">
			        		<table width="100%" border="1" cellpadding="5" cellspacing="0">
			        			<tr>
			        				<td><b>Ngày</b></td>
			        				<td><b>Điểm danh</b></td>
			        			</tr>
				';
                $sub_query = "SELECT * FROM attendance WHERE student_id = '" . $_GET["student_id"] . "' 
				AND (attendance_date BETWEEN '" . $_GET["from_date"] . "' AND '" . $_GET["to_date"] . "') 
				ORDER BY attendance_date ASC";

                $statement = $connection->prepare($sub_query);
                $statement->execute();
                $sub_result = $statement->fetchAll();
                foreach ($sub_result as $sub_row) {
                    $output .= '
					<tr>
						<td>' . $sub_row["attendance_date"] . '</td>
						<td>' . $sub_row["attendance_status"] . '</td>
					</tr>
					';
                }
                $output .= '
						</table>
					</td>
					</tr>
				</table>
				';

                $file_name = "Bao_cao_chuyen_can_cua_1_hoc_sinh.pdf";
                $pdf->loadHtml($output);
                $pdf->render();
                $pdf->stream($file_name, array("Attachment" => false));
                exit(0);
            }
        }
    }
}
