<?php
  include('config/connection.php');
  session_start();
  if(!isset($_SESSION['user_status'])){
    header('location: index.php');
  }
  else if(isset($_SESSION['select_branch'])){
    header('location: select-branch.php');
  }
  require_once __DIR__.'/vendor/autoload.php';
  use Infobip\Configuration;
  use  Infobip\Api\SmsApi;
  use  Infobip\Model\SmsDestination;
  use  Infobip\Model\SmsTextualMessage;
  use  Infobip\Model\SmsAdvancedTextualRequest;
  use Infobip\Resources\SMS\Models\Destination;
  $id = $_SESSION['id'];
  $position = $_SESSION['position'];
  $designation;
  $branch;
  $name;
  $image;

  $stmt0 = $conn->prepare('SELECT * FROM assistant WHERE assistant_id = ? AND position = ? ');
  $stmt0->bind_param('ss',$id,$position);
  $stmt0->execute();
  $result0= $stmt0->get_result();
  if($row = $result0->fetch_Array(MYSQLI_ASSOC)){
    $name = $row['first_name'].' '.$row['last_name'];
    $image = $row['image'];
    $designation = $_SESSION['designation'];
  }
  $stmt1 = $conn->prepare('SELECT * FROM register_patient WHERE register_id = ? AND position = ? ');
  $stmt1->bind_param('ss',$id,$position);
  $stmt1->execute();
  $result1= $stmt1->get_result();
  if($row = $result1->fetch_Array(MYSQLI_ASSOC)){
    $name = $row['first_name']." ". $row['last_name'];
    $image = $row['image'];
  }
  $stmt2 = $conn->prepare('SELECT * FROM  dentist WHERE dentist_id = ? AND position = ? ');
  $stmt2->bind_param('ss',$id,$position);
  $stmt2->execute();
  $result2= $stmt2->get_result();
  if($row = $result2->fetch_Array(MYSQLI_ASSOC)){
    $name = $row['first_name']." ". $row['last_name'];
    $image = $row['image'];
    $branch = $_SESSION['branch'];
  }

  $dash_board = "patientDashboard();dashboard0();dashboard1();";


  // try{

    $status1 = "Approved";
    $branch="";
    $appointment_date="";
    $sms = '0';
    
    if($position == 'patient'){
      
      $stmt=$conn->prepare("SELECT * FROM appointment WHERE patient_id = ? AND status = ? AND sms = ?");
      $stmt->bind_param('sss', $id, $status1, $sms);
      $stmt->execute();
      $result=$stmt->get_result();
      while($row=$result->fetch_assoc()){

        $tomorrow = date("Y-m-d", strtotime("+1 day"));
        if($tomorrow == $row['appointment_date']){
          
          $time_input = strtotime($row['appointment_date']);
          $date = getDate($time_input);
          $mDay = $date['mday'];
          $Month = $date['month'];
          $Year = $date['year'];
          $appointment_date = $Month.' '.$mDay.', '.$Year;
    
          $_stmt=$conn->prepare("SELECT * FROM branch WHERE branch_id = ?");
          $_stmt->bind_param('s', $row['branch_id']);
          $_stmt->execute();
          $_result=$_stmt->get_result();
          if($_row=$_result->fetch_assoc()){
            $branch = $_row['location'];
    
            $current = strtotime(date('Y-m-d'));
            $date = strtotime($row['appointment_date']);
    
            $datediff = $date - $current;
            $difference = floor($datediff/(60*60*24));
    
            if($difference > 0){

              $apikey = 'a2473fa68bb306ea4ef450c9de65722f-fcf1a136-5cf6-4058-a3ce-a4e279200905';
              $host = '9kd91v.api.infobip.com';
              $number = "+639659616583";
              $_message = "Good day, you have an appointment tomorrow at 'D 13th Smile Dental Clinic-$branch. See you soon!";
      
              $configuration = new Configuration(host: $host, apiKey: $apikey);
      
              $api = new SmsApi(config: $configuration);
      
              $destination = new SmsDestination(to: $number);
      
              $message = new SmsTextualMessage(
                  destinations: [$destination],
                  text: $_message
              );
      
              $req = new SmsAdvancedTextualRequest(messages:[$message]);
              
              $res = $api->sendSmsMessage($req);
              $sms="1";
              $stmt0=$conn->prepare("UPDATE appointment SET sms = ? WHERE patient_id = ? AND status = ? AND appointment_date = ? ");
              $stmt0->bind_param('ssss', $sms, $id, $status1, $tomorrow);
              $stmt0->execute();
            }
          }
        }
      }
      $st="1";
      $date=date('Y-m-d');
      $status="Approved";
      $sms="0";

      $stmt_0 = $conn->prepare("SELECT * FROM appointment WHERE status = ? AND appointment_date = ? AND sms = ? AND patient_id = ? ");
      $stmt_0->bind_param('ssss',$status,$date,$sms,$id);
      $stmt_0->execute();
      $result_0=$stmt_0->get_result();
      $row_0=$result_0->fetch_assoc();
      if(mysqli_num_rows($result_0) > 0){
        $time_input = strtotime($row_0['appointment_date']);
        $date = getDate($time_input);
        $mDay = $date['mday'];
        $Month = $date['month'];
        $Year = $date['year'];
        $appointment_date = $Month.' '.$mDay.', '.$Year;
        
        $stmt_1 = $conn->prepare("SELECT * FROM branch WHERE branch_id = ? ");
        $stmt_1->bind_param('s',$row_0['branch_id']);
        $stmt_1->execute();
        $result_1=$stmt_1->get_result();
        $row_1=$result_1->fetch_assoc();
        $b_name  = "'D 13th Smile Dental Clinic (".strtoupper($row_1['location']).")";
          
        $stmt0 = $conn->prepare("SELECT * FROM register_patient WHERE register_id = ?");
        $stmt0->bind_param('s',$id);
        $stmt0->execute();
        $result0=$stmt0->get_result();
        $row0=$result0->fetch_assoc();
        
        $contact = $row0['contact_no'];
        $contact = ltrim($contact, $contact[0]);
        $p_name = strtoupper($row0['first_name']);						

        $notif = "Hi $p_name, this is a reminder for your appointment today $appointment_date at $b_name.Please be remindful that this is a first come first serve basis. Please check your account for updates or any changes to stay informed. See you soon! - $b_name";

        $stmt2=$conn->prepare("INSERT INTO notifications (patient_id,message,status) VALUES (?,?,?) ");
        $stmt2->bind_param('sss',$id,$notif,$st);
        $stmt2->execute();

        $sms="1";
        $stmt_0 = $conn->prepare("UPDATE appointment SET  sms = ? WHERE status = ? AND appointment_date = ? AND patient_id = ? ");
        $stmt_0->bind_param('ssss',$sms,$status,$row_0['appointment_date'],$id);
        $stmt_0->execute();
      }
    }
  // }
  // catch(Exception $e){
  //   echo $e->getMessage();
  // }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DENTAL CLINIC SYSTEM | <?php echo strtoupper($position) ?></title>
    <link rel="icon" href="photo/logo_circle.png">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <!-- <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css"> -->
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <!-- <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css"> -->
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <!-- <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css"> -->
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <!-- <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css"> -->
    
    <!-- DataTables -->
    <link rel="stylesheet" href="dist/css/dataTables.dataTables.min.css"> 
    <!-- DataTables -->
    <!-- <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css"> -->

    
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->

    <!-- <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> -->
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    
  </head>
  <style>
    ::-webkit-scrollbar {
      width: 5px;
    }
    /* ::-webkit-scrollbar-button {
        background: #9640b0
    } */
    ::-webkit-scrollbar-track-piece {
        background: #fff
    }
    ::-webkit-scrollbar-thumb {
        background: #9640b0
    }

    .modal{
      overflow-y: auto;
    }
    #log-out{
      display: none;
    }
    .patient-appointments, .patient-health-records{
      display: none;
    } 
    @media screen and (min-width: 100px) and (max-width: 531px)
    {
      #assistant-view-proof
      {
          width: 100%;
          height: 100%;
      }
      #log-out{
        display: block;
        border-radius: 0;
      }
      #nav-log-out{
        display: none; 
      } 
      .btn-group{
        width: 100%;
      }
    }
    .ui-datepicker-calendar{
    }
    
    .ui-datepicker-header,.ui-datepicker-current {
      background-color: #9640b0; 
      color:#fff   
    }
    
    input[type="time"]::-webkit-calendar-picker-indicator {
        display: none;
        -webkit-appearance: none;
    }
    .calendar-background .ui-state-default{
      background-color: #9640b0;
      color: #fff;
    }
    .calendar-color .ui-state-default{
      background-color: #9640b0;
      color: red;
    }
    table.dataTable thead thead td tr {
      border: none;
    }
    nav, ul, li, .sidebar, aside{
      overflow-x: hidden;
    }
    .logo {
        display: block;
        margin: 20px;
        box-shadow: 0px 0px 20px 3px gray;
        width: 130px;
        height: 130px;
        border-radius: 50%;
      }
    .btn-secondary, .bg-secondary, .gcash, .alert-secondary{
      /* background: #a981b5; */
      background: #9640b0;
    }
    .main-header, .main-footer{
      background: #9640b0;
      /* background: #a981b5; */
    }
    .text-header:after{
      <?php 
        if($position != 'patient'){
          $location = isset($_SESSION['designation']) ? strtoupper($_SESSION['designation']): strtoupper($_SESSION['branch']); 
          $stmt=$conn->prepare("SELECT * FROM branch WHERE branch_id = ? ");
          $stmt->bind_param('s', $location);
          $stmt->execute();
          $result=$stmt->get_result();
          while($r = $result->fetch_assoc()){
            ?>
              content: "'D 13th Smile Dental Clinic Management System-<?php echo $r['location']; ?>";color: #fff;font-weight: 500;
            <?php
          }
        }
        else{
          ?>content: "'D 13th Smile Dental Clinic Management System";color: #fff;font-weight: 500; <?php
        }
      ?> 
    }
    .log-out{
      display: flex; 
      justify-content: flex-end;
      top: 5;
      right:0;
      position: absolute;
    }
    #nav-log-out{
      color: #fff;
      font-weight: 500;
      cursor: default;
    }
    @media only screen and (max-width: 768px) {
      .text-header:after{
          <?php 
            if($position != 'patient'){
              $location = isset($_SESSION['designation']) ? strtoupper($_SESSION['designation']): strtoupper($_SESSION['branch']); 
              $stmt=$conn->prepare("SELECT * FROM branch WHERE branch_id = ? ");
              $stmt->bind_param('s', $location);
              $stmt->execute();
              $result=$stmt->get_result();
              while($r = $result->fetch_assoc()){
                ?>
                  content: "'D 13th Smile Dental Clinic-<?php echo $r['location']; ?>";color: #fff;font-weight: 500;
                <?php
              }
            }
            else{
              ?>content: "'D 13th Smile Dental Clinic";color: #fff;font-weight: 500; <?php
            }
        ?> 
      }
      .text-header{
        padding-left:0;
        margin:0;
        cursor: default;
        width: 115%;
        font-size:  16px;
      }    
      ::-webkit-scrollbar-track-piece {
        background: #9640b0
      } 
      #clinic-logo{
        padding:5px;
      }
      .header{
        overflow-x: auto;
      }
      .btn-secondary,.btn-default{
        width: 100%; 
        display:block; 
      }
      .btn-default{
        margin-bottom: 5px;
        padding:1px;
      } 
      .patient-view-appointment-table , .patient-view-health-record-table{
        display: none;
      }
      .patient-appointments, .patient-health-records{
          display: block;
      } 
    }
    .activeTab{
      text-decoration: none !important;
      color: #fff !important;
      background: #9640b0 !important;
    }
    .card{
      /* box-shadow: 0 0 1px #A9A9A9; */
    }
    body, html, .sidebar, .main-sidebar, .modal,
    #btn1, #btn2, #btn3, #btn4, #btn5, #btn6, #btn7, 
    #btn8, #btn9, #btn10, #btn11, #btn12, #btn13, #btn14, 
    #btn15, #btn16, #btn17, #btn18, #btn19, #btn20, #btn21, 
    #btn22, #btn23, #btn24, #btn25, #btn26, #btn27, #btn28, #btn29, #btn30 { 
      color: #9640b0;
    }
    .d-block{
      word-wrap: break-word; 
    }
    .info, .notif{
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
    }
    #btn{
      bottom: 0;
      align-self: flex-end; 
      position: fixed; 
      margin-bottom:15px; 
      border-top: 1px solid lightgrey;
      padding-top:15px;
      padding-bottom:15px;
      border-radius: 0;
    }
    body{
      overflow-x: hidden;
    }
    input{
      color:#9640b0
    }
    #service_img{
      width: 100%;
      /* height: 50vh; */
    }
    html, section, table{
      cursor:default;
    }
    select.dt-input{
      margin-right:5px;
    }
  </style>
  <body class="hold-transition sidebar-mini layout-fixed" onload="<?php echo $dash_board?>">
    <div class="wrapper">

      <!-- Preloader -->
      <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="photo/logo_circle.png" alt="android" height="60" width="60">
      </div>

      <!-- Navbar -->
      <nav class="main-header navbar navbar-expand navbar-white navbar-light header" >
        <!-- Left navbar links -->
        <ul class="navbar-nav" >
          <li class="nav-item">
            <!-- <a class="nav-link"  href="#" > -->
              <img src="photo/logo_circle.png" alt="" style="width:44px; height:44px; object-fit: contain;position: flex; margin-left: 10px;" role="button" id="clinic-logo">
            <!-- </a> -->
          </li class="nav-item">
          <li style="overflow-x:auto;">
              <a href="" class="nav-link text-header" ></a>
          </li>
          <li class="nav-item log-out ">
            <a data-toggle="modal" data-target="#logout" class="nav-link float-right" id="nav-log-out">
              <span class="fas fa-sign-out-alt fa-fw"></span>
              Sign out
            </a>
          </li>
          <!-- <li class="nav-item d-none d-sm-inline-block">
            <a href="index3.html" class="nav-link">Home</a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
          </li> -->
        </ul>


          <!-- Notifications Dropdown Menu -->
          <!-- <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
              <i class="far fa-bell"></i>
              <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
              <span class="dropdown-item dropdown-header">15 Notifications</span>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <i class="fas fa-envelope mr-2"></i> 4 new messages
                <span class="float-right text-muted text-sm">3 mins</span>
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <i class="fas fa-users mr-2"></i> 8 friend requests
                <span class="float-right text-muted text-sm">12 hours</span>
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <i class="fas fa-file mr-2"></i> 3 new reports
                <span class="float-right text-muted text-sm">2 days</span>
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
          </li> -->
          <!-- <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
              <i class="fas fa-expand-arrows-alt"></i>
            </a>
          </li> -->
          <!-- <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
              <i class="fas fa-th-large"></i>
            </a>
          </li> -->
        </ul>
      </nav>
      <!-- /.navbar -->

      <!-- Main Sidebar Container -->
      <aside class="main-sidebar sidebar-light-primary elevation-4 main">
          <!-- Brand Logo -->
          <!-- <a href="index3.html" class="brand-link"> -->
            <!-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">AdminLTE 3</span> -->
          <!-- </a> -->

        <!-- Sidebar -->
        <div class="sidebar">
          <!-- Sidebar user panel (optional) -->
          <div class="mt-3 pb-3 d-flex">
            <div class="image">
              <a href="" data-toggle="modal" data-target="#imageModal">
                <?php
                  if($image == "" or $image == "None" or $image == 'profilepic/default.jpg'){
                ?>
                  <img src="profilepic/default.jpg" class="img-circle elevation-2" alt="Profile Photo" style="width:44px; height:44px; object-fit: fill; position: flex; margin-left: 10px;">
                <?php
                  }else{
                ?>
                  <img src="<?php print_r($image);?>" class="img-circle elevation-2" alt="Profile Photo" style="width:44px; height:44px; object-fit: fill; position: flex; margin-left: 10px;">
                <?php
                  }
                ?>
                <!-- height="160px;" width="160px;" style="box-shadow: 0 0 15px #A9A9A9;object-fit: cover;"*/ -->
              </a>
            </div>
            <div class="info ml-3">
              <div href="#" class="d-block" role="button">
                  <?php
                    echo strtoupper($name);
                  ?>
              </div>
            </div>
          </div>

          <hr class="my-0">
          <!-- SidebarSearch Form -->
          <!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
              <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-sidebar">
                  <i class="fas fa-search fa-fw"></i>
                </button>
              </div>
            </div>
          </div> -->

          <!-- Sidebar Menu -->
          <nav class="mt-2">
              <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                    with font-awesome or any other icon font library -->
                <?php 
                  if($position == 'patient'){
                ?>
                  <li class="nav-item">
                    <a href="#patient" onclick="patientDashboard()" class="nav-link" id="btn1">
                      <i class="fas fa-briefcase-medical nav-icon"></i>
                      <p>Services Offered</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#patient" onclick="myInformation()" class="nav-link" id="btn1">
                      <i class="fas fa-user-alt nav-icon"></i>
                      <p>My Information</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#patient" onclick="healthRecord()" class="nav-link" id="btn2">
                      <i class="far fa-file nav-icon"></i>
                      <p>Health Record</p>
                    </a>
                  </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon far fa-calendar-alt"></i>
                    <p>
                      Appointments
                      <i class="fas fa-angle-left right"></i>
                      <!-- <span class="badge badge-info right">6</span> -->
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="#patient" onclick="setAppointment()" class="nav-link" id="btn3">
                        <i class="far fa-calendar-plus nav-icon"></i>
                        <p>Set an Appointment</p>
                      </a>
                    </li>
                    <li class="nav-item">
                    <a href="#patient" onclick="viewAppointment()" class="nav-link" id="btn4">
                      <i class="fas fa-calendar-check nav-icon"></i>
                      <p>View Appointments</p>
                    </a>
                  </li>
                  </ul>
                </li>
                  <li class="nav-item">
                    <a href="#patient" onclick="notifications()" class="nav-link" id="btn5">
                      <i class="fa fa-list nav-icon"></i>
                      <p id="notifications"></p>
                      <?php 
                        // $st='1';
                        // $stmt=$conn->prepare("SELECT COUNT(*) AS id FROM notifications WHERE patient_id = ? AND status = ? ");
                        // $stmt->bind_param('ss',$id,$st);
                        // $stmt->execute();
                        // $result=$stmt->get_result();
                        // while($row=$result->fetch_assoc()){
                        //   if($row['id'] != 0){
                        //     echo '<p>Notifications </p><span class="right badge badge-info" id="notif-count"><b>'.$row['id'].'</b></span>';
                        //   }
                        //   else{
                        //     echo '<p>Notifications</p>';
                        //   }
                        // }
                      ?>
                    </a>
                  </li>
                <?php
                  }
                  if($position == "assistant"){
                ?>
                  <li class="nav-item">
                    <a href="#assistant" onclick="dashboard0()" class="nav-link" id="btn1">
                      <i class="fa fa-dashboard nav-icon"></i>
                      <p>Dashboard</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#assistant" onclick="myAssistantInformation()" class="nav-link" id="btn1">
                      <i class="fas fa-user-alt nav-icon"></i>
                      <p>My Information</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="fas fa-users nav-icon"></i>
                      <p>
                        Patients
                        <i class="fas fa-angle-left right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      <li class="nav-item">
                        <a href="#assistant" onclick="assistantRegisterPatient()" class="nav-link" id="btn4">
                          <i class="fas fa-user-plus nav-icon"></i>
                          <p>Register a Patient</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#assistant" onclick="assistantViewAppointment()" class="nav-link" id="btn3">
                          <i class="fas fa-calendar-check nav-icon"></i>
                          <p>Pending Appointments</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#assistant" onclick="assistantPatientApprovedRecords()" class="nav-link" id="btn2">
                          <i class="fas fa-file-medical-alt nav-icon"></i>
                          <p>Approved Appointments</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#assistant" onclick="assistantPatientDeclinedRecords()" class="nav-link" id="btn5">
                          <i class="fas fa-file-alt nav-icon"></i>
                          <p>Declined Appointments</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#assistant" onclick="assistantPatientRecords()" class="nav-link" id="btn5">
                          <i class="fas fa-file-alt nav-icon"></i>
                          <p>Patient Records</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-cog"></i>
                      <p>
                        Settings
                        <i class="fas fa-angle-left right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      <li class="nav-item">
                        <a href="#assistant" onclick="assistantAddServices()" class="nav-link" id="btn6">
                          <i class="fas fa-briefcase-medical nav-icon"></i>
                          <p>Services</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#assistant" onclick="assistantAddSchedule()" class="nav-link" id="btn7">
                          <i class="fa fa-calendar nav-icon"></i>
                          <p>Dentist Schedule</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#assistant" onclick="assistantAddBranch()" class="nav-link" id="btn8">
                          <i class="fas fa-clinic-medical nav-icon"></i>
                          <p>Branches</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#assistant" onclick="assistantAddProduct()" class="nav-link" id="btn9">
                          <i class="fas fa-shopping-bag nav-icon"></i>
                          <p>Products</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#assistant" onclick="assistantAddTools()" class="nav-link" id="btn10">
                          <i class="far fas fa-syringe nav-icon"></i>
                          <p>Tools</p>
                        </a>
                      </li>
                    </ul>
                  </li> 
                  <li class="nav-item">
                    <a href="#assistant" onclick="assistantDelivery()" class="nav-link" id="btn11">
                      <i class="fas fa-truck nav-icon"></i>
                      <p>Deliveries</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#assistant" onclick="assistantDamageProducts()" class="nav-link" id="btn7">
                      <i class="fas fa-file-excel nav-icon"></i>
                      <p>Damage Items</p>
                    </a>
                  </li> 
                  <li class="nav-item">
                    <a href="#assistant" onclick="assistantInventory()" class="nav-link" id="btn11">
                      <i class="fas fa-align-right nav-icon"></i>
                      <p>Inventory</p>
                    </a>
                  </li>
                  <li class="nav-item">
                      <a href="#assistant" onclick="assistantOnBilling()" class="nav-link" id="btn11">
                        <i class="fas fa-coins nav-icon"></i>
                        <p>Billing</p>
                      </a>
                    </li>
                <?php
                  }
                  if($position == 'dentist'){
                ?>
                  <li class="nav-item">
                    <a href="#dentist" onclick="dashboard1()" class="nav-link" id="btn1">
                      <i class="fa fa-dashboard nav-icon"></i>
                      <p>Dashboard</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#dentist" onclick="myDentistInformation()" class="nav-link" id="btn1">
                      <i class="fas fa-user-alt nav-icon"></i>
                      <p>My Information</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-users"></i>
                      <p>
                        Patients
                        <i class="fas fa-angle-left right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      <li class="nav-item">
                        <a href="#dentist" onclick="dentistRegisterPatient()" class="nav-link" id="btn4">
                          <i class="fas fa-user-plus nav-icon"></i>
                          <p>Register a Patient</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#dentist" onclick="dentistViewAppointment()" class="nav-link" id="btn3">
                          <i class="fas fa-calendar-check nav-icon"></i>
                          <p>Pending Appointments</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#dentist" onclick="dentistPatientApprovedRecords()" class="nav-link" id="btn2">
                          <i class="fas fa-file-medical-alt nav-icon"></i>
                          <p>Approved Appointments</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#dentist" onclick="dentistPatientDeclinedRecords()" class="nav-link" id="btn5">
                          <i class="fas fa-file-alt nav-icon"></i>
                          <p>Declined Records</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#dentist" onclick="dentistPatientRecords()" class="nav-link" id="btn2">
                          <i class="fas fa-file-medical-alt nav-icon"></i>
                          <p>Patient Records</p>
                        </a>
                      </li>
                    </ul>
                  </li> 
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-cog"></i>
                      <p>
                        Settings
                        <i class="fas fa-angle-left right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      <li class="nav-item">
                        <a href="#dentist" onclick="addDentistService()" class="nav-link" id="btn6">
                          <i class="fas fa-briefcase-medical nav-icon"></i>
                          <p>Services</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#dentist" onclick="dentistAddSchedule()" class="nav-link" id="btn7">
                          <i class="fa fa-calendar nav-icon"></i>
                          <p>Dentist Schedule</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#dentist" onclick="addDentistDentist()" class="nav-link" id="btn7">
                          <i class="fas fa-user-plus nav-icon"></i>
                          <p>Dentists</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#dentist" onclick="addDentistBranch()" class="nav-link" id="btn8">
                          <i class="fas fa-clinic-medical nav-icon"></i>
                          <p>Branches</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#dentist" onclick="addDentistAssistant()" class="nav-link" id="btn9">
                          <i class="fas fa-user-plus nav-icon"></i>
                          <p>Assistants</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#dentist" onclick="addDentistProduct()" class="nav-link" id="btn10">
                          <i class="fas fa-shopping-bag nav-icon"></i>
                          <p>Products</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#dentist" onclick="addDentistTools()" class="nav-link" id="btn11">
                          <i class="fas fa-syringe nav-icon"></i>
                          <p>Tools</p>
                        </a>
                      </li>
                    </ul>
                  </li> 
                  <li class="nav-item">
                    <a href="#dentist" onclick="dentistDelivery()" class="nav-link" id="btn11">
                      <i class="fas fa-truck nav-icon"></i>
                      <p>Deliveries</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#dentist" onclick="dentistDamageProducts()" class="nav-link" id="btn7">
                      <i class="fas fa-file-excel nav-icon"></i>
                      <p>Damage Items</p>
                    </a>
                  </li> 
                  <li class="nav-item">
                    <a href="#dentist" onclick="dentistInventory()" class="nav-link" id="btn11">
                      <i class="fas fa-align-right nav-icon"></i>
                      <p>Inventory</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#dentist" onclick="dentistOnBilling()" class="nav-link" id="btn11">
                      <i class="fas fa-coins nav-icon"></i>
                      <p>Billing</p>
                    </a>
                  </li> 
                  <li class="nav-item">
                    <a href="#dentist" onclick="dentistAssistantLogs()" class="nav-link" id="btn11">
                      <i class="fas fa-chalkboard-teacher nav-icon"></i>
                      <p id="logs"></p>
                    </a>
                  </li> 
                <?php
                  }
                ?>
                  <li class="nav-item" id="log-out">
                    <hr id="hr">
                    <a data-toggle="modal" data-target="#logout" class="nav-link" id="btn">
                      <i class="fas fa-sign-out-alt nav-icon"></i>
                      <p>Sign out</p>
                    </a>
                  </li>
              </ul>
            </nav>
          <!-- /.sidebar-menu -->
                  
        </div>
      <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <section class="content">

          <?php 
            if($position == 'patient'){
              ?><div id="patient"></div><?php 
            }
          ?>
          <?php 
            if($position == 'assistant'){
              ?><div id="assistant"></div><?php 
            }
          ?>
          <?php 
            if($position == 'dentist'){
              ?><div id="dentist"></div></div><?php 
            }
          ?>
          
        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->
      <footer class="main-footer">
        <!-- <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
          <b>Version</b> 3.2.0
        </div> -->
      </footer>

      <!-- Control Sidebar -->
      <!-- <aside class="control-sidebar control-sidebar-dark"> -->
      
        <!-- Control sidebar content goes here -->
      <!-- </aside> -->
      <!-- /.control-sidebar -->

    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <!-- <script src="plugins/jquery/jquery.min.js"></script> -->
    <!-- jQuery UI 1.11.4 -->
    <!-- <script src="plugins/jquery-ui/jquery-ui.min.js"></script> -->
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <!-- <script src="plugins/sparklines/sparkline.js"></script> -->
    <!-- JQVMap -->
    <!-- <script src="plugins/jqvmap/jquery.vmap.min.js"></script> -->
    <!-- <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script> -->
    <!-- jQuery Knob Chart -->
    <!-- <script src="plugins/jquery-knob/jquery.knob.min.js"></script> -->
    <!-- daterangepicker -->
    <!-- <script src="plugins/moment/moment.min.js"></script> -->
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <!-- <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script> -->
    <!-- Summernote -->
    <!-- <script src="plugins/summernote/summernote-bs4.min.js"></script> -->
    <!-- overlayScrollbars -->
    <!-- <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script> -->
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>
    <!-- DataTables  & Plugins -->
    <!-- Bootstrap 4 -->
    <!-- <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script> -->
    <!-- DataTables  & Plugins -->
    <script src="dist/css/dataTables.min.js"></script>
    <!-- <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="../../plugins/jszip/jszip.min.js"></script>
    <script src="../../plugins/pdfmake/pdfmake.min.js"></script>
    <script src="../../plugins/pdfmake/vfs_fonts.js"></script>
    <script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script> -->

    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="dist/js/demo.js"></script> -->
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->

    <!-- <script src="dist/js/pages/dashboard.js"></script> -->

  </body>
