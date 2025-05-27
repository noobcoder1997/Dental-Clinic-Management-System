<?php

    session_start();
    include 'config/connection.php';


    $id = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['id'])));
    // $patient_id = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['patient_id'])));
    // $service_id = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['service_id'])));
    // $branch_id = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['branch_id'])));
    // $dentist_id = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['dentist_id'])));
    // $appoinment_date = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['appoinment_date'])));
    // $appoinment_time = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['appoinment_time'])));
    // $gcash_no = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['gcash_no'])));
    // $gcash_name = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['gcash_name'])));
    // $payment_ref = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['payment_ref'])));
    // $payment_proof = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['payment_proof'])));
    $status = htmlentities(stripcslashes(mysqli_real_escape_string($conn, "Approved")));

    $stmt = $conn->prepare("UPDATE appointment SET status = ? WHERE appointment_id = ?");
    $stmt->bind_param("ss", $status,$id);
    $stmt->execute();

    echo 'Appointment successfully approved!'
?>