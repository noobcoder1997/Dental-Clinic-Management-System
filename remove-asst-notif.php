<?php 
    session_start();
    include 'config/connection.php';

    $asst_id = $_SESSION['id'];
    $position = $_SESSION['position'];

    if(!isset($_SESSION['branch'])){
        $designation = $_SESSION['designation'];        
    }
    else{
        $designation = $_SESSION['branch'];
    }

    $notif_id = $_POST['id'];

    mysqli_query($conn, "UPDATE notifications SET status = 0 WHERE id = '$notif_id' ");
?>