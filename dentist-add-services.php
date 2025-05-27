<?php
    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];
    $designation = $_SESSION['branch'];

    $name = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['name'])));
    $desc = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['description'])));

    $stmt = $conn->prepare("SELECT * FROM services WHERE service_name = ? AND branch_id = ? ");
    $stmt->bind_param("ss", $name,$designation);
    $stmt->execute();
    $result=$stmt->get_result();
    if(mysqli_num_rows($result) < 1){
        $stmt = $conn->prepare("INSERT INTO services ( service_name, service_description,branch_id) VALUES (?,?,?) ");
        $stmt->bind_param('sss',$name,$desc,$designation);
        $stmt->execute();
    
        echo "Service Successfully Inserted!";
    }
    else{
        echo "Service already listed!";
    }


?>