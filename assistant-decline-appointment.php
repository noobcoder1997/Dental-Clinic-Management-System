<?php
    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];
    
    try{
        $id = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['id'])));
        $approve = htmlentities(stripslashes(mysqli_real_escape_string($conn, 'Declined')));
        $patient_id = htmlentities(stripslashes(mysqli_real_escape_string($conn,  $_POST['patient_id'])));
        $branch_id = htmlentities(stripslashes(mysqli_real_escape_string($conn,  $_POST['branch_id'])));
        $service_id = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['service_id'])));
        $appointment_date = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['date'])));
        $s_name="";
        $p_name="";
        $b_name="";

        $time_input = strtotime($appointment_date);
		$date = getDate($time_input);
		$mDay = $date['mday'];
		if($mDay < 10){
			$mDay = "0".$mDay;
		}
		$Month = $date['month'];
		if($Month < 10){
			$Month = "0".$Month;
		}
		$Year = $date['year'];
		$appointment_date = $Month.", ".$mDay.", ".$Year;

            $stmt = $conn->prepare("UPDATE appointment SET status = ? WHERE appointment_id = ? ");
            $stmt->bind_param('ss', $approve,$id);
            $stmt->execute();

        echo 'Appointment successfully Declined!';
        
        $stmt0=$conn->prepare("SELECT * FROM branch WHERE branch_id = ?");
        $stmt0->bind_param('s', $branch_id);
        $stmt0->execute();
        $result0=$stmt0->get_result();
        while($row0=$result0->fetch_assoc()){
            $b_name  = "'D 13th Smile Dental Clinic (".strtoupper($row0['location']).")";
        }

        $stmt1=$conn->prepare("SELECT * FROM services WHERE service_id = ?");
        $stmt1->bind_param('s',$service_id);
        $stmt1->execute();
        $result1=$stmt1->get_result();
        while($row1=$result1->fetch_assoc()){
            $s_name  = $row1['service_name'];
        }

        $stmt2=$conn->prepare("SELECT * FROM register_patient WHERE register_id = ?");
        $stmt2->bind_param('s',$patient_id);
        $stmt2->execute();
        $result2=$stmt2->get_result();
        while($row2=$result2->fetch_assoc()){
            $p_name = strtoupper($row2['first_name']); 
        }

        echo $notif = "Hi $p_name, We reget to inform you that we declined your appointment ( $s_name ) at $b_name on $appointment_date because of some foreseen invalidity. Please visit our branch for more information and inquiries. - $b_name";

        $st='1';
        $stmt2=$conn->prepare("INSERT INTO notifications (patient_id,message,status) VALUES (?,?,?) ");
        $stmt2->bind_param('sss',$patient_id,$notif,$st);
        $stmt2->execute();

        if($position == 'assistant'){
            $query = "SELECT * FROM assistant WHERE assistant_id = ?";
            $stmt=$conn->prepare($query);
            $stmt->bind_param('s',$_SESSION['id']);
            $stmt->execute();
            $result=$stmt->get_result();
            if($row=$result->fetch_assoc()){
                $date = date('Y-m-d');
                $transaction = "Assistant ID #$_SESSION[id] (".strtoupper($row['first_name']).")- declined an appointment of patient, patient ID #$patient_id on $date at".date('H:i:s A');
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