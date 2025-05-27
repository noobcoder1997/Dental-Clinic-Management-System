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
                <h4 class="">Add Tools</h4>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="card">
                        <div class="card-header">
                            <div class="row float-right">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#dentist-add-tool-modal">Add Tool</button>
                                <!-- Add Modal -->
                                <div class="modal fade" id="dentist-add-tool-modal" data-keyboard="false" data-backdrop="static">
                                    <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h4 class="modal-title">Add Tool</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="tool-name">Tool: </label>
                                                        <input class="form-control" type="text" name="" id="a-tool-name">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="tool-desc">Description: </label>
                                                        <textarea class="form-control" rows="8" name="" id="a-tool-desc" style="resize:none"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer float-right">
                                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="addTool()" id="addTool">Add tool</button>
                                        <script>
                                            function addTool(){
                                                if($('#a-tool-name').val() == '' || $('#a-tool-desc').val() == ''){
                                                    alert('Fields should not leave empty.');
                                                    $("#addTool").removeAttr('data-dismiss');
                                                }else{
                                                    form = new FormData();
                                                    form.append('name', $('#a-tool-name').val());
                                                    form.append('description', $('#a-tool-desc').val());
                                                    $.ajax({
                                                        url: "dentist-add-tools.php",
                                                        type: "POST",
                                                        data: form,
                                                        processData: false,
                                                        contentType: false,
                                                    }).done( function(data){
                                                        $('#dentist-add-tool-modal').modal('toggle');
                                                        $(".modal-backdrop").remove();
                                                        alert(data);
                                                    })
                                                    addDentistTools();
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
                            <table id="dentist-view-tools-table" class="table table-borderless table-hover">
                              <thead>
                              <tr>
                                <th style="width: 30px">#</th>
                                <th></th>
                                <th>Tool</th>
                                <th>Description</th>
                                <th></th>
                              </tr>
                              </thead>
                              <tbody>
                                <?php
                                $no=1;
                                $stmt = $conn->prepare("SELECT * FROM tools WHERE branch_id = ?");
                                $stmt->bind_param('s', $branch);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while($row = $result->fetch_array(MYSQLI_ASSOC)){
                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td>
                                                <button class="btn btn-success btn-xs" data-target="#dentist-view-tool-modal<?php echo $row['tool_id']?>" data-toggle="modal"><span class="fa fa-eye fa-fw"></span></button>
                                                <!-- View Modal -->
                                              <div class="modal fade" id="dentist-view-tool-modal<?php echo $row['tool_id']?>" data-keyboard="false" data-backdrop="static">
                                                <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h4 class="modal-title">View Tool Information</h4>
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
                                                                            <label for="">Tool: </label>
                                                                            <input readonly class="form-control" type="text" id="v-tool-nim-<?php echo $row['tool_id']?>" value="<?php echo $row['tool_name']; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="">Description: </label>
                                                                            <textarea readonly class="form-control" rows="8" name="" id="v-tool-desc-<?php echo $row['tool_id']?>" style="resize:none"><?php echo $row['description']; ?></textarea>
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
                                            <td><?php echo $row['tool_name']; ?></td>
                                            <td><?php echo $row['description']; ?></td>
                                            <td>
                                              <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#dentist-edit-tool-modal<?php echo $row['tool_id']?>"><span class="fa fa-edit fa-fw"></span>
                                              </button>

                                            <!-- Edit Modal -->
                                              <div class="modal fade" id="dentist-edit-tool-modal<?php echo $row['tool_id']?>" data-keyboard="false" data-backdrop="static">
                                                <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h4 class="modal-title">Edit Tool Information</h4>
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
                                                                            <label for="">Tool: </label>
                                                                            <input class="form-control" type="text" id="tool-nim-<?php echo $row['tool_id']?>" value="<?php echo $row['tool_name']; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="">Description: </label>
                                                                            <textarea class="form-control" rows="8" name="" id="tool-desc-<?php echo $row['tool_id']?>" style="resize:none"><?php echo $row['description']; ?></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer float-right">
                                                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="editTools(<?php echo $row['tool_id']?>)" id="editTool">Save changes</button>
                                                    <script>
                                                        function editTools(id){
                                                            if($('#tool-nim'+id).val() == '' || $('#tool-desc'+id).val() == ''){
                                                                alert('Fields should not leave empty.');
                                                                $("#editTool").removeAttr('data-dismiss');
                                                            }else{
                                                                form = new FormData();
                                                                form.append('name', $('#tool-nim-'+id).val());
                                                                form.append('description', $('#tool-desc-'+id).val());
                                                                form.append('id', id);
                                                                $.ajax({
                                                                    url: "dentist-edit-tools.php",
                                                                    type: "POST",
                                                                    data: form,
                                                                    processData: false,
                                                                    contentType: false,
                                                                }).done( function(data){
                                                                    if(data != ''){
                                                                        $("#dentist-edit-tool-modal"+id).modal('toggle');
                                                                        $(".modal-backdrop").remove();
                                                                        alert(data)
                                                                    }
                                                                })
                                                                addDentistTools();
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
                                              <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#dentist-delete-tool-modal<?php echo $row['tool_id']?>"><span class="fa fa-trash fa-fw"></span>
                                              </button>

                                              <!-- Delete Assistant -->
                                                <div class="modal fade" id="dentist-delete-tool-modal<?php echo $row['tool_id']?>" data-keyboard="false" data-backdrop="static">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <h4 class="modal-title">Delete Tool Informaton</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            </div>
                                                            <div class="modal-body">
                                                            <p>Do you want to continue? This action cannot be undone.&hellip;</p>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="deleteTool(<?php echo $row['tool_id'] ?>)">Continue</button>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                            <script>
                                                                function deleteTool(id){
                                                                    $.ajax({
                                                                        url: 'dentist-delete-tool.php',
                                                                        method: 'post',
                                                                        data: 'id='+id,
                                                                    }).done( function(data){
                                                                        alert(data); 
                                                                        addDentistTools();  
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
                                <th>Tool</th>
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
            $("#dentist-view-tools-table").DataTable({
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