<?php
    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];
    $designation = $_SESSION['designation'];

    $bill_id = $_POST['bill_id'];
    $patient = $_POST['patient_id'];
    $date = date('Y-m-d');
    $status = '1';

    $qry = mysqli_query($conn, "UPDATE service_transaction SET status = '$status' WHERE billing_id = '$bill_id' AND patient_id = '$patient' AND date = '$date' ");
    $qry = mysqli_query($conn, "UPDATE product_transaction SET status = '$status' WHERE billing_id = '$bill_id' AND patient_id = '$patient' AND date = '$date' ");
    echo 'Transaction has been cancelled!';
?>