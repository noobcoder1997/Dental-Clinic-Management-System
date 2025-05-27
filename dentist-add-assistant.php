<?php
    include('config/connection.php');
    session_start();

    $fnim = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['first_name'])));
    $mnim = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['middle_name'])));
    $lnim = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['last_name'])));
    $age = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['age'])));
    $contact = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['contact'])));
    $bdate = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['bdate'])));
    $address = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['address'])));
    $user = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['username'])));
    $pass = htmlentities(stripslashes(mysqli_real_escape_string($conn, md5(trim($_POST['password'])))));
    $key='dcms.dcms';
    $salt='bad geniuses';
    $pepper='T';
    $pass=$pass.$salt.$pepper;
    $pass=hash_hmac('sha256', $pass, $key, false);
    $designation = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['designation'])));
    $position = htmlentities(stripslashes(mysqli_real_escape_string($conn, "assistant")));
    $image = htmlentities(stripslashes(mysqli_real_escape_string($conn, "profilepic/default.ico")));
    $message ='';

    $query0 = "SELECT * FROM assistant WHERE first_name = ? AND middle_name = ? AND last_name = ? ";
    $query1 = "SELECT * FROM dentist WHERE first_name = ? AND middle_name = ? AND last_name = ? ";
    $query2 = "SELECT * FROM register_patient WHERE first_name = ? AND middle_name = ? AND last_name = ? ";

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
        mysqli_num_rows($result2) > 0 ){
        
            $message = "This Account is already created!";
        
    }
    else{
        $query_0 = "SELECT * FROM assistant WHERE username = ? AND password = ? ";
        $query_1 = "SELECT * FROM dentist WHERE username = ? AND password = ? ";
        $query_2 = "SELECT * FROM register_patient WHERE username = ? AND password = ? ";

        $stmt_0=$conn->prepare($query_0);
        $stmt_0->bind_param("ss", $user,$pass);
        $stmt_0->execute();
        $result_0=$stmt_0->get_result();

        $stmt_1=$conn->prepare($query_1);
        $stmt_1->bind_param("ss", $user,$pass);
        $stmt_1->execute();
        $result_1=$stmt_1->get_result();

        $stmt_2=$conn->prepare($query_2);
        $stmt_2->bind_param("ss", $user,$pass);
        $stmt_2->execute();
        $result_2=$stmt_2->get_result();

        if( mysqli_num_rows($result_0) > 0 || 
            mysqli_num_rows($result_1) > 0 || 
            mysqli_num_rows($result_2) > 0  ){
            
                $message='Please create another username or password!';
            
        }
        else{
            $stmt = $conn->prepare('INSERT `assistant` (`first_name`,`middle_name`,`last_name`,`username`,`password`,`position`,`designation`,`image`,age,bdate,address,contact_no) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt->bind_param("ssssssssssss",$fnim,$mnim,$lnim,$user,$pass,$position,$designation,$image,$age,$bdate,$address,$contact);
            $stmt->execute(); 
            $message = "Assistant Successfully Added!"; 
        }
    }

    echo $message;

?>