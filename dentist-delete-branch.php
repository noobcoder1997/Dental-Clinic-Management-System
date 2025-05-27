<?php
    session_start();
    include 'config/connection.php';
    $id = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['id'])));

    $stmt = $conn->prepare("DELETE FROM branch WHERE branch_id = ?");
    $stmt->bind_param('s', $id);
    $stmt->execute();

    echo 'Branch was deleted successfuly!';
?>