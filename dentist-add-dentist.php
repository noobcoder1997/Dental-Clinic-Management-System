<?php
    session_start();
    include 'config/connection.php';

    $fnim = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['firstname'])));
    $mnim = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['middlename'])));
    $lnim = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['lastname'])));
    $user = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['username'])));
    $pass = htmlentities(stripcslashes(mysqli_real_escape_string($conn, md5(trim($_POST['password'])))));
    $key='dcms.dcms';
    $salt='bad geniuses';
    $pepper='T';
    $pass=$pass.$salt.$pepper;
    $pass=hash_hmac('sha256', $pass, $key, false);
    $pos = htmlentities(stripcslashes(mysqli_real_escape_string($conn, 'dentist')));
    $image = htmlentities(stripcslashes(mysqli_real_escape_string($conn, 'profilepic/default.ico')));
    $message='';

    $query0 = "SELECT * FROM dentist WHERE first_name = ? AND middle_name = ? AND last_name = ?";
    $query1 = "SELECT * FROM assistant WHERE first_name = ? AND middle_name = ? AND last_name = ?";
    $query2 = "SELECT * FROM register_patient WHERE first_name = ? AND middle_name = ? AND last_name = ?";

    $stmt0=$conn->prepare($query0);
    $stmt0->bind_param("sss",$fnim,$mnim,$lnim);
    $stmt0->execute();
    $result0=$stmt0->get_result();

    $stmt1=$conn->prepare($query1);
    $stmt1->bind_param("sss",$fnim,$mnim,$lnim);
    $stmt1->execute();
    $result1=$stmt1->get_result();

    $stmt2=$conn->prepare($query2);
    $stmt2->bind_param("sss",$fnim,$mnim,$lnim);
    $stmt2->execute();
    $result2=$stmt2->get_result();

    if( mysqli_num_rows($result0) > 0 ||
        mysqli_num_rows($result1) > 0 ||
        mysqli_num_rows($result2) > 0   ){
        
        $message = "This Account is already created!";
        
    }
    else{
        $_stmt=$conn->prepare("SELECT * FROM dentist WHERE username = ? AND password = ?");
        $_stmt->bind_param("ss",$user,$pass);
        $_stmt->execute();
        $_result=$_stmt->get_result();

        $__stmt=$conn->prepare("SELECT * FROM assistant WHERE username = ? AND password = ?");
        $__stmt->bind_param("ss",$user,$pass);
        $__stmt->execute();
        $__result=$__stmt->get_result();

        $___stmt=$conn->prepare("SELECT * FROM register_patient WHERE username = ? AND password = ?");
        $___stmt->bind_param("ss",$user,$pass);
        $___stmt->execute();
        $___result=$___stmt->get_result();
        if( mysqli_num_rows($_result) > 0 ||
            mysqli_num_rows($__result) > 0 ||
            mysqli_num_rows($___result) > 0   ){
    
                $message = "Please create another Username or Password!";

        }
        else{
            $stmt_ = $conn->prepare("INSERT INTO dentist (first_name, middle_name, last_name, username, password, position, image   ) VALUES (?, ?, ?, ?, ?, ?, ?) ");
            $stmt_->bind_param("sssssss", $fnim,$mnim,$lnim,$user,$pass,$pos,$image);
            $stmt_->execute();
            $message = "Successfully Inserted!";
        }
    }
    echo $message;    
?>