<?php
if(isset($_GET['download'])){
    $file_name = $_GET['download'];
    header("Cache-Control: public");
    header("Content-Disposition: attachment; filename = $file_name");
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: binary");

    readfile($filePath);
    exit;  
}
?>
