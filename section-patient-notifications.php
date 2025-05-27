<?php
    include('config/connection.php');
    session_start();

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];
?>

<div class="container-fluid">
    <?php
        if($position == 'patient'){
        ?>
            <div class="row"  id="container">
                <div class="col-sm-12">
                    <div class="card mt-3">
                        <div class="card-header">
                            <h4 class="float-left">Notifications</h4>
                        </div>
                        <div class="card-body">
                            <div class="row row-notif">
                                <div class="col-sm-12">
                                    <a class="btn btn-primary float-right new-notif" onclick=" $('#patient-new-notif-modal').modal('show');">New Notifications</a>
                                    <br>
                                    <br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <h5>Recent Notification</h5>
                                </div>
                            </div>
                           
                            <?php
                                $st = '0';
                                $stmt = $conn->prepare("SELECT * FROM notifications WHERE patient_id = ? AND status = ? ORDER BY notif_date DESC");
                                $stmt->bind_param('ss', $id,$st);
                                $stmt->execute();
                                $result=$stmt->get_result();
                                
                                while($row = $result->fetch_assoc()){
                                $date = strtotime($row['notif_date']);
                                $_date = getDate($date);
                                $day = $_date['mday'];
                                $month = $_date['month'];
                                $year = $_date['year'];
                                $date = $month.' '.$day.', '.$year;
                            ?>
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row float-right">
                                            <a class=""><?php echo $date; ?></a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <?php     
                                                echo '<div>'.$row['message'].'</div>';
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                }
                            ?>
                        </div>      
                        <div class="card-footer"></div>
                    </div>
                </div>
            </div>
        <?php
        }
    ?>
</div>

<div class="modal fade" id="patient-new-notif-modal" data-keyboard="false" data-backdrop="false"> 
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">New Notifications</h4>
            </div>
            <div class="modal-body">
                <?php
                $st='1';
                // $notif_id="";
                $stmt=$conn->prepare("SELECT * FROM notifications WHERE patient_id = ? AND status = ? ORDER BY notif_date ");
                $stmt->bind_param("ss",$id,$st);
                $stmt->execute();
                $result=$stmt->get_result();

                while($row=$result->fetch_assoc()){  
                    $_date = strtotime($row['notif_date']);
                    $date = getDate($_date);
                    $day = $date['mday'];
                    $month = $date['month'];
                    $year = $date['year'];
                    $_date = $month.' '.$day.', '.$year;
                    // $notif_id = $row['id'];
                ?>
                    <div id="" class="alert alert-secondary">
                        <span><?php echo $_date; ?></span>
                        <br>
                        <br>
                        <?php echo $row['message']; ?>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default float-right" data-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>

    $('#patient-new-notif-modal').on('hidden.bs.modal', function() {
        $.ajax({
            url: 'change-notif-status.php',
            data: "id="+"<?php echo $id; ?>",//patient_id
            type: 'post',
            cache:false,
        }).done(function(data){
            // alert(data)
            $("#notif-count").html(data);
        });
        notifications();
    });

    $(document).ready(function(){
        if($("#notif-count").html() == '0' || $("#notif-count").html() == undefined || $("#notif-count").html() == null){
            $(".row-notif").css('display', 'none');
            $("#notif-count").hide()
        }
        else{
            $(".new-notif").html($("#notif-count").html()+' New Notifications')
        }
    })
</script>