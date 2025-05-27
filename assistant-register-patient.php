<?php
    include('config/connection.php');
    session_start();

    try{
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
        $_bdate =  $Year.'-'.$Month.'-'.$mDay; //format the date example {12-2-2024}
        $add=htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['add'])));
        $cno=htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['cno'])));
        $user=htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['user'])));
        $pass=htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['pass'])));
        $pass=md5($pass);
        $key='dcms.dcms';
        $salt='bad geniuses';
        $pepper='T';
        $pass=$pass.$salt.$pepper;
        $pass=hash_hmac('sha256', $pass, $key, false);
        $position=htmlentities(stripcslashes(mysqli_real_escape_string($conn, 'patient')));
        $image = "profilepic/default.ico";
    
        if(strlen($cno) != 11 && $cno[0] != '0'){
            $message = 'Invalid Phone number';
        }
        else{
            $stmt1 = $conn->prepare("SELECT * FROM register_patient WHERE first_name = ? AND middle_name = ? AND last_name = ? AND birthdate = ? ");
            $stmt1->bind_param("ssss",$fnim,$mnim,$lnim,$_bdate);
            $stmt1->execute();
            $result1 = $stmt1->get_result();
            if(mysqli_num_rows($result1) > 0){
                    $message = '<div class="alert alert-warning">
                                    <label>This account was already created! Please select the <b>Names</b> below that you want to check on</label>';
                while($row1 = $result1->fetch_assoc()){
                    $message .=    '<a class="badge bg-success" onclick="assistantReviewAccount('.$row1['register_id'].')">'.$row1['first_name'].' '.$row1['middle_name'] .' '.$row1['last_name'].'</a>
                                    ';
                }
                    echo $message .= '</div>';
            }
            else{
                $stmt2 = $conn->prepare("SELECT * FROM register_patient WHERE (username = ? AND password = ?) ");
                $stmt2->bind_param("ss",$user,$pass);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                if(mysqli_num_rows($result2) > 0){
                    while($row2 = $result2->fetch_assoc()){
                        echo $message =    'Please create another Username or Password!';
                    }
                }
                else{
                    $stmt = $conn->prepare("INSERT INTO `register_patient`(`first_name`, `middle_name`, `last_name`, `birthdate`, `address`, `contact_no`, `age`, `username`, `password`, `position`, `image`) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
                    $stmt->bind_param('sssssssssss',$fnim,$mnim,$lnim,$_bdate,$add,$cno,$age,$user,$pass,$position,$image);
                    $stmt->execute();
                    $last_id=$conn->insert_id;
                    echo $message = "Patient Registered Successfully!";

                    if($position == 'assistant'){
                        $query = "SELECT * FROM assistant WHERE assistant_id = ?";
                        $stmt=$conn->prepare($query);
                        $stmt->bind_param('s',$_SESSION['id']);
                        $stmt->execute();
                        $result=$stmt->get_result();
                        if($row=$result->fetch_assoc()){
                            $date = date('Y-m-d');
                            $transaction = "Assistant ID #$_SESSION[id] (".strtoupper($row['first_name']).")- registered a patient, Patient name $fnim $lnim on $date at".date('H:i:s A');
                        }
                        $status='1';
                        $stmt = $conn->prepare("INSERT INTO `logs`(`transaction`, `branch_id`, `assistant_id`, `date`, `status`) VALUES (?,?,?,?,?)");
                        $stmt->bind_param('sssss',$transaction, $_SESSION['designation'], $_SESSION['id'], $date,$status);
                        $stmt->execute();
                    }
                }
            }    
        }   
    }
    catch(Exception $e){
        echo $e->getMessage();
    }
?>