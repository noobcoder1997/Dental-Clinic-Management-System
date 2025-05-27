<?php

    include 'config/connection.php';

    $id = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['id'])));

    $div='';

    $stmt = $conn->prepare('SELECT * FROM branch WHERE branch_id = ?');
    $stmt->bind_param('s', $id);
    $stmt->execute();

    $result = $stmt->get_result();
    if($row = $result->fetch_array(MYSQLI_ASSOC)){   
        $div = "<div><h3>DENTAL CLINIC {".strtoupper($row['location'])."} GCASH NUMBER :".$row['branch_gcash_no']."</h3></div>";
    }
    echo $div;
?>