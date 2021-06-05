<?php

//student_action.php

include('database_connection.php');

session_start();

if (isset($_POST["action"])) {
    
    //Load data in database for webpage
    if ($_POST["action"] == "fetch") {
        $query = "SELECT * FROM student INNER JOIN grade WHERE student.grade_id = grade.grade_id ORDER BY student_id DESC";

        $statement = $connection->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $data = array();
        $filtered_rows = $statement->rowCount();

        foreach ($result as $row) {
            $sub_array = array();
            $sub_array[] = $row["student_name"];
            $sub_array[] = $row["student_roll_number"];
            $sub_array[] = $row["student_dob"];
            $sub_array[] = $row["grade_name"];
            $sub_array[] = '<button type="button" name="edit_student" class="btn btn-primary btn-sm edit_student" id="'
                . $row["student_id"] . '">Sửa</button>';
            $sub_array[] = '<button type="button" name="delete_student" class="btn btn-danger btn-sm delete_student" id="'
                . $row["student_id"] . '">Xóa</button>';
            $data[] = $sub_array;
        }

        $output = array(
            "recordsTotal"        =>     $filtered_rows,
            "recordsFiltered"    =>    $filtered_rows,
            "data"                =>    $data
        );

        echo json_encode($output);
    }

    
    if ($_POST["action"] == "Add" ||$_POST["action"] == "Edit" ) {
        $student_name = '';
        $student_roll_number = '';
        $student_dob = '';
        $student_grade_id = '';
        $error_student_name = '';
        $error_student_roll_number = '';
        $error_student_dob = '';
        $error_student_grade_id = '';
        $error = 0;

        if (empty($_POST["student_name"])) {
            $error_student_name = 'Họ và tên không được để trống';
            $error++;
        } else {
            $student_name = $_POST["student_name"];
        }
        if (empty($_POST["student_roll_number"])) {
            $error_student_roll_number = 'Mã Học Sinh không được để trống';
            $error++;
        } else {
            $student_roll_number = $_POST["student_roll_number"];
        }
        if (empty($_POST["student_dob"])) {
            $error_student_dob = 'Ngày sinh không được để trống';
            $error++;
        } else {
            $student_dob = $_POST["student_dob"];
        }
        if (empty($_POST["student_grade_id"])) {
            $error_student_grade_id = "Lớp không được để trống";
            $error++;
        } else {
            $student_grade_id = $_POST["student_grade_id"];
        }

        if ($error > 0) {
            $output = array(
                'error'                            =>    true,
                'error_student_name'               =>    $error_student_name,
                'error_student_roll_number'        =>    $error_student_roll_number,
                'error_student_dob'                =>    $error_student_dob,
                'error_student_grade_id'           =>    $error_student_grade_id
            );
        } else {
            if ($_POST["action"] == 'Add') {
                $data = array(
                    ':student_name'             =>    $student_name,
                    ':student_roll_number'      =>    $student_roll_number,
                    ':student_dob'              =>    $student_dob,
                    ':student_grade_id'         =>    $student_grade_id
                );
                $query = "INSERT INTO student (student_name, student_roll_number, student_dob, grade_id) 
				        VALUES (:student_name, :student_roll_number, :student_dob, :student_grade_id)";

                $statement = $connection->prepare($query);
                if ($statement->execute($data)) {
                    $output = array(
                        'success'        =>    'Thêm Học Sinh thành công',
                    );
                }
            }

            if ($_POST["action"] == "Edit") {
                $data = array(
                    ':student_name'           =>    $student_name,
                    ':student_roll_number'    =>    $student_roll_number,
                    ':student_dob'            =>    $student_dob,
                    ':student_grade_id'       =>    $student_grade_id,
                    ':student_id'             =>    $_POST["student_id"]
                );
                $query = "UPDATE student SET student_name = :student_name, student_roll_number = :student_roll_number, 
				student_dob = :student_dob, grade_id = :student_grade_id WHERE student_id = :student_id";
                $statement = $connection->prepare($query);
                if ($statement->execute($data)) {
                    $output = array(
                        'success'        =>    'Sửa thông tin thành công',
                    );
                }
            }
        }
        echo json_encode($output);
    }


    if ($_POST["action"] == "edit_fetch") {
        $query = "SELECT * FROM student WHERE student_id = '" . $_POST["student_id"] . "' ";

        $statement = $connection->prepare($query);
        if ($statement->execute()) {
            $result = $statement->fetchAll();
            foreach ($result as $row) {
                $output["student_name"] = $row["student_name"];
                $output["student_roll_number"] = $row["student_roll_number"];
                $output["student_dob"] = $row["student_dob"];
                $output["student_grade_id"] = $row["grade_id"];
                $output["student_id"] = $row["student_id"];
            }
            echo json_encode($output);
        }
    }

    if($_POST["action"] == "delete")
	{
		$query = "DELETE FROM student WHERE student_id = '".$_POST["student_id"]."'";
		$statement = $connection->prepare($query);
		if($statement->execute())
		{
			echo 'Thông tin Học Sinh đã bị xóa';
		}
	}
}
