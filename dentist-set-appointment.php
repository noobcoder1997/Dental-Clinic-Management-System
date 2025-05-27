<?php
	session_start();
	include 'config/connection.php';

	$id = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['id'])));
	$dp = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['downpayment'])));
	$services = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['srvcs'])));
	// $services = htmlentities(stripslashes(mysqli_real_escape_string($conn, '1')));
	$branch = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['brch'])));
	$dentist = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['dent'])));
	$appointment_date = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['appdte'])));
	$time_input = strtotime($appointment_date);
	$date = getDate($time_input);
	$mDay = $date['mday'];
	if($mDay < 10 )
	$mDay = "0".$mDay;
	$Month = $date['mon'];
	if($Month < 10)
	$Month = "0".$Month;
	$Year = $date['year'];
	$appointment_date = $Year.'-'.$Month.'-'.$mDay; //format the date example {12-2-2024}
	$appointment_time = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['appdtme'])));
	$appointment_time  = date('h:i A', strtotime($appointment_time));
	$gcash_name = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['gcshnim'])));
	$gcash_number = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['gcshno'])));
	$payment_reference = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['pmntref'])));
	$price = htmlentities(stripslashes(mysqli_real_escape_string($conn, 1000)));
	// $status = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['sts'])));
	$status = htmlentities(stripslashes(mysqli_real_escape_string($conn, 'Pending')));
	$message;

	if($price >= $dp && $dp > 499){
		if(strlen($gcash_number) != 11 || strlen($payment_reference) != 13){
			echo 'Invalid Phone Number or Reference Number!';
		}
		else{
			if( !empty( $_FILES["pymntprof"]["tmp_name"] ) ){
				// $folder_name=$_POST['image']; 
				// $output_dir = @'photo'; 
				// 	if (!file_exists($output_dir . $folder_name))//checking if folder exist 
				// 	{ 
				// 		@mkdir($output_dir . $folder_name, 0777);//making folder 
					// } 
				$fileinfo=PATHINFO($_FILES["pymntprof"]["name"]);
				$newFilename=$fileinfo['filename'] ."." . $fileinfo['extension'];
				move_uploaded_file($_FILES["pymntprof"]["tmp_name"],"payment_proofs/" . $newFilename);
				$location="payment_proofs/" . $newFilename; //payment proof
			
				$stmt = $conn->prepare("INSERT INTO `appointment`(`service_id`, `patient_id`, `branch_id`, `dentist_id`, `appointment_date`, `appointment_time`, `down_payment`, `gcash_no`, `gcash_name`, `payment_ref`, `payment_proof`, `status`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)");
				$stmt->bind_param("ssssssssssss", $services, $id, $branch, $dentist, $appointment_date, $appointment_time, $dp, $gcash_number, $gcash_name, $payment_reference, $location, $status);
			
				$stmt->execute();
				echo 'Appointment created successfully';

			}
			else{
				echo 'No Gcash payment proof is added!';
			}		
		}

	}
	else if($dp < 500){
		echo 'Insufficient Down payment!';
	}
	else{
		echo 'Your down payment is more than enough than the price!';
	}

	//<!-- Make notification like PLEASE BRING YOUR GCASH REFERENCE NUMBER FOR VERIFICATION -->
?>
