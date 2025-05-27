<?php
    session_start();
    include 'config/connection.php';

    $fnim = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['fnim'])));
    $mnim = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['mnim'])));
    $lnim = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['lnim'])));
    $bdate = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['bdate'])));
    $add = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['address'])));
    $age = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['age'])));
    $contact = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['contact'])));
    $id = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['id'])));
    $_bdate = strtotime($bdate);
    $date = getDate($_bdate);
    // $fDay = $date['weekday'];
    $mDay = $date['mday'];
    $Month = $date['mon'];
    $Year = $date['year'];
    $_bdate =  $Year.'-'.$Month.'-'.$mDay; //format the date example {2024-12-2}

    $query0="SELECT * FROM assistant WHERE first_name = ? AND middle_name = ? AND last_name = ?";
    $query1="SELECT * FROM dentist WHERE first_name = ? AND middle_name = ? AND last_name = ?";
    $query2="SELECT * FROM register_patient WHERE first_name = ? AND middle_name = ? AND last_name = ?";

    $stmt0=$conn->prepare($query0);
    $stmt0->bind_param("sss",$fnim,$mnim,$lnim);
    $stmt0->execute();
    $result0=$stmt0->get_result();

    $stmt1=$conn->prepare($query1);
    $stmt1->bind_param("sss",$fnim,$mnim,$lnim);
    $stmt1->execute();
    $result1=$stmt1->get_result();

    $stmt2=$conn->prepare($query2);
    $stmt2->bind_param("sss",$fnim,$mnim,$lnim);
    $stmt2->execute();
    $result2=$stmt2->get_result();

    if( mysqli_num_rows($result0) < 1 ||
        mysqli_num_rows($result1) < 1 ||
        mysqli_num_rows($result2) < 1   ){
        
            $stmt = $conn->prepare("UPDATE register_patient SET first_name = ?, middle_name = ?, last_name = ?, birthdate = ?, address = ?, contact_no = ?, age = ? WHERE register_id = ? ");
            $stmt->bind_param("ssssssss", $fnim,$mnim,$lnim,$_bdate,$add,$contact,$age,$id);
            $stmt->execute(); 
            echo "Successfully Updated!";
    }
    

?>