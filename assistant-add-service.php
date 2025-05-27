<?php
    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];
    $designation = $_SESSION['designation'];

    $name = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['name'])));
    $desc = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['desc'])));

    try{
        $stmt = $conn->prepare("SELECT * FROM services WHERE service_name = ? AND branch_id = ? ");
        $stmt->bind_param("ss", $name,$designation);
        $stmt->execute();
        $result=$stmt->get_result();
        if(mysqli_num_rows($result) < 1){
            $stmt = $conn->prepare("INSERT INTO services ( service_name, service_description,branch_id) VALUES (?,?,?) ");
            $stmt->bind_param('sss',$name,$desc,$designation);
            $stmt->execute();
        
            echo "Service Successfully Inserted!";


            if($position == 'assistant'){
                $query = "SELECT * FROM assistant WHERE assistant_id = ?";
                $stmt=$conn->prepare($query);
                $stmt->bind_param('s',$_SESSION['id']);
                $stmt->execute();
                $result=$stmt->get_result();
                if($row=$result->fetch_assoc()){
                    $date = date('Y-m-d');
                    $transaction = "Assistant ID #$_SESSION[id] (".strtoupper($row['first_name']).")- added a service, service name: $name on $date at".date('H:i:s A');
                }
                $status = '1';
                
                $stmt = $conn->prepare("INSERT INTO `logs`(`transaction`, `branch_id`, `assistant_id`, `date`, `status`) VALUES (?,?,?,?,?)");
                $stmt->bind_param('sssss',$transaction, $_SESSION['designation'], $_SESSION['id'], $date,$status);
                $stmt->execute();                
            }
        }
        else{
            echo "Service already listed!";
        }

    }
    catch( Exception $e){
        echo $e->getMessage();
    }

?>