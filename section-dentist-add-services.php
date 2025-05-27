<?php
    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];   
    $branch = $_SESSION['branch'];
?>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card mt-3">
              <div class="card-header">
                <!-- <h3 class="card-title">Dental Clinic Branch</h3> -->
                <h4 class="">Add Services</h4>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="card">
                        <div class="card-header">
                            <div class="row float-right">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#dentist-add-service-modal">Add Service</button>
                                <!-- Add Modal -->
                                <div class="modal fade" id="dentist-add-service-modal" data-keyboard="false" data-backdrop="static">
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
                                                        <label for="service-name">Service: </label>
                                                        <input class="form-control" type="text" name="" id="service-name">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="service-desc">Description: </label>
                                                        <textarea class="form-control" rows="8" name="" id="service-desc" style="resize:none"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer float-right">
                                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="addService()" id="addService">Add Service</button>
                                        <script>
                                            function addService(){
                                                if($('#service-name').val() == '' || $('#service-desc').val() == ''){
                                                    alert('Fields should not leave empty.');
                                                    $("#addService").removeAttr('data-dismiss');
                                                }else{
                                                    form = new FormData();
                                                    form.append('name', $('#service-name').val());
                                                    form.append('description', $('#service-desc').val());
                                                    $.ajax({
                                                        url: "dentist-add-services.php",
                                                        type: "POST",
                                                        data: form,
                                                        processData: false,
                                                        contentType: false,
                                                    }).done( function(data){
                                                        $('#dentist-add-service-modal').modal('toggle');
                                                        $('.modal-backdrop').remove();
                                                        alert(data);
                                                    })
                                                    addDentistService();
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
                        <div class="card-body">
                            <table id="dentist-view-services-table" class="table table-borderless table-hover">
                              <thead>
                              <tr>
                                <th style="width: 30px">#</th>
                                <th></th>
                                <th>Service</th>
                                <th>Description</th>
                                <th></th>
                              </tr>
                              </thead>
                              <tbody>
                                <?php
                                $no=1;
                                $stmt = $conn->prepare("SELECT * FROM services WHERE branch_id = ? ");
                                $stmt->bind_param('s',$branch);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while($row = $result->fetch_array(MYSQLI_ASSOC)){
                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td>
                                                <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#dentist-view-service-modal<?php echo $row['service_id']?>"><span class="fa fa-eye fa-fw"></span></button>
                                                <!-- View Modal -->
                                                <div class="modal fade" id="dentist-view-service-modal<?php echo $row['service_id']?>" data-keyboard="false" data-backdrop="static">
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
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="">Service: </label>
                                                                                <input class="form-control" type="text" value="<?php echo $row['service_name']; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="">Description: </label>
                                                                                <textarea class="form-control" rows="8" name="" style="resize:none"><?php echo $row['service_description']; ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer float-right">
                                                        <button type="button" class="btn btn-secodary" data-dismiss="modal">Close</button>
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
                                              <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#dentist-edit-service-modal<?php echo $row['service_id']?>"><span class="fa fa-edit fa-fw"></span>
                                              </button>

                                            <!-- Edit Modal -->
                                              <div class="modal fade" id="dentist-edit-service-modal<?php echo $row['service_id']?>" data-keyboard="false" data-backdrop="static">
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
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="">Service: </label>
                                                                            <input class="form-control" type="text" id="serv-nim-<?php echo $row['service_id']?>" value="<?php echo $row['service_name']; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="">Description: </label>
                                                                            <textarea class="form-control" rows="8" name="" id="serv-desc-<?php echo $row['service_id']?>" style="resize:none"><?php echo $row['service_description']; ?></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer float-right">
                                                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="editServices(<?php echo $row['service_id']?>)" id="editService">Save changes</button>
                                                    <script>
                                                        function editServices(id){
                                                            if($('#serv-nim'+id).val() == '' || $('#serv-desc'+id).val() == ''){
                                                                alert('Fields should not leave empty.');
                                                            }else{
                                                                form = new FormData();
                                                                form.append('name', $('#serv-nim-'+id).val());
                                                                form.append('description', $('#serv-desc-'+id).val());
                                                                form.append('id', id);
                                                                $.ajax({
                                                                    url: "dentist-edit-services.php",
                                                                    type: "POST",
                                                                    data: form,
                                                                    processData: false,
                                                                    contentType: false,
                                                                }).done( function(data){
                                                                    $("#dentist-edit-service-modal"+id).modal('toggle');
                                                                    $(".modal-backdrop").remove();
                                                                    alert(data);
                                                                })
                                                                addDentistService();
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
                                              <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#dentist-delete-service-modal<?php echo $row['service_id']?>"><span class="fa fa-trash fa-fw"></span>
                                              </button>

                                              <!-- Delete Assistant -->
                                                <div class="modal fade" id="dentist-delete-service-modal<?php echo $row['service_id']?>" data-keyboard="false" data-backdrop="static">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header ">
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
                                                                        url: 'dentist-delete-service.php',
                                                                        method: 'post',
                                                                        data: 'id='+id,
                                                                    }).done( function(data){
                                                                        alert(data);
                                                                        addDentistService(); 
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
                              <tfoot>
                              <tr>
                                <th style="width: 30px">#</th>
                                <th></th>
                                <th>Service</th>
                                <th>Description</th>
                                <th></th>
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
            $("#dentist-view-services-table").DataTable({
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