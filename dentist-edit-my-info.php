<?php
    session_start();
    include 'config/connection.php';

    $fnim = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['fnim'])));
    $mnim = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['mnim'])));
    $lnim = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['lnim'])));
    // $bdate = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['bdate'])));
    // $add = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['address'])));
    // $age = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['age'])));
    // $contact = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['contact'])));
    $id = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['id'])));

    $stmt0=$conn->prepare("SELECT * FROM dentist WHERE first_name = ? AND middle_name = ? AND last_name = ? ");
    $stmt0->bind_param("sss",$fnim,$mnim,$lnim);
    $stmt0->execute();
    $result0=$stmt0->get_result();

    $stmt1=$conn->prepare("SELECT * FROM dentist WHERE first_name = ? AND middle_name = ? AND last_name = ? ");
    $stmt1->bind_param("sss",$fnim,$mnim,$lnim);
    $stmt1->execute();
    $result1=$stmt1->get_result();

    $stmt2=$conn->prepare("SELECT * FROM dentist WHERE first_name = ? AND middle_name = ? AND last_name = ? ");
    $stmt2->bind_param("sss",$fnim,$mnim,$lnim);
    $stmt2->execute();
    $result2=$stmt2->get_result();

    if( mysqli_num_rows($result0)>0 ||
        mysqli_num_rows($result1)>0 ||
        mysqli_num_rows($result2)>0  ){
        echo 'This Name already exist!';
    }
    else{
        $stmt = $conn->prepare("UPDATE dentist SET first_name = ?, middle_name = ?, last_name = ? WHERE dentist_id = ? ");
        $stmt->bind_param("ssss", $fnim,$mnim,$lnim,$id);
        $stmt->execute();

        echo "Successfully Updated!";    
    }
?>