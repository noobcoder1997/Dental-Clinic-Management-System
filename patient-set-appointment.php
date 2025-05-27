<?php
		session_start();
		include 'config/connection.php';

		$message="";

		$id = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['id'])));
		$dp = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['downpayment'])));
		$services = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['srvcs'])));
		// $services = htmlentities(stripslashes(mysqli_real_escape_string($conn, '1')));
		$branch = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['brch'])));
		$dentist = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['dent'])));
		$_appointment_date = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['appdte'])));
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
		$appointment_date = $Year.'-'.$Month.'-'.$mDay; //format the date example {12-2-2024}
		$appointment_time = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['appdtme'])));
		$appointment_time  = date('h:i A', strtotime($appointment_time));
		$gcash_name = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['gcshnim'])));
		$gcash_number = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['gcshno'])));
		$payment_reference = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['pmntref'])));
		$price = htmlentities(stripslashes(mysqli_real_escape_string($conn, '1000')));

		// $status = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['sts'])));
		$status = htmlentities(stripslashes(mysqli_real_escape_string($conn, 'Pending')));
		$message="";
		$notif ='';
		$b_name="";
		$p_name='';
		$st='1';
		$sms='0';	

		$stmt=$conn->prepare("SELECT * FROM dentist WHERE dentist_id = ?");
		$stmt->bind_param("s", $dentist);
		$stmt->execute();
		$result=$stmt->get_result();
		if($row=$result->fetch_assoc()){

			if($dp==null || $services==null || $dentist==null || $appointment_date==null || $appointment_time==null || $gcash_name==null || $gcash_number==null || $payment_reference==null || $price==null){
				$message='Fields should not leave empty!';
			}
			else{
				if($price >= $dp && $dp > 499){
				if(strlen($gcash_number) != 11 || strlen($payment_reference) != 13){
					$message = 'Invalid Phone Number or Reference Number!';
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
					
						$stmt = $conn->prepare("INSERT INTO `appointment`(`service_id`, `patient_id`, `branch_id`, `dentist_id`, `appointment_date`, `appointment_time`, `down_payment`, `gcash_no`, `gcash_name`, `payment_ref`, `payment_proof`, `status`,`sms`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)");
						$stmt->bind_param("sssssssssssss", $services, $id, $branch, $dentist, $appointment_date, $appointment_time, $dp, $gcash_number, $gcash_name, $payment_reference, $location, $status,$sms);
						$stmt->execute();

						$stmt0 = $conn->prepare("SELECT * FROM register_patient WHERE register_id = ?");
						$stmt0->bind_param('s',$id);
						$stmt0->execute();
						$result0=$stmt0->get_result();
						$row0=$result0->fetch_assoc();
						
						$contact = $row0['contact_no'];
						$contact = ltrim($contact, $contact[0]);
						$p_name = strtoupper($row0['first_name']);				

						$query = mysqli_query($conn, "SELECT * FROM branch WHERE branch_id = '$branch' ");
						$row1 = mysqli_fetch_assoc($query);
						$b_name  = "'D 13th Smile Dental Clinic (".strtoupper($row1['location']).")";					

						$notif = "Hi $p_name, this is a reminder for your appointment at $b_name on $_appointment_date Please be remindful that this is a first come first serve basis. Please check your account for updates or any changes to stay informed. See you soon! - $b_name";

						$stmt2=$conn->prepare("INSERT INTO notifications (patient_id,message,status) VALUES (?,?,?) ");
						$stmt2->bind_param('sss',$id,$notif,$st);
						$stmt2->execute();
						
						$message = 'Appointment created successfully';

						if($_SESSION['position'] == 'assistant'){
							$query = "SELECT * FROM assistant WHERE assistant_id = ?";
							$stmt=$conn->prepare($query);
							$stmt->bind_param('s',$_SESSION['id']);
							$stmt->execute();
							$result=$stmt->get_result();
							if($row=$result->fetch_assoc()){
								$date = date('Y-m-d');
								$transaction = "Assistant ID #$_SESSION[id] (".strtoupper($row['first_name']).")- set an appointment on patient, Patient name: $row0[first_name] $row0[last_name] on $date at".date('H:i:s A');
							}
							$status='1';
							$stmt = $conn->prepare("INSERT INTO `logs`(`transaction`, `branch_id`, `assistant_id`, `date`, `status`) VALUES (?,?,?,?,?)");
							$stmt->bind_param('sssss',$transaction, $_SESSION['designation'], $_SESSION['id'], $date,$status);
							$stmt->execute();
						}
				}
				else{
					$message = 'No Gcash payment proof is added!';
				}		
			}
		
		}
		else if($dp < 500){
			$message = 'Insufficient Down payment!';
		}
		else{
			$message = 'Your down payment is more than enough than the price!';
		}
		}
	}
	echo $message;
	//<!-- Make notification like PLEASE BRING YOUR GCASH REFERENCE NUMBER FOR VERIFICATION -->
?>
