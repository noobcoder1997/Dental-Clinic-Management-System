<?php
    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];

    $id = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['id'])));
    // $id = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['appointment_id'])));
    $approve = htmlentities(stripslashes(mysqli_real_escape_string($conn, 'Declined')));
    // echo $age = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['age'])));
    // echo $bdate = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['bdate'])));
    // echo $address = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['address'])));
    // echo $contact = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['contact'])));
    // echo $service = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['service'])));
    // echo $dentist = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['dentist'])));
    // echo $branch = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['branch'])));
    // echo $gname = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['gname'])));
    // echo $gnumber = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['gnumber'])));
    // echo $gref = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['gref'])));
    // echo $proof = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['proof'])));

    $stmt = $conn->prepare("UPDATE appointment SET status = ? WHERE appointment_id = ? ");
    // $id = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['appointment_id'])));
    $stmt->bind_param('ss', $approve,$id);
    $stmt->execute();

    echo 'Appointment successfully Declined!';
?>