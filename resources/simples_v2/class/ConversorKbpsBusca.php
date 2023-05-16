<?php
    require_once(__DIR__."/System.php");
    $parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';

    $kbps = addslashes($parametros['kbps']);
    
    $mbps = $kbps / 1024;
    echo '<label>Valor em Mbp/s:</label> '.round($mbps);

?>