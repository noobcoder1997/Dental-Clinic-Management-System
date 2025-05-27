<?php
session_start();
include 'config/connection.php';

$id = $_SESSION['id'];
$position = $_SESSION['position'];
$designation = $_SESSION['designation'];

$services = explode(",", $_POST['service']);
$products = explode(",", $_POST['product']);
$bill_id = $_POST['bill_id'];
$patient = $_POST['patient'];
$teeth_worked = explode(",", $_POST['tooth']);
$service_prices = explode(",",$_POST['service_price']);
$service_qty = explode(",",$_POST['service_qty']);
$product_qty = explode(",",$_POST['product_qty']);
$product_name = explode(",",$_POST['product_names']);
$price=0;
$price1=0;
$index=0;
$index1=0;
$x=0;

$patient_name='';

$stmt0=$conn->prepare("SELECT * FROM billing WHERE billing_id = ?");
$stmt0->bind_param('s',$bill_id);
$stmt0->execute();
$result0=$stmt0->get_result();
if($row0=$result0->fetch_assoc()){
    
    $stmt_0=$conn->prepare("SELECT * FROM register_patient WHERE register_id = ?");
    $stmt_0->bind_param('s',$row0['patient_id']);
    $stmt_0->execute();
    $result_0=$stmt_0->get_result();
    if($row_0=$result_0->fetch_assoc()){
        $patient_name = strtoupper($row_0['first_name'].' ' .$row_0['middle_name'].' ' .$row_0['last_name']);
    }
}
?>
<div class="modal fade" id="modal-transaction" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered  modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Transaction</h4>
            <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button> -->
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <h5 for="">Services:</h5>
                    <p id="service-empty"></p>
                    <div class="col-sm-12">
                        <div class="row" style="text-align: center;">
                            <div class="col-sm-1">
                                <b>QTY</b>
                            </div>
                            <div class="col-sm-6">
                                <b>Service</b>
                            </div>
                            <div class="col-sm-2">
                                <b>Price</b>
                            </div>
                            <div class="col-sm-2">
                                <b>Tooth</b>
                            </div>
                            <div class="col-sm-1">
                                <b>Action</b>
                            </div>
                        </div>
                    </div>
                    <?php
                        foreach($services as $service){
                        $stmt=$conn->prepare("SELECT * FROM services WHERE service_id = ?");
                        $stmt->bind_param("s", $service);
                        $stmt->execute();
                        $result=$stmt->get_result();
                            while($row=$result->fetch_assoc()){
                                
                            ?>
                            <div class="col-sm-12" id="show<?php echo $row['service_id'] ?>">
                                <div class="alert alert-default" id="<?php echo $row['service_id'] ?>">
                                    <div class="row"  style="text-align: center;">
                                    <div class="col-sm-1">
                                        <?php echo "x".$service_qty[$index]; ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?php echo $row['service_name']; ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <?php echo $service_prices[$index]; ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <?php echo $teeth_worked[$index]; ?>
                                    </div>
                                    <div class="col-sm-1">
                                        <span class="fa fa-remove fa-fw float-right" onclick="onremove(<?php echo $row['service_id'] ?>)"></span>
                                    </div>
                                        
                                    <input type="hidden" name="" value="<?php echo $service_qty[$index]; ?>" id="s_qty<?php echo $row['service_id'] ?>" >
                                    <input type="hidden" name="" value="<?php echo $service_prices[$index]; ?>" id="service_price<?php echo $row['service_id'] ?>" >
                                    <input type="hidden" name="" value="<?php echo $teeth_worked[$index]; ?>" id="tooth<?php echo $row['service_id'] ?>" >
                                   
                                    </div>
                                </div>                    
                            </div>
                            <?php
                            $index++;
                            
                            }
                            $x++;
                            
                        }
                        // echo $price;
                    ?>  
                     <?php
                        $inc=0;
                        foreach($products as $product){
                        $stmt=$conn->prepare("SELECT * FROM product WHERE product_id = ? AND product_name = ? ");
                        $stmt->bind_param("ss", $product,$product_name[$inc]);
                        $stmt->execute();
                        $result=$stmt->get_result();
                        if(mysqli_num_rows($result)>0){
                            $index1=0;
                            while($row=$result->fetch_assoc()){
                                $price1+=intval($row['product_price']);
                            ?>
                            <div class="col-sm-12" id="_show<?php echo $row['product_id'] ?>">
                                <div class="alert alert-default" id="<?php echo $row['product_id'] ?>">
                                    <div class="row"  style="text-align: center;">
                                        <div class="col-sm-1">
                                            <?php echo "x".$product_qty[$index1]; ?>
                                        </div>
                                        <div class="col-sm-6">
                                            <?php echo $row['product_name']; ?>
                                        </div>
                                        <div class="col-sm-2">
                                            <?php echo $row['product_price']; ?>
                                        </div>
                                        <div class="col-sm-2">
                                            <?php  ?>
                                        </div>
                                        <div class="col-sm-1">
                                            <span class="fa fa-remove fa-fw float-right" onclick="_onremove(<?php echo $row['product_id'] ?>)"></span>
                                        </div>
                                        <input type="hidden" name="" value="<?php echo $product_qty[$index1]; ?>" id="p_qty<?php echo $row['product_id'] ?>" >
                                    </div>
                                </div>                    
                            </div>
                            <?php
                            $index1++;
                            }
                        }
                        else{
                            $stmt=$conn->prepare("SELECT * FROM tools WHERE tool_id = ? AND tool_name = ? ");
                            $stmt->bind_param("ss", $product,$product_name[$inc]);
                            $stmt->execute();
                            $result=$stmt->get_result();
                            $index1=0;
                            while($row=$result->fetch_assoc()){ 
                                ?>
                                <div class="col-sm-12" id="_show_<?php echo $row['tool_id'] ?>">
                                    <div class="alert alert-default" id="<?php echo $row['tool_id'] ?>">
                                        <div class="row"  style="text-align: center;">
                                            <div class="col-sm-1">
                                                <?php echo "x".$product_qty[$index1]; ?>
                                            </div>
                                            <div class="col-sm-6">
                                                <?php echo $row['tool_name']; ?>
                                            </div>
                                            <div class="col-sm-2">
                                                <?php ?>
                                            </div>
                                            <div class="col-sm-2">
                                                <?php  ?>
                                            </div>
                                            <div class="col-sm-1">
                                                <span class="fa fa-remove fa-fw float-right" onclick="_onremove(<?php echo $row['tool_id'] ?>)"></span>
                                            </div>
                                       
                                            <input type="hidden" name="" value="<?php echo $product_qty[$index1]; ?>" id="t_qty<?php echo $row['tool_id'] ?>" >
                                        </div>
                                    </div>                    
                                </div>
                                <?php  
                            }
                            $index1++;
                        }
                    $inc++;
                    }
                    // echo $price1;
                    ?> 
                </div>
            </div> 
            <div class="row">
                <div class="col-sm-12">
                    
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="_back(<?php echo $bill_id; ?>)">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="transaction()" data-dismiss="modal">Proceed Billing</button>
        </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
 <div id="show-billing" style="overflow-y: scroll;"></div>
 <script>
    var product_qty = [<?php echo '"'.implode('","', $product_qty).'"' ?>];
    var service_qty = [<?php echo '"'.implode('","', $service_qty).'"' ?>];
    
    var services = [<?php echo '"'.implode('","', $services).'"' ?>];
    var products = [<?php echo '"'.implode('","', $products).'"' ?>];
    var product_names = [<?php echo '"'.implode('","', $product_name).'"' ?>];

    var service_prices = [<?php echo  '"'.implode('","', $service_prices).'"' ?>];
    var teeth_worked = [<?php echo  '"'.implode('","', $teeth_worked).'"' ?>];
    
    function onremove(id){
        
        removeItem(services, id.toString())
        removeItem( service_qty, $('#s_qty'+id).val())
        removeItem( service_prices, $('#service_price'+id).val())
        removeItem( teeth_worked, $('#tooth'+id).val())

        $('#show'+id).remove();
        if(services.length == 0 || service_qty.length == 0 || service_prices.length == 0){
            // $('#modal-default').modal('toggle');
            $('#modal-default').on('hidden-bs-modal', function(){
                alert("No Services Applied!")
            })
        }
    }

    function removeItem(array, itemToRemove) {
        const index = array.indexOf(itemToRemove);

        if (index !== -1) {
            array.splice(index, 1);
        }
        console.log("Updated Array: ", array);
    }

    function _onremove(id){
        
        removeItem(products, id.toString())
        removeItem( product_qty, $('#p_qty'+id).val())

        $('#_show'+id).remove();
    }

    function _onremove_(id){
        
        removeItem(products, id.toString())
        removeItem( product_qty, $('#t_qty'+id).val())

        $('#_show_'+id).remove();
    }

    function _back(id){
        $('#assistant-view-bill-modal'+id).modal('toggle');
        product_qty = [];
        service_qty = [];
    
        services = [];
        products = [];

        service_prices = [];
        teeth_worked = [];
    }

    function transaction(){
        form=new FormData();
        form.append('service_qty',service_qty);
        form.append('product_qty',product_qty);
        form.append('service',services);
        form.append('service_prices',service_prices);
        form.append('product',products);
        form.append('product_names',product_names);
        form.append('tooth',teeth_worked);
        form.append('patient',<?php echo $patient; ?>);
        form.append('billing_id',<?php echo $bill_id; ?>);
        $.ajax({
            data: form,
            type: 'POST',
            url: 'view-billing-modal.php',
            processData: false,
            contentType: false,
            cache:false,
        }).done( function(data){
            // alert(data)
            $('#show-billing').html(data);
            $('#billing-modal').modal('toggle');
        })
        // console.log(
        //             {js_array},
        //             {_js_array},
        //             {services},
        //             {products}
        //         )
        // form=new FormData();
        // form.append('service_qty',service_qty);
        // form.append('product_qty',product_qty);
        // form.append('service',services);
        // form.append('service_prices',service_prices);
        // form.append('product',products);
        // form.append('tooth',teeth_worked);
        // form.append('patient',<?php //echo $patient; ?>);
        // form.append('billing_id',<?php //echo $bill_id; ?>);
        // $.ajax({
        //     data: form,
        //     type: 'POST',
        //     url: 'assistant-add service-transaction.php',
        //     processData: false,
        //     contentType: false,
        // }).done( function(data){
        //     console.log(data)
        //     form=new FormData();
        //     form.append('patient',"<?php //echo $patient; ?>");
        //     form.append('billing_id',"<?php //echo $bill_id; ?>");
        //     // form.append('service_qty',js_array);//qty  services
        //     // form.append('product_qty',_js_array);//qty  products
        //     $.ajax({
        //         data: form,
        //         type: 'POST',
        //         url: 'view-billing-modal.php',
        //         processData: false,
        //         contentType: false,
        //     }).done( function(data){
        //         $('#show-billing').html(data);
        //         $('#billing-modal').modal('toggle');
        //     })
        // })

    }
 </script>