</html>

<script>
	
	$(document).ready(function() {
		$("#imageModal").on("hidden.bs.modal", function() {
			$("#imageModal input[type='file']").val('');
			var img = document.getElementById('img_content');
    		img.src = "<?php print_r($image);?>";
		});
	});

    window.addEventListener('load', function () {
      document.querySelector('input[type="file"]').addEventListener('change', function () {
        if (this.files && this.files[0]) {
          var img = document.getElementById('img_content');
          img.onload = () => {
            URL.revokeObjectURL(img.src);
          }
          img.src = URL.createObjectURL(this.files[0]);
        }
      });
    });
    
    function cancelUpload() {
    	var img = document.getElementById('img_content');
    	img.src = "<?php print_r($image);?>";
    	$('#imageModal').modal('toggle');
    }

    $("#btn,#btn1, #btn2, #btn3, #btn4, #btn5, #btn6, #btn7, #btn8, #btn9, #btn10, #btn11, #btn12, #btn13, #btn14, #btn15, #btn16, #btn17, #btn18, #btn19, #btn20, #btn30").click(function (e) {     
      $("#btn,#btn1, #btn2, #btn3, #btn4, #btn5, #btn6, #btn7, #btn8, #btn9, #btn10, #btn11, #btn12, #btn13, #btn14, #btn15, #btn16, #btn17, #btn18, #btn19, #btn20, #btn30").removeClass("activeTab"); 
      if (screen.width < 531){
        $('[data-widget="pushmenu"]').PushMenu("collapse");
      }
      $( this ).addClass("activeTab");
    });

  $(document).ready(function(){
    if (screen.width > 531){
      document.getElementById('clinic-logo').removeAttribute('data-widget');
    } 
  })
  $(document).ready(function(){
    if(screen.width < 531){
      document.getElementById('clinic-logo').setAttribute('data-widget', "pushmenu");
    }
  })
  $(' .btn-secondary').click(function(){
    $(' .btn-secondary').css('background-color', '#9640b0');
  })

  //Updates the notification tab for notifications every 1 seconds
  $(document).ready( function (){
    $('#notifications').html('Notifications');
    setInterval( function () {
      form = new FormData();
      form.append('patient',<?php echo $id; ?>)
      $.ajax({
          data: form,
          url:'notifications.php',
          type:'post',
          cache:false,
          contentType:false,
          processData:false,
      }).done(function(data){
          // console.log(data)
          $('#notifications').html('');
          $('#notifications').html(data)
      })
    },1000) 
  })
  $(document).ready( function (){
    $('#logs').html('Assistant Logs');
    setInterval( function () {
      form = new FormData();
      $.ajax({
          data: form,
          url:'assistant-logs.php',
          type:'post',
          cache:false,
          contentType:false,
          processData:false,
      }).done(function(data){
          // console.log(data)
          $('#logs').html('');
          $('#logs').html(data)
      })
    },500) 
  })

