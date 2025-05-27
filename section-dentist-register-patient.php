<?php
    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];    
    $branch = $_SESSION['branch'];
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
                            <div class="card">
                                <div class="card-header"></div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-12" id="den-registration-form">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <center><label for="">Personal Information</label></center>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="">First Name</label>
                                                                <input type="text" name="den_first_name" id="den_first_name" class="form-control" placeholder="First Name" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="">Middle Name</label>
                                                                <input type="text" name="den_middle_name" id="den_middle_name" class="form-control" placeholder="Middle Name" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="">Last Name</label>
                                                                <input type="text" name="den_last_name" id="den_last_name" class="form-control" placeholder="Last Name" required>
                                                            </div>
                                                        </div>  
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="row">
                                                                <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label for="">Birth Date:</label> 
                                                                    <input type="text" name="den_birthdate" id="den_birthdate" class="form-control" required>
                                                                </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label for="">Age:</label> 
                                                                    <input type="number" name="den_age" id="den_age" class="form-control" placeholder="Age" required min='1'>
                                                                </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label for="">Contact Number:</label> 
                                                                    <input type="number" name="den_contact_no" id="den_contact_no" class="form-control" placeholder="Contact Number" required inputmode="numeric" pattern="[0-9\s]{13,19}" ]maxlength="11">
                                                                </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                    <label for="">Address:</label> 
                                                                    <textarea name="den_address" id="den_address" class="form-control" placeholder="Address" rows="3" cols="50"placeholder="Address" required></textarea>
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
                                                                            <input type="text" name="den_username" id="den_username" class="form-control" placeholder="Username" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <label for="">Password:</label> 
                                                                            <input type="password" name="den_password" id="den_password" class="form-control" placeholder="Password" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <label for="">Confirm Password: </label> 
                                                                            <input type="password" name="den_confirm_password" id="den_confirm_password" class="form-control" placeholder="Confirm Password" required>
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
                                                
                                            </div>
                                            </div>
                                            <!-- <div class="row float-right">
                                                <div class="col-sm-12">
                                                    <button class="btn btn-primary" type="button" onclick="">Register Patient</button>
                                                </div>
                                            </div> -->
                                           
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12" id="div-show-patient-detail" style="cursor: pointer;"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12" id="appointment-patient-table"></div>
                                    </div>
                                </div>
                                <div class="card-footer footer2">
                                    <button class="btn btn-secondary float-right r2" onclick="_dentistRegisterPatient()">Register Patient</button>
                                    <script>
                                        function _dentistRegisterPatient(){
                                            fnim=$('#den_first_name').val();
                                            mnim=$('#den_middle_name').val();
                                            lnim=$('#den_last_name').val();
                                            age=$('#den_age').val();
                                            bdate=$('#den_birthdate').val();
                                            add=$('#den_address').val();
                                            cno=$('#den_contact_no').val();
                                            user=$('#den_username').val();
                                            pass=$('#den_password').val();
                                            cpass=$('#den_confirm_password').val();
                                            
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
                                                    url: "dentist-register-patient.php",
                                                }).done(function(data){
                                                    $('#den_first_name').val('')
                                                    $('#den_middle_name').val('');
                                                    $('#den_last_name').val('');
                                                    $('#den_age').val('');
                                                    $('#den_birthdate').datepicker('refresh');
                                                    $('#den_address').val('');
                                                    $('#den_contact_no').val('');
                                                    $('#den_username').val('');
                                                    $('#den_password').val('');
                                                    $('#den_confirm_password').val('');
                                                   
                                                    if(data == "Patient Registered Successfully!"){
                                                        alert(data);
                                                        dentistRegisterPatient();
                                                    }
                                                    else if(data == "Please create another Username or Password!"){
                                                        alert(data);
                                                        dentistRegisterPatient();
                                                    }
                                                    // else if(data == "This account was already exist!"){
                                                    //     alert(data);
                                                    // }
                                                    else{
                                                        $('#div-show-patient-detail').slideDown();
                                                        $('#div-show-patient-detail').html(data);
                                                    }
                                                })
                                            }
                                        }
                                        function dentistReviewAccount(id){
                                            form = new FormData();
                                            form.append('id', id);
                                            $.ajax({
                                                url: "dentist-review-patient.php",
                                                type: "POST",
                                                data: form,
                                                processData: false,
                                                contentType: false,
                                            }).done( function(data){
                                                $('#appointment-patient-table').slideDown();
                                                $("#appointment-patient-table").html(data);
                                            })
                                        }
                                        function dentistBacktoForm(){
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
            $("#dentist-view-patient-appointments-table").DataTable({
                "responsive": true,
                "lengthChange": true, 
                "autoWidth": true,
                // "pageLength": 50,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                "scrollX": true,
            });
        });

        
        $('#den_birthdate').datepicker({
            maxDate:-1,
            dateFormat: "MM dd, yy",
            beforeShowDay: beforeShowDay,
        });
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
            $('#den_birthdate').attr('value', date);
        });
    })
</script>       
          