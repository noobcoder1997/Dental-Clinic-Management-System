<?php
    session_start();
    include 'config/connection.php';

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];
        
    if(!isset($_SESSION['designation'])){
        $designation = $_SESSION['branch'];
    }
    else{
        $designation = $_SESSION['designation'];
    }

    $months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
    $long_months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    
    $already_selected_value = date('Y');
    $earliest_year = 1950;
?>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card mt-3">
              <div class="card-header">
                <!-- <h3 class="card-title">Dental Clinic Branch</h3> -->
                <h4 class="">Damage Items</h4>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="card">
                        <div class="card-header">
                            <div class="row float-right">

                                <div class="btn-group">
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#assistant-add-damage-modal">Add Damage Items</button>
                                    
                                    <!-- Assistant add damage -->
                                    <div class="modal fade" id="assistant-add-damage-modal" data-keyboard="false" data-backdrop="static">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Add Damage Items</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <label for="">Description: </label><br>
                                                            <textarea class="form-control" type="text" name=""  id="del-description"  rows="5" placeholder=".e.g. Nalumo ang syringe, expire ang medisina"></textarea><br>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <!-- <h4 for="">Products</h4> -->
                                                            <div class="form-group">
                                                                <label for="">Stocks left: </label><br>
                                                                <input class="form-control" type="number" name="" id="del-quantity" readonly>
                                                                <br>
                                                                <label for="">Stock Name: </label><br>
                                                                <select name="" id="del-product" class="form-control" onchange="selecttool();">
                                                                    <option value=""></option>
                                                                    <?php
                                                                        $status = '1';
                                                                        $query = "SELECT * FROM product WHERE branch_id = ? ";
                                                                        $stmt = $conn->prepare($query);
                                                                        $stmt->bind_param('s',$designation);
                                                                        $stmt->execute();
                                                                        $result=$stmt->get_result();
                                                                        while($row=$result->fetch_array(MYSQLI_ASSOC)){
                                                                            echo "<option value='$row[product_id]' >$row[product_name]</option>";
                                                                        }
                                                                        $query = "SELECT * FROM tools WHERE branch_id = ? ";
                                                                        $stmt = $conn->prepare($query);
                                                                        $stmt->bind_param('s',$designation);
                                                                        $stmt->execute();
                                                                        $result=$stmt->get_result();
                                                                        while($row=$result->fetch_array(MYSQLI_ASSOC)){
                                                                            echo "<option value='$row[tool_id]' >$row[tool_name]</option>";
                                                                        }
                                                                    ?>
                                                                </select>
                                                                <br>
                                                                <label for="">Enter Quantity:</label>
                                                                <input type="number" class="form-control" value='' id="qty" 
                                                                onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                    <button type="button" class="btn btn-primary" onclick="addDamageAssistant()" id="btn-add-delv" data-dismiss="modal">Add Damage Item</button>
                                                    
                                                </div>
                                                <script>  
                                                    
                                                    function disabledOption(){
                                                        var selected = []; 
                                                        var $select = $("#del-product");
                                                            $.each($select, function(index, select) {           
                                                                if (select.value !== "") { selected.push(select.value); }
                                                            });         
                                                        $("option").prop("disabled", false);         
                                                        for (var index in selected) { 
                                                            $('option[value="'+selected[index]+'"]').prop("disabled", true); 
                                                        }
                                                    }

                                                    function addDamageAssistant(){
                                                        $('#btn-add-delv').removeAttr('data-dismiss');

                                                        prodname = $('#del-product').val();
                                                        stock = $('#del-product :selected').text();
                                                        proddesc = $('#del-description').val();
                                                        quantity = $('#del-quantity').val();
                                                        prodqty = $('#qty').val();
                                                        
                                                        if((prodname=='' || prodqty=='' || proddesc == '' )) {
                                                            alert('Fields should not leave empty!');
                                                            $('#btn-add-delv').removeAttr('data-dismiss');
                                                        }
                                                        else if(quantity == ''){
                                                            alert('No Stocks Available!');
                                                            $('#btn-add-delv').removeAttr('data-dismiss');
                                                        }
                                                        else if(parseInt(prodqty) > parseInt(quantity)){
                                                            alert('Insufficient Stocks!');
                                                            $('#btn-add-delv').removeAttr('data-dismiss');
                                                        }
                                                        else{
                                                            $('#btn-add-delv').removeAttr('data-dismiss');
                                                            form=new FormData();
                                                            form.append('product', prodname);
                                                            form.append('product_name', stock);
                                                            form.append('product_description', proddesc);
                                                            form.append('product_qty', prodqty);
                                                            $.ajax({
                                                                data: form,
                                                                method: "POST",
                                                                url: "assistant-add-damage.php",
                                                                processData:false,
                                                                contentType:false,
                                                                cache:false,
                                                            }).done(function(data){
                                                                $("#assistant-add-delivery-modal").modal('toggle');
                                                                $('.modal-backdrop').remove();
                                                                assistantDamageProducts();
                                                                prodname = "";
                                                                prodqty = "";
                                                                
                                                                alert(data)
                                                            });
                                                            <?php if($position == 'assistant'){ echo "assistantDamageProducts();"; }else if($position == 'dentist'){ echo "dentistDamageProducts();"; }?>
                                                        }
                                                    }

                                                    function selecttool(){
                                                        $('#del-product').val();
                                                        $('#del-description').val();
                                                        form=new FormData();
                                                        form.append("product",$('#del-product').val());
                                                        form.append("product_name",$('#del-product :selected').text());
                                                        $.ajax({
                                                            type:"POST",
                                                            url:'view-tool-quantity-0.php',
                                                            data:form,
                                                            contentType:false,
                                                            processData:false,
                                                            cache:false,
                                                        }).done( function(data){
                                                            $('#del-quantity').val(data);
                                                        });
                                                    }
                                                </script>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>

                                    <!-- <button class="btn btn-default" data-toggle="modal" data-target="#modal-damage-items">Print</button> -->

                                    <div class="modal fade" id="modal-damage-items" data-keyboard="false" data-backdrop="static">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Print Inventory</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- <div class="row">
                                                    <div class="col-sm-12">
                                                        <label for="">From:</label>
                                                        <input type="text" id="sched-from-datepicker" class="form-control" placeholder="Pick a Date"><br>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label for="">To:</label>
                                                        <input type="text" id="sched-to-datepicker" class="form-control" placeholder="Pick a Date"><br>
                                                    </div>
                                                </div>
                                                OR -->
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <label for="">Month</label>
                                                                <select name="" id="month" class="form-control">
                                                                    <option value=""></option>
                                                                    <?php
                                                                        $index=0;
                                                                        foreach($months as $month){
                                                                            echo "<option value='".$month."'>".$long_months[$index]."</option>";
                                                                            $index++;
                                                                        }
                                                                    ?>
                                                                </select>    
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <label for="">Year</label>
                                                                <select name="" id="year" class="form-control">
                                                                    <option value=""></option>
                                                                    <?php
                                                                        foreach (range(date('Y'), $earliest_year) as $x) {
                                                                            print '<option value="'.$x.'"'.($x === $already_selected_value ? ' selected=selected ' : '').'>'.$x.'</option>';
                                                                        }
                                                                    ?>
                                                                </select>    
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <!-- <iframe id="iframe" alt="PDF not available" frameborder="0" style="display:none; width:100%; height:80vh"></iframe> -->
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary" onclick="printThis()" >Print Document</button>
                                            </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                     <script>
                                        function printThis(){
                                            form=new FormData()
                                            // form.append('from',$("#sched-from-datepicker").val())
                                            form.append('to',$('#sched-to-datepicker').val())
                                            form.append('month',$('#month').val())
                                            form.append('year',$('#year').val())
                                            ajax = $.ajax({
                                                type: "POST",
                                                data: form,
                                                url: "assistant-print-damage-items.php",
                                                cache: false,
                                                contentType:false,
                                                processData:false,
                                            });
                                            $.when(ajax).done(function (ajax){
                                                alert(ajax);
                                                window.open("pdf_damage/damage_item.pdf","_blank");
                                            });
                                        }
                                     </script>
                                </div>

                            </div>
                        </div>
                        <div class="card-body">
                        <table id="assistant-view-damage-table" class="table table-borderless table-hover">
                              <thead>
                                <tr>
                                    <th style="width: 30px">#</th>
                                    <!-- <th></th> -->
                                    <th>Item</th>
                                    <th>Description</th>
                                    <!-- <th>Damaged</th>
                                    <th>Stocks</th> -->
                                    <th>Date</th>
                                    <th></th>
                                    <!-- <th></th> -->
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                // $time_input = strtotime(date('Y').'-'.$m.'-'.'1');
                                            
                                // $time_input = strtotime($time_input);
                                // $date = getDate($time_input);
                                // $type = CAL_GREGORIAN;
                                // $year = date('Y'); // Year in 4 digit 2009 format.
                                // $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days
                                // $dayEnd = $year."-".$m.'-'.$day_count;
                                // $dayStrt = $year."-".$m.'-'.'1';

                                $no=1;
                                $status='1';
                                $stmt = $conn->prepare("SELECT * FROM damage_items WHERE branch_id = ? ");
                                $stmt->bind_param('s',$designation);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while($row = $result->fetch_array(MYSQLI_ASSOC)){
                                    $time_input = strtotime($row['date']);
                                    $date = getDate($time_input);
                                    $mDay = $date['mday'];
                                    $Month = $date['month'];
                                    $Year = $date['year'];
                                    $_date = $Month.' '.$mDay.', '.$Year; //format the date example {December 2, 2024}
                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td><?php echo $row['product_name']; ?></td>
                                            <td><?php echo $row['description']; ?></td>
                                            <td><?php echo $_date; ?></td>
                                            <td>
                                                <?php if($row['date'] == date("Y-m-d")){ ?>
                                                    <!-- <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#assistant-edit-damage-modal<?php echo $row['item_id']?>"><span class="fa fa-edit fa-fw"></span>
                                                    </button> -->
                                                <?php } ?>

                                            <!-- Edit Modal -->
                                              <div class="modal fade" id="assistant-edit-damage-modal<?php echo $row['item_id']?>" data-keyboard="false" data-backdrop="static">
                                                <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h4 class="modal-title">Edit Damage Information</h4>
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
                                                                            <label for="">Product: </label>
                                                                            <select name="" id="item-<?php echo $row['item_id']?>" class="form-control">
                                                                                <?php
                                                                                    $stmt0=$conn->prepare('SELECT * FROM product WHERE branch_id = ?');
                                                                                    $stmt0->bind_param('s',$designation);
                                                                                    $stmt0->execute();
                                                                                    $result0=$stmt0->get_result();
                                                                                    while($row0=$result0->fetch_assoc()){
                                                                                ?>
                                                                                    <option value="<?php echo $row0['product_id']?>"><?php echo $row0['product_name']?></option>
                                                                                <?php
                                                                                    }   
                                                                                ?>
                                                                                <?php
                                                                                    $stmt1=$conn->prepare('SELECT * FROM tools WHERE branch_id = ?');
                                                                                    $stmt1->bind_param('s',$designation);
                                                                                    $stmt1->execute();
                                                                                    $result1=$stmt1->get_result();
                                                                                    while($row1=$result1->fetch_assoc()){
                                                                                ?>
                                                                                    <option value="<?php echo $row1['tool_id']?>"><?php echo $row1['tool_name']?></option>
                                                                                <?php
                                                                                    }   
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="">Quantity: </label>
                                                                            <input class="form-control" type="text" id="qty-<?php echo $row['item_id']?>" value="<?php echo $row['quantity']?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="">Description: </label>
                                                                            <textarea class="form-control" rows="8" name="" id="desc-<?php echo $row['item_id']?>" style="resize:none"><?php echo $row['description']; ?></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer float-right">
                                                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="editAssistantProducts(<?php echo $row['item_id']?>)" data-dismiss="modal" id="btn-edit-prod">Save changes</button>
                                                    <script>
                                                        function editAssistantProducts(id){
                                                            if($('#item-'+id).val() == '' || $('#qty-'+id).val() == '' || $('#desc-'+id).val() == '' || $('#qty-'+id).val() == '0'){
                                                                alert('Fields should not leave empty.');
                                                                $("#btn-edit-prod").removeAttr('data-dismiss');
                                                            }else{
                                                                form = new FormData();
                                                                form.append('p_id', $('#item-'+id).val());
                                                                form.append('name', $('#item-'+id+' :selected').text());
                                                                form.append('quantity', $('#qty-'+id).val());
                                                                form.append('old_quantity', <?php echo $row['quantity'] ?>);
                                                                form.append('description', $('#desc-'+id).val());
                                                                form.append('inv_id', id);
                                                                form.append('date', <?php echo $row['date'];?>);
                                                                $.ajax({
                                                                    url: "assistant-edit-damage-products.php",
                                                                    type: "POST",
                                                                    data: form,
                                                                    processData: false,
                                                                    contentType: false,
                                                                }).done( function(data){
                                                                    $('#assistant-edit-damage-modal'+id).modal('hide');
                                                                    $('.modal-backdrop').remove();
                                                                    alert(data);
                                                                })
                                                                <?php if($position == 'assistant'){ echo "assistantDamageProducts();"; }else if($position == 'dentist'){ echo "dentistDamageProducts();"; }?>
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
                                              <!-- <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#assistant-delete-product-modal<?php //echo $row['item_id']?>"><span class="fa fa-trash fa-fw"></span>
                                              </button> -->

                                              <!-- Delete Assistant -->
                                                <div class="modal fade" id="assistant-delete-product-modal<?php echo $row['item_id']?>" data-keyboard="false" data-backdrop="static">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <h4 class="modal-title">Delete Product Informaton</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            </div>
                                                            <div class="modal-body">
                                                            <p>Do you want to continue? This action cannot be undone.&hellip;</p>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="deleteAssistantDamage(<?php echo $row['item_id'] ?>)">Continue</button>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                            <script>
                                                                function deleteAssistantDamage(id){
                                                                    $.ajax({
                                                                        url: 'assistant-delete-damage-items.php',
                                                                        method: 'post',
                                                                        data: 'id='+id,
                                                                    }).done( function(data){
                                                                        alert(data);
                                                                        assistantDamageProducts(); 
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
                                    <!-- <th></th> -->
                                    <th>Item</th>
                                    <th>Description</th>
                                    <!-- <th>Damaged</th>
                                    <th>Stocks</th> -->
                                    <th>Date</th>
                                    <th></th>
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
            $("#assistant-view-damage-table").DataTable({
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