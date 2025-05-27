<?php
    include 'config/connection.php';

    $id = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['id'])));
    $location = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['loc'])));
    $gcashnumber = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['gcash'])));
    $branchno = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['cno'])));

    $div='Branch was successfully updated!';

    $stmt = $conn->prepare('UPDATE branch SET location = ?, branch_gcash_no = ?,  contact_no = ?  WHERE branch_id = ?');
    $stmt->bind_param('ssss',$location,$gcashnumber,$branchno,$id);
    $stmt->execute();

    echo $div;
?>