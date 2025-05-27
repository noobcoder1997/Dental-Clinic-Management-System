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


    $bill_id = $_POST['billing_id'];
    $patient = $_POST['patient'];
    $service_qty = explode(",",$_POST['service_qty']);
    $product_qty = explode(",",$_POST['product_qty']);
    $services = explode(",",$_POST['service']);
    $service_prices = explode(",",$_POST['service_prices']);
    $products = explode(",",$_POST['product']);
    $product_names = explode(",",$_POST['product_names']);
    $tooth = explode(",",$_POST['tooth']);
    $products_prices = [];

?>

<div class="modal fade" id="billing-modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Billing</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="location.reload();">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <p for="">Patient Name: 
                        <?php
                            $stmt=$conn->prepare("SELECT * FROM register_patient WHERE register_id = ?");
                            $stmt->bind_param('s',$patient);
                            $stmt->execute();
                            $result=$stmt->get_result();
                            if($row=$result->fetch_assoc()){
                               echo strtoupper($row['first_name']." ".$row['middle_name']." ".$row['last_name']); 
                            }
                        ?>
                    </p>
                </div>
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-hover" style="width: 100%; text-align:center; ">
                            <tr>
                                <td>QTY</td>
                                <td>ITEM DESCRIPTION</td>
                                <td>PRICE</td>
                                <td>AMOUNT</td>
                            </tr>
                        <?php
                            $qty_0=0;
                            $qty_1=0;
                            $service_name='';
                            $product_name='';
                            $service_price=0;
                            $product_price=0;
                            $subtotal_0=0;
                            $subtotal_1=0;
                            $incrementor=0;
                            $status='0';
                            if($services != '') {

                            
                                foreach($services as $service){
                                    
                                    $stmt_1=$conn->prepare("SELECT * FROM services WHERE service_id = ?");
                                    $stmt_1->bind_param('s',$service);
                                    $stmt_1->execute();
                                    $result_1=$stmt_1->get_result();
                                    if($row_1=$result_1->fetch_assoc())

                                    $qty_0 = $service_qty[$incrementor];
                                    $service_name = $row_1['service_name'];
                                    $service_price = $service_prices[$incrementor]
                        ?>
                            <tr>
                                <td>
                                    <?php echo $qty_0; ?>
                                </td>
                                <td>
                                    <?php echo $service_name; ?>
                                </td>
                                <td>
                                    <?php echo $service_price; ?>
                                </td>
                                <td>
                                    <?php echo floatval($qty_0 * $service_price); ?>    
                                </td>
                            </tr>
                        <?php
                                $incrementor++;
                                $subtotal_0 += floatval($qty_0 * $service_price);;
                                }
                            }
                        ?> 
                        <?php
                        $incrementor=0;
                            if($products != ''){
                                foreach($products as $product) {
                                    
                                    $stmt_2=$conn->prepare("SELECT * FROM product WHERE product_id = ? AND product_name = ?");
                                    $stmt_2->bind_param('ss',$product,$product_names[$incrementor]);
                                    $stmt_2->execute();
                                    $result_2=$stmt_2->get_result();
                                    if(mysqli_num_rows($result_2)>0){

                                        if($row_2=$result_2->fetch_assoc()){

                                            $qty_1 = $product_qty[$incrementor];
                                            if(isset($row_2['product_name'])){
                                                $product_name = $row_2['product_name'];
                                            }
                                            if(isset($row_2['product_price'])){
                                                $product_price = $row_2['product_price'];
                                                array_push($products_prices, $product_price);
                                            }
                                        }
                        ?> 
                            <tr>
                                <td>
                                    <?php echo $qty_1; ?>
                                </td>
                                <td>
                                    <?php echo $product_name; ?>
                                </td>
                                <td>
                                    <?php echo $product_price; ?>
                                </td>
                                <td>
                                    <?php echo floatval($qty_1*$product_price); ?>
                                </td>
                            </tr>
                        <?php
                                    
                                    $subtotal_1 += floatval($qty_1*$product_price);
                                    }
                                    $incrementor++;
                                }                                   
                            }
                            $amount = ($subtotal_0 + $subtotal_1); 
                        ?>      
                            <tr>
                                <td>TOTAL:</td>
                                <td></td>
                                <td></td>
                                <td>
                                    <input type="text" id="amount" value="<?php 
                                            $formatedNumber = number_format($amount, 2, '.', '');
                                            echo $formatedNumber;
                                        ?>"
                                        style="text-align:center; background:none; border:none; outline:none"
                                        onfocus="this.style.outline='none'"
                                        readonly>
                                </td>
                            </tr>     
                        </table>
                    </div>
                    <div class="row">
                        
                        <div class="col-sm-8" id="reciept-frame" >
                            <!--  -->
                        </div>
                    <?php
                        $stmt0=$conn->prepare("SELECT * FROM billing WHERE billing_id = ? ");
                        $stmt0->bind_param('s',$bill_id);
                        $stmt0->execute();
                        $result0=$stmt0->get_result();
                        if($row0=$result0->fetch_assoc()){
                            $stmt1=$conn->prepare("SELECT * FROM appointment WHERE appointment_id = ? ");
                            $stmt1->bind_param('s',$row0['appointment_id']);
                            $stmt1->execute();
                            $result1=$stmt1->get_result();
                            if($row1=$result1->fetch_assoc()){
                                echo '<input id="appointment_id" value="'.$row1['appointment_id'].'" hidden>';
                            
                    ?>
                        <div class="col-sm-12" id="div-calc">
                             <script>
    
                                if(parseFloat($('#down_payment').val()) > parseFloat($('#amount').val())){
                                    // alert('Down payment exceeds '+bal*(-1)+' on the expected amount. Please return the exceeded amount');
                                    $('#alert').html('Down payment exceeds on the expected amount.');
                                    // $('#alert').html('Down payment exceeds '+bal*(-1)+' on the expected amount.');
                                    $('#cash').prop('disabled','disabled');
                                }
                                else{
                                    // $('#down_payment').val()
                                    $('#remaining_balance').val((parseFloat($('#amount').val()) - parseFloat($('#down_payment').val())))
                                }

                                // $('#reciept-frame').html();
                                // var iframe = $('<iframe>');
                                // iframe.css('width','100%')
                                // iframe.css('height','100%')
                                // iframe.css('overflow','visible')
                                // iframe.attr('src','pdf_receipt/document_.pdf');
                                // $('#reciept-frame').html(iframe);
                                
                            </script>
                            <div class="form-group">
                                <label>Down payment:</label>
                                <input type="number" class="form-control" id="down_payment" style="color:blueviolet" value="<?php echo $row1['down_payment']?>" readonly><br>
                                <label>Remaining Balance:</label>
                                <input type="number" class="form-control" id="remaining_balance" style="color:blueviolet" readonly>
                                <small id="alert" style="color:red"></small><br>
                                <label>Input Cash:</label> 
                                <input type="number" class="form-control" id="cash" oninput="cash = this.value">
                                <br>
                                <button class="btn btn-primary float-right" onclick="calculate()" style="margin-bottom: 10px;">Calculate</button>
                                <br>
                                <br>
                                <label>Change:</label>
                                <input type="number" class="form-control" id="change" style="color:blueviolet" readonly>
                            </div>
                        </div>
                    <?php
                            }
                        }

                    ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-confirmation">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="bill()" id="modal-dismiss">Continue</button>
        </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<script>
    var teeth = [<?php echo '"'.implode('","', $tooth).'"' ?>];
    var treatments = [<?php echo '"'.implode('","', $services).'"' ?>];
    var products = [<?php echo '"'.implode('","', $products).'"' ?>];
    var product_names = [<?php echo '"'.implode('","', $product_names).'"' ?>];
    var treatments_qty = [<?php echo '"'.implode('","', $service_qty).'"' ?>];
    var products_qty = [<?php echo '"'.implode('","', $product_qty).'"' ?>];
    var treatments_prices = [<?php echo '"'.implode('","', $service_prices).'"' ?>];
    var products_prices = [<?php echo '"'.implode('","', $products_prices).'"' ?>];
    var change = 0;
    var cash = 0;
    var downpayment = 0;
    var balance = 0;
    var amt=0;
    downpayment = $('#down_payment').val();
    cash = $('#cash').val();
    balance = $('#remaining_balance').val();
    amt = $('#amount').val();

    function calculate(){

        alert(cash)
        alert(balance)

        if(parseFloat(cash) < parseFloat(balance)){
            alert('Insufficient Cash!')
        }
        else{
            change = parseFloat(cash-balance);
            
            $('#change').val(parseFloat(change).toFixed(2));

            form = new FormData();
            form.append("patient",<?php echo $patient ?>)
            form.append("treatments",treatments)
            form.append("products",products)
            form.append("product_names",product_names)
            form.append("treatments_qty",treatments_qty)
            form.append("products_qty",products_qty)
            form.append("treatments_prices",treatments_prices)
            ajax = $.ajax({
                data: form,
                url: 'get-reciept.php',
                type:'POST',
                contentType:false,
                processData:false,
                cache:false,
            })
            $.when(ajax).done(function (ajax) {
            
                alert(ajax)
                $('#reciept-frame').html('<iframe frameborder="0" src="pdf_receipt/document_'+<?php echo $patient ?>+'.pdf" style="width:100%; height:100vh; display:block"></iframe>');
                $('#div-calc').remove();
                $('#reciept-frame').removeClass('col-sm-8');
                $('#reciept-frame').addClass('col-sm-12');
            });
        }
    }

    function bill(){

        change = parseFloat(cash-balance).toFixed(2);
        
        if( change == '' || cash == ''){
            alert('Invalid Transaction!')
        }
        else{
            
            form = new FormData();
            form.append("bill_id","<?php echo $bill_id; ?>")
            form.append("patient",<?php echo $patient ?>)
            form.append("tooth",teeth)
            form.append("treatments",treatments)
            form.append("products",products)
            form.append("appointment_id",$('#appointment_id').val())
            form.append("total_payment",amt)
            form.append("down_payment",downpayment)
            form.append("remaining_balance",balance)
            form.append("cash",cash)
            form.append("change",change)
            form.append("treatments_qty",treatments_qty)
            form.append("products_qty",products_qty)
            form.append("treatments_prices",treatments_prices)
            form.append("products_prices",products_prices)
            form.append("product_names",product_names)
            var ajax = $.ajax({
                type:"POST",
                data:form,
                url:"assistant-to-billing.php",
                contentType:false,
                processData:false,
                cache:false,
            });
            $.when(ajax).done(function (ajax) {
                alert(ajax)
            });
            location.reload();
        }
    }

    function _close(){
        form = new FormData()
        form.append("bill_id",<?php echo $bill_id; ?>);
        form.append("patient_id",<?php echo $patient; ?>);
        $.ajax({
            data:form,
            url:'delete-error-transaction.php',
            type:"POST",
            processData:false,
            contentType:false,
            cache:false,
        }).done( function(data){
            alert(data)
            teeth=[]
            treatments=[]
            products=[]
            treatments_qty=[]
            products_qty=[]
            treatments_prices=[]
            products_prices=[]
        });
        location.reload();
    }

</script>

<div class="modal fade" id="modal-confirmation">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Confirmation</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to cancel the transaction?</p>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="_close()" >Continue</button>
        </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->