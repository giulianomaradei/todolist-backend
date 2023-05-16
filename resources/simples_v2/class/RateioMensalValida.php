<?php
require_once(__DIR__ . "/System.php");
$id_centro_custos_utilizado  = (!empty($_POST['id_centro_custos_utilizado'])) ? $_POST['id_centro_custos_utilizado'] : '';
$id_centro_custos_principal  = (!empty($_POST['id_centro_custos_principal'])) ? $_POST['id_centro_custos_principal'] : '';
$porcentagem_rateio_centro_custos  = (!empty($_POST['porcentagem_rateio_centro_custos'])) ? $_POST['porcentagem_rateio_centro_custos'] : '';
$data_periodo_ano  = (!empty($_POST['data_periodo_ano'])) ? $_POST['data_periodo_ano'] : '';
$data_periodo_mes  = (!empty($_POST['data_periodo_mes'])) ? $_POST['data_periodo_mes'] : '';

if($id_centro_custos_utilizado && $id_centro_custos_principal  && $porcentagem_rateio_centro_custos && $data_periodo_ano && $data_periodo_mes){
    $daata_referencia = $data_periodo_ano.'-'.$data_periodo_mes.'-01';
    $dados_rateio = DBRead('','tb_centro_custos_rateio_centro_custos a',"INNER JOIN tb_centro_custos_rateio b ON a.id_centro_custos_rateio = b.id_centro_custos_rateio WHERE b.data_referencia = '$daata_referencia' AND a.id_centro_custos = '$id_centro_custos_utilizado' AND b.id_centro_custos_principal != '$id_centro_custos_principal'", "SUM(a.porcentagem) AS 'porcentagem_total'");
    if($dados_rateio){
        if($porcentagem_rateio_centro_custos + $dados_rateio[0]['porcentagem_total'] > 100){
            echo 100-$dados_rateio[0]['porcentagem_total'];
        }else{
            echo 'n';
        }
    }else{
        echo 'n';
    }
}else{
    echo 'n';
}
?>