<?php
    session_start();
    include "config/connection.php";

    $id = htmlentities(stripcslashes(mysqli_real_escape_string($conn, $_POST['id'])));
?>
<!-- View basic Info of Patient Modal -->
<div class="table-responsive">
    <table id="dentist-view-patient-appointments-table" class="table table-borderless table-hover">
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
            $stmt0 = $conn->prepare("SELECT * FROM appointment WHERE patient_id = ?");
            $stmt0->bind_param("s", $id);
            $stmt0->execute();
            $result0 = $stmt0->get_result();
            while($row0 = $result0->fetch_array(MYSQLI_ASSOC)){
                $time_input = strtotime($row0['appointment_time']);
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
                    <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#dentist-view-appointment-modal<?php echo $row0['appointment_id']; ?>"><span class="fa fa-eye fa-fw"></span></button>
                    <div class="modal fade" id="dentist-view-appointment-modal<?php echo $row0['appointment_id']; ?>" data-keyboard="false" data-backdrop="static">
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
                                <div class="modal-footer ">
                                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Decline</button>
                                    <button type="button" class="btn btn-primary" onclick="dentistAcceptAppointment(<?php //echo $row0['appointment_id']; ?>)">Accept</button> -->
                                    
                                </div>
                            </div>
                        <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
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
                    <?php
                    if($row0['status'] == 'Approved'){
                        ?>
                            <span class="status badge bg-success" style="text-align: center;"> <?php echo $row0['status']?></span>
                        <?php
                    }
                    else if($row0['status'] == 'Declined'){
                        ?>
                            <span class="status badge bg-danger" style="text-align: center;"> <?php echo $row0['status']?></span>
                        <?php
                    }
                    else if($row0['status'] == 'Pending'){
                        ?>
                            <span class="status badge bg-warning" style="text-align: center;"> <?php echo $row0['status']?></span>
                        <?php
                    }
                    else{
                        ?>
                        <span class="status badge bg-primary" style="text-align: center;"> <?php echo 'Succesful';?></span>
                        <?php
                    }
                    ?>
                </td>
                <!-- <td>
                    <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#dentist-edit-apointment-modal<?php //echo $row['appointment_id']?>"><span class="fa fa-edit fa-fw"></span>
                    </button>
                    

                    <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#dentist-delete-service-modal<?php //echo $row['appointment_id']?>"><span class="fa fa-trash fa-fw"></span>
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
<button type="button" class="btn btn-default float-right" onclick="dentistBacktoForm()">Close</button>
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
    })
</script>       
                    