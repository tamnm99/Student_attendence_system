<?php

$serverName = "localhost";
$userName = "root";
$passWord = "";

$connection = new PDO("mysql:host=$serverName; dbname=qlhs", $userName, $passWord);


// test PDO 
// try{
//     $connection = new PDO("mysql:host=$serverName; dbname=qlsv", $userName, $passWord);

//     // set the PDO error mode to exception
//     $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// } catch(PDOExcetion $e){
//     echo "Connection failed";
//     $e->getMessage();
// }


//Đường dẫn cơ bản của trang web
$base_url = "http://localhost/qlhs";

function get_total_records($connection, $table_name)
{
    $query = "SELECT * FROM $table_name";
    $statement = $connection->prepare($query);
    $statement->execute();
    return $statement->rowCount();
}

// get list about grade in database
function load_grade_list($connection)
{
    $query = "SELECT * FROM GRADE ORDER BY grade_name ASC";
    $statement = $connection->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $output = '';
    foreach ($result as $row) {
        $output .= '<option value="' . $row["grade_id"] . '"> ' . $row["grade_name"] . ' </option>';
    }
    return $output;
}

// get attendance percentage of a student
function get_attendance_percentage($connection, $student_id)
{
    $query = "SELECT ROUND((SELECT COUNT(*) FROM attendance 
		WHERE attendance_status = 'Đi Học' 
		AND student_id = '" . $student_id . "') * 100 / COUNT(*)) AS percentage FROM attendance 
	    WHERE student_id = '" . $student_id . "'";

    $statement = $connection->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row) {
        return $row["percentage"] . '%';
    }
}

function Get_student_name($connection, $student_id)
{
    $query = "SELECT student_name FROM student WHERE student_id = '" . $student_id . "'";

    $statement = $connection->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();

    foreach ($result as $row) {
        return $row["student_name"];
    }
}

function Get_student_grade_name($connection, $student_id)
{
    $query = "SELECT grade.grade_name FROM student 
	INNER JOIN grade ON grade.grade_id = student.grade_id 
	WHERE student.student_id = '" . $student_id . "'";
    $statement = $connection->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row) {
        return $row['grade_name'];
    }
}

function Get_student_teacher_name($connection, $student_id)
{
    $query = "SELECT teacher.teacher_name FROM student INNER JOIN grade 
	ON grade.grade_id = student.grade_id INNER JOIN teacher ON teacher.grade_id = grade.grade_id 
    WHERE student.student_id = '" . $student_id . "'";
    $statement = $connection->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row) {
        return $row["teacher_name"];
    }
}

function Get_grade_name($connection, $grade_id)
{
    $query = "SELECT grade_name FROM grade WHERE grade_id = '" . $grade_id . "'";
    $statement = $connection->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row) {
        return $row["grade_name"];
    }
}
