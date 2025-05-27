<?php 
    session_start();
    include('config/connection.php');

    $st='1';
    $id = $_POST['patient'];
    $stmt=$conn->prepare("SELECT COUNT(*) AS id FROM notifications WHERE patient_id = ? AND status = ? ");
    $stmt->bind_param('ss',$id,$st);
    $stmt->execute();
    $result=$stmt->get_result();
    while($row=$result->fetch_assoc()){
        if($row['id'] != 0){
            echo 'Notifications<span class="right badge badge-info" id="notif-count"><b>'.$row['id'].'</b></span>';
        }
        else{
            echo 'Notifications';
        }
    }
?>