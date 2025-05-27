<?php
    session_start();
    include 'config/connection.php';

    $loc = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['loc'])));
    $gcash = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['gcash'])));
    $contact = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['contact'])));
    $id = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['id'])));

    $stmt = $conn->prepare('SELECT * FROM branch WHERE location = ? ');
    $stmt->bind_param("s",$loc);
    $stmt->execute();
    $result=$stmt->get_result();
    if(mysqli_num_rows($result) < 1){

        $stmt = $conn->prepare("UPDATE branch SET location = ?, branch_gcash_no = ?, contact_no = ? WHERE branch_id = ? ");
        $stmt->bind_param('ssss',$loc,$gcash,$contact,$id);
        $stmt->execute();        
    }
    echo "Branch Successfully Updated!";

?>