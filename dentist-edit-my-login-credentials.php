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

    $query0="SELECT * FROM dentist WHERE username = ? AND password = ?";
    $stmt0=$conn->prepare($query0);
    $stmt0->bind_param('ss',$user,$pass);
    $stmt0->execute();
    $result0=$stmt0->get_result();

    $query1="SELECT * FROM assistant WHERE username = ? AND password = ?";
    $stmt1=$conn->prepare($query1);
    $stmt1->bind_param('ss',$user,$pass);
    $stmt1->execute();
    $result1=$stmt1->get_result();

    $query0="SELECT * FROM register_patient WHERE username = ? AND password = ?";
    $stmt0=$conn->prepare($query0);
    $stmt0->bind_param('ss',$user,$pass);
    $stmt0->execute();
    $result2=$stmt2->get_result();

    if( mysqli_num_rows($result0)>0 ||
        mysqli_num_rows($result1)>0 ||
        mysqli_num_rows($result2)>0  ){
            echo 'This Username or Password already exist!';
        }
    else{
        $stmt = $conn->prepare("UPDATE dentist SET username = ?, password = ? WHERE dentist_id = ? ");
        $stmt->bind_param("sss", $user,$pass,$id);
        $stmt->execute();

        echo "Successfully Updated!";    
    }
?>