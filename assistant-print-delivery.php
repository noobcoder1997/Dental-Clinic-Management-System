<?php
    session_start();
    include 'config/connection.php';
    
    $id=$_SESSION['id'];
    $position=$_SESSION['position'];
      
    $delivery_id = $_POST['delivery_id'];  

    if(!isset($_SESSION['designation'])){
        $designation = $_SESSION['branch'];
    }
    else{
        $designation = $_SESSION['designation'];

        $stmt=$conn->prepare("SELECT * FROM delivery WHERE delivery_id = ? AND branch_id = ?");
        $stmt->bind_param('ss', $delivery_id,$designation);
        $stmt->execute();
        $result=$stmt->get_result();
        $row0=$result->fetch_assoc();

        $query = "SELECT * FROM assistant WHERE assistant_id = ?";
        $stmt=$conn->prepare($query);
        $stmt->bind_param('s',$_SESSION['id']);
        $stmt->execute();
        $result=$stmt->get_result();
        if($row=$result->fetch_assoc()){
            $date = date('Y-m-d');
            $transaction = "Assistant ID #$_SESSION[id] (".strtoupper($row['first_name']).")- printed a copy of delivery, description: $row0[description] on $date at".date('H:i:s A');
        }
        $status = '1';
        $stmt = $conn->prepare("INSERT INTO `logs`(`transaction`, `branch_id`, `assistant_id`, `date`, `status`) VALUES (?,?,?,?,?)");
        $stmt->bind_param('sssss',$transaction, $_SESSION['designation'], $_SESSION['id'], $date,$status);
        $stmt->execute();
    }

    require 'vendor/autoload.php';

    use Dompdf\Dompdf;

    $stmt=$conn->prepare("SELECT * FROM delivery WHERE delivery_id = ? AND branch_id = ?");
    $stmt->bind_param('ss', $delivery_id,$designation);
    $stmt->execute();
    $result=$stmt->get_result();
    $index=0;
    while($row=$result->fetch_assoc()){
        $stocks = explode(",",$row['stock_no']); //Array
        $stock_names = explode(",",$row['product_name']); //Array
        $quantities = explode(",",$row['quantity']); //Array
        $description = $row['description'];

        $input = strtotime($row['date']);
        $date = getDate($input);
        $day = $date['mday'];
        $month = $date['month'];
        $year = $date['year'];
        $_date = $month.' '.$day.', '.$year;

    $i = 0;

    $html = "<style>
                @page { 
                        margin: 20mm; 
                        // size: 148.5mm 105mm;
                        font-size: 11px;
                        color: #9640b0;
                    }
                body { 
                        margin: 0;
                        // background: lightgrey;
                        color: #9640b0;
                    }

                .pdf-table,td,tr{ 
                    border: 1px solid black;
                    border-collapse:collapse;
                    // padding: 3px;    
                    text-align: center;
                    
                }
                label, small{
                    font-weight: bold;
                }
                label{
                    font-size: 14px;
                }
                input{
                    background: transparent;
                    border: transparent;
                    border-bottom: 1px solid black;
                } 
                p{
                    color: black;
                    padding: 0;
                }
                input{
                    text-indent: 10px;
                }
            </style>";
    $html .=    "<div style='margin-top: 5px'>
                    <center>
                        <label >D' 13TH SMILE DENTAL CLINIC</label><br>
                        <small>2nd Floor Town Center, Himatagon, Saint Bernard, Southern Leyte</small><br>
                        <small>TRECE DURAN M. MAGBANUA <I>-P</I></small><br>
                        <label>Delivery History</label><br>
                    </center>
                </div>";
    $html .= "<div class='row'>
                <label style='margin-left:460px; fontsize:14px; '>Date: <input type='text' style='width: 20%;font-size: 12px' value='".$_date."'></label><br>
            </div>";
    $html .=   "
            <div style='margin: 10px'>    
                <table class='pdf-table' style='width:100%;'>
                    <tr>
                        <td style='height: 18px; font-size: 16px'>
                            QTY
                        </td>
                        <td style='height: 18px; font-size: 16px'>
                            ITEM DESCRIPTION
                        </td>
                        <!--<td>
                            PRICE
                        </td>
                        <td>
                            AMOUNT
                        </td>
                        <<td>
                            Fee
                        </td>
                        <td>
                            Paid
                        </td>
                        <td>
                            Balance
                        </td>-->
                    </tr>";
            while($i < count($stocks)){
    $html   .=      "<tr>
                        <td style='height: 18px; font-size: 14px'> ".$quantities[$index]."</td>
                        <td style='height: 18px; font-size: 14px'>".$stock_names[$index]."</td>
                        <!--<td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>-->
                    </tr>";
                    $i++;
                    $index++;
            }
    $html   .=  "</table>
                <!--<div style='margin-top: 10px'>
                    <label>Mode of payment:<input type='text' style='width: 78.25%'></label>
                </div>-->
            </div>";

    }   
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait'); 
    $dompdf->render();
    $output = $dompdf->output();
    file_put_contents('pdf_delivery/delivery.pdf', $output);
    $dompdf->stream();
?>