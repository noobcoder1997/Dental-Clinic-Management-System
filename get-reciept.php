<?php
    include('config/connection.php');
    session_start();

    $id = $_SESSION['id'];
    $position = $_SESSION['position'];
    
    if(isset($_SESSION['branch'])){
        $designation = $_SESSION['branch'];  
    }
    else{
        $designation = $_SESSION['designation'];        
    }

    $patient = $_POST['patient'];
    $treatments = $_POST['treatments'];
    $products = $_POST['products'];
    $product_names = $_POST['product_names'];
    $treatments_prices    = $_POST['treatments_prices'];
    $treatments_qty = $_POST['treatments_qty'];
    $products_qty = $_POST['products_qty'];

    require_once('fpdf/fpdf.php');
    require_once('fpdf/fpdi/src/autoload.php');  
   
    // use setasign\fpdf\fpdf; 
    use setasign\Fpdi\Fpdi;

    // class _PDF extends FPDI
    // {
    //     var $angle=0;

    //     function Rotate($angle,$x=-1,$y=-1)
    //     {
    //         if($x==-1)
    //             $x=$this->x;
    //         if($y==-1)
    //             $y=$this->y;
    //         if($this->angle!=0)
    //             $this->_out('Q');
    //         $this->angle=$angle;
    //         if($angle!=0)
    //         {
    //             $angle*=M_PI/180;
    //             $c=cos($angle);
    //             $s=sin($angle);
    //             $cx=$x*$this->k;
    //             $cy=($this->h-$y)*$this->k;
    //             $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
    //         }
    //     }

    //     function _endpage()
    //     {
    //         if($this->angle!=0)
    //         {
    //             $this->angle=0;
    //             $this->_out('Q');
    //         }
    //         parent::_endpage();
    //     }

    //     function RotatedText($x,$y,$txt,$angle)
    //     {
    //         //Text rotated around its origin
    //         $this->Rotate($angle,$x,$y);
    //         $this->Text($x,$y,$txt);
    //         $this->Rotate(0);
    //     }

    //     function RotatedImage($file,$x,$y,$w,$h,$angle)
    //     {
    //         //Image rotated around its upper-left corner
    //         $this->Rotate($angle,$x,$y);
    //         $this->Image($file,$x,$y,$w,$h);
    //         $this->Rotate(0);
    //     }
    // }

    $query = "SELECT sales_id FROM sales ORDER BY sales_id DESC";
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_array($result);
    $lastid = isset($row['sales_id']);
    if(empty($lastid))
    {
        $number = "E-0000001";
    }
    else
    {
        $idd = str_replace("E-", "", $lastid);
        $id = str_pad($idd + 1, 7, 0, STR_PAD_LEFT);
        $number = 'E-'.$id;
    }

    $pdf = new FPDI('L','mm', array(204.2, 150));
    
    // add a page
    $pdf->AddPage();

    // set the source file
    $pdf->setSourceFile("document_.pdf");

    // import page 1
    $tplId = $pdf->importPage(1);
    // use the imported page and place it at point 10,10 with a width of 100 mm
    $pdf->useTemplate($tplId, 2, 5, 200);

    // Now, you can add additional content on top of the existing PDF page
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor(148, 0, 211); // Red text color
    $y=51;
    # Date
    $pdf->SetXY(163, 28); // Set position where text will be inserted
    $pdf->Write(10, date('m-d-Y'));
    # reciept#
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetXY(151, 36); // Set position where text will be inserted
    $pdf->Write(10, $number);

    if($treatments != "" || $treatments_qty != "" || $treatments_prices != ""){
        $treatments = explode(',',$treatments);
        $treatments_qty = explode(',', $treatments_qty);
        $treatments_prices = explode(',', $treatments_prices);
        $x=0;
        $ser_subtotal=0;
        $prod_subtotal=0;
        foreach($treatments as $treatment){
            $query_1=mysqli_query($conn, "SELECT * FROM register_patient WHERE register_id ='$patient' ");
            if($row_1=mysqli_fetch_assoc($query_1)){
                $f = $row_1['first_name'];
                $m = $row_1['middle_name'];
                $l = $row_1['last_name'];

                $fulnim = strtoupper($f.' '.$m.' '.$l);
                # Name
                $pdf->SetXY(24, 33); // Set position where text will be inserted
                $pdf->Write(10, $fulnim);   
            } 
            
            //QTY
            $pdf->SetXY(7, $y); // Set position where text will be inserted
            $pdf->Write(10, $treatments_qty[$x]);
            //Treatment Rendered
            $query=mysqli_query($conn, "SELECT * FROM services WHERE service_id='$treatment' ");
            $row0=mysqli_fetch_assoc($query);

            $pdf->SetXY(67, $y); // Set position where text will be inserted
            $pdf->Write(10, $row0['service_name']);
            //Fee
            $pdf->SetXY(123, $y); // Set position where text will be inserted
            $pdf->Write(10, $treatments_prices[$x]);
            //Paid
            $pdf->SetXY(159, $y); // Set position where text will be inserted
            $pdf->Write(10, $treatments_prices[$x]*$treatments_qty[$x]);
            $y+=6;
            
            $ser_subtotal+=$treatments_prices[$x]*$treatments_qty[$x];
            $x++;
        }     
    }
    else{
        $ser_subtotal=0;
    }

    $x=0;   
    if($products != "" || $products_qty != "" ){
        $products = explode(',',$products);
        $products_qty = explode(',',$products_qty);
        $product_names = explode(',',$product_names);
        foreach($products as $product){
        
            //QTY
            $pdf->SetXY(7, $y); // Set position where text will be inserted
            $pdf->Write(10, $products_qty[$x]);
            //Treatment Rendered
            $query=mysqli_query($conn, "SELECT * FROM product WHERE product_id='$product' AND  product_name = '$product_names[$x]' ");
            if(mysqli_num_rows($query)>0){
                $row0=mysqli_fetch_assoc($query);
                if(isset($row0['product_name'])){
                    $pdf->SetXY(67, $y); // Set position where text will be inserted
                    $pdf->Write(10, $row0['product_name']);
                }
                if(isset($row0['product_price'])){
                    //Fee
                    $pdf->SetXY(123, $y); // Set position where text will be inserted
                    $pdf->Write(10, $row0['product_price']);
                }
                //Paid
                $pdf->SetXY(159, $y); // Set position where text will be inserted
                $pdf->Write(10, floatval($row0['product_price'])*$products_qty[$x]);

                $prod_subtotal+=floatval($row0['product_price'])*$products_qty[$x];
            }
            $y+=6;
            $x++;
        } 
    } 
    else{
        $prod_subtotal=0;
    }
    //TOTAL
    $pdf->SetXY(159, 112); // Set position where text will be inserted
    $pdf->Write(10, $ser_subtotal+$prod_subtotal);

    // Signature
    $pdf->SetXY(96, 128); // Set position where text will be inserted
    $pdf->Write(10, '');
    // Mode Payment
    $pdf->SetXY(55, 114); // Set position where text will be inserted
    $pdf->Write(10, 'Cash');

    $pdf->Output('F', 'pdf_receipt/document_'.$patient.'.pdf');

    echo "Reciept Generated!";
    

?>