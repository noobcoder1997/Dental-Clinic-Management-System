<?php
    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];

    $patient = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['patient'])));
    $id = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['appointment_id'])));
    // echo $age = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['age'])));
    // echo $bdate = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['bdate'])));
    // echo $address = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['address'])));
    // echo $contact = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['contact'])));
    // echo $service = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['service'])));
    // echo $dentist = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['dentist'])));
    // echo $branch = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['branch'])));
    // echo $gname = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['gname'])));
    // echo $gnumber = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['gnumber'])));
    // echo $gref = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['gref'])));
    // echo $proof = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['proof'])));

    $stmt = $conn->prepare("UPDATE appointment SET status = 'Declined' WHERE patient_id = ? AND appointment_id = ? ");
    $stmt->bind_param('ss', $patient, $id);
    $stmt->execute();

    echo 'Successfully declined an appointment!';

    if($_SESSION['position'] == 'assistant'){
        $query = "SELECT * FROM assistant WHERE assistant_id = ?";
        $stmt=$conn->prepare($query);
        $stmt->bind_param('s',$_SESSION['id']);
        $stmt->execute();
        $result=$stmt->get_result();
        if($row=$result->fetch_assoc()){
            $query = "SELECT * FROM register_patient WHERE register_id = ?";
            $stmt=$conn->prepare($query);
            $stmt->bind_param('s',$patient_id);
            $stmt->execute();
            $result0=$stmt->get_result();
            $row0=$result0->fetch_assoc();
            $date = date('Y-m-d');
            $transaction = "Assistant ID #$_SESSION[id] (".strtoupper($row['first_name']).")- declined an appointment of patient, patient name: $row0[first_name] $row0[last_name] on $date at".date('H:i:s A');
        }
        $status = '1';
        $stmt = $conn->prepare("INSERT INTO `logs`(`transaction`, `branch_id`, `assistant_id`, `date`, `status`) VALUES (?,?,?,?,?)");
        $stmt->bind_param('sssss',$transaction, $_SESSION['designation'], $_SESSION['id'], $date,$status);
        $stmt->execute();
    }
?>