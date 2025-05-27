<?php
    session_start();
    include 'config/connection.php';

    try{
        $id = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['id'])));
        $approve = htmlentities(stripslashes(mysqli_real_escape_string($conn, 'Approved')));
        $patient_id = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['patient_id'])));
        $service_id = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['service_id'])));
        $_appointment_date = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['date'])));

        $time_input = strtotime($_appointment_date);
		$date = getDate($time_input);
		$mDay = $date['mday'];
		if($mDay < 10){
			$mDay = "0".$mDay;
		}
		$Month = $date['mon'];
		if($Month < 10){
			$Month = "0".$Month;
		}
		$Year = $date['year'];
		$appointment_date = $Year.'-'.$Month.'-'.$mDay;

        $stmt = $conn->prepare("UPDATE appointment SET status = ? WHERE appointment_id = ? ");
        $stmt->bind_param('ss', $approve,$id);
        $stmt->execute();
        
        echo 'Appointment successfully approved!';

        $_query_ = "SELECT * FROM branch WHERE branch_id = ?";
        $_stmt_=$conn->prepare($_query_);
        $_stmt_->bind_param('s',$_SESSION['designation']);
        $_stmt_->execute();
        $_result_=$_stmt_->get_result();
        $_row_=$_result_->fetch_assoc();
        $b_name  = "'D 13th Smile Dental Clinic (".strtoupper($_row_['location']).")";

        $_query = "SELECT * FROM services WHERE service_id = ?";
        $_stmt=$conn->prepare($_query);
        $_stmt->bind_param('s',$service_id);
        $_stmt->execute();
        $_result=$_stmt->get_result();
        $_row=$_result->fetch_assoc();
        $s_name  = strtoupper($_row['service_name']);

        $query = "SELECT * FROM register_patient WHERE register_id = ?";
        $stmt=$conn->prepare($query);
        $stmt->bind_param('s',$patient_id);
        $stmt->execute();
        $result0=$stmt->get_result();
        $row0=$result0->fetch_assoc();
        $p_name=strtoupper($row0["first_name"]);

        $notif = "Hi $p_name, We have approved your appointment $s_name at $b_name on $_appointment_date. Please be remindful that this is a first come first serve basis. Please check your account for updates or any changes to stay informed. See you soon! - $b_name";

        $st='1';
        $stmt2=$conn->prepare("INSERT INTO notifications (patient_id,message,status) VALUES (?,?,?) ");
        $stmt2->bind_param('sss',$patient_id,$notif,$st);
        $stmt2->execute();

        if($_SESSION['position'] == 'assistant'){
            $query = "SELECT * FROM assistant WHERE assistant_id = ?";
            $stmt=$conn->prepare($query);
            $stmt->bind_param('s',$_SESSION['id']);
            $stmt->execute();
            $result=$stmt->get_result();
            if($row=$result->fetch_assoc()){
                $date = date('Y-m-d');
                
                $transaction = "Assistant ID #$_SESSION[id] (".strtoupper($row['first_name']).")- approved an appointment of patient, patient name: $row0[first_name] $row0[last_name] on $date at".date('H:i:s A');
            }
            $status = '1';
            $stmt = $conn->prepare("INSERT INTO `logs`(`transaction`, `branch_id`, `assistant_id`, `date`, `status`) VALUES (?,?,?,?,?)");
            $stmt->bind_param('sssss',$transaction, $_SESSION['designation'], $_SESSION['id'], $date,$status);
            $stmt->execute();
        }
    }
    catch(Exception $e){
        echo $e->getMessage();
    }

?>