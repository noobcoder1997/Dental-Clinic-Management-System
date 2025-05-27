<?php
    session_start();
    include('config/connection.php');

    $fnim=htmlentities(mysqli_real_escape_string($conn, stripslashes($_POST['fnim'])));
    $mnim=htmlentities(mysqli_real_escape_string($conn, stripslashes($_POST['mnim'])));
    $lnim=htmlentities(mysqli_real_escape_string($conn, stripslashes($_POST['lnim'])));
    $bdate=htmlentities(mysqli_real_escape_string($conn, stripslashes($_POST['bdate'])));
    $sex=htmlentities(mysqli_real_escape_string($conn, stripslashes($_POST['sex'])));
    $status=htmlentities(mysqli_real_escape_string($conn, stripslashes($_POST['status'])));
    $job=htmlentities(mysqli_real_escape_string($conn, stripslashes($_POST['job'])));
    $office_address=htmlentities(mysqli_real_escape_string($conn, stripslashes($_POST['office_address'])));
    $_bdate = strtotime($bdate);
    $date = getDate($_bdate);
    // $fDay = $date['weekday'];
    $mDay = $date['mday'];
    $Month = $date['mon'];
    $Year = $date['year'];
    $_bdate = $Year.'-'.$Month.'-'.$mDay; //2024-12-3
    $cno=htmlentities(mysqli_real_escape_string($conn, stripslashes($_POST['cno'])));
    $age=htmlentities(mysqli_real_escape_string($conn, stripslashes($_POST['age'])));
    $add=htmlentities(mysqli_real_escape_string($conn, stripslashes($_POST['add'])));
    $userid=htmlentities(mysqli_real_escape_string($conn, stripslashes($_POST['userid'])));
    $position=htmlentities(mysqli_real_escape_string($conn, stripslashes('patient')));
    $pass=htmlentities(mysqli_real_escape_string($conn, stripcslashes(md5(trim($_POST['pass'])))));
    $key='dcms.dcms';
    $salt='bad geniuses';
    $pepper='T';
    $pass=$pass.$salt.$pepper;
    $pass=hash_hmac('sha256', $pass, $key, false);
    $image="profilepic/default.ico";

    $stmt1 = $conn->prepare("SELECT * FROM register_patient WHERE first_name = ? AND middle_name = ? AND last_name = ? AND birthdate = ? ");
    $stmt1->bind_param("ssss",$fnim,$mnim,$lnim,$_bdate);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    if(mysqli_num_rows($result1) > 0){
        while($row1 = $result1->fetch_assoc()){
            $message = 'This Account is already created!';
        }
    }
    else{
        $stmt_0 = $conn->prepare("SELECT * FROM register_patient WHERE (username = ? AND password = ?) ");
        $stmt_0->bind_param("ss",$userid,$pass);
        $stmt_0->execute();
        $result_0 = $stmt_0->get_result();
        
        $stmt_1 = $conn->prepare("SELECT * FROM dentist WHERE (username = ? AND password = ?) ");
        $stmt_1->bind_param("ss",$userid,$pass);
        $stmt_1->execute();
        $result_1 = $stmt_1->get_result();
        
        $stmt_2 = $conn->prepare("SELECT * FROM assistant WHERE (username = ? AND password = ?) ");
        $stmt_2->bind_param("ss",$userid,$pass);
        $stmt_2->execute();
        $result_2 = $stmt_2->get_result();

        if( mysqli_num_rows($result_0) > 0 ||
            mysqli_num_rows($result_1) > 0 ||
            mysqli_num_rows($result_2) > 0 ){
            
                $message = 'Please create another username or password!';
            
        }
        else{
            $stmt = $conn->prepare("INSERT INTO `register_patient`(`first_name`, `middle_name`, `last_name`, `birthdate`, `address`, `contact_no`, `age`, `username`, `password`, `position`,`sex`, `marital_status`, `occupation`, `office_address`, `image`) VALUES (?,?,?,?,?, ?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param('sssssssssssssss',$fnim,$mnim,$lnim,$_bdate,$add,$cno,$age,$userid,$pass,$position,$sex,$status,$job,$office_address,$image);
            $stmt->execute();
            $lastid = mysqli_insert_id($conn);
            $message = 1;

            $notif = "Hi ".strtoupper($fnim).", Thank you for registering at 'D 13th Teeth Dental Clinic. We hope you the best!";

            $st='1';
            $stmt2=$conn->prepare("INSERT INTO notifications (patient_id,message,status) VALUES (?,?,?) ");
            $stmt2->bind_param('sss',$lastid,$notif,$st);
            $stmt2->execute();

            unset($_SESSION['register']);
        }
        
    }    
    echo $message;

$conn->close();
?>