<?php
// teacher_action.php

include('database_connection.php');

session_start();

if (isset($_POST["action"])) {

	//get data from database, send to webpage and show via DataTable
	if ($_POST["action"] == "fetch") {
		$query = "SELECT * FROM teacher INNER JOIN grade ON grade.grade_id = teacher.grade_id ORDER BY teacher_id DESC";

		$statement = $connection->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$data = array();
		$records_total = $statement->rowCount();

		foreach ($result as $row) {
			$sub_array = array();
			$sub_array[] = '<img src="teacher_image/' . $row["teacher_image"] . '" class="img-thumbnail" width="75"/>';
			$sub_array[] = $row["teacher_name"];
			$sub_array[] = $row["teacher_email"];
			$sub_array[] = $row["grade_name"];
			$sub_array[] = '<button type="button" name="view_teacher" class="btn btn-info btn-sm view_teacher" 
                            id="' . $row["teacher_id"] . '">Xem</button>';
			$sub_array[] = '<button type="button" name="edit_teacher" class="btn btn-primary btn-sm edit_teacher" 
                            id="' . $row["teacher_id"] . '">Sửa</button>';
			$sub_array[] = '<button type="button" name="delete_teacher" class="btn btn-danger btn-sm delete_teacher" 
                            id="' . $row["teacher_id"] . '">Xóa</button>';
			$data[] = $sub_array;
		}

		$output = array(
			"recordsTotal"		=> 	$records_total,
			"recordsFiltered"	=>	$records_total,
			"data"				=>	$data
		);
		echo json_encode($output);
	}

	//Add or Edit Teacher
	if ($_POST["action"] == 'Add' || $_POST["action"] == "Edit") {
		$teacher_name = '';
		$teacher_address = '';
		$teacher_email = '';
		$teacher_password = '';
		$teacher_grade_id = '';
		$teacher_qualification = '';
		$teacher_date_of_join = '';
		$teacher_image = '';
		$error_teacher_name = '';
		$error_teacher_address = '';
		$error_teacher_email = '';
		$error_teacher_password = '';
		$error_teacher_grade_id = '';
		$error_teacher_qualification = '';
		$error_teacher_date_of_join = '';
		$error_teacher_image = '';
		$error = 0;

		//Validation file image in PHP
		$teacher_image = $_POST["hidden_teacher_image"];
		if ($_FILES["teacher_image"]["name"] != '') {
			$file_name = $_FILES["teacher_image"]["name"];
			$tmp_name = $_FILES["teacher_image"]["tmp_name"];
			$extension_array = explode(".", $file_name);// split name file, example: 12qer3.jpg => [0]: 12qer3 and [1]: jpg
			$extension = strtolower($extension_array[1]); // get string lower case of extension file
			$allowed_extension = array('jpg', 'png');
			if (!in_array($extension, $allowed_extension)) {
				$error_teacher_image = 'Không đúng định dạng ảnh';
				$error++;
			} else {
				$teacher_image = uniqid() . '.' . $extension;
				$upload_path = "teacher_image/" . $teacher_image;
				move_uploaded_file($tmp_name, $upload_path);// function: uploade file img to folder in host
			}
		} else {
			if ($teacher_image == '') {
				$error_teacher_image = 'Ảnh không được để trống';
				$error++;
			}
		}

		if (empty($_POST["teacher_name"])) {
			$error_teacher_name = 'Họ và tên không được để trống';
			$error++;
		} else {
			$teacher_name = $_POST["teacher_name"];
		}

		if (empty($_POST["teacher_address"])) {
			$error_teacher_address = 'Địa chỉ không được để trống';
			$error++;
		} else {
			$teacher_address = $_POST["teacher_address"];
		}

		//Validation email and password in PHP
		if ($_POST["action"] == "Add") {
			if (empty($_POST["teacher_email"])) {
				$error_teacher_email = 'Email không được để trống';
				$error++;
			} else {
				if (!filter_var($_POST["teacher_email"], FILTER_VALIDATE_EMAIL)) {
					$error_teacher_email = 'Định dạng Email không đúng';
					$error++;
				} else {
					$teacher_email = $_POST["teacher_email"];
				}
			}

			if (empty($_POST["teacher_password"])) {
				$error_teacher_password = "Password không được để trống";
				$error++;
			} else {
				$teacher_password = $_POST["teacher_password"];
			}
		}

		if (empty($_POST["teacher_grade_id"])) {
			$error_teacher_grade_id = "Lớp không được để trống";
			$error++;
		} else {
			$teacher_grade_id = $_POST["teacher_grade_id"];
		}

		if (empty($_POST["teacher_qualification"])) {
			$error_teacher_qualification = 'Trình độ không được để trống';
			$error++;
		} else {
			$teacher_qualification = $_POST["teacher_qualification"];
		}

		if (empty($_POST["teacher_date_of_join"])) {
			$error_teacher_date_of_join = 'Ngày kí hợp đồng không được để trống';
			$error++;
		} else {
			$teacher_date_of_join = $_POST["teacher_date_of_join"];
		}

		if ($error > 0) {
			$output = array(
				'error'							=>	true,
				'error_teacher_name'			=>	$error_teacher_name,
				'error_teacher_address'			=>	$error_teacher_address,
				'error_teacher_email'			=>	$error_teacher_email,
				'error_teacher_password'		=>	$error_teacher_password,
				'error_teacher_grade_id'		=>	$error_teacher_grade_id,
				'error_teacher_qualification'	=>	$error_teacher_qualification,
				'error_teacher_date_of_join'	=>	$error_teacher_date_of_join,
				'error_teacher_image'			=>	$error_teacher_image
			);
		} else {
			if ($_POST["action"] == 'Add') {
				$data = array(
					':teacher_name'		            =>	$teacher_name,
					':teacher_address'		        =>	$teacher_address,
					':teacher_email'		        =>	$teacher_email,
					// Hash password with CRYPT_BLOWFISH algorithm
					':teacher_password'		        =>	password_hash($teacher_password, PASSWORD_DEFAULT),
					':teacher_qualification'        =>	$teacher_qualification,
					':teacher_date_of_join'			=>	$teacher_date_of_join,
					':teacher_image'		        =>	$teacher_image,
					':teacher_grade_id'		        =>	$teacher_grade_id
				);

				$query = "INSERT INTO teacher(teacher_name, teacher_address, teacher_email, teacher_password,
                        teacher_qualification, teacher_date_of_join, teacher_image, grade_id)
                        SELECT * FROM (SELECT :teacher_name, :teacher_address, :teacher_email, :teacher_password, 
                        :teacher_qualification, :teacher_date_of_join, :teacher_image, :teacher_grade_id) as temp
                        WHERE NOT EXISTS ( SELECT teacher_email FROM teacher WHERE teacher_email = :teacher_email) LIMIT 1";

				$statement = $connection->prepare($query);
				if ($statement->execute($data)) {
					if ($statement->rowCount() > 0) {
						$output = array(
							'success'		=>	'Thêm Giáo Viên mới thành công',
						);
					} else {
						$output = array(
							'error'					=>	true,
							'error_teacher_email'	=>	'Email Giáo viên đã tồn tại'
						);
					}
				}
			}

			if ($_POST["action"] == "Edit") {
				$data = array(
					':teacher_name'				=>	$teacher_name,
					':teacher_address'			=>	$teacher_address,
					':teacher_qualification'	=>	$teacher_qualification,
					':teacher_date_of_join'		=>	$teacher_date_of_join,
					':teacher_image'			=>	$teacher_image,
					':teacher_grade_id'			=>	$teacher_grade_id,
					':teacher_id'				=>	$_POST["teacher_id"]
				);

				$query = "UPDATE teacher SET teacher_name = :teacher_name, teacher_address = :teacher_address,  
						grade_id = :teacher_grade_id, teacher_qualification = :teacher_qualification, 
						teacher_date_of_join = :teacher_date_of_join, teacher_image = :teacher_image
						WHERE(teacher_id = :teacher_id)";
				$statement = $connection->prepare($query);
				if ($statement->execute($data)) {
					$output = array(
						'success'		=>	'Sửa Giáo Viên thành công',
					);
				}
			}
		}
		echo json_encode($output);
	}

	// Handle view detail a teacher
	if ($_POST["action"] == "single_fetch") {
		$query = "SELECT * FROM teacher INNER JOIN grade ON grade.grade_id = teacher.grade_id 
		WHERE teacher.teacher_id = '" . $_POST["teacher_id"] . "'";
		$statement = $connection->prepare($query);
		if ($statement->execute()) {
			$result = $statement->fetchAll();
			$output1 = '<div class="row">';
			foreach ($result as $row) {
				$output1 .= '
				<div class="col-md-3">
					<img src="teacher_image/' . $row["teacher_image"] . '" class="img-thumbnail" />
				</div>
				<div class="col-md-9">
					<table class="table">
						<tr>
							<th>Họ và tên</th>
							<td>' . $row["teacher_name"] . '</td>
						</tr>
						<tr>
							<th>Địa chỉ</th>
							<td>' . $row["teacher_address"] . '</td>
						</tr>
						<tr>
							<th>Email</th>
							<td>' . $row["teacher_email"] . '</td>
						</tr>
						<tr>
							<th>Trình độ</th>
							<td>' . $row["teacher_qualification"] . '</td>
						</tr>
						<tr>
							<th>Ngày kí hợp đồng</th>
							<td>' . $row["teacher_date_of_join"] . '</td>
						</tr>
						<tr>
							<th>Chủ nhiệm lớp</th>
							<td>' . $row["grade_name"] . '</td>
						</tr>
					</table>
				</div>';
			}
			$output1 .= '</div>';
			echo $output1;
		}
	}

	//Get information a teacher from database and send to Front End modal
	if ($_POST["action"] == "edit_fetch") {
		$query = "SELECT * FROM teacher WHERE teacher_id ='" . $_POST["teacher_id"] . "'";
		$statement = $connection->prepare($query);
		if ($statement->execute()) {
			$result = $statement->fetchAll();
			$output = array();
			foreach ($result as $row) {
				$output["teacher_name"] = $row["teacher_name"];
				$output["teacher_address"] = $row["teacher_address"];
				$output["teacher_qualification"] = $row["teacher_qualification"];
				$output["teacher_date_of_join"] = $row["teacher_date_of_join"];
				$output["teacher_image"] = $row["teacher_image"];
				$output["grade_id"] = $row["grade_id"];
				$output["teacher_id"] = $row["teacher_id"];
			}
			echo json_encode($output);
		}
	}

	// Handle delete a teacher
	if ($_POST["action"] == "delete") {
		$query = "DELETE FROM teacher WHERE teacher_id = '" . $_POST["teacher_id"] . "' ";
		$statement = $connection->prepare($query);
		if ($statement->execute()) {
			echo 'Xóa thông tin giáo viên thành công';
		}
	}
}
