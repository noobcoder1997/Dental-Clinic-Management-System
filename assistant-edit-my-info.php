<?php
    session_start();
    include 'config/connection.php';
    
    try{
        $fnim = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['fnim'])));
        $mnim = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['mnim'])));
        $lnim = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['lnim'])));
        $age = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['age'])));
        $address = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['address'])));
        $bdate = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['bdate'])));
        $contact_no = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['contact_no'])));

        $id = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['id'])));

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
            
                $stmt = $conn->prepare("UPDATE assistant SET first_name = ?, middle_name = ?, last_name = ?, age = ?, address = ?, contact_no = ?, bdate = ? WHERE assistant_id = ? ");
                $stmt->bind_param("ssssssss", $fnim,$mnim,$lnim,$age,$address,$contact_no,$bdate,$id);
                $stmt->execute();
        
                echo "Successfully Updated!";  
            
        }
       

    }catch(Exception $e){
        echo $e->getMessage();
    }   
?>