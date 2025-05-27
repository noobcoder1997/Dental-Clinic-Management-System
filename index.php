<?php
session_start(); // Start the session

include('config/connection.php'); // Include the connection file 

if(isset($_SESSION['user_status'])){
  header('location: home.php');
}
else if(isset($_SESSION['select_branch'])){
  header('location: select-branch.php');
}
else if(isset($_SESSION['register'])){
  header('location: register_patient.php');
}
else if(isset($_SESSION['forgot_pass'])){
  header('location: forgot-password.php');
}

?>
<?php

$message='';

// if(isset($_POST['login-button'])){
//   $username = htmlentities(stripslashes(mysqli_real_escape_string($conn, $_POST['username'])));
//   $password = htmlentities(stripslashes(mysqli_real_escape_string($conn, md5(trim($_POST['password']))))); 
//   // Prepare the SQL query to check the user credentials
//   $stmt0 = $conn->prepare("SELECT * FROM register_patient WHERE username=? AND password = ? ");
//   $stmt0->bind_param('s', $username);
//   $stmt0->execute(); // Execute query
//   // Get the result
//   $result0 = $stmt0->get_result();

//   // Fetch the result Check if user exists
//   if ($row = $result0->fetch_Array(MYSQLI_ASSOC)) {
//       // Set session variables
//           $_SESSION['id'] = $row['register_id'];
//           // $_SESSION['username'] = $username;
//           $_SESSION['position'] = $row['position'];
//           // if($row['postion'] == 'assistant')
//           // $_SESSION['designation'] = $row['designation'];
//           // $_SESSION['patient_full_name'] = $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name'];
//           $_SESSION['user_status'] = 1;

//           // $message = 1;'<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Successfully Login!</div>';
//           header('location: home.php');
//           exit();
//       else{
//         $message=1;
//       }
//   }
//   $stmt1 = $conn->prepare("SELECT * FROM assistant WHERE username=?");
//   $stmt1->bind_param('s', $username);
//   $stmt1->execute(); // Execute query
//   $result1 = $stmt1->get_result();

//   if ($row = $result1->fetch_Array(MYSQLI_ASSOC)) {
//     if(password_verify($password, $row['password'])){
//     // Set session variables
//         $_SESSION['id'] = $row['assistant_id'];
//         // $_SESSION['username'] = $username;
//         $_SESSION['position'] = $row['position'];
//         // $_SESSION['patient_full_name'] = $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name'];
//         $_SESSION['user_status'] = 1;

//         // $message = 1;'<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Successfully Login!</div>';
//         header('location: home.php');
//         exit();
//     }
//     else{
//       $message=1;
//     }
//   }
//   $stmt2 = $conn->prepare("SELECT * FROM dentist WHERE username=?");
//   $stmt2->bind_param('s', $username); // Bind parameters
//   $stmt2->execute(); // Execute query
//   $result2 = $stmt2->get_result();

//   if ($row = $result2->fetch_Array(MYSQLI_ASSOC)) {
//     if(password_verify($password, $row['password'])){
//     // Set session variables
//         $_SESSION['id'] = $row['dentist_id'];
//         // $_SESSION['username'] = $username;
//         $_SESSION['position'] = $row['position'];
//         // $_SESSION['patient_full_name'] = $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name'];
//         $_SESSION['user_status'] = 1;

//         // $message = 1;'<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Successfully Login!</div>';
//         header('location: home.php');
//         exit();
//     }
//     else{
//       $message=1;
//     }
//   }
//   if(isset($message)){
//     $_SESSION['error']='Incorrect Username or Password!';
//   } 
// }

