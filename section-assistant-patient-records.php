<?php
session_start();
include 'config/connection.php';

$id = $_SESSION['id'];
$position = $_SESSION['position'];
$branch = $_SESSION['designation'];
?>
<?php
$tooth_numbers = [
    '18','17','16','15','14','13','12','11','21','22','23','24','25','26','27','28',
    '48','47','46','45','44','43','42','41','31','32','33','34','35','36','37','38',
    '55','54','53','52','51','61','62','63','64','65',
    '85','84','83','82','81','71','72','73','74','75'];
?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
    <div class="row">
        <div class="col-12">

        <div class="card mt-3">
            <div class="card-header">
                <h4 class="">Patient Records</h4>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <!-- <h4 style="text-align: center;"></h4> -->
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-hover table-borderless" id="assistant-view-patient-record-table">
                                        <thead>
                                            <th style="width: 30px;">#</th>
                                            <th>Patient</th>
                                            <th>Date</th>
                                            <!-- <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th> -->
                                        </thead>
                                        <tfoot>
                                            <th style="width: 30px;">#</th>
                                            <th>Patient</th>
                                            <th>Date</th>
                                            <!-- <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th> -->
                                        </tfoot>
                                        <tbody>
                                            <?php
                                                $i=1;

                                                $stmt0=$conn->prepare("SELECT * FROM patient_records WHERE branch_id = ? GROUP BY patient_id");
                                                $stmt0->bind_param('s',$branch,);
                                                $stmt0->execute();
                                                $result0=$stmt0->get_result();
                                                while($row0=$result0->fetch_assoc()){

                                                    $d=strtotime($row0['date']);
                                                    $dt=getDate($d);
                                                    $day=$dt['mday'];
                                                    $month=$dt['month'];
                                                    $year=$dt['year'];
                                                    $date=$month.' '.$day.', '.$year;

                                                    $stmt1=$conn->prepare("SELECT * FROM register_patient WHERE register_id=? ");
                                                    $stmt1->bind_param('s', $row0['patient_id']);
                                                    $stmt1->execute();
                                                    $result1=$stmt1->get_result();

                                                    if($row1=$result1->fetch_assoc())

                                                    $row ='<tr onclick="clickRow('.$row0['patient_id'].')">';
                                                    $row .='<td>'.$i.'</td>';
                                                    $row .='<td>'.strtoupper($row1['first_name'].' '.$row1['middle_name'].' '.$row1['last_name']).'</td>';
                                                    $row .='<td>'.$date.'</td>';
                                                    $row .='</tr>';

                                                    echo $row;
                                                    $i++;
                                                    echo '<input id="id" value="'.$row0['patient_id'].'" type="hidden">';
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
            $("#assistant-view-patient-record-table").DataTable({
            "responsive": true,
            "lengthChange": true, 
            "autoWidth": true,
            // "pageLength": 50,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "scrollX": true,
            });
        });
        

        
  })
    function clickRow(id){
        // window.open();

        form = new FormData();
        form.append('patient_id', id);
        ajax = $.ajax({
            type: 'POST',
            data: form,
            url: 'view-PDF.php',
            contentType: false,
            processData: false,
        })
        $.when(ajax).done( function( ajax){
                // alert(ajax);
            $('#modal-show-patient-records').modal('toggle');
            document.getElementById('myFrame').style.display="block";
            document.getElementById('myFrame').src="pdf_patient_record/doc_"+id+".pdf";
        })
    }
</script>
<div class="modal fade" id="modal-show-patient-records">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
        <!-- <div class="modal-header">
            <h4 class="modal-title">Default Modal</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div> -->
        <div class="modal-body">
            <div class="row">
                <iframe id="myFrame" style="display:none" width="100%" height="600"></iframe>
            </div> 
        </div>
        <!-- <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </div> -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->