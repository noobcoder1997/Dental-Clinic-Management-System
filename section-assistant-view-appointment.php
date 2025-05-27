<?php
    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];
    
    if(isset($_SESSION['designation'])){
        $designation = $_SESSION['designation'];
    }
    else{
        $designation = $_SESSION['branch'];
    }
    $array=[];
?>

<!-- Main content -->
<section class="content" id="content">
    <div class="container-fluid">
    <div class="row">
        <div class="col-12">

        <div class="card mt-3">
            <div class="card-header">
            <h4 class="">View Appointments</h4>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                    <div class="card-header">
                        <div class="row float-right">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#search-patient-modal">Add Appointment</button>
                            <!-- Search Patient Modal -->
                            <div class="modal fade" id="search-patient-modal">
                                <div class="modal-dialog ">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h4 class="modal-title">Search Patient</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <label for="">First Name:</label><br>
                                                            <input type="text" class="form-control" id="as-input-search-1"  placeholder="Search First Name"><br>
                                                            <label for="">Last Name:</label><br>
                                                            <input type="text" class="form-control" id="as-input-search-2" placeholder="Search Last Name"><br>
                                                            <label for="">Username:</label><br>
                                                            <input type="text" class="form-control" id="as-input-search-3" placeholder="Search Username"><br>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <script>
                                                            function assistantSearchPatientKeyUp(){
                                                                val1 = document.getElementById('as-input-search-1');
                                                                val2 = document.getElementById('as-input-search-2');
                                                                val3 = document.getElementById('as-input-search-3');
                                                                form = new FormData();
                                                                form.append('id1', val1.value);
                                                                form.append('id2', val2.value);
                                                                form.append('id3', val3.value);
                                                                $.ajax({
                                                                    url: "assistant-search-patient.php",
                                                                    type: "POST",
                                                                    data: form,
                                                                    processData: false,
                                                                    contentType: false,
                                                                }).done( function(data){
                                                                    // alert(data);
                                                                    $('#as-search-result').html(data)
                                                                    // $("#assistant-view-appointment-modal"+id).modal('hide');
                                                                    // $('.modal-backdrop').remove();
                                                                    // $("#assistant-view-appointment-modal"+id).trigger('reset');
                                                                    // assistantViewAppointment();
                                                                })
                                                                val1.value='';
                                                                val2.value='';
                                                                val3.value='';
                                                            }
                                                        </script>
                                                    </div>
                                                    <div id="as-search-result" style="border-radius: 4px; padding: 3px; cursor: pointer">
                                                    <script>
                                                        function assistantToAppointmentForm(id){
                                                            form.append('id', id);
                                                            $.ajax({
                                                                url: "assistant-goto-appointment-form.php",
                                                                type: "POST",
                                                                data: form,
                                                                processData: false,
                                                                contentType: false,
                                                            }).done( function(data){
                                                                $('#show-patient-data').html(data);
                                                                $('#as-show-patient-modal'+id).modal('show');
                                                            })
                                                        }
                                                    </script>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default float-right" data-dismiss="modal" onclick="_close()">Close</button>
                                    <button type="button" class="btn btn-primary" onclick="assistantSearchPatientKeyUp()">Search</button>
                                    <script>
                                        function _close(){
                                            $('#search-patient-modal').trigger('reset');
                                            $('#search-patient-modal').modal('hide');
                                            $('.modal-backdrop').remove();
                                            assistantViewAppointment();
                                        }
                                    </script>
                                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->
                        </div>
                        <div id="show-patient-data"></div>
                    </div>
                    <div class="card-body">
                        <table id="assistant-view-appointment-table" class="table table-borderless table-hover">
                            <thead>
                            <tr>
                            <th style="width: 30px">#</th>
                            <th></th>
                            <th>Patient</th>
                            <th>Service</th>
                            <th>Branch</th>
                            <th>Dentist</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <!-- <th></th> -->
                            <!-- <th></th> -->
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no=1;
                            $status0 = "Pending";
                            $status1 = "Declined";
                            $_date = date('Y-m-d');
                            // $stmt0 = $conn->prepare("SELECT * FROM appointment WHERE status = ? AND branch_id = ? AND appointment_date >= ?");
                            // $stmt0->bind_param("sss", $status0, $designation, $_date);
                            $stmt0 = $conn->prepare("SELECT * FROM appointment WHERE status = ? AND branch_id = ?  ORDER BY appointment_date DESC");
                            $stmt0->bind_param("ss", $status0, $designation);
                            $stmt0->execute();
                            $result0 = $stmt0->get_result();
                            while($row0 = $result0->fetch_array(MYSQLI_ASSOC)){

                                $time_input = strtotime($row0['appointment_date']);
                                $date = getDate($time_input);
                                $mDay = $date['mday'];
                                $Month = $date['month'];
                                $Year = $date['year'];
                                $appointment_date = $Month.' '.$mDay.', '.$Year; //format the date example {December 2, 2024}
                                ?>
                                    <tr>
                                        <td>
                                            <?php echo $no; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#assistant-view-appointment-modal<?php echo $row0['appointment_id']; ?>"><span class="fa fa-eye fa-fw"></span></button>

                                            <div class="modal fade" id="assistant-view-appointment-modal<?php echo $row0['appointment_id']; ?>" data-keyboard="false" data-backdrop="static">
                                                <!-- <div class="modal-dialog modal-xl"> -->
                                                <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h4 class="modal-title">View Patient Appointment</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <?php
                                                                if($row0['status'] == "Pending") {
                                                                    ?>
                                                                        <span class="form-control bg-warning" ><?php echo $row0['status']." Appointment"; ?></span><br><br>
                                                                    <?php
                                                                }
                                                                else  if($row0['status'] == "Approved") {
                                                                    ?>
                                                                        <span class="form-control bg-success" ><?php echo $row0['status']." Appointment"; ?></span><br><br>
                                                                    <?php
                                                                }
                                                                else if($row0['status'] == "Declined"){
                                                                    ?>
                                                                        <span class="form-control bg-danger" ><?php echo $row0['status']." Appointment"; ?></span><br><br>
                                                                    <?php
                                                                }
                                                            ?>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="row">
                                                                    <?php
                                                                        $stmt5 = $conn->prepare("SELECT * FROM register_patient WHERE register_id = ? ");
                                                                        $stmt5->bind_param("s", $row0['patient_id']);
                                                                        $stmt5->execute();
                                                                        $result5 = $stmt5->get_result();
                                                                        if($row5 = $result5->fetch_assoc()){
                                                                    ?>
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <label for="">First Name:</label><br>
                                                                            <input class="form-control" type="text" value="<?php echo strtoupper($row5['first_name']); ?>" readonly><br>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <label for="">Middle Name:</label><br>
                                                                            <input class="form-control" type="text" value="<?php echo strtoupper($row5['middle_name']); ?>" readonly><br>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <label for="">Last Name:</label><br>
                                                                            <input class="form-control" type="text" value="<?php echo strtoupper($row5['last_name']); ?>" readonly><br>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                        }
                                                                    ?>
                                                                </div>

                                                                <div class="row">
                                                                    <?php
                                                                        $stmt6 = $conn->prepare("SELECT * FROM services WHERE service_id = ? ");
                                                                        $stmt6->bind_param("s", $row0['service_id']);
                                                                        $stmt6->execute();
                                                                        $result6 = $stmt6->get_result();
                                                                        if($row6 = $result6->fetch_assoc()){
                                                                    ?>
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <label for="">Service:</label><br>
                                                                            <input class="form-control" type="text" value="<?php echo $row6['service_name']; ?>" readonly><br>
                                                                        </div>
                                                                    <?php
                                                                        }
                                                                        $stmt7 = $conn->prepare("SELECT * FROM branch WHERE branch_id = ? ");
                                                                        $stmt7->bind_param("s", $row0['branch_id']);
                                                                        $stmt7->execute();
                                                                        $result7 = $stmt7->get_result();
                                                                        if($row7 = $result7->fetch_assoc()){
                                                                    ?>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <label for="">Location:</label><br>
                                                                            <input class="form-control" type="text" value="<?php echo $row7['location']; ?>" readonly><br>
                                                                        </div>
                                                                    <?php
                                                                        }
                                                                    ?>    
                                                                    </div>
                                                                    <?php
                                                                        $stmt8 = $conn->prepare("SELECT * FROM dentist WHERE dentist_id = ? ");
                                                                        $stmt8->bind_param("s", $row0['dentist_id']);
                                                                        $stmt8->execute();
                                                                        $result8 = $stmt8->get_result();
                                                                        if($row8 = $result8->fetch_assoc()){
                                                                    ?>
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <label for="">Dentist:</label>
                                                                            <input class="form-control" type="text" value="<?php echo $row8['first_name']." ".$row8['middle_name']." ".$row8['last_name']; ?>" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                        }
                                                                    ?>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <label for="">Appointment Date:</label><br>
                                                                            <input class="form-control" type="text" value="<?php echo $appointment_date; ?>" readonly><br>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <label for="">Appointment Time:</label><br>
                                                                            <input class="form-control" type="text" value="<?php echo $row0['appointment_time']; ?>" readonly><br>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <label for="">Down payment:</label><br>
                                                                            <input class="form-control" type="text" value="<?php echo $row0['down_payment']; ?>" readonly><br>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row">
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <label for="">Gcash Name:</label><br>
                                                                            <input class="form-control" type="text" value="<?php echo $row0['gcash_name']; ?>" readonly><br>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <label for="">Gcash #:</label><br>
                                                                            <input class="form-control" type="text" value="<?php echo $row0['gcash_no']; ?>" readonly><br>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <label for="">Payment Reference:</label><br>
                                                                            <input class="form-control" type="text" value="<?php echo $row0['payment_ref']; ?>" readonly><br>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="">Payment Proof:</label>
                                                                            <img class="center" src="<?php echo $row0['payment_proof']; ?>" alt="" style="margin: auto; width: 100%; height: 100%; object-fit: cover; background: red;">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default" onclick="assistantDeclineAppointment(<?php echo $row0['appointment_id']; ?>)">Decline</button>
                                                        <button type="button" class="btn btn-primary" onclick="assistantAcceptAppointment(<?php echo $row0['appointment_id']; ?>)">Accept</button>
                                                        <script>
                                                            function assistantAcceptAppointment(id){
                                                                // alert()
                                                                form = new FormData();
                                                                form.append('id', id);
                                                                form.append('patient_id', "<?php echo $row0['patient_id'] ?>");
                                                                form.append('service_id', "<?php echo $row0['service_id'] ?>");
                                                                // form.append('branch_id', "<?php //echo $row0['branch_id'] ?>");
                                                                // form.append('assistant_id', "<?php //echo $row0['assistant_id'] ?>");
                                                                form.append('date', "<?php echo $row0['appointment_date']; ?>");
                                                                // form.append('time', "<?php //echo $row0['appoinment_time'] ?>");
                                                                // form.append('gcash_#', "<?php //echo $row0['gcash_no'] ?>");
                                                                // form.append('gcash_name', "<?php //echo $row0['gcash_name'] ?>");
                                                                // form.append('payment_ref', "<?php //echo $row0['payment_ref'] ?>");
                                                                // form.append('payment_proof', "<?php //echo $row0['payment_proof'] ?>");
                                                                $.ajax({
                                                                    url: "assistant-accept-appointment.php",
                                                                    type: "POST",
                                                                    data: form,
                                                                    processData: false,
                                                                    contentType: false,
                                                                }).done( function(data){
                                                                    $("#assistant-view-appointment-modal"+id).modal('hide');
                                                                    $('.modal-backdrop').remove();
                                                                    $("#assistant-view-appointment-modal"+id).trigger('reset');
                                                                    alert(data);
                                                                })
                                                                assistantViewAppointment();
                                                            }
                                                            
                                                            function assistantDeclineAppointment(id){
                                                                form = new FormData();
                                                                form.append('id', id);
                                                                form.append('patient_id', "<?php echo $row0['patient_id'] ?>");
                                                                form.append('service_id', "<?php echo $row0['service_id'] ?>");
                                                                form.append('branch_id', "<?php echo $designation; ?>");
                                                                // form.append('assistant_id', "<?php //echo $row0['assistant_id'] ?>");
                                                                form.append('date', "<?php echo $row0['appointment_date']; ?>");
                                                                // form.append('time', "<?php //echo $row0['appoinment_time'] ?>");
                                                                // form.append('gcash_#', "<?php //echo $row0['gcash_no'] ?>");
                                                                // form.append('gcash_name', "<?php //echo $row0['gcash_name'] ?>");
                                                                // form.append('payment_ref', "<?php //echo $row0['payment_ref'] ?>");
                                                                // form.append('payment_proof', "<?php //echo $row0['payment_proof'] ?>");
                                                                $.ajax({
                                                                    url: "assistant-decline-appointment.php",
                                                                    type: "POST",
                                                                    data: form,
                                                                    processData: false,
                                                                    contentType: false,
                                                                }).done( function(data){
                                                                    alert(data);
                                                                    $("#assistant-view-appointment-modal"+id).modal('hide');
                                                                    $('.modal-backdrop').remove();
                                                                    $("#assistant-view-appointment-modal"+id).trigger('reset');
                                                                    assistantViewAppointment();
                                                                })
                                                            }
                                                        </script>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                            <!-- /.modal -->

                                            <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#assistant-edit-appointment-modal<?php echo $row0['appointment_id']; ?>" ><span class="fa fa-edit fa-fw"></span></button>

                                            <div class="modal fade" id="assistant-edit-appointment-modal<?php echo $row0['appointment_id']; ?>" >
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h4 class="modal-title">Edit Appointment Date</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <?php
                                                                    if($row0['status'] == "Pending") {
                                                                        ?>
                                                                            <span class="form-control bg-warning" ><?php echo $row0['status']." Appointment"; ?></span><br><br>
                                                                        <?php
                                                                    }
                                                                    else  if($row0['status'] == "Approved") {
                                                                        ?>
                                                                            <span class="form-control bg-success" ><?php echo $row0['status']." Appointment"; ?></span><br><br>
                                                                        <?php
                                                                    }
                                                                    else if($row0['status'] == "Declined"){
                                                                        ?>
                                                                            <span class="form-control bg-danger" ><?php echo $row0['status']." Appointment"; ?></span><br><br>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="row">
                                                                        <?php
                                                                            $stmt5 = $conn->prepare("SELECT * FROM register_patient WHERE register_id = ? ");
                                                                            $stmt5->bind_param("s", $row0['patient_id']);
                                                                            $stmt5->execute();
                                                                            $result5 = $stmt5->get_result();
                                                                            if($row5 = $result5->fetch_assoc()){
                                                                        ?>
                                                                        <div class="col-sm-4">
                                                                            <div class="form-group">
                                                                                <label for="">First Name:</label><br>
                                                                                <input class="form-control" type="text" value="<?php echo strtoupper($row5['first_name']); ?>" readonly><br>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <div class="form-group">
                                                                                <label for="">Middle Name:</label><br>
                                                                                <input class="form-control" type="text" value="<?php echo strtoupper($row5['middle_name']); ?>" readonly><br>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <div class="form-group">
                                                                                <label for="">Last Name:</label><br>
                                                                                <input class="form-control" type="text" value="<?php echo strtoupper($row5['last_name']); ?>" readonly><br>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                            }
                                                                        ?>
                                                                    </div>

                                                                    <div class="row">
                                                                        <?php
                                                                            $stmt6 = $conn->prepare("SELECT * FROM services WHERE service_id = ? ");
                                                                            $stmt6->bind_param("s", $row0['service_id']);
                                                                            $stmt6->execute();
                                                                            $result6 = $stmt6->get_result();
                                                                            if($row6 = $result6->fetch_assoc()){
                                                                        ?>
                                                                        <div class="col-sm-4">
                                                                            <div class="form-group">
                                                                                <label for="">Service:</label><br>
                                                                                <input class="form-control" type="text" value="<?php echo $row6['service_name']; ?>" readonly><br>
                                                                            </div>
                                                                        <?php
                                                                            }
                                                                            $stmt7 = $conn->prepare("SELECT * FROM branch WHERE branch_id = ? ");
                                                                            $stmt7->bind_param("s", $row0['branch_id']);
                                                                            $stmt7->execute();
                                                                            $result7 = $stmt7->get_result();
                                                                            if($row7 = $result7->fetch_assoc()){
                                                                        ?>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <div class="form-group">
                                                                                <label for="">Location:</label><br>
                                                                                <input class="form-control" type="text" value="<?php echo $row7['location']; ?>" readonly><br>
                                                                            </div>
                                                                        <?php
                                                                            }
                                                                        ?>    
                                                                        </div>
                                                                        <?php
                                                                            $stmt8 = $conn->prepare("SELECT * FROM dentist WHERE dentist_id = ? ");
                                                                            $stmt8->bind_param("s", $row0['dentist_id']);
                                                                            $stmt8->execute();
                                                                            $result8 = $stmt8->get_result();
                                                                            if($row8 = $result8->fetch_assoc()){
                                                                        ?>
                                                                        <div class="col-sm-4">
                                                                            <div class="form-group">
                                                                                <label for="">Dentist:</label>
                                                                                <input class="form-control" type="text" value="<?php echo $row8['first_name']." ".$row8['middle_name']." ".$row8['last_name']; ?>" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                            }
                                                                        ?>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-sm-4">
                                                                            <div class="form-group">
                                                                                <label for="">Appointment Date:</label>
                                                                                <input type="text" class="form-control" id="edit-datepicker<?php echo $row0['appointment_id']; ?>" value="<?php echo $appointment_date; ?>">
                                                                                <script>
                                                                                    $('#edit-datepicker<?php echo $row0['appointment_id']; ?>').datepicker(
                                                                                        {
                                                                                            // maxDate: -1,
                                                                                            beforeShowDay: beforeShowDay,
                                                                                            dateFormat: "MM dd, yy",
                                                                                        }
                                                                                    );
                                                                                    function beforeShowDay(sunday){
                                                                                        var day = sunday.getDay();
                                                                                        return [day>0, "calendar-background"];  
                                                                                    }
                                                                                </script>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <div class="form-group">
                                                                                <label for="">Appointment Time:</label><br>
                                                                                <input class="form-control" type="text" value="<?php echo $row0['appointment_time']; ?>"><br>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <div class="form-group">
                                                                                <label for="">Down payment:</label><br>
                                                                                <input class="form-control" type="text" value="<?php echo $row0['down_payment']; ?>" readonly><br>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="row">
                                                                        <div class="col-sm-4">
                                                                            <div class="form-group">
                                                                                <label for="">Gcash Name:</label><br>
                                                                                <input class="form-control" type="text" value="<?php echo $row0['gcash_name']; ?>" readonly><br>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <div class="form-group">
                                                                                <label for="">Gcash #:</label><br>
                                                                                <input class="form-control" type="text" value="<?php echo $row0['gcash_no']; ?>" readonly><br>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <div class="form-group">
                                                                                <label for="">Payment Reference:</label><br>
                                                                                <input class="form-control" type="text" value="<?php echo $row0['payment_ref']; ?>" readonly><br>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="">Payment Proof:</label>
                                                                                <img class="center" src="<?php echo $row0['payment_proof']; ?>" alt="" style="margin: auto; width: 100%; height: 100%; object-fit: cover; background: red;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <input type="hidden" id="dentist-id" value="<?php echo $row0['dentist_id'] ?>">
                                                        <input type="hidden" id="branch-id"  value="<?php echo $row0['branch_id'] ?>">
                                                        <input type="hidden" id="patient-id">

                                                        <div class="modal-footer justify-content-between">
                                                            
                                                            <button type="button" class="btn btn-default" data-dismiss="modal" id="close_btn">Close</button>
                                                            
                                                            <button type="button" class="btn btn-primary" onclick="editAppointment(<?php echo $row0['appointment_id']; ?>)" data-dismiss="modal">Save changes</button>
                                                            <script>
                                                                function editAppointment(id){

                                                                    if($('#edit-datepicker'+id).val() == ''){
                                                                        alert('Fields should not leave empty!');
                                                                    }
                                                                    else{

                                                                        form = new FormData();
                                                                        form.append("date", $('#edit-datepicker'+id).val())
                                                                        form.append("id", id)
                                                                        $.ajax({
                                                                            data: form,
                                                                            url: 'assistant-edit-appointment-date.php',
                                                                            type:'post',
                                                                            contentType: false,
                                                                            processData: false,
                                                                        }).done( function(data){
                                                                            
                                                                            if(data!=''){
                                                                                $('.modal-backdrop').remove();
                                                                                $("#assistant-edit-appointment-modal"+id).modal('toggle');
                                                                                alert(data) 
                                                                            } 
                                                                        }) 
                                                                        assistantViewAppointment(); 
                                                                    }
                                                                }
                                                                 
                                                                // $("#assistant-edit-appointment-modal<?php //echo $row0['appointment_id']; ?>").on('toggle.bs.modal', function(){
                                                                //     assistantViewAppointment();                                                            
                                                                // });
                                                            </script>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                            <!-- /.modal -->
                                             
                                            <script>
                                                function showEditModal(id){
                                                    $("#assistant-edit-appointment-modal"+id).modal("show");
                                                    $.ajax({
                                                        data: 'dentist='+$('#dentist-id').val()+'&branch='+$('#branch-id').val()+'&date='+"<?php echo $appointment_date;?>"+"&id="+id,
                                                        type: 'POST',
                                                        url: 'load-edit-appointment.php',
                                                    }).done( function(data){
                                                        // alert(data)
                                                        $("#display-edit-date"+id).html(data)
                                                    })
                                                }
                                            </script>   
                                        </td>
                                        <td>
                                            <?php
                                                $stmt1 = $conn->prepare("SELECT * FROM register_patient WHERE register_id = ? ");
                                                $stmt1->bind_param("s", $row0['patient_id']);
                                                $stmt1->execute();
                                                $result1 = $stmt1->get_result();
                                                if($row1 = $result1->fetch_assoc()){
                                                    echo $row1['first_name']." ".$row1['middle_name']." ".$row1['last_name'];
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                $stmt2 = $conn->prepare("SELECT * FROM services WHERE service_id = ? ");
                                                $stmt2->bind_param("s", $row0['service_id']);
                                                $stmt2->execute();
                                                $result2 = $stmt2->get_result();
                                                if($row2 = $result2->fetch_assoc()){
                                                    echo $row2['service_name'];
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                $stmt3 = $conn->prepare("SELECT * FROM branch WHERE branch_id = ? ");
                                                $stmt3->bind_param("s", $row0['branch_id']);
                                                $stmt3->execute();
                                                $result3 = $stmt3->get_result();
                                                if($row3 = $result3->fetch_assoc()){
                                                    echo $row3['location'];
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                $stmt4 = $conn->prepare("SELECT * FROM dentist WHERE dentist_id = ? ");
                                                $stmt4->bind_param("s", $row0['dentist_id']);
                                                $stmt4->execute();
                                                $result4 = $stmt4->get_result();
                                                if($row4 = $result4->fetch_assoc()){
                                                    echo $row4['first_name']." ".$row4['middle_name']." ".$row4['last_name'];
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo $appointment_date." ".$row0['appointment_time']; ?>
                                        </td>
                                        <td>
                                            <span class="status badge bg-warning" style="text-align: center;">
                                                <?php echo $row0['status']; ?></span>
                                        </td>
                                        <!-- <td>
                                            <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#assistant-edit-apointment-modal<?php //echo $row['appointment_id']?>"><span class="fa fa-edit fa-fw"></span>
                                            </button>
                                            
                                        
                                            <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#assistant-delete-service-modal<?php //echo $row['appointment_id']?>"><span class="fa fa-trash fa-fw"></span>
                                            </button>

                                            
                                        </td> -->
                                    </tr>
                                <?php
                                $no++;
                            }
                            ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="width: 30px">#</th>
                                <th></th>
                                <th>Patient</th>
                                <th>Service</th>
                                <th>Branch</th>
                                <th>Dentist</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                                <!-- <th></th> -->
                            <!-- <th></th> -->
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
<script>
  $(document).ready( function(){
        $(function () {
            $("#assistant-view-appointment-table").DataTable({
            "responsive": true,
            "lengthChange": true, 
            "autoWidth": true,
            // "pageLength": 50,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "scrollX": true,
            });
        });
        
  })
</script>
