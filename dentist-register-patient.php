<?php
    include('config/connection.php');
    session_start();
    $message = '';

    $fnim=htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['fnim'])));
    $mnim=htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['mnim'])));
    $lnim=htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['lnim'])));
    $age=htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['age'])));
    $bdate=htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['bdate'])));
    $_bdate = strtotime($bdate);
    $date = getDate($_bdate);
    // $fDay = $date['weekday'];
    $mDay = $date['mday'];
    $Month = $date['mon'];
    $Year = $date['year'];
    $_bdate =  $Year.'-'.$Month.'-'.$mDay; //format the date example {2024-12-2}
    $add=htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['add'])));
    $cno=htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['cno'])));
    $user=htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['user'])));
    $pass=htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['pass'])));
    $pass = md5($pass);
    $key='dcms.dcms';
    $salt='bad geniuses';
    $pepper='T';
    $pass=$pass.$salt.$pepper;
    $pass=hash_hmac('sha256', $pass, $key, false);
    $position=htmlentities(stripcslashes(mysqli_real_escape_string($conn, 'patient')));
    $image = "profilepic/default.ico";

    $stmt1 = $conn->prepare("SELECT * FROM register_patient WHERE first_name = ? AND middle_name = ? AND last_name = ? AND birthdate = ? ");
    $stmt1->bind_param("ssss",$fnim,$mnim,$lnim,$_bdate);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    if(mysqli_num_rows($result1) > 0){
        // $r1=$result1->fetch_assoc();
        // $stmt_1 = $conn->prepare("SELECT * FROM appointment WHERE patient_id = ? ");
        // $stmt_1->bind_param("s",$r1["register_id"]);
        // $stmt_1->execute();
        // $result_1 = $stmt_1->get_result();
        // if(mysqli_num_rows($result_1) > 0){
                
            $message = '<div class="alert alert-warning">
                                    <label>This account was already created! Please select the <b>Names</b> below that you want to check on their Pending appointments</label><br>';
            while($row1 = $result1->fetch_assoc()){
                $message .=    '<a class="badge bg-success" onclick="dentistReviewAccount('.$row1['register_id'].')">'.$row1['first_name'].' '.$row1['middle_name'] .' '.$row1['last_name'].'</a>
                                ';
            }
                echo $message .= '</div>';
        // }
        // else{
        //     echo "This account was already exist!";
        // }
    }
    else{
        $stmt2 = $conn->prepare("SELECT * FROM register_patient WHERE (username = ? AND password = ?) ");
        $stmt2->bind_param("ss",$user,$pass);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        if(mysqli_num_rows($result2) > 0){
            while($row2 = $result2->fetch_assoc()){
                echo $message = 'Please create another Username or Password!';
            }
        }
        else{
            $stmt = $conn->prepare("INSERT INTO `register_patient`(`first_name`, `middle_name`, `last_name`, `birthdate`, `address`, `contact_no`, `age`, `username`, `password`, `position`, `image`) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param('sssssssssss',$fnim,$mnim,$lnim,$_bdate,$add,$cno,$age,$user,$pass,$position,$image);
            $stmt->execute();
            echo $message = "Patient Registered Successfully!";
        };
    }

    // $stmt->close();
    // $conn->close();
?>