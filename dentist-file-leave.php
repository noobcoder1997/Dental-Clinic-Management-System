<?php
    session_start();
    require('config/connection.php');
    $branch = $_SESSION['branch'];

    $id = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['id'])));

    $from_date=htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['from-date'])));
    $to_date=htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['to-date'])));

    $from_time_input = strtotime($from_date);
    $to_time_input = strtotime($to_date);

    $from_date = getDate($from_time_input);
    $to_date = getDate($to_time_input);

    $from_mDay = $from_date['mday'];
    $to_mDay = $to_date['mday'];

    $from_Month = $from_date['mon'];
    $to_Month = $to_date['mon'];

    $from_Year = $from_date['year'];
    $to_Year = $to_date['year'];

    $_from_date = $from_Year.'-'.$from_Month.'-'.$from_mDay; //format the date example {12-2-2024}
    $_to_date = $to_Year.'-'.$to_Month.'-'.$to_mDay; //format the date example {12-2-2024}

    function getDatesFromRange($start, $end, $format = "Y-m-d") {
        $array = array();
        $interval = new DateInterval('P1D');

        $realEnd = new DateTime($end);
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

        foreach($period as $date) { 
            $array[] = $date->format($format); 
        }

        return $array;
    }
    $_date = implode(",",getDatesFromRange($_from_date,$_to_date));

    $stmt=$conn->prepare("SELECT * FROM dentist WHERE dentist_id = ?");
    $stmt->bind_param('s',$id);
    $stmt->execute();
    $result=$stmt->get_result();
    if($row=$result->fetch_assoc()){
        
        $_date = explode(',',$_date);

        foreach($_date as $d){
            $stmt0=$conn->prepare(" DELETE FROM schedules WHERE dentist_id = ? AND schedule = ? AND branch_id = ? ");
            $stmt0->bind_param('sss', $id, $d, $branch);
            $stmt0->execute();          
        }
        
        echo "You have set your leave!";  
    }

    // $stmt=$conn->prepare("SELECT * FROM dentist WHERE dentist_id = ?");
    // $stmt->bind_param('s',$id);
    // $stmt->execute();
    // $result=$stmt->get_result();
    // if($row=$result->fetch_assoc()){
        

    //     $stmt0=$conn->prepare("UPDATE dentist SET _leave = ? WHERE dentist_id = ?");
    //     $stmt0->bind_param('ss',$_date,$id);
    //     $stmt0->execute();
    //     echo "You have set your leave on $_from_date to $_to_date!";
    // }
?>