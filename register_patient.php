<?php
  session_start();
  // Include the necessary connection file
  include 'config/connection.php'; // Database connection
  if(!isset($_SESSION['register'])){
    header('location: index.php');
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dental Clinic | Registration</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
    <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
    
    <!-- <script src="https://code.jquery.com/jquery-3.7.1.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
      body, html{
        color: #9640b0
      }
      /* Custom styles for the form */
      body {
        font-family: 'Poppins', sans-serif;
        /* height: 100vh; */
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        background-image: url('assets/dist/img/ehh.jpg');
        background-size: cover;
        overflow: hidden;
      }
      textarea{
        resize: none;
      }

      .form-container {
        background: rgba(255, 255, 255, 0.85);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        /* width: 100%; */
        /* max-width: 50%; */
        margin: 10px; 

      }

      .logo {
        display: block;
        margin: 20px;
        box-shadow: 0px 0px 20px 3px gray;
        width: 130px;
        height: 130px;
        border-radius: 50%;
      }

      .form-title {
        text-align: center;
        font-size: 2rem;
        /* font-weight: 700; */
        color: #9640b0;
        margin-bottom: 25px;
      }

      .form-group {
        margin-bottom: 18px;
      }

      .form-control {
        border-radius: 8px;
        /* padding: 1px; */
        font-size: 1rem;
        border: 1px solid #ddd;
        /* margin:10px; */
      }

      .btn-submit {
        /* background-color: #5a67d8; */
        background: #9640b0;
        color: white;
        border-radius: 8px;
        padding: 12px 25px;
        font-size: 1.1rem;
        width: 100%;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
      }

      body{
        overflow: auto;
      }

      /*.btn-submit:hover {
        background-color: #4c51bf;
        transform: scale(1.05); 
      }*/

      .register-link {
        color: #5a67d8;
        text-align: center;
        display: block;
        margin-top: 20px;
        font-size: 0.9rem;
        text-decoration: none;
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
    </style>
  </head>
  <body>

    <div class="form-container col-sm-9"> 
      <center><img src="photo/logo_circle.png" alt="Dental Clinic Logo" class="logo"> 
        <div class="form-title">Register Patient</div>
      </center>
        <!-- <form action="" method="post" id="registrationForm"> -->
          <center><label for="">Personal Information</label></center>
          <div class="col-sm-12" id="alert-message">

          </div>
          <div class="col-sm-12">
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                  <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <input type="text" name="middle_name" class="form-control" placeholder="Middle Name" required>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                </div>
              </div>  
            </div>
          </div>
          <div class="col-sm-12">
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                  <input type="text" name="birthdate" id="birthdate" class="form-control" required>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <input type="number" name="age" class="form-control" placeholder="Age" required min='1'>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <input type="number" name="contact_no" class="form-control" placeholder="Contact Number" required>
                </div>
                </div>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <textarea name="address" class="form-control" placeholder="Address" rows="3" cols="50"placeholder="Address" required></textarea>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <select id="" name="sex" class="form-control">
                      <option value="">Sex</option>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <select name="marital_status" id="" class="form-control">
                      <option value="">Marital Status</option>
                      <option value="Single">Single</option>
                      <option value="Married">Married</option>
                      <option value="Separated">Separated</option>
                      <option value="Widow">Widow</option>
                      <option value="Divorced">Divorced</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <input type="text" class="form-control" name="occupation" placeholder="Occupation">
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <textarea name="office_address" class="form-control" rows="3" cols="50" placeholder="Office Address" required></textarea>
                  </div>
                </div>
            </div>
          </div>
          <center><label for="">User Identification</label></center>
          <div class="col-sm-12">
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" required>
                  <p id="-alert-password"></p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-12">
              <div class="form-group"><button type="button" id="submit-registrant" class="btn-submit" name="register-patient-button">Register</button></div>
          </div>
          
        <!-- </form> -->
          <center><a class="register-link" href="logout.php">Already have account? Sign in</a></center>
    </div>

    <!-- <script src="plugins/jquery/jquery.min.js"></script> -->
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  </body>
</html>
<script>
    $('#submit-registrant').on('click', function(){
      fnim = $('[name = first_name]').val();
      mnim = $('[name = middle_name]').val();
      lnim = $('[name = last_name]').val();
      bdate = $('[name = birthdate]').val();
      age = $('[name = age]').val();
      cno = $('[name = contact_no]').val();
      sex = $('[name = sex]').val();
      status = $('[name = marital_status]').val();
      job = $('[name = occupation]').val();
      office_address = $('[name = office_address]').val();
      add = $('[name = address]').val();
      userid = $('[name = username]').val();
      pass0 = $('#password').val();
      pass1 = $('#confirm_password').val();
      
      if(pass0 != pass1 || pass1 != pass0){
       $('#-alert-password').html('Password did not match!');
      }
      else if( fnim == "" || lnim == "" || bdate == "" || age == "" || cno == "" || add == "" || userid == "" || sex == "" || status == "" || job == "" || office_address == ""){ 
        $('#alert-message').html('<div class="alert alert-warning alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please fill empty fields!</div>');
        window.scrollTo(0,0);
        setTimeout(function () {
          $("#alert-message").slideDown(500);
        }, 2000);
      }
      else if($('[name = contact_no]').val().length != 11){
        alert('Invalid Phone number!')
      }
      else{
        data = "fnim="+fnim+"&mnim="+mnim+"&lnim="+lnim+"&bdate="+bdate+"&age="+age+"&cno="+cno+"&add="+add+"&userid="+userid+"&pass="+pass0+"&sex="+sex+"&status="+status+"&job="+job+"&office_address="+office_address;
        $.ajax({
          url: 'register.php',
          method: 'POST',
          data: data,
        }).done( function( data){
          console.log(data);
          if(data == '1'){
            $('#alert-message').html('<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>You are now registered!</div>');
            setTimeout(function () {
              location.reload();
              location.replace('index.php');
            }, 2000);
          }
          else{
            $('#alert-message').html('<div class="alert alert-warning alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+data+'</div>');
          }
        })
      }
    })

    $(document).ready(function(){
      
      $('#birthdate').datepicker(
        { 
          maxDate: -1,
          dateFormat: 'MM dd, yy',
          beforeShowDay: beforeShowDay,
        }
      );
        function beforeShowDay(sunday){
            var day = sunday.getDay();
            return [true, "calendar-background"];  
        }
      $(function(){
        month_Array = ["January","February","March","April","May","June","July","August","September","October","November","December",""];
        month_index='';
        var dtToday = new Date();
        var month = dtToday.getMonth()+1 ;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();
        var weekday = dtToday.getDay();
        // for(x = 0; x < month_Array.lenght; x++){
        //     if(x == month){
        //         month_index = month_Array[x];
        //     }
        // }
        switch(month){
            case 1: 
                    month_index = month_Array[0];
                    break;
            case 2: 
                    month_index = month_Array[1];
                    break;
            case 3: 
                    month_index = month_Array[2];
                    break;
            case 4: 
                    month_index = month_Array[3];
                    break;
            case 5: 
                    month_index = month_Array[4];
                    break;
            case 6: 
                    month_index = month_Array[5];
                    break;
            case 7: 
                    month_index = month_Array[6];
                    break;
            case 8: 
                    month_index = month_Array[7];
                    break;
            case 9: 
                    month_index = month_Array[8];
                    break;
            case 10: 
                    month_index = month_Array[9];
                    break;
            case 11: 
                    month_index = month_Array[10];
                    break;
            case 12: 
                    month_index = month_Array[11];
                    break;
            case 13: 
                    month_index = month_Array[12];
                    break;
        }

        if(month < 10)
            month = '0' + month.toString();
        if(day < 10)
        day = '0' + day.toString();
        var maxDate = year + '-' + month + '-' + day;
        date =  month_index + '  ' + day + ', ' + year;
        $('#birthdate').attr('min', maxDate);
        $('#birthdate').attr('value', date);
      });
    })
</script>