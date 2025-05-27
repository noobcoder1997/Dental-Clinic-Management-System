<?php
    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];
    $designation = $_SESSION['designation'];
    $branches = array();
?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="">Add Services</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                        <div class="card-header">
                                            <div class="row float-right">
                                                <button class="btn btn-primary" data-toggle="modal" data-target="#assistant-add-service">Add Services</button>
                                                <!-- Assistant Add service -->
                                                <div class="modal fade" id="assistant-add-service" data-keyboard="false" data-backdrop="static">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Add Service</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                <label for="">Service Name: </label><br>
                                                                <input class="form-control" type="text" name="" id="s-name"><br>
                                                                <label for="">Service Description: </label><br>
                                                                <input class="form-control" type="text" name="" id="s-desc"><br>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                            <button type="button" class="btn btn-primary" onclick="addServiceAssistant()" id="btn-add-servcs" data-dismiss="modal">Save changes</button>
                                                            <script>
                                                                function addServiceAssistant(){

                                                                    s_name = $('#s-name').val();
                                                                    s_desc = $('#s-desc').val();

                                                                    if(s_name==''|| s_desc==''){
                                                                        alert('Fields should not leave empty!');
                                                                        $('#btn-add-servcs').removeAttr('data-dismiss');
                                                                    }
                                                                    else{
                                                                        data = 'name='+s_name+'&desc='+s_desc;
                                                                        $.ajax({
                                                                            data: data,
                                                                            method: "POST",
                                                                            url: "assistant-add-service.php",
                                                                        }).done(function(data){
                                                                            // $('#assistant').html(data);
                                                                            $("#assistant-add-service").modal('hide');
                                                                            $('.modal-backdrop').remove();
                                                                            assistantAddServices();
                                                                            alert(data)
                                                                        });
                                                                    }
                                                                }
                                                            </script>
                                                        </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->
                                            </div>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="assistant-view-services-table" class="table table-borderless table-hover">
                                                <thead>
                                                <tr>
                                                    <th style="width: 35px">#</th>
                                                    <th></th>
                                                    <th>Service</th>
                                                    <th>Description</th>
                                                    <th></th>
                                                    <!-- <th></th> -->
                                                </tr>
                                                </thead>
                                                <?php
                                                    $no=1;
                                                    $stmt=$conn->prepare('SELECT * FROM services WHERE branch_id = ?');
                                                    $stmt->bind_param('s',$designation);
                                                    $stmt->execute();
                                                    $result=$stmt->get_result();
                                                    ?>
                                                        <tbody>
                                                    <?php
                                                    while($row=$result->fetch_Array(MYSQLI_ASSOC)){
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $no; ?></td>
                                                                <td>
                                                                    <button class="btn btn-success btn-xs"  data-toggle="modal" data-target="#assistant-view-service-modal<?php echo $row['service_id']?>"><span class="fa fa-eye fa-fw"></span></button>
                                                                    <!-- Edit Modal -->
                                                                    <div class="modal fade" id="assistant-view-service-modal<?php echo $row['service_id']?>" data-keyboard="false" data-backdrop="static">
                                                                        <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                            <h4 class="modal-title">View Service Information</h4>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="row">
                                                                                    <div class="col-sm-12">
                                                                                        <div class="row">
                                                                                            <div class="col-sm-6">
                                                                                                <div class="form-group">
                                                                                                    <label for="">Service: </label>
                                                                                                    <input readonly class="form-control" type="text"  value="<?php echo $row['service_name']; ?>">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-sm-12">
                                                                                                <div class="form-group">
                                                                                                    <label for="">Description: </label>
                                                                                                    <textarea readonly class="form-control" rows="8" name=""  style="resize:none"><?php echo $row['service_description']; ?></textarea>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer float-right">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div>
                                                                        <!-- /.modal-content -->
                                                                        </div>
                                                                        <!-- /.modal-dialog -->
                                                                    </div>
                                                                    <!-- /.modal -->
                                                                </td>
                                                                <td><?php echo $row['service_name']; ?></td>
                                                                <td><?php echo $row['service_description']; ?></td>
                                                                <td>
                                                                    <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#assistant-edit-service-modal<?php echo $row['service_id']?>"><span class="fa fa-edit fa-fw"></span>
                                                                    </button>
                                                                    <!-- Edit Modal -->
                                                                    <div class="modal fade" id="assistant-edit-service-modal<?php echo $row['service_id']?>" data-keyboard="false" data-backdrop="static">
                                                                        <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                            <h4 class="modal-title">Edit Service Information</h4>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="row">
                                                                                    <div class="col-sm-12">
                                                                                        <div class="row">
                                                                                            <div class="col-sm-6">
                                                                                                <div class="form-group">
                                                                                                    <label for="">Service: </label>
                                                                                                    <input class="form-control" type="text" id="as-serv-nim-<?php echo $row['service_id']?>" value="<?php echo $row['service_name']; ?>">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-sm-12">
                                                                                                <div class="form-group">
                                                                                                    <label for="">Description: </label>
                                                                                                    <textarea class="form-control" rows="8" name="" id="as-serv-desc-<?php echo $row['service_id']?>" style="resize:none"><?php echo $row['service_description']; ?></textarea>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer float-right">
                                                                            <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                                            <button type="button" class="btn btn-primary" onclick="editServices(<?php echo $row['service_id']?>)" id="btn-edit-servcs" data-dismiss="modal">Save changes</button>
                                                                            <script>
                                                                                function editServices(id){
                                                                                    if($('#as-serv-nim'+id).val() == '' || $('#as-serv-desc'+id).val() == ''){
                                                                                        alert('Fields should not leave empty.');
                                                                                        $('#btn-edit-servcs').removeAttr("data-dismiss")
                                                                                    }else{
                                                                                        form = new FormData();
                                                                                        form.append('name', $('#as-serv-nim-'+id).val());
                                                                                        form.append('description', $('#as-serv-desc-'+id).val());
                                                                                        form.append('id', id);
                                                                                        $.ajax({
                                                                                            url: "assistant-edit-services.php",
                                                                                            type: "POST",
                                                                                            data: form,
                                                                                            processData: false,
                                                                                            contentType: false,
                                                                                        }).done( function(data){
                                                                                            $("#assistant-edit-service-modal"+id).modal('hide');
                                                                                            $('.modal-backdrop').remove();
                                                                                            assistantAddServices();
                                                                                            alert(data);
                                                                                        })
                                                                                    }
                                                                                }
                                                                            </script>
                                                                            </div>
                                                                        </div>
                                                                        <!-- /.modal-content -->
                                                                        </div>
                                                                        <!-- /.modal-dialog -->
                                                                    </div>
                                                                    <!-- /.modal -->

                                                                    <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#assistant-delete-service-modal<?php echo $row['service_id']?>"><span class="fa fa-trash fa-fw"></span>
                                                                    </button>
                                                                    <!-- Delete Assistant -->
                                                                    <div class="modal fade" id="assistant-delete-service-modal<?php echo $row['service_id']?>" data-keyboard="false" data-backdrop="static">
                                                                        <div class="modal-dialog">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                <h4 class="modal-title">Delete Service Informaton</h4>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                <p>Do you want to continue? This action cannot be undone.&hellip;</p>
                                                                                </div>
                                                                                <div class="modal-footer justify-content-between">
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="deleteService(<?php echo $row['service_id'] ?>)">Continue</button>
                                                                                </div>
                                                                            </div>
                                                                            <!-- /.modal-content -->
                                                                                <script>
                                                                                    function deleteService(id){
                                                                                        $.ajax({
                                                                                            url: 'assistant-delete-service.php',
                                                                                            method: 'post',
                                                                                            data: 'id='+id,
                                                                                        }).done( function(data){
                                                                                            alert(data);
                                                                                            assistantAddServices();   
                                                                                        })
                                                                                    }
                                                                                </script>
                                                                        </div>
                                                                        <!-- /.modal-dialog -->
                                                                    </div>
                                                                    <!-- /.modal -->
                                                                </td>
                                                            </tr>
                                                        <?php
                                                    $no++;
                                                    }
                                                    ?>
                                                        </tbody>
                                                    <?php
                                                ?>
                                                <tfoot>
                                                <tr>
                                                    <th style="width: 35px">#</th>
                                                    <th></th>
                                                    <th>Service</th>
                                                    <th>Description</th>
                                                    <th></th>
                                                    <!-- <th></th> -->
                                                </tr>
                                                </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <!-- /.card -->
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
            $("#assistant-view-services-table").DataTable({
            "paging":true,
            "responsive": true,
            "lengthChange": true, 
            "autoWidth": true,
            "pageLength": 50,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "scrollX": true,
            "lengthMenu": [[10, 25, 50, -1],[10, 25, 50, 'All']],
            "searching": true,
            "ordering": true,
            "info": true,
            });
        });
  })
</script>