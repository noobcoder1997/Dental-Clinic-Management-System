<?php 
    session_start();
	include_once 'config/connection.php';
	$id = stripcslashes(mysqli_real_escape_string($conn, $_POST['id']));
    // $id = $_SESSION['id'];
    $position = $_SESSION['position'];	
    $stmt = mysqli_stmt_init($conn);

	if(!empty($_FILES["image"]["tmp_name"])){
	// $folder_name=$_POST["image"]; 
	// $output_dir = @'photo'; 
	
		// if (!file_exists($output_dir . $folder_name))//checking if folder exist 
		// { 
		// 	@mkdir($output_dir . $folder_name, 0777);//making folder 
		// } 
		$fileinfo=PATHINFO($_FILES["image"]["name"]);
		$newFilename=$fileinfo['filename'] ."_". time() . "." . $fileinfo['extension'];
		move_uploaded_file($_FILES["image"]["tmp_name"],"profilepic/" . $newFilename);
		$location="profilepic/" . $newFilename; 

		if($position == "patient"){
			mysqli_stmt_prepare($stmt, "UPDATE register_patient SET image = ? WHERE register_id = ?");
			mysqli_stmt_bind_param($stmt, "ss", $location, $id);
			mysqli_stmt_execute($stmt);
		}
		else if($position == "assistant"){
			mysqli_stmt_prepare($stmt, "UPDATE assistant SET image = ? WHERE assistant_id = ?");
			mysqli_stmt_bind_param($stmt, "ss", $location, $id);
			mysqli_stmt_execute($stmt);
		}
        else if($position == "dentist"){
			mysqli_stmt_prepare($stmt, "UPDATE dentist SET image = ? WHERE dentist_id = ?");
			mysqli_stmt_bind_param($stmt, "ss", $location, $id);
			mysqli_stmt_execute($stmt);
		}
        echo "<script>alert('Your Profile Picture has been changed!')</script>";
		header('location:home.php');
	}
	else{
        echo'error';
		header('location:home.php');
	}
?>