// $stmt->close();
// $conn->close(); 
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dental Clinic | Login</title>

  <!-- Font Awesome -->
  <!-- <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css"> -->
  <!-- Bootstrap 4 -->

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <style>
    /* Global Styles */
    body {
      font-family: 'Poppins', sans-serif;
      display: flex;
      height: 100vh;
      justify-content: center;
      align-items: center;
      background: linear-gradient(135deg, #0056b3, #007bff); /* Blue gradient */
      position: relative;
    }

    /* Background Image with Light Blur */
    body::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100vh;
      background-image: url('assets/dist/img/clinic.jpg'); /* Replace with your image's relative path */
      background-size: cover;
      background-position: center;
      filter: blur(3px); /* Lighter blur effect */
      z-index: -1; /* Place the background behind all content */
      overflow: auto;
    }

    @media screen and (max-width: 531px) {
      body { 
        height: 100%;
      }
      body::before { 
        height: 100%;
        margin-bottom:20px;
      }
      .container-fluid, .main-container{
        margin: 12px 0px 12px 0px;
        padding: 0px;
      }
      .carousel-container{
       width: 100%;
      }
      .login-panel, .carousel-container{
        margin: 0px;
      } 
    }

    .main-container {
      margin: auto;
      display: flex;
      width: 100%;
      max-width: 100%;
      height: 100%;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
      background: rgba(255, 255, 255, 0.9); /*  Slightly transparent white */
    }

    .carousel-container {
      width: 100%;
      height: 100%;
      /* background: rgba(255, 255, 255, 0.9); */
      display: absolute;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      position: relative;
      margin: 20px;
    }

    .carousel-inner {
      display: flex;
      transition: transform 0.6s ease-in-out;
    }

    .carousel-item {
      display: none; /* Hide all items initially */
      flex: 0 0 100%;
      align-items: center;
      justify-content: center;
    }

    .carousel-item.active {
      display: flex; /* Show only the active item */
      flex-direction: column;
      justify-content: center;
    }

    .d-block{
      max-width: 100%;
      margin: auto;
      width: 100%; /* Make the image fill the container width */
      height: 85vh; /* Adjust the height automatically to maintain aspect ratio */
      object-fit: fill;  /*Ensure the image is fully visible and scaled correctly */
      max-height: 100%; /* Limit the height to a fixed value */
      /* border-radius: 10px; */
      /* box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); */
    }

    .carousel-caption {
      color: #5a67d8;
      font-size: 1.2rem;
      margin-top: 10px;
      font-weight: 500;
      text-align: center;
      width: 100%; /* Ensure caption takes full width */
      position: absolute;
      bottom: 10px; /* Align caption to the bottom of the image */
      left: 0;
    }

    #carouselExampleIndicators {
      background: transparent;
      margin: auto;
      display: flex;
      align-items: center;
      justify-content: center;
    }

        .carousel-control-prev-icon,
    .carousel-control-next-icon {
      height: 100px;
      width: 100px;
      outline: black;
      background-size: 100%, 100%;
      /* border-radius: 50%; */
      /* border: 1px solid black; */
      background-image: none;
    }

    .carousel-control-next-icon:after
    {
      content: '>';
      font-size: 55px;
      color: #9640b0;
    }

    .carousel-control-prev-icon:after {
      content: '<';
      font-size: 55px;
      color: #9640b0;
    }

    .carousel-control-prev .btn:hover, .carousel-control-next .btn:hover {
      color: #9640b0;
    }

    .login-panel {
      width: 100%;
      padding: 30px;
      /*  background: rgba(255, 255, 255, 0.9);Slightly transparent white */
      display: flex;
      flex-direction: column;
      justify-content: center;
      /* border-radius: 0 20px 20px 0; */
    }

    .login-title {
      font-size: 2.2rem;
      color: #9640b0;
      text-align: center;
      /* font-weight: 600; */
      margin-bottom: 20px;
      margin-top: 20px;
    }

    .login-box-msg {
      color: #9640b0;
      margin-bottom: 35px;
      font-size: 1rem;
      text-align: center;
    }

    .form-control {
      border-radius: 8px;
      height: 50px;
      margin-bottom: 20px;
      border: 1px solid #ddd;
      /* padding-left: 15px; */
      transition: border-color 0.3s ease;
    }

    .form-control:focus {
      border-color: #5a67d8;
      box-shadow: 0 0 5px rgba(90, 103, 216, 0.3);
    }

    .btn-primary {
      margin-top: 27px;
      background-color: #5a67d8;
      border-color: #5a67d8;
      border-radius: 10px;
      font-size: 1.1rem;
      padding: 10px 20px;
      width: 100%;
      transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #4c51bf;
    }
    
    .forgot-password{
      position: relative;
      margin-bottom: 30px;  
      text-align: right;
    }
    .register-link{
       
      text-align: center;
    }
    .register-link,
    .forgot-password {
      color: #5a67d8;
      display: block ;
      font-size: 0.9rem;
      text-decoration: none;
    }

    .register-link:hover,
    .forgot-password:hover {
      text-decoration: underline;
    }
    .btn-primary{
      background: #9640b0;
    }
    body, html{
      color: #9640b0
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="container-fluid">
      <div class="main-container">
        <div class="col-sm-12">
        <div class="row">
          <div class="col-sm-7">
           <div class="row">
            <div class="carousel-container">
              <div id="carouselExampleIndicators" class="carousel slide" data-interval="2000" data-ride="carousel">
                  <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                  </ol>
                  <div class="carousel-inner">
                    <div class="carousel-item active">
                      <img class="d-block w-100" src="photo/l.jpg" alt="First slide">
                      <div class="carousel-caption">Before and After of composite filling</div>
                    </div>
                    <div class="carousel-item">
                      <img class="d-block w-100" src="photo/m.jpg" alt="Second slide">
                      <div class="carousel-caption">Achievement 2: Award for Innovation</div>
                    </div>
                    <div class="carousel-item">
                      <img class="d-block w-100" src="photo/n.jpg" alt="Third slide">
                      <div class="carousel-caption">Achievement 3: Leading in Technology</div>
                    </div>
                  </div>
                  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                  </a>
                  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                  </a>
                </div>
            </div>
          </div>
          </div>
          <div class="col-sm-5" >
            <div class="row">
              <div class="login-panel">
                <div class="login-title">Dental Clinic System</div>
                <p class="login-box-msg">Sign in to start your session</p>
                  
                    <!-- <form id="" > -->
                      <div class="form-group">
                        <span id="login-alert-message"></span>
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                      </div>
                        <div class="form-group">
                          <button type="button" class="btn btn-primary" id="" onclick="login()">
                          <span class="fas fa-tooth"></span> Sign In
                        </button>
                      <!-- </div> -->
                      </div>
                    
                  <!-- </form> -->
                  <div class="form-group">
                    <a onclick="forgotPass()" class="forgot-password">I forgot my password</a>
                    <a onclick="register()" class="register-link">New here? Register now</a>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  <!-- <div class="main-container"> -->
    <!-- <div class="carousel-container">
      <div class="carousel-inner" id="carouselImages">
        <div class="carousel-item active">
          <img src="assets/dist/img/dentalfilling.png" alt="Achievement 1">
          <div class="carousel-caption">Before and After of composite filling</div>
        </div>
        <div class="carousel-item">
          <img src="https://via.placeholder.com/600x400?text=Achievement+2" alt="Achievement 2">
          <div class="carousel-caption">Achievement 2: Award for Innovation</div>
        </div>
        <div class="carousel-item">
          <img src="https://via.placeholder.com/600x400?text=Achievement+3" alt="Achievement 3">
          <div class="carousel-caption">Achievement 3: Leading in Technology</div>
        </div>
      </div>
      <div class="carousel-buttons">
        <button class="btn" id="prevBtn">&#10094;</button>
        <button class="btn" id="nextBtn">&#10095;</button>
      </div>
    </div> -->
    

    <!-- <div class="login-panel">
      <div class="login-title">Dental Clinic System</div>
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="patient_dashboard.php" method="post">
        <div class="input-group">
          <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
        <div class="input-group">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-tooth"></i> Sign In
        </button>
      </form>

      <a href="forgot-password.html" class="forgot-password">I forgot my password</a>
      <a href="register_patient.php" class="register-link">New here? Register now</a>
    </div>
  </div> -->

  <!-- jQuery and Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
</body>
</html>
<script>
      function forgotPass(){
        $.ajax({
          url: 'forgot-pass.php',
        }).done( function(){
          location.reload();
          location.href='forgot-password.php';
        })
      }
      function register(){
        $.ajax({
          url: 'patient-register.php',
        }).done( function(){
          location.reload();
          location.href='register_patient.php';
        })
      }

      function login(){
        form=new FormData();
        form.append('username',$('[name=username]').val());
        form.append('password',$('[name=password').val());
        $.ajax({
          method: 'post',
          data: form,
          url:'login.php',
          cache:false,
          contentType:false,
          processData:false,
        }).done( function(data){
          // console.log(data);
          $('#login-alert-message').html(data);
          if(data === '<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Successfully Login!</div>'){
            location.reload();           
            location.href='home.php';
          }
          else if($('#position').val() == 'dentist'){
            $('#login-alert-message').html(`<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Successfully Login!</div>`)
            setTimeout(
              branchLoaded
              , 1000 //miliseconds
            )  
            function branchLoaded(){
              location.reload();
              location.replace('select-branch.php');
            }      
          }
        });
      }
    </script> 