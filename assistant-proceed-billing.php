<?php
    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];

    if(isset($_SESSION['designation'])){
        $designation = $_SESSION['designation'];        
    }
    else{
        $designation = $_SESSION['branch'];        
    }

    $_id = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['id'])));
    $date = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['date'])));
    $time_input = strtotime($date);
    $date = getDate($time_input);
    $mDay = $date['mday'];
    $Month = $date['mon'];
    $Year = $date['year'];
    $date = $Year.'-'.$Month.'-'.$mDay; //format the date example {12-2-2024}
    $status="0";

    $stmt0 = $conn->prepare("SELECT * FROM appointment WHERE appointment_id = ? AND branch_id = ?");
    $stmt0->bind_param('ss', $_id,$designation);
    $stmt0->execute();
    $result0 = $stmt0->get_result();
    if($row0 = $result0->fetch_assoc()){

        $stmt_0 = $conn->prepare("SELECT * FROM billing WHERE appointment_id = ? AND branch_id = ?");
        $stmt_0->bind_param('ss', $_id,$designation);
        $stmt_0->execute();
        $result_0 = $stmt_0->get_result();
        if(mysqli_num_rows($result_0) > 0){
            echo 'Patient was already moved to billing!';
        }
        else{
            $stmt1=$conn->prepare("INSERT INTO billing (branch_id, patient_id, appointment_id, date, down_payment, status) VALUES (?,?,?,?,?,?)");
            $stmt1->bind_param("ssssss",$designation,$row0['patient_id'], $_id, $date, $row0['down_payment'], $status);
            $stmt1->execute();            
    
            echo 'Patient has been moved successfully to Billing!';


            if($position == 'assistant'){
                $query = "SELECT * FROM assistant WHERE assistant_id = ?";
                $stmt=$conn->prepare($query);
                $stmt->bind_param('s',$_SESSION['id']);
                $stmt->execute();
                $result=$stmt->get_result();
                if($row=$result->fetch_assoc()){
                    $query = "SELECT * FROM register_patient WHERE register_id = ?";
                    $stmt_0=$conn->prepare($query);
                    $stmt_0->bind_param('s',$row0['patient_id']);
                    $stmt_0->execute();
                    $result_0=$stmt_0->get_result();
                    $r_0=$result_0->fetch_assoc();
                    $date = date('Y-m-d');
                    $transaction = "Assistant ID #$_SESSION[id] (".strtoupper($row['first_name']).")- accepted an appointment for billing, Patient name: $r_0[first_name] $r_0[last_name] on $date at".date('H:i:s A');
                }
                $status = '1';
                $stmt = $conn->prepare("INSERT INTO `logs`(`transaction`, `branch_id`, `assistant_id`, `date`, `status`) VALUES (?,?,?,?,?)");
                $stmt->bind_param('sssss',$transaction, $_SESSION['designation'], $_SESSION['id'], $date,$status);
                $stmt->execute();
            }
        }
    }

?>

