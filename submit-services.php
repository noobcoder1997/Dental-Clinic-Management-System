<?php
    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];
    $designation = $_SESSION['designation'];
    $date = date('Y-m-d');

    $ids = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['ids'])));
    $patient = htmlentities(stripcslashes(mysqli_real_escape_string($conn, '8')));
    $ids = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['ids'])));
    $ids = explode(',', $ids);
    foreach($ids as $id){
    //    print_r($id);
        $stmt0=$conn->prepare("SELECT * FROM service_transaction WHERE service_id = ?");
        $stmt0->bind_param('s',$key);
        $stmt0->execute();
        $result0=$stmt0->get_result();
        if(mysqli_num_rows($result0) > 0){
            echo 'This service already exist with the patient!';
        }
        else{
            $stmt1=$conn->prepare("SELECT * FROM services WHERE service_id = ?");
            $stmt1->bind_param("s",$id);
            $stmt1->execute();
            $result1=$stmt1->get_result();
            if($row1=$result1->fetch_assoc()){
                $stmt2=$conn->prepare("INSERT INTO service_transaction (patient_id,service_id,date) VALUES (?,?,?) ");
                $stmt2->bind_param('sss',$patient,$row1['service_id'],$date);
                $stmt2->execute();
            }
        }
        
    }
?>