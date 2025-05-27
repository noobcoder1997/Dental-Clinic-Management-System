<?php
    session_start();
    include 'config/connection.php';
    $id = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['id'])));

    $stmt = $conn->prepare("DELETE FROM services WHERE service_id = ?");
    $stmt->bind_param('s', $id);
    $stmt->execute();

    echo 'Service was deleted successfuly!';
?>