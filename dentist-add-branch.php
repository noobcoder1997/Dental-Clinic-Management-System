<?php
    include('config/connection.php');
    session_start();

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];

    $location = strtoupper(htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['loc']))));
    $gcash = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['gcash'])));
    $contact = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['contact'])));

    $stmt = $conn->prepare('SELECT * FROM branch WHERE location = ? ');
    $stmt->bind_param("s",$location);
    $stmt->execute();
    $result=$stmt->get_result();
    if(mysqli_num_rows($result) < 1){
        $stmt = $conn->prepare('INSERT branch (branch_gcash_no,location,contact_no) VALUES (?,?,?)');
        $stmt->bind_param("sss",$gcash,$location,$contact);
        $stmt->execute();
        echo "Successfully Inserted!";
    }
    else{
        echo "Branch already listed!";
    }
?>