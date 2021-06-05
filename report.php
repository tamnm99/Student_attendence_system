<?php

//report.php

if (isset($_GET["action"])) {
	include('admin/database_connection.php');
	require_once('admin/pdf.php');
	session_start();

	//Create file report pdf in attendance.php
	if ($_GET["action"] == "attendance_report") {
		if (isset($_GET["from_date"], $_GET["to_date"])) {
			$pdf = new Pdf();
			$query = "SELECT attendance_date FROM attendance WHERE teacher_id = '" . $_SESSION["teacher_id"] . "' 
			AND (attendance_date BETWEEN '" . $_GET["from_date"] . "' AND '" . $_GET["to_date"] . "')
			GROUP BY attendance_date ORDER BY attendance_date ASC";
			$statement = $connection->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();

			// <meta> and <style>: support show utf in website for DomPdf
			$output = '
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
				<style>
					@page { margin: 20px; }
					*{
						font-family: DejaVu Sans, sans-serif;
					}
				</style>
				<p>&nbsp;</p>
				<h3 align="center">Điểm danh Học Sinh</h3><br />';
			foreach ($result as $row) {
				$output .= '
				<table width="100%" border="0" cellpadding="5" cellspacing="0">
			        <tr>
			        	<td><b>Ngày Học - ' . $row["attendance_date"] . '</b></td>
			        </tr>
			        <tr>
			        	<td>
			        		<table width="100%" border="1" cellpadding="5" cellspacing="0">
			        			<tr>
			        				<td><b>Tên Học Sinh</b></td>
			        				<td><b>Mã Sinh Viên</b></td>
			        				<td><b>Lớp</b></td>
			        				<td><b>Trạng Thái</b></td>
			        			</tr>
				';
				$sub_query = "SELECT * FROM attendance INNER JOIN student 
			    		ON student.student_id = attendance.student_id INNER JOIN grade ON grade.grade_id = student.grade_id 
			    		WHERE teacher_id = '" . $_SESSION["teacher_id"] . "' AND attendance_date = '" . $row["attendance_date"] . "'";
				$statement = $connection->prepare($sub_query);
				$statement->execute();
				$sub_result = $statement->fetchAll();
				foreach ($sub_result as $sub_row) {
					$output .= '
					<tr>
						<td>' . $sub_row["student_name"] . '</td>
						<td>' . $sub_row["student_roll_number"] . '</td>
						<td>' . $sub_row["grade_name"] . '</td>
						<td>' . $sub_row["attendance_status"] . '</td>
					</tr>
					';
				}
				$output .= '
					</table>
					</td>
					</tr>
				</table><br />
				';
			}

			$file_name = 'Bao_Cao_Diem_Danh.pdf';
			$pdf->loadHtml($output);
			$pdf->render();
			$pdf->stream($file_name, array("Attachment" => false));
			exit(0);
		}
	}

	//Create file report pdf in index.php
	if ($_GET["action"] == "student_report") {
		if (isset($_GET["student_id"], $_GET["from_date"], $_GET["to_date"])) {
			$pdf = new Pdf();
			$query = "SELECT * FROM student INNER JOIN grade ON grade.grade_id = student.grade_id 
			WHERE student.student_id = '" . $_GET["student_id"] . "' ";

			$statement = $connection->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();
			$output = '';
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
				<h3 align="center">Báo cáo Điểm danh cá nhân</h3><br /><br />
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
			        		<h3 align="center">Chi Tiết</h3>
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
				$file_name = 'Bao_cao_diem_danh_cua_' .$row["student_name"];
				$pdf->loadHtml($output);
				$pdf->render();
				$pdf->stream($file_name, array("Attachment" => false));
				exit(0);
			}
		}
	}
}
