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
    $branches = array();
?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="m-0">Deliveries</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                        <div class="card-header">                                           
                                            <div class="row float-right">
                                                <button class="btn btn-primary" data-toggle="modal" data-target="#assistant-add-delivery-modal1">Add Delivery</button>
                                                <!-- Assistant add delivery -->
                                                <div class="modal fade" id="assistant-add-delivery-modal1" data-keyboard="false" data-backdrop="static">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Add Delivery</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <label for="">Description: </label><br>
                                                                        <textarea class="form-control" type="text" name=""  id="del-description1"  rows="5" placeholder=".e.g. From Japan"></textarea><br>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        
                                                                        <div class="form-group">
                                                                            <label for="">Stocks left: </label><br>
                                                                            <input class="form-control" type="text" name="" id="del-quantity1" value="0" readonly>
                                                                        </div>
                                                                        <!-- <h4 for="">Products</h4> -->
                                                                        <div class="form-group">
                                                                            <label for="">Stock Name: </label><br>
                                                                            <select name="" id="del-product1" class="form-control" onchange="selectproduct();">
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
                                                                            <!-- <br> -->
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="">Enter Quantity:</label>
                                                                            <input type="number" class="form-control" value='' id="qty1" 
                                                                            onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">
                                                                            <!-- <br> -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="row">
                                                                            <div class="col-sm-12">
                                                                                <button type="button" class="btn btn-primary float-right" onclick="add_stock()">
                                                                                    <span class="fa fa-plus fa-fw"></span>
                                                                                </button>
                                                                                <br>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row" id="append-row">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-primary" onclick="addDeliveryAssistant()" id="btn-add-delivery" data-dismiss="modal">Add Delivery</button>
                                                                
                                                            </div>
                                                            <script>  
                                                                
                                                                stock_no = [];
                                                                stock_qty = [];
                                                                stock_name = [];

                                                                function add_stock(){
                                                                    if($('#qty1').val() == '' || $('#del-description1').val() == ''){
                                                                        alert('Fields should not leave empty!');
                                                                    }
                                                                    // else if(parseInt($('#del-product1').val()) < parseInt($('#qty1').val())){
                                                                    //     alert('Insufficient Stock!');
                                                                    // }   
                                                                    else if($('#del-product1').val() == '' || $('#del-product1').val() == '0'){
                                                                        alert('No Stocks available!');
                                                                    }
                                                                    else{
                                                                        $('#append-row').append(`<div class="col-sm-12" id="`+$('#del-product1').val()+`-`+$('#qty1').val()+`">
                                                                                                    <div class="alert alert-default" style="padding:0">
                                                                                                        <p>(x`+ $('#qty1').val() +`) `+ $('#del-product1 :selected').text() +`
                                                    
                                                                                                        <span class="fa fa-remove fa-fw float-right" onclick="remove_stock(`+$('#del-product1 :selected').val()+','+$('#qty1').val()+", '"+$('#del-product1 :selected').text()+`')"></span>
                                                                                                        </p>
                                                                                                    </div>
                                                                                                    
                                                                                                </div>`);
                                                                        stock_no.push($('#del-product1').val())
                                                                        stock_qty.push(parseInt($('#qty1').val()))
                                                                        stock_name.push($('#del-product1 :selected').text());
                                                                    }

                                                                }

                                                                function removeItem(array, itemToRemove) {
                                                                    const index = array.indexOf(itemToRemove);

                                                                    if (index !== -1) {
                                                                        array.splice(index, 1);
                                                                    }
                                                                    console.log("Updated Array: ", array);
                                                                }

                                                                function remove_stock(id,qty,name){
                                                                    $('#'+id+"-"+qty).remove();
                                                                    removeItem(stock_no,id.toString())
                                                                    removeItem(stock_qty,qty)
                                                                    removeItem(stock_name,name)
                                                                }

                                                                function addDeliveryAssistant(){

                                                                    prodname = $('#del-product').val();
                                                                    proddesc = $('#del-description1').val();
                                                                    prodqty = $('#qty').val();

                                                                    if(($('#del-product1').val() == '' || $('#qty1').val() == '' || $('#del-description1').val() == '')) {
                                                                        $('#btn-add-delivery').removeAttr('data-dismiss');
                                                                        alert('Fields should not leave empty!');
                                                                    }
                                                                    else if( (stock_no.length == 0 || stock_qty.length == 0) ){
                                                                        $('#btn-add-delivery').removeAttr('data-dismiss');
                                                                        alert('Stocks was not listed! Please add the stocks first');
                                                                    }
                                                                    else{
                                                                        form=new FormData();
                                                                        form.append('product_name', stock_name);
                                                                        form.append('product', stock_no);
                                                                        form.append('product_description', proddesc);
                                                                        form.append('product_qty', stock_qty);
                                                                        $.ajax({
                                                                            data: form,
                                                                            method: "POST",
                                                                            url: "assistant-add-delivery.php",
                                                                            processData:false,
                                                                            contentType:false,
                                                                            cache:false,
                                                                        }).done(function(data){
                                                                            $("#assistant-add-delivery-modal").modal('hide');
                                                                            $('.modal-backdrop').remove();
                                                                            prodname = "";
                                                                            prodqty = "";
                                                                            
                                                                            alert(data)
                                                                            
                                                                        });
                                                                        <?php if($position == 'dentist'){ echo "dentistDelivery();";}
                                                                        else if($position == 'assistant'){ echo "assistantDelivery();";}?>
                                                                        
                                                                    }
                                                                }

                                                                function selectproduct(){
                                                                    $('#del-product1').val();
                                                                    form=new FormData();
                                                                    form.append("product",$('#del-product1').val());
                                                                    form.append("product_name",$('#del-product1 :selected').text());
                                                                    $.ajax({
                                                                        type:"POST",
                                                                        url:'view-product-quantity.php',
                                                                        data:form,
                                                                        contentType:false,
                                                                        processData:false,
                                                                        cache:false,
                                                                    }).done( function(data){
                                                                        $('#del-quantity1').val(data);
                                                                    });
                                                                }
                                                                
                                                                function selecttool1(){
                                                                    form=new FormData();
                                                                    form.append("tool",$('#del-toolname').val());
                                                                    $.ajax({
                                                                        type:"POST",
                                                                        url:'view-tool-quantity.php',
                                                                        data:form,
                                                                        contentType:false,
                                                                        processData:false,
                                                                        cache:false,
                                                                    }).done( function(data){
                                                                        $('#del-quantity1').val(data);
                                                                    });
                                                                }
                                                            </script>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table id="assistant-view-delivery-table" class="table table-borderless table-hover">
                                            <thead>
                                            <tr>
                                                <th style="width: 35px">#</th>
                                                <th></th>
                                                <!-- <th>Stock #</th> -->
                                                <!-- <th>Stock Name</th> -->
                                                <th>Description</th>
                                                <!-- <th>Quantity</th> -->
                                                <th>Date</th>
                                                <th></th>
                                                <!-- <th></th> -->
                                                <!-- <th></th> -->
                                            </tr>
                                            </thead>
                                            <?php
                                                $time_input = strtotime(date('Y').'-'.date('m').'-'.'1');
                                                
                                                $time_input = strtotime($time_input);
                                                $date = getDate($time_input);
                                                $type = CAL_GREGORIAN;
                                                $year = date('Y'); // Year in 4 digit 2009 format.
                                                $month = $date['mon'];
                                                $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days
                                                $dayEnd = $year."-".date('m').'-'.$day_count;
                                                $dayStrt = $year."-".date('m').'-'.'1';
                                                $no_=1;
                                                $stmt=$conn->prepare('SELECT * FROM delivery WHERE branch_id = ? ORDER BY date DESC');
                                                $stmt->bind_param("s", $designation,);
                                                $stmt->execute();
                                                $result=$stmt->get_result();
                                                ?>
                                                    <tbody>
                                                <?php
                                                while($row=$result->fetch_Array(MYSQLI_ASSOC)){                                                     
                                        
                                                    $input = strtotime($row['date']);
                                                    $date = getDate($input);
                                                    $day = $date['mday'];
                                                    $month = $date['month'];
                                                    $year = $date['year'];
                                                    $_date = $month.' '.$day.', '.$year;
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $no_; ?></td>
                                                            <td>
                                                                <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#assistant-view-delivery-modal<?php echo $row['delivery_id']?>"><span class="fa fa-eye fa-fw"></span></button>
                                                                <!-- View Modal -->
                                                                <div class="modal fade" id="assistant-view-delivery-modal<?php echo $row['delivery_id']?>" data-keyboard="false" data-backdrop="static">
                                                                    <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                        <h4 class="modal-title">View Delivery Information</h4>
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
                                                                                                <label for="">Description: </label>
                                                                                                <textarea readonly class="form-control" rows="4" name="" style="resize:none"><?php echo $row['description']; ?></textarea>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-12">
                                                                                            <div class="table-responsive">
                                                                                                <table class="table table-hover" id="table_id-" width="100%">
                                                                                                    <thead>
                                                                                                        <tr>
                                                                                                            <th>#</th>
                                                                                                            <th>QTY</th>
                                                                                                            <th>Product</th>
                                                                                                            <!-- <th></th> -->
                                                                                                        </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                        <?php
                                                                                                            $inc=0;
                                                                                                            $no=1;
                                                                                                            $_product = explode(",", $row['stock_no']);
                                                                                                            $_qty = explode(",", $row['quantity']);
                                                                                                            $_name = explode(",", $row['product_name']);
                                                                                                            foreach($_product as $p){
                                                                                                                ?>
                                                                                                                    <TR>
                                                                                                                <?PHP
                                                                                                                    echo "<td>".$no."</td>";
                                                                                                                    echo "<td>".$_qty[$inc]."</td>";
                                                                                                                    echo "<td>".$_name[$inc]."</td>";
                                                                                                                ?>
                                                                                                                    <!-- <td>
                                                                                                                        <button class="btn btn-primary btn-xs" onclick="edit_delivery()">
                                                                                                                            <span class="fa fa-edit fa-fw"></span>
                                                                                                                        </button> -->
                                                                                                                        <!-- <button class="btn btn-danger btn-xs">
                                                                                                                            <span class="fa fa-trash fa-fw"></span>
                                                                                                                        </button> 
                                                                                                                    </td>-->
                                                                                                                    </TR>
                                                                                                                <?php
                                                                                                                $inc++;
                                                                                                                $no++;
                                                                                                            }
                                                                                                        ?>
                                                                                                    </tbody>
                                                                                                </table>

                                                                                            </div>
                                                                                        </div>

                                                                                        <!-- <div class="col-sm-12">
                                                                                            <div class="form-group">
                                                                                                <label for="">Stock #: </label>
                                                                                                <input readonly class="form-control" type="text" value="<?php //echo $row['stock_no']; ?>">
                                                                                            </div>
                                                                                        </div>
                                                                                        
                                                                                        <div class="col-sm-12">
                                                                                            <div class="form-group">
                                                                                                <label for="">Quantity: </label>
                                                                                                <input  readonly class="form-control" type="text" value="<?php //echo $row['quantity'] ?>" >
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-sm-12">
                                                                                            <div class="form-group">
                                                                                                <label for="">Date: </label>
                                                                                                <input readonly class="form-control" type="text" value="<?php //echo $_date; ?>">
                                                                                            </div>
                                                                                        </div> -->
                                                                                        <div class="col-sm-12" id="reciept-frame">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer justify-content-between">
                                                                            <button type="button" class="btn btn-default" data-dismiss="-modal" onclick="printThis(<?php echo $row['delivery_id']?>)">Print</button>
                                                                            <script>
                                                                                function printThis(id){
                                                                                    form = new FormData();
                                                                                    form.append('delivery_id',id)
                                                                                    ajax = $.ajax({
                                                                                        data: form,
                                                                                        url: 'assistant-print-delivery.php',
                                                                                        type: 'POST',
                                                                                        processData: false,
                                                                                        cache: false,
                                                                                        contentType: false,
                                                                                    })
                                                                                    $.when(ajax).done(function (ajax) {
                                                                                        // alert(ajax)
                                                                                        // var iframe = $('<iframe>');
                                                                                        // iframe.css('width','100%')
                                                                                        // iframe.css('height','100%')
                                                                                        // iframe.css('overflow','visible')
                                                                                        // iframe.attr('src','asdf.pdf');
                                                                                        // $('#reciept-frame').html('');
                                                                                        // $('#reciept-frame').html(iframe);
                                                                                        window.open('pdf_delivery/delivery.pdf', '_blank');
                                                                                    });
                                                                                }
                                                                            </script>
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        </div>
                                                                    </div>
                                                                    <!-- /.modal-content -->
                                                                    </div>
                                                                    <!-- /.modal-dialog -->
                                                                </div>
                                                                <!-- /.modal -->
                                                            </td>
                                                            <!-- <td>
                                                                <?php 
                                                                   
                                                                ?>
                                                            </td> -->
                                                            <td>
                                                                <?php echo $row['description']; 
                                                                   
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $_date; ?>
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#assistant-edit-delivery-modal<?php echo $row['delivery_id']?>"><span class="fa fa-edit fa-fw"></span>
                                                                </button>
                                                                <!-- Edit Modal -->
                                                                <div class="modal fade" id="assistant-edit-delivery-modal<?php echo $row['delivery_id']?>" data-keyboard="false" data-backdrop="static">
                                                                    <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                        <h4 class="modal-title">Edit Delivery Information</h4>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-sm-12">
                                                                                    <div class="row">
                                                                                        <!-- <div class="col-sm-12">
                                                                                            <div class="form-group">
                                                                                                <label for="">Stock #: </label>
                                                                                                <input class="form-control" type="text" id="edit-stock-<?php echo $row['delivery_id']?>" value="<?php //echo $row['stock_no']; ?>">
                                                                                            </div>
                                                                                        </div> -->
                                                                                        <div class="col-sm-12">
                                                                                            <div class="form-group">
                                                                                                <label for="">Description: </label>
                                                                                                <textarea class="form-control" rows="8" name="" id="edit-desc-<?php echo $row['delivery_id']?>" style="resize:none"><?php echo $row['description']; ?></textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                        <!-- <div class="col-sm-12">
                                                                                            <div class="form-group">
                                                                                                <label for="">Quantity: </label>
                                                                                                <input class="form-control"  name="" id="edit-qty-<?php //echo $row['delivery_id']?>" value="<?php //echo $row['quantity']; ?>">
                                                                                            </div>
                                                                                        </div> -->
                                                                                        <div class="col-sm-12">
                                                                                            <div class="form-group">
                                                                                                <label for="">Date: </label>
                                                                                                <input class="form-control" type="text" id="edit-date-<?php echo $row['delivery_id']?>" value="<?php echo $_date; ?>">
                                                                                            </div>
                                                                                        </div>
                                                                                        <script>
                                                                                            $('#edit-date-<?php echo $row['delivery_id']?>').datepicker(
                                                                                                {
                                                                                                    maxDate: -1,
                                                                                                    beforeShowDay: beforeShowDay,
                                                                                                    dateFormat: "MM dd, yy",
                                                                                                }
                                                                                            );
                                                                                            function beforeShowDay(sunday){
                                                                                                var day = sunday.getDay();
                                                                                                return [true, "calendar-background"];  
                                                                                            }
                                                                                        </script>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer float-right">
                                                                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                                        <button type="button" class="btn btn-primary" onclick="editDelivery(<?php echo $row['delivery_id']?>)" data-dismiss="modal">Save changes</button>
                                                                        <script>
                                                                            function editDelivery(id){
                                                                                if($('#edit-stock-'+id).val() == '' || $('#edit-stock-name-'+id).val() == '' || $('#edit-desc-'+id).val() == ''||$('#edit-date-'+id).val() == '' ||$('#edit-quantity-'+id).val() == ''){
                                                                                    alert('Fields should not leave empty.');
                                                                                }else{
                                                                                    form = new FormData();
                                                                                    form.append('stock', $('#edit-stock-'+id).val());
                                                                                    form.append('stock_name', $('#edit-stock-name-'+id).val());
                                                                                    form.append('description', $('#edit-desc-'+id).val());
                                                                                    form.append('quantity', $('#edit-qty-'+id).val());
                                                                                    form.append('date', $('#edit-date-'+id).val());
                                                                                    form.append('id', id);
                                                                                    $.ajax({
                                                                                        url: "assistant-edit-delivery.php",
                                                                                        type: "POST",
                                                                                        data: form,
                                                                                        processData: false,
                                                                                        contentType: false,
                                                                                        cache:false,
                                                                                    }).done( function(data){
                                                                                        alert(data);
                                                                                        $('#assistant-edit-delivery-modal'+id).modal('hide')
                                                                                        $('.modal-backdrop').remove();
                                                                                    })
                                                                                    <?php if($position == 'dentist'){ echo "dentistDelivery();";} else if($position == 'assistant'){ echo "assistantDelivery();";}?>
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
                                                                 
                                                                <!-- <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#assistant-delete-delivery-modal<?php echo $row['delivery_id']?>"><span class="fa fa-trash fa-fw"></span>
                                                                </button> -->
                                                                <!-- Delete Assistant -->
                                                                <div class="modal fade" id="assistant-delete-delivery-modal<?php echo $row['delivery_id']?>" data-keyboard="false" data-backdrop="static">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                            <h4 class="modal-title">Delete Delivery Informaton</h4>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                            <p>Do you want to continue? This action cannot be undone.&hellip;</p>
                                                                            </div>
                                                                            <div class="modal-footer justify-content-between">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                                            
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="deleteDelivery(<?php echo $row['delivery_id'] ?>)">Continue</button>
                                                                            </div>
                                                                        </div>
                                                                        <!-- /.modal-content -->
                                                                            <script>
                                                                                function deleteDelivery(id){
                                                                                    $.ajax({
                                                                                        url: 'assistant-delete-delivery.php',
                                                                                        method: 'post',
                                                                                        data: 'id='+id,
                                                                                    }).done( function(data){
                                                                                        alert(data);  
                                                                                        assistantDelivery();
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
                                                $no_++;
                                                }
                                                ?>
                                                    </tbody>
                                                <?php
                                            ?>
                                            <tfoot>
                                            <tr>
                                                <th style="width: 35px">#</th>
                                                <th></th>
                                                <!-- <th>Stock #</th> -->
                                                <!-- <th>Stock Name</th> -->
                                                <th>Description</th>
                                                <!-- <th>Quantity</th> -->
                                                <th>Date</th>
                                                <th></th>
                                                <!-- <th></th> -->
                                                <!-- <th></th> -->
                                            </tr>
                                            </tfoot>
                                            </table>
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
            $("#assistant-view-delivery-table").DataTable({
            "responsive": true,
            "lengthChange": true, 
            "autoWidth": true,
            "pageLength": 50,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "scrollX": true,
            "aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
            });
        });
        $(function () {
            $("#table_id").DataTable({
            "responsive": true,
            "lengthChange": true, 
            "autoWidth": true,
            "pageLength": 10,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "scrollX": true,
            "aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
            });
        });
  })
</script>
