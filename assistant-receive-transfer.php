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

    $notif_id = htmlentities($_POST['id']);
    $branch= htmlentities($_POST['branch']);
    $sendto= htmlentities($_POST['sendto']);
    $date = date('Y-m-d');
    $time = date('h:i:s A');
    
    $query0 = mysqli_query($conn, "SELECT * FROM branch WHERE branch_id = '$branch'");
    while($row0 = mysqli_fetch_assoc($query0)){
        $message = "Hi! A tool/s has been successfully received at branch: $row0[location] date: $date. ";

        $stmt1 = $conn->prepare("INSERT INTO notifications (patient_id, message) VALUES (?, ?) ");
        $stmt1->bind_param('ss',$sendto, $message);
        $stmt1->execute();       
    }
    
    mysqli_query($conn, "UPDATE notifications SET status = 0 WHERE id = '$notif_id' ");

    $query = mysqli_query($conn, "SELECT * FROM assistant WHERE assistant_id = '$asst_id' ");
    $AsstName = "";
    $rowAst = mysqli_fetch_assoc($query);
    $AsstName = $rowAst['first_name'];

    $query = mysqli_query($conn, "SELECT location FROM branch WHERE branch_id = '$sendto' ");
    if($row= mysqli_fetch_assoc($query)){
            
        $transaction = "Assistant ID #$asst_id ($AsstName)- received a tool/s from $row[location] on $date at $time";
        mysqli_query($conn, "INSERT INTO logs (transaction, branch_id, assistant_id ) VALUES ('$transaction', '$designation','$asst_id') ");
    }
?>