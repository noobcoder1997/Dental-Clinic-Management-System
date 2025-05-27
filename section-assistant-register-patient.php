<?php
    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];
    $designation = $_SESSION['designation'];
?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card mt-3">
                    <div class="card-header">
                        <h4>Register a Patient</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="card" style="max-width: 100%;">
                                <div class="card-header"></div>
                                <div class="card-body">
                                    <div class="row" width="100%" >
                                        <div class="col-sm-12">
                                            <div class="row" id="as-registration-form">
                                                <div class="col-sm-12" >
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <center><label for="">Personal Information</label></center>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="">First Name</label>
                                                                <input type="text" name="a_first_name" id="a_first_name" class="form-control" placeholder="First Name" required><br>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="">Middle Name</label>
                                                                <input type="text" name="a_middle_name" id="a_middle_name" class="form-control" placeholder="Middle Name" required><br>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="">Last Name</label>
                                                                <input type="text" name="a_last_name" id="a_last_name" class="form-control" placeholder="Last Name" required><br>
                                                            </div>
                                                        </div>  
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="row">
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label for="">Birth Date:</label> 
                                                                        <input type="text" name="a_birthdate" id="a_birthdate" class="form-control" required><br>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label for="">Age:</label> 
                                                                        <input type="number" name="a_age" id="a_age" class="form-control" placeholder="Age" required min='1' pattern="[0-9\-\+\(\)\s]+" maxLength="3" minLength="3"><br>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label for="">Contact Number:</label> 
                                                                        <input type="number" name="a_contact_no" id="a_contact_no" class="form-control" placeholder="Contact Number" required inputmode="numeric" pattern="[0-9\s]{13,19}" ]maxlength="11"><br>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label for="">Address:</label> 
                                                                        <textarea name="a_address" id="a_address" class="form-control" placeholder="Address" rows="3" cols="50"placeholder="Address" required></textarea><br>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <center><label for="">User Identification</label></center>
                                                            <div class="row">
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label for="">Username:</label> 
                                                                        <input type="text" name="a_username" id="a_username" class="form-control" placeholder="Username" required><br>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label for="">Password:</label> 
                                                                        <input type="password" name="a_password" id="a_password" class="form-control" placeholder="Password" required><br>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label for="">Confirm Password: </label> 
                                                                        <input type="password" name="a_confirm_password" id="a_confirm_password" class="form-control" placeholder="Confirm Password" required><br>
                                                                        <p id="-alert-password"></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                                <!-- <center>
                                                                <img src="assets/dist/img/office.jpg" alt="Dental Clinic Logo" class="logo" id="profilepic"> 
                                                                </center> -->
                                                        </div>
                                                    
                                                    </div>
                                                </div> 
                                           
                                            </div>
                                                    <!-- <div class="row float-right">
                                                        <div class="col-sm-12">
                                                            <button class="btn btn-primary" type="button" onclick="">Register Patient</button>
                                                        </div>
                                                    </div> -->
                                            <div class="row" style="cursor: pointer;" width="100%">
                                                <div class="col-sm-12" >
                                                    <div class="row">
                                                        <div class="col-sm-12" id="div-show-patient-detail">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" width="100%">
                                                <div class="col-sm-12" >
                                                    <div class="row">
                                                        <div class="col-sm-12" id="appointment-patient-table">
                                                            <div class="row"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <div class="card-footer">
                                    <button class="btn btn-secondary float-right" onclick="_assistantRegisterPatient()" id="reg-patient-btn">Register Patient</button>
                                    <script>
                                        function _assistantRegisterPatient(){
                                            fnim=$('#a_first_name').val();
                                            mnim=$('#a_middle_name').val();
                                            lnim=$('#a_last_name').val();
                                            age=$('#a_age').val();
                                            bdate=$('#a_birthdate').val();
                                            add=$('#a_address').val();
                                            cno=$('#a_contact_no').val();
                                            user=$('#a_username').val();
                                            pass=$('#a_password').val();
                                            cpass=$('#a_confirm_password').val();
                                            
                                            if(fnim == ""||lnim == ""||age == ""||bdate == ""||add == ""||cno == ""||user == ""||pass == ""||cpass == ""){
                                              alert('Fields should not leave empty!');
                                            }
                                            else if(cpass != pass){
                                                alert('Passwords did not match!');
                                            }
                                            else if(cno.length != 11){
                                                alert('Invalid Phone number!');
                                            }
                                            else{
                                                data = 'fnim='+fnim+'&mnim='+mnim+"&lnim="+lnim+'&age='+age+'&bdate='+bdate+'&add='+add+'&cno='+cno+'&user='+user+"&pass="+pass;
                                                $.ajax({
                                                    data: data,
                                                    method: "post",
                                                    url: "assistant-register-patient.php",
                                                }).done(function(data){
                                                    $('.modal-backdrop').remove();
                                                    $('#a_first_name').val('')
                                                    $('#a_middle_name').val('');
                                                    $('#a_last_name').val('');
                                                    $('#a_age').val('');
                                                    $('#a_birthdate').datepicker('refresh');
                                                    $('#a_address').val('');
                                                    $('#a_contact_no').val('');
                                                    $('#a_username').val('');
                                                    $('#a_password').val('');
                                                    $('#a_confirm_password').val('');
                                                    
                                                    if(data == "Patient Registered Successfully!"){
                                                        alert(data);
                                                    }
                                                    else if(data == 'Please create another Username or Password!'){
                                                        alert(data);
                                                    }
                                                    else{
                                                        $('#div-show-patient-detail').slideDown();
                                                        $('#div-show-patient-detail').html(data);
                                                    }
                                                })
                                            }
                                        }
                                        function assistantReviewAccount(id){
                                            form = new FormData();
                                            form.append('id', id);
                                            $.ajax({
                                                url: "assistant-review-patient.php",
                                                type: "POST",
                                                data: form,
                                                processData: false,
                                                contentType: false,
                                            }).done( function(data){
                                                $('#appointment-patient-table').slideDown();
                                                $("#appointment-patient-table").html(data);
                                            })
                                        }
                                        function assistantBacktoForm(){
                                            $("#div-show-patient-detail").html('');
                                            $("#appointment-patient-table").html('');
                                            $('#div-show-patient-detail').slideUp();
                                            $("#appointment-patient-table").slideUp();
                                        }
                                    </script>
                                </div>
                            </div>                    
                        </div>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready( function(){
        $(function () {
            $("#assistant-view-patient-appointments-table").DataTable({
                "responsive": true,
                "lengthChange": true, 
                "autoWidth": true,
                // "pageLength": 50,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                "scrollX": true,
            });
        });
        $('#a_birthdate').datepicker({
            maxDate:-1,
            dateFormat: "MM dd, yy",
            beforeShowDay: beforeShowDay,
        });
            function beforeShowDay(sunday){
                var day = sunday.getDay();
                return [true, "calendar-background"];  
            }
        $(function(){
            $('#datepicker').datepicker();
            month_Array = ["January","February","March","April","May","June","July","August","September","October","November","December",""];
            month_index='';
            var dtToday = new Date();
            var month = dtToday.getMonth()+1 ;
            var day = dtToday.getDate();
            var year = dtToday.getFullYear();
            var weekday = dtToday.getDay();

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
            $('#a_birthdate').attr('value', date);
            $('#datepicker').attr('value', date);
        });
    })
</script>       
          