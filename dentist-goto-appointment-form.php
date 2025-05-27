<?php
    session_start();
    include "config/connection.php";

    $id = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['id'])));
    $branches = array()
?>

<!-- View basic Info of Patient Modal -->


    <div class="modal fade" id="show-patient-modal<?php echo $id ?>" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add Appointment</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <section class="patient-info">
                    <?php
                        $stmt0 = $conn->prepare("SELECT * FROM register_patient WHERE register_id = ? ");
                        $stmt0->bind_param("s",$id);
                        $stmt0->execute();
                        $result0 = $stmt0->get_result();
                        if($row0 = $result0->fetch_assoc()){

                            $birthdate = strtotime($row0['birthdate']);
                            $date = getDate($birthdate);
                            $fDay = $date['weekday'];
                            $mDay = $date['mday'];
                            $Month = $date['month'];
                            $Year = $date['year'];
                            $birthdate = $Month.' '.$mDay.', '.$Year;
                    ?>
                        <div class="row">
                            <div class="col-sm-12"><h4>Patient Information</h4></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="">Full Name:</label>
                                    <input class="form-control" type="text" value="<?php echo $row0['last_name'].", ".$row0['first_name']." ".$row0['middle_name']?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">Birthdate:</label>
                                    <input class="form-control" type="text" value="<?php echo $birthdate ?>" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">Age:</label>
                                    <input class="form-control" type="text" value="<?php echo $row0['age'] ?>" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">Contact #:</label>
                                    <input class="form-control" type="text" value="<?php echo $row0['contact_no'] ?>" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">Username:</label>
                                    <input class="form-control" type="text" value="<?php echo $row0['username'] ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="">Address:</label>
                                    <input class="form-control" type="text" value="<?php echo $row0['address'] ?>" readonly>
                                </div>
                            </div>
                        </div>
                    <?php       
                        }
                    ?>
                    <button type="button" class="btn btn-primary float-right" id="dentist-set-appointment">Set an Appointment</button>
                </section>
      
                <section class="content show-appontment" style="display: none;">
                    <div class="container-fluid">
                        <div class="row mt-3">
                            <div class="col-sm-12">
                            <div class="form-group">
                                <center>
                                    <div class="gcash" style="border-radius: 3px; color:#fff">
                                        <div class="col-sm-12">
                                            <div><h3>DENTAL CLINIC {} GCASH NUMBER</h3></div>
                                        </div>
                                    </div>
                                    <p style="color: red"><span class="fa fa-warning fa-fw"></span>Please make sure to check the Gcash number that you are going to pay and be sure to pay the right amount in your Gcash account  to avoid mix-up transaction. Strictly <label for="">NO REFUND POLICY.</label></p> 
                                </center>
                            </div>
                            </div>
                            <div class="col-sm-12">
                                <form id="dentist-patient-set-appointment-form" role="form" enctype="multipart/form-data">
                                    <div class="row">
                                        <?php
                                            $stmt = $conn->prepare("SELECT * FROM register_patient WHERE register_id = ? ");
                                            $stmt->bind_param('s', $id);
                                            $stmt->execute();
                                            $result=$stmt->get_result();
                                            while($row = $result->fetch_Array(MYSQLI_ASSOC)){
                                            ?>
                                                <!-- <div class="form-group"> -->
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="name">Patient Name: <b style="color: red">*</b> </label><br>
                                                                        <?php
                                                                            $stmt = $conn->prepare("SELECT * FROM register_patient WHERE register_id = ?");
                                                                            $stmt->bind_param('s', $id);
                                                                            $stmt->execute();

                                                                            $result=$stmt->get_result();
                                                                            if($row = $result->fetch_Array()){
                                                                                ?>
                                                                                <input class="form-control" type="text" name="name" id="" value="<?php echo strtoupper($row['first_name']." ".$row['middle_name']." ".$row['last_name']); ?>" readonly><br>
                                                                                <?php
                                                                            }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row" id="f-row">
                                                                <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="brch">Branch: <b style="color: red">*</b></label><br>
                                                                        <?php
                                                                            $stmt = $conn->prepare("SELECT * FROM branch");
                                                                            $stmt->execute();
                                                                            $result = $stmt->get_result();
                                                                            if(mysqli_num_rows($result)<=0){
                                                                                echo "<option> No Branch available</option>";
                                                                            }   
                                                                            else{
                                                                        ?>
                                                                            <select class="form-control" name="brch" onchange="selectBranch();patientSelectServices()" >
                                                                        <?php
                                                                                while($row = $result->fetch_Array(MYSQLI_ASSOC)){
                                                                                    ?>
                                                                                        <option value='<?php echo $row['branch_id']; ?>'><?php echo $row['location']; ?></option>
                                                                                    <?php
                                                                                    
                                                                                    array_push($branches, $row['branch_id']);
                                                                                } 
                                                                        ?>
                                                                            </select><br>
                                                                        <?php  
                                                                            }
                                                                        ?>
                                                                        <label for="srvcs">Services: <b style="color: red">*</b></label><br>
                                                                        <select class="form-control" name="srvcs" id="srvcs">
                                                                            <option value=""></option>
                                                                        </select><br>
                                                                        <label for="dent">Dentist: <b style="color: red">*</b></label><br>
                                                                        <select class="form-control" name="dent" id="dent" >
                                                                            <?php
                                                                                $stmt = $conn->prepare("SELECT * FROM dentist");
                                                                                $stmt->execute();
                                                                                $result = $stmt->get_result();
                                                                                if(mysqli_num_rows($result) > 0){
                                                                                    while($row = $result->fetch_Array(MYSQLI_ASSOC)){
                                                                                    ?>
                                                                                        <option value='<?php echo $row['dentist_id']; ?>'> Dr. <?php echo $row['first_name'].' '.$row['middle_name'].' '.$row['last_name']; ?></option>
                                                                                    <?php
                                                                                    }
                                                                                }
                                                                                else{
                                                                                    echo "<option> No Dentist Available </option>";
                                                                                }
                                                                            ?>
                                                                        </select><br>  
                                                                        <button type="button" class="btn btn-secondary float-right" id="submit-dentist"><span class="fas fa-arrow-right"></span></button>
                                                                        
                                                                        <script>
                                                                            $('#submit-dentist').on("click", function(){
                                                                                form = new FormData();
                                                                                form.append("dentist", $("#dent").val());
                                                                                form.append("branch", $('[name=brch]').val());
                                                                                $.ajax({
                                                                                    url: "appointment-date.php",
                                                                                    data: form,
                                                                                    type: "POST",
                                                                                    processData: false,     
                                                                                    contentType: false,  
                                                                                }).done( function(data){
                                                                                    // alert(data)
                                                                                    $('#div-date').html(data);
                                                                                    $('#s-row').show()
                                                                                    $('#s-row').slideDown();
                                                                                    $('#f-row').slideUp();
                                                                                    
                                                                                })
                                                                            })
                                                                        </script>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row" id="s-row" style="display:none"> 
                                                                <div class="col-sm-12">                                                
                                                                    <div class="form-group">

                                                                        <label for="appdte">Appointment Date: <b style="color: red">*</b></label><br>
                                                                        <div id="div-date">
                                                                        <input class="form-control" name="appdte" id="" placeholder="Pick a Date" ><br>    
                                                                        </div>
                                                                        
                                                                        <label for="appdtme">Appointment Time: <b style="color: red">*</b></label><br>
                                                                        <input class="form-control" type="time" name="appdtme" id="timepicker" min="08:00:00" max="17:00:00" value="08:00:00" placeholder="Pick a time" ><br>

                                                                        <label for="downpyment">Downpayment: <small>(minimum of P500.00)</small><b style="color: red">*</b></label><br>
                                                                        <input class="form-control" type="number" name="downpayment" id="down-payment" 
                                                                        placeholder="Down payment" >
                                                                        <small style="color: red" id="dp-alert">Make sure to pay the amount you applied for in your Gcash account</small><br><br>

                                                                        <div class="btn-group float-right">
                                                                            <button type="button" class="btn btn-secondary" id="submit-dentist1"><span class="fas fa-arrow-left"></span></button>
                                                                            <button type="button" class="btn btn-secondary" id="submit-dentist2"><span class="fas fa-arrow-right"></span></button>
                                                                        </div>
                                                                        
                                                                        <script>
                                                                            $('#submit-dentist1').on("click", function(){
                                                                                $('#f-row').slideDown();
                                                                                $('#s-row').slideUp();
                                                                            })
                                                                            $('#submit-dentist2').on("click", function(){
                                                                                $('#t-row').show();
                                                                                $('#t-row').slideDown();
                                                                                $('#s-row').slideUp();
                                                                            })
                                                                            $('#submit-dentist3').on("click", function(){
                                                                                $('#t-row').slideUp();
                                                                                $('#s-row').slideDown();
                                                                            })
                                                                        </script>
                                                                    </div>
                                                                </div>  
                                                            </div>

                                                            <div class="row" id="t-row" style="display: none;">
                                                                <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="gcshnim">GCASH Name: <b style="color: red">*</b></label><br>
                                                                        <input class="form-control" type="text" name="gcshnim" placeholder="Your Gcash Name" ><br>

                                                                        <label for="gcshno">GCASH Number: <b style="color: red">*</b></label><br>
                                                                        <input class="form-control" type="number" name="gcshno" pattern="[0-9\-\+\(\)\s]+" maxLength="11" minLength="11" placeholder="Your Gcash Number" ><br>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="pmntref">Payment Reference: <b style="color: red">*</b></label><br>
                                                                        <input class="form-control" type="number" name="pmntref" pattern="[0-9\-\+\(\)\s]+" maxLength="13" minLength="13" placeholder="Your Gcash Reference Number" ><br>

                                                                        <label for="pymntprof">Payment Proof: <b style="color: red">*</b></label><br>
                                                                        <input class="form-control" type="file" name="pymntprof" id="pymntprof" ><br>
                                                                    </div>
                                                                        
                                                                    <button href="" class="btn btn-secondary float-right" type="submit" name="submit-patient-appointment" >Set Appointment</button>
                                                                    <script>
                                                                        $('#dentist-patient-set-appointment-form').on('submit', function(e){
                                                                            e.preventDefault();
                                                                            const fileInput = document.getElementById('pymntprof');
                                                                            const file = fileInput.files[0];
                                                                            if(!file){
                                                                                alert('Please Add Gcash Payment Proof!');
                                                                                return;
                                                                            }
                                                                            form.append('id', <?php echo $id; ?>)
                                                                            form.append('srvcs', $('[name=srvcs]').val())
                                                                            form.append('downpayment', $('[name=downpayment]').val())
                                                                            form.append('brch', $('[name=brch]').val())
                                                                            form.append('dent', $('[name=dent]').val())
                                                                            form.append('appdte', $('#sched-datepicker').val())
                                                                            form.append('appdtme', $('[name=appdtme]').val())
                                                                            form.append('gcshnim', $('[name=gcshnim]').val())
                                                                            form.append('gcshno', $('[name=gcshno]').val())
                                                                            form.append('pmntref', $('[name=pmntref]').val())
                                                                            form.append('pymntprof', file)
                                                                            $.ajax({
                                                                                url: 'patient-set-appointment.php',
                                                                                method: 'post',
                                                                                data: form,
                                                                                processData: false, 
                                                                                contentType: false, 
                                                                                cache:false, 
                                                                            }).done( function (data){
                                                                                if(data == 'Appointment created successfully'){
                                                                                    alert(data)
                                                                                    date = encodeURIComponent(window.btoa($('#sched-datepicker').val()))
                                                                                    branch = encodeURIComponent(window.btoa($('[name=brch]').val()))
                                                                                    patient_id = encodeURIComponent(window.btoa(<?php echo $id; ?>))
                                                                                    location.href="send-confirmation-sms.php?d="+date+"&b="+branch+"&patient_id="+patient_id;
                                                                                }
                                                                                else{
                                                                                    alert(data)
                                                                                }
                                                                            })
                                                                        })
                                                                    </script>

                                                                    <br><br>
                                                                    <button type="button" class="btn btn-secondary float-right" id="submit-dentist3"><span class="fas fa-arrow-left"></span></button>
                                                                </div>
                                                            </div>
                                                        </div>    
                                                    </div>
                                                </div>
                                                <!-- </div> -->
                                                <!-- <label for="name">Full Name</label>
                                                <p id="name"></p>
                                                <label for="name">Full Name</label>
                                                <p id="name"></p>
                                                <label for="name">Full Name</label>
                                                <p id="name"></p>
                                                <label for="name">Full Name</label>
                                                <p id="name"></p> -->
                                            <?php
                                            }
                                        ?>
                                    </div><!-- row  -->
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
                <script>
                    var branches_ = <?php echo '["' . implode('", "', $branches) . '"]' ?>;

                    
                    function asssitantSelectServices(){
                        $.ajax({
                            data: "branch="+$("[name=brch]").val(),
                            method: "post",
                            url: "services.php",
                        }).done( function(data){
                            // alert(data)
                            $('#srvcs').html(data);
                        })
                    }
                        
                    function assistantSelectBranch(){
                        var brnch = $('[name=brch]').val()

                        $.ajax({
                            method: 'post',
                            data: "id="+$('[name=brch]').val(),
                            url: 'branch.php',
                        }).done( function(data){
                            $('.gcash').html(data);
                        })
                    }

                    $(document).ready( function(){  
                        var b = $('[name=brch]').val()
                        $.ajax({
                            method: 'post',
                            data: "id="+$('[name=brch]').val(),
                            url: 'branch.php',
                        }).done( function(data){
                            $('.gcash').html(data);
                        }) ;

                        $.ajax({
                            data: "service="+$('#srvcs').val()+"&price="+$('#prce').val(),
                            method: "post",
                            url: "services.php",
                        }).done( function(data){
                            $('#div-price').html(data);
                        })

                        $.ajax({
                            data: "branch="+$("[name=brch]").val(),
                            method: "post",
                            url: "services.php",
                        }).done( function(data){
                            // alert(data)
                            $('#srvcs').html(data);
                        })
                    });

                    $('#datepicker').datepicker(
                        {
                            minDate: 0,
                            beforeShowDay: function(date){
                                var day = date.getDay();
                                var string = jQuery.datepicker.formatDate("yy-mm-dd",date);
                                return [(sched_array.indexOf(string)== -1)?(day>0):"", "calendar-background"];
                            },
                            dateFormat: "MM dd, yy",
                        }
                    );
                    function beforeShowDay(sunday){
                        var day = sunday.getDay();
                        return [(day>0), "calendar-background"];  
                    }
                </script>

                <!-- /.modal -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <script>
                $('#dentist-set-appointment').on('click', function(){
                    $('.show-appontment').css("display", "block");
                    $('.patient-info').slideUp();
                    $('.show-appontment').slideDown();
                    $('#input-search').val('');
                })
                // $(document).ready(function(){

                // })
            </script>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
       


                    