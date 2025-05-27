<?php
   session_start();
   include 'config/connection.php';

   $id = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['id'])));
   $st='0';
   $st1='1';

   $stmt=$conn->prepare("SELECT * FROM notifications WHERE status =  ?");
   $stmt->bind_param('s',$st1);
   $stmt->execute();
   $result=$stmt->get_result();
   while($row = $result->fetch_assoc()){
      $stmt1=$conn->prepare("UPDATE notifications SET status = ? WHERE patient_id = ? ");
      $stmt1->bind_param('ss',$st,$row['patient_id']);
      $stmt1->execute();      
   }

   $st='1';
   $count;

   $stmt=$conn->prepare("SELECT COUNT(*) AS count FROM notifications WHERE status = ? ");
   $stmt->bind_param('s',$st1);
   $stmt->execute();
   $result=$stmt->get_result();
   while($row=$result->fetch_assoc()){
      $count = $row['count'];
   }
   echo $count;
?>
