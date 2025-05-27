<?php 
    session_start();
    require('config/connection.php');

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];

    if(isset($_SESSION['designation'])){
        $designation = $_SESSION['designation'];
        try{
            $from = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['from'])));
            $to = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['to'])));
            $dentist = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['dentist'])));
        
            $from_time_input = strtotime($from);
            $to_time_input = strtotime($to);
        
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
        
            $dates=[];
            $message='';
            $message0 = 'The Dentist has another schedule on ';
        
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
            $_date=explode(",",implode(",", getDatesFromRange($_from_date, $_to_date)));
        
            foreach($_date as $d){
                if($d == null){
                    $message = 'Dates are Empty, Please set the dates in order.';
                }else{
                    $schedQRY = mysqli_query($conn, "SELECT * FROM schedules WHERE schedule = '$d' ");
                    if(mysqli_num_rows($schedQRY) == 0)
                    {
                        $_d= strtotime($d);
                        $from_date = getDate($_d);
                        $from_mDay = $from_date['mday'];
                        $from_Month = $from_date['month'];
                        $from_Year = $from_date['year'];
                        $_from_date = $from_Month.' '.$from_mDay.', '.$from_Year;
                        mysqli_query($conn, "INSERT INTO schedules (dentist_id, schedule, branch_id) VALUES ('$dentist', '$d', '$designation')"); 
                        $message = 'New Schedule was set! ';

                        $query = "SELECT * FROM assistant WHERE assistant_id = ?";
                        $stmt=$conn->prepare($query);
                        $stmt->bind_param('s',$_SESSION['id']);
                        $stmt->execute();
                        $result=$stmt->get_result();
                        if($row=$result->fetch_assoc()){
                            $date = date('Y-m-d');
                            $transaction = "Assistant ID #$_SESSION[id] (".strtoupper($row['first_name']).")- added a Dentist schedule, from $from to $to on $date at".date('H:i:s A');
                        }
                        $status = '1';
                        $stmt = $conn->prepare("INSERT INTO `logs`(`transaction`, `branch_id`, `assistant_id`, `date`, `status`) VALUES (?,?,?,?,?)");
                        $stmt->bind_param('sssss',$transaction, $_SESSION['designation'], $_SESSION['id'], $date,$status);
                        $stmt->execute();
                    }
                    else{
                        $_d= strtotime($d);
                        $from_date = getDate($_d);
                        $from_mDay = $from_date['mday'];
                        $from_Month = $from_date['month'];
                        $from_Year = $from_date['year'];
                        $_from_date = $from_Month.' '.$from_mDay.', '.$from_Year;
                        array_push($dates,$_from_date);
                        $message = $message0.implode(' and ',$dates);
                        // break;   
                    }    
                }
            }  
            echo $message;
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
    else{
        $designation = $_SESSION['branch'];
        try{
            $from = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['from'])));
            $to = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['to'])));
            $dentist = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['dentist'])));
        
            $from_time_input = strtotime($from);
            $to_time_input = strtotime($to);
        
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
        
            $dates=[];
            $message='';
            $message0 = 'The Dentist has another schedule on ';
        
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
            $_date=explode(",",implode(",", getDatesFromRange($_from_date, $_to_date)));
        
            foreach($_date as $d){
                if($d == null){
                    $message = 'Dates are Empty, Please set the dates in order.';
                }else{
                    $schedQRY = mysqli_query($conn, "SELECT * FROM schedules WHERE schedule = '$d' ");
                    if(mysqli_num_rows($schedQRY) == 0)
                    {
                        $_d= strtotime($d);
                        $from_date = getDate($_d);
                        $from_mDay = $from_date['mday'];
                        $from_Month = $from_date['month'];
                        $from_Year = $from_date['year'];
                        $_from_date = $from_Month.' '.$from_mDay.', '.$from_Year;
                        mysqli_query($conn, "INSERT INTO schedules (dentist_id, schedule, branch_id) VALUES ('$dentist', '$d', '$designation')"); 
                        $message = 'New Schedule was set! ';
                    }
                    else{
                        $_d= strtotime($d);
                        $from_date = getDate($_d);
                        $from_mDay = $from_date['mday'];
                        $from_Month = $from_date['month'];
                        $from_Year = $from_date['year'];
                        $_from_date = $from_Month.' '.$from_mDay.', '.$from_Year;
                        array_push($dates,$_from_date);
                        $message = $message0.implode(' and ',$dates);
                        // break;   
                    }    
                }
            }  
            echo $message;
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
    
?>