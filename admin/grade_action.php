<?php
// grade_action.php

include('database_connection.php');

session_start();


if (isset($_POST["action"])) {

    //Get data for DataTable
    if ($_POST["action"] == "fetch") {
        $query = "SELECT * FROM grade ORDER BY grade_id DESC";

        $statement = $connection->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $data = array();// multiple dimension array
        $records_total = $statement->rowCount();

        foreach ($result as $row) {
            // Inserting database results into array in PHP
            $sub_array = array();
            $sub_array[] = $row["grade_name"];
            $sub_array[] = '<button type="button" name="edit_grade" class="btn btn-primary btn-sm edit_grade" 
                                id="' . $row["grade_id"] . '">Sửa</button>';
            $sub_array[] = '<button type="button" name="delete_grade" class="btn btn-danger btn-sm delete_grade"   
                                id="' . $row["grade_id"] . '">Xóa</button>';
            $data[] = $sub_array;
        }

        $output = array(
            "recordsTotal"  =>  $records_total,
            "recordsFiltered" => $records_total,
            "data"    => $data
        );
        echo json_encode($output);
    }

    // Add Grade or Edit Grade
    if ($_POST["action"] == "Add" || $_POST["action"] == "Edit") {
        $grade_name = '';
        $error_grade_name = '';
        $error = 0;

        if (empty($_POST["grade_name"])) {
            $error_grade_name = 'Tên lớp không được để trống';
            $error++;
        } else {
            $grade_name = $_POST["grade_name"];
        }

        if ($error > 0) {
            $output = array(
                "error"  =>  true,
                "error_grade_name" =>  $error_grade_name
            );
        } else { // Add Grade
            if ($_POST["action"] == "Add") {
                $data = array(':grade_name'  => $grade_name);
                $query = "INSERT INTO grade (grade_name)
                        SELECT * FROM (SELECT :grade_name) as temp
                         WHERE NOT EXISTS (SELECT grade_name FROM grade WHERE grade_name = :grade_name) LIMIT 1";

                $statement = $connection->prepare($query);

                if ($statement->execute($data)) {
                    if ($statement->rowCount() > 0) {
                        $output = array('success'  =>  'Thêm lớp thành công',);
                    } else {
                        $output = array( // check grade name
                            'error'  =>  true,
                            'error_grade_name' =>  'Tên lớp đã tồn tại',
                        );
                    }
                }
            }

            if ($_POST["action"] == "Edit") { //Edit grade 
                $data = array(':grade_name' => $grade_name, ':grade_id' => $_POST["grade_id"]);
                $query = "UPDATE grade SET grade_name = :grade_name WHERE (grade_id = :grade_id
                        AND NOT EXISTS (SELECT grade_name FROM grade WHERE grade_name = :grade_name) )";
                $statement = $connection->prepare($query);
                if ($statement->execute($data)) {
                    if ($statement->rowCount() > 0) {
                        $output = array('success'  =>  'Sửa lớp thành công',);
                    } else {
                        $output = array( // check grade name
                            'error'  =>  true,
                            'error_grade_name' =>  'Tên lớp đã tồn tại',
                        );
                    }
                }
            }
        }
        echo json_encode($output);
    }

    // Get information grade from database and show to web page
    if ($_POST["action"] == "edit_fetch") {
        $query = "SELECT * FROM grade WHERE grade_id = '" . $_POST["grade_id"] . "'";
        $statement = $connection->prepare($query);
        if ($statement->execute()) {
            $result = $statement->fetchAll();
            $output = array();
            foreach ($result as $row) {
                $output["grade_name"] =  $row["grade_name"];
                $output["grade_id"] =  $row["grade_id"];
            }
        }
        echo json_encode($output);
    }

    // Delete grade
    if ($_POST["action"] == "delete") {
        $query = "DELETE FROM grade WHERE grade_id = '" . $_POST["grade_id"] . "'";
        $statement = $connection->prepare($query);
        if ($statement->execute()) {
            echo 'Xóa thành công !!!';
        } else {
            echo "Xóa thất bại vì lớp có id này là khóa ngoại của bản ghi khác !!";
        }
    }
}
