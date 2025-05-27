<?php
  // Include the necessary connection file
  session_start();
  include 'config/connection.php'; // Database connection
  $id = $_SESSION['id'];
  $position = $_SESSION['position'];
  if(!isset($_SESSION['select_branch'])){
      header('location: index.php');
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dental Clinic | Select Branch</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- <script src="plugins/jquery/jquery.min.js"></script> -->
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
      }
      .form{
          margin: 0;
          position: absolute;
          top: 50%;
          -ms-transform: translateY(-50%);
          transform: translateY(-50%);
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
      } */

      .register-link {
        color: #5a67d8;
        text-align: center;
        display: block;
        margin-top: 20px;
        font-size: 0.9rem;
        text-decoration: none;
      }
    </style>
  </head>
  <body>

    <div class="form-container col-sm-4 form"> 
      <center><img src="photo/logo_circle.png" alt="Dental Clinic Logo" class="logo"> 
        <div class="form-title">Select Branch</div>
      </center>
        <!-- <form action="" method="post" id="registrationForm"> -->
          <div class="col-sm-12">
              <div class="row">
                  <div class="col-sm-12">
                      <div class="form-group">
                          <select class="form-control" name="select-branch" id="">
                              <?php
                                  $stmt = $conn->prepare("SELECT * FROM branch");
                                  $stmt->execute();
                                  $result = $stmt->get_result();
                                  while($row = $result->fetch_assoc()){
                                      ?>
                                          <option value='<?php echo $row['branch_id'] ?>'><?php echo $row['location'] ?></option>
                                      <?php
                                  }
                              ?>
                          </select>
                          <!-- <input type="text" name="select-branch" class="form-control" placeholder="First Name" required> -->
                      </div>
                  </div> 
              </div>
          </div>

          <div class="col-sm-12">
            <div class="form-group"><button type="button" id="submit-branch" class="btn-submit btn-block" name="register-patient-button">Select Branch</button></div>
            <div class="form-group"><button type="button" id="cancel-branch" class="btn btn-default btn-block" >Sign out</button></div>
          </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
  </body>
</html>
<script>
    $(document).ready( function(){
        $('#cancel-branch').on('click', function(){
          $.ajax({
              url: 'logout.php',
              method: 'post',
          }).done( function(data){
              location.reload()
              // alert(data)
              location.href = ('index.php');
          })
        })  
        $('#submit-branch').on('click', function(){
          $.ajax({
              url: 'selected-branch.php',
              method: 'post',
              data: 'id='+$("[name = select-branch]").val(),
          }).done( function(data){
              location.reload()
              // alert(data)
              location.href = ('home.php');
          })
        })       
    })

</script>