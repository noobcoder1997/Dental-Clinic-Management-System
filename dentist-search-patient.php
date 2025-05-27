<?php
    session_start();
    include "config/connection.php";

    $fnim = htmlentities(stripcslashes(mysqli_real_escape_string($conn, strtolower($_POST['id1']))));
    $lnim = htmlentities(stripcslashes(mysqli_real_escape_string($conn, strtolower($_POST['id2']))));
    $usernim = htmlentities(stripcslashes(mysqli_real_escape_string($conn, strtolower($_POST['id3']))));
    $mess= array();
    $stmt = $conn->prepare('SELECT * FROM register_patient WHERE first_name = ? OR username = ? OR last_name = ?');
    $stmt->bind_param("sss", $fnim,$usernim,$lnim);
    $stmt->execute();
    $result = $stmt->get_result();
    $c=array();
    if(mysqli_num_rows($result)>0){
        while($row = $result->fetch_assoc()){
            echo "<p class='badge bg-success'  style='margin-top: 10px; margin-right:10px;' onclick='dentistToAppointmentForm($row[register_id])' >".$row['first_name'].' '.$row['middle_name'].' '.$row['last_name']."</p>";
            echo "<input type='hidden' value='$row[register_id]' id='patient-id' >";
            array_push($c, $row['register_id']);
        }
    }
    else{

        echo "<p>No Patient Found!</p>";
    }
    echo "<p>Patients Found: <b>".count($c)."</b></p>";
?>