</script> 

<!-- Patient -->
<script>
  function patientDashboard(){
    $.ajax({
      type: "GET",
      url: "section-dashboard.php",
    }).done(function(data){
      $('#patient').html(data);
    });    
  }
  function healthRecord(){
    $.ajax({
      type: "GET",
      url: "section-patient-health-record.php",
    }).done(function(data){
      $('#patient').html(data);
    });
  }
  function myInformation(){
    $.ajax({
      type: "GET",
      url: "section-patient-my-information.php",
    }).done(function(data){
      $('#patient').html(data);
    });
  }
  function setAppointment(){
    $.ajax({
      type: "GET",
      url: "section-patient-set-appointment.php",
    }).done(function(data){
      $('#patient').html(data);
    });
  }
  function viewAppointment(){
    $.ajax({
      type: "GET",
      url: "section-patient-view-appointment.php",
    }).done(function(data){
      $('#patient').html(data);
    });
  }
  function patientRecords(){
    $.ajax({
      type: "GET",
      url: "section-assistant-view-appointment.php",
    }).done(function(data){
      $('#assistant').html(data);
    });
  }
  function notifications(){
    $.ajax({
      type: "GET",
      url: "section-patient-notifications.php",
    }).done(function(data){
      $('#patient').html(data);
    });
  }
