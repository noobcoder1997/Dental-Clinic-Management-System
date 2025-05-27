<?php
    session_start();
    include 'config/connection.php';

    $pass = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['pass'])));
    $user = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['user'])));
    $id = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['id'])));
    $pass = md5(trim($pass));
    $key='dcms.dcms';
    $salt='bad geniuses';
    $pepper='T';
    $pass=$pass.$salt.$pepper;
    $pass=hash_hmac('sha256', $pass, $key, false);
    $stmt = $conn->prepare("UPDATE register_patient SET username = ?, password = ? WHERE register_id = ? ");
    $stmt->bind_param("sss", $user,$pass,$id);
    $stmt->execute();

    echo "Successfully Updated!";

?>