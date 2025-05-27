<?php
    $getfile ='doc-'.time().'.pdf';

    $id = $_POST['dir'];

    if(isset($id)){
        $dir = $id;

        if (file_exists($dir)) {
            $type = mime_content_type( $dir);
            header('Content-Type: ' . $type);
            header('Content-Disposition: attachment;filename=' . $getfile);
            readfile($dir);
            echo '<script>alert(File has been downloaded!)</script>';
        }
        else{
            echo "<script>alert(File Not Found!)</script>File Not Found";
        }
    }
    else{
        $dir = "fpdf/Doc1.pdf";

        if (file_exists($dir)) {
            $type = mime_content_type( $dir);
            header('Content-Type: ' . $type);
            header('Content-Disposition: attachment;filename=' . $getfile);
            readfile($dir);
        }
        else{
            echo "File Not Found";
        }
    }


?>