</script>

<!-- Assistant -->
<script>
  function dashboard0(){
    $.ajax({
      type: "GET",
      url: "section-dashboard.php",
    }).done(function(data){
      $('#assistant').html(data);
    });
  }
  function assistantPatientDeclinedRecords(){
    $.ajax({
      type: "GET",
      url: "section-assistant-declined-appointments.php",
    }).done(function(data){
      $('#assistant').html(data);
    });
  }
  function myAssistantInformation(){
    $.ajax({
      type: "GET",
      url: "section-assistant-my-information.php",
    }).done(function(data){
      $('#assistant').html(data);
    });
  }
  function assistantPatientApprovedRecords(){
    $.ajax({
      type: "GET",
      url: "section-assistant-approved-appointments.php",
    }).done(function(data){
      $('#assistant').html(data);
    });
  }
  function assistantPatientRecords(){
    $.ajax({
      type: "GET",
      url: "section-assistant-patient-records.php",
    }).done(function(data){
      $('#assistant').html(data);
    });
  }
  function assistantRegisterPatient(){
    $.ajax({
      type: "GET",
      url: "section-assistant-register-patient.php",
    }).done(function(data){
      $('#assistant').html(data);
    });
  }
  function assistantAddBranch(){
    $.ajax({
      type: "GET",
      url: "section-assistant-add-branch.php",
    }).done(function(data){
      $('#assistant').html(data);
    });
  }
  function assistantAddDentist(){
    $.ajax({
      type: "GET",
      url: "section-assistant-add-dentist.php",
    }).done(function(data){
      $('#assistant').html(data);
    });    
  }
  function assistantAddServices(){
    $.ajax({
      type: "GET",
      url: "section-assistant-add-services.php",
    }).done(function(data){
      $('#assistant').html(data);
    });
  }
  function assistantAddProduct(){
    $.ajax({
      type: "GET",
      url: "section-assistant-add-product.php",
    }).done(function(data){
      $('#assistant').html(data);
    });
  }
  function assistantAddTools(){
    $.ajax({
      type: "GET",
      url: "section-assistant-add-tool.php",
    }).done(function(data){
      $('#assistant').html(data);
    });
  }
  function assistantOnBilling(){
    $.ajax({
      type: "GET",
      url: "section-assistant-on-billing.php",
    }).done(function(data){
      $('#assistant').html(data);
    });
  }
  function assistantViewAppointment(){
    $.ajax({
      type: "GET",
      url: "section-assistant-view-appointment.php",
    }).done(function(data){
      $('#assistant').html(data)
    });
  }
  function assistantAddSchedule(){
    $.ajax({
      type: "GET",
      url: "section-assistant-add-schedule.php",
    }).done(function(data){
      $('#assistant').html(data)
    });
  }
  function assistantDelivery(){
    $.ajax({
      type: "GET",
      url: "section-assistant-delivery.php",
    }).done(function(data){
      $('#assistant').html(data)
    });
  }
  function assistantServiceTransaction(){
    $.ajax({
      type: "GET",
      url: "section-assistant-service-transaction.php",
    }).done(function(data){
      $('#assistant').html(data)
    });
  }
  function assistantInventory(){
    $.ajax({
      type: "GET",
      url: "section-assistant-inventory.php",
    }).done(function(data){
      $('#assistant').html(data)
    });    
  }
  function assistantDamageProducts(){
    $.ajax({
      type: "GET",
      url: "section-assistant-damage-products.php",
    }).done(function(data){
      $('#assistant').html(data)
    }); 
  }
</script>

<!-- Dentist -->
<script>
  function dashboard1(){
    $.ajax({
      type: "GET",
      url: "section-dashboard.php",
    }).done(function(data){
      $('#dentist').html(data);
    });
  }
  function dentistPatientDeclinedRecords(){
    $.ajax({
      type: "GET",
      url: "section-dentist-declined-records.php",
    }).done(function(data){
      $('#dentist').html(data);
    });
  }
  function myDentistInformation(){
    $.ajax({
      type: "GET",
      url: "section-dentist-my-information.php",
    }).done(function(data){
      $('#dentist').html(data);
    });
  }
  function dentistRegisterPatient(){
    $.ajax({
      type: "GET",
      url: "section-dentist-register-patient.php",
    }).done(function(data){
      $('#dentist').html(data);
    });
  }  
  function dentistPatientApprovedRecords(){
    $.ajax({
      type: "GET",
      url: "section-assistant-approved-appointments.php",
    }).done(function(data){
      $('#dentist').html(data);
    });
  }
  function dentistPatientRecords(){
    $.ajax({
      type: "GET",
      url: "section-dentist-patient-record.php",
    }).done(function(data){
      $('#dentist').html(data);
    });
  }
  //section-dentist-view-appointment.php
  function dentistViewAppointment(){
    $.ajax({
      type: "GET",
      url: "section-dentist-view-appointment.php",
    }).done(function(data){
      $('#dentist').html(data);
    });
  }
  function addDentistService(){
    $.ajax({
      type: "GET",
      url: "section-dentist-add-services.php",
    }).done(function(data){
      $('#dentist').html(data);
    });
  }
  function addDentistAssistant(){
    $.ajax({
      type: "GET",
      url: "section-dentist-add-assistant.php",
    }).done(function(data){
      $('#dentist').html(data);
    });
  }
  function addDentistBranch(){
    $.ajax({
      type: "GET",
      url: "section-dentist-add-branch.php",
    }).done(function(data){
      $('#dentist').html(data);
    });
  }
  function addDentistDentist(){
    $.ajax({
      type: "GET",
      url: "section-dentist-add-dentist.php",
    }).done(function(data){
      $('#dentist').html(data);
    });
  }
  function addDentistProduct(){
    $.ajax({
      type: "GET",
      url: "section-dentist-add-product.php",
    }).done(function(data){
      $('#dentist').html(data);
    });
  }
  function addDentistTools(){
    $.ajax({
      type: "GET",
      url: "section-dentist-add-tool.php",
    }).done(function(data){
      $('#dentist').html(data);
    });
  }
  function dentistInventory(){
    $.ajax({
      type: "GET",
      url: "section-assistant-inventory.php",
    }).done(function(data){
      $('#dentist').html(data)
    });    
  }
  function dentistDamageProducts(){
    $.ajax({
      type: "GET",
      url: "section-assistant-damage-products.php",
    }).done(function(data){
      $('#dentist').html(data)
    }); 
  }
  function dentistOnBilling(){
    $.ajax({
      type: "GET",
      url: "section-assistant-on-billing.php",
    }).done(function(data){
      $('#dentist').html(data);
    });
  }
  function dentistDelivery(){
    $.ajax({
      type: "GET",
      url: "section-assistant-delivery.php",
    }).done(function(data){
      $('#dentist').html(data)
    });
  }
  function dentistAddSchedule(){
    $.ajax({
      type: "GET",
      url: "section-assistant-add-schedule.php",
    }).done(function(data){
      $('#dentist').html(data)
    });
  }
  function dentistAssistantLogs(){
    $.ajax({
      type: "GET",
      url: "dentist-observe-assistant-logs.php",
    }).done(function(data){
      $('#dentist').html(data)
    });
  }
</script>

<!-- Change Profile Pic Modal -->
<div class="modal fade" id="imageModal" data-backdrop="static" data-keyboad="false">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Update Profile Picture</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="upload.php" method="POST" role="form" enctype="multipart/form-data">
					<center>
						<label for='image' style="cursor: pointer;">
						<?php
							if($image == "" or $image == "None" or $image == 'profile/default.ico'){
						?>
							<img id="img_content" src="images/default.jpg" class="img-circle" alt="Profile Photo" height="160px;" width="160px;">
						<?php
							}
							else{
						?>
							<img id="img_content" src="<?php print_r($image);?>" class="img-circle img-display" alt="Profile Photo" height="160px;" width="160px;" style="box-shadow: 0 0 15px #A9A9A9;object-fit: cover;">						
						<?php
							}
						?>
					<br>
						Browse Pictures
						<span class="glyphicon glyphicon-camera" style="color: #A9A9A9;"></span> 
						</label>
						<input type="file" name="image" id="image" accept="image/*" style="display: none;" >
					</center>
					<input type="hidden" class="form-control" value="<?php echo $id;?>" name="id">
				</div>
				<div class="modal-footer float-right">
					<button type="submit" class="btn btn-default btn-block"><span class="glyphicon glyphicon-ok-sign"></span> Upload Selected Profile Picture</button>
					</form>
          <button type="button" class="btn btn-secondary btn-block" onclick="cancelUpload();"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

 <!-- Logout Modal -->
 <div class="modal fade" id="logout">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
        <!-- <div class="modal-header">
            <h4 class=""><center>Log out</center></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div> -->
        <div class="modal-body">
          <center>
            <img class="img-circle mt-3" src="photo/logo_circle.png" alt=""  height="160px;" width="160px;" style="box-shadow: 0 0 15px #A9A9A9;object-fit: cover;">
            <br>
            <br>
            <p>Do you want to continue?&hellip;</p>
          </center>
          
          <button type="button" class="btn btn-default btn-block" onclick="location.href='logout.php'">Exit</button>
          <?php
            if($position != 'patient' && $position != 'assistant'){
              ?>
                <button type="button" class="btn btn-primary btn-block" onclick="location.href='switch-branch.php'">Switch Branch</button>
              <?php
            }
          ?>
          <button type="button" class="btn btn-secondary btn-block"  data-dismiss="modal">Cancel</button>
        </div>
          <!-- <div class="modal-footer justify-content-between">
          </div> -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->