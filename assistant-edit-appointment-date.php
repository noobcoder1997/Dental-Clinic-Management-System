<?php
    session_start();
    include 'config/connection.php';

    $id=htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['id'])));
    $_date=htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['date'])));

    $st='1';
    $notif="";

    $time_input = strtotime($_date);
    $date = getDate($time_input);

    $mDay = $date['mday'];
    if($mDay < 10){
        $mDay = "0".$date['mday'];        
    }
    $Month = $date['mon'];
    if($Month < 10){
        $Month = "0".$date['mon'];        
    }
 
    $Year = $date['year'];
    $appointment_date = $Year.'-'.$Month.'-'.$mDay;

    try{

        $stmt=$conn->prepare("SELECT * FROM appointment WHERE appointment_id = ? ");
        $stmt->bind_param('s',$id);
        $stmt->execute();
        $result=$stmt->get_result();
    
        if($row = $result->fetch_assoc()){
    
            if($row['appointment_date'] != $appointment_date){
    
                $stmt0=$conn->prepare("SELECT * FROM register_patient WHERE register_id = ?");
                $stmt0->bind_param('s',$row['patient_id']);
                $stmt0->execute();
                $result0=$stmt0->get_result();
                
                if($row0 = $result0->fetch_assoc()){
                    $_query_ = "SELECT * FROM branch WHERE branch_id = ?";
                    $_stmt_=$conn->prepare($_query_);
                    $_stmt_->bind_param('s',$_SESSION['designation']);
                    $_stmt_->execute();
                    $_result_=$_stmt_->get_result();
                    $_row_=$_result_->fetch_assoc();
                    $b_name  = "'D 13th Smile Dental Clinic (".strtoupper($_row_['location']).")";
            
                    $stmt1=$conn->prepare("SELECT * FROM services WHERE service_id = ?");
                    $stmt1->bind_param("s",$row['service_id']);
                    $stmt1->execute();
                    $result1=$stmt1->get_result();
            
                    if($row1=$result1->fetch_assoc()){
                    
                        $notif = 'Hi '.strtoupper($row0['first_name']).'. We regret to inform you that your appointment for '.$row1["service_name"].' at '.$b_name.' has been moved to '.$_date.'. We are sorry for the inconvenience that may caused you. - '.$b_name;
                        
                        $stmt1=$conn->prepare("UPDATE appointment SET appointment_date = ? WHERE appointment_id = ? ");
                        $stmt1->bind_param("ss", $appointment_date,$id);
                        $stmt1->execute(); 
            
                        $stmt2=$conn->prepare("INSERT INTO notifications (patient_id,message,status) VALUES (?,?,?) ");
                        $stmt2->bind_param('sss',$row['patient_id'],$notif,$st);
                        $stmt2->execute();
                        echo 'Patient\'s Schedule was moved successfully!';

                        if($_SESSION['position'] == 'assistant'){
                            $query = "SELECT * FROM assistant WHERE assistant_id = ?";
                            $stmt=$conn->prepare($query);
                            $stmt->bind_param('s',$_SESSION['id']);
                            $stmt->execute();
                            $result=$stmt->get_result();
                            if($row=$result->fetch_assoc()){
                                $date = date('Y-m-d');
                                $transaction = "Assistant ID #$_SESSION[id] (".strtoupper($row['first_name']).")- edited an appointment date of patient, patient name: $row0[first_name] $row0[last_name] on $date at".date('H:i:s A');
                            }
                            $status = '1';
                        
                            $stmt = $conn->prepare("INSERT INTO `logs`(`transaction`, `branch_id`, `assistant_id`, `date`, `status`) VALUES (?,?,?,?,?)");
                            $stmt->bind_param('sssss',$transaction, $_SESSION['designation'], $_SESSION['id'], $date,$status);
                            $stmt->execute();
                        }
                    }
                }
            }
        }
    }
    catch(Exception $e){
        echo $e->getMessage();
    }

?>