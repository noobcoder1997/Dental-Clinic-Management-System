<?php
    session_start();
    require('config/connection.php');
    $id = $_SESSION['id'];
    $position = $_SESSION['position'];
    $designation = $_SESSION['designation'];
?>
<div class="container-fluid">
    <?php
        if($position == 'assistant'){
        ?>
            <div class="row">
            <div class="col-sm-12">
                <div class="card mt-3">
                <div class="card-header">
                    <h4 class="">Service Transaction</h4>
                </div>
                <div class="card-body">
                    <div class="card">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive">
                                <table id="assistant-view-branch-table" class="table table-borderless table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 30px">#</th>
                                            <th></th>
                                            <th>Patient</th>
                                            <th>Service</th>
                                            <th>Branch</th>
                                            <th>Dentist</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no=1;
                                        $status = 'Approved';
                                        $stmt = $conn->prepare("SELECT * FROM appointment WHERE status = ? AND branch_id = ? ");
                                        $stmt->bind_param('ss', $status,$designation);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        while($row = $result->fetch_array(MYSQLI_ASSOC)){
                                            ?>
                                                <tr>
                                                    <td><?php echo $no; ?></td>
                                                    <td>
                                                        <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#_dentist-view-service-trans-modal<?php echo $row['appointment_id']?>"><span class="fa fa-eye fa-fw"></span></button>
                                                    
                                                    </td>
                                                    <td>
                                                        <?php echo $row['patient_id']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['service_id']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['branch_id']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['dentist_id']; ?>
                                                    </td>
                                                    <td>
                                                    <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#dentist-edit-branch-modal<?php echo $row['branch_id']?>"><span class="fa fa-edit fa-fw"></span>
                                                    </button>
                                                    <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#dentist-delete-branch-modal<?php echo $row['branch_id']?>"><span class="fa fa-trash fa-fw"></span>
                                                    </button>
                                                    </td>
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
                                        <th></th>
                                    </tr>
                                    </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer"></div> 
                    </div>
                <div class="card-footer"></div>
                </div>
            </div>
            </div>
        <?php
        }
    ?>
</div>
<script>
  $(document).ready( function(){
        $(function () {
            $("#assistant-view-branch-table").DataTable({
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