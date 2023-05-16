<?php
require_once(__DIR__."/System.php");


$id_usuario = $_SESSION['id_usuario'];

$parametros = (isset($_POST['parametros'])) ? $_POST['parametros'] : '';
$id_monitoria_avaliacao_texto = addslashes($parametros['id_monitoria_avaliacao_texto']);

$dados_texto = DBRead('', 'tb_monitoria_avaliacao_texto', "WHERE id_monitoria_avaliacao_texto = $id_monitoria_avaliacao_texto", 'id_usuario_atendente, data_referencia, id_monitoria_mes');

$id_usuario_atendente = $dados_texto[0]['id_usuario_atendente'];
$data_referencia = $dados_texto[0]['data_referencia'];
$id_monitoria_mes = $dados_texto[0]['id_monitoria_mes'];

$link = DBConnect('');
DBBegin($link);

$dados = array(
    'considerar' => 2
);

DBUpdateTransaction($link, 'tb_monitoria_avaliacao_texto', $dados, "id_monitoria_avaliacao_texto = $id_monitoria_avaliacao_texto");
registraLogTransaction($link, 'Atualização de monitoria avaliacao texto.','a','tb_monitoria_avaliacao_texto',$id_monitoria_avaliacao_texto,"considerar: 2 | id_usuario: $id_usuario");

$dados_aval_texto = DBReadTransaction($link, 'tb_monitoria_avaliacao_texto', "WHERE id_monitoria_mes = $id_monitoria_mes AND id_usuario_atendente = '$id_usuario_atendente' AND considerar = 1", 'SUM(total_pontos) AS total_pontos, COUNT(*) AS cont_texto');

$dados_monitoria_mes = DBReadTransaction($link, 'tb_monitoria_mes', "WHERE id_monitoria_mes = $id_monitoria_mes AND status = 1 ", 'soma_total_pontos_quesitos');

$total_pontos = $dados_aval_texto[0]['total_pontos'];
$cont_texto = $dados_aval_texto[0]['cont_texto'];
$soma_total_pontos_quesitos = $dados_monitoria_mes[0]['soma_total_pontos_quesitos'];

if(!$total_pontos && $cont_texto == 0){
    $total_pontos = 0;
    $media = 0;
    $resultado = 0;

}else{
    $media = $total_pontos / $cont_texto;
    $resultado = ($media*100) / $soma_total_pontos_quesitos;
    $resultado = number_format($resultado, 2, '.', ' ');
}

$verifica = DBReadTransaction($link, 'tb_monitoria_resultado', "WHERE id_monitoria_mes = $id_monitoria_mes AND id_usuario = '$id_usuario_atendente' ");

if($verifica){

    $id_monitoria_resultado = $verifica[0]['id_monitoria_resultado'];

    $dados = array(
        'resultado' => $resultado,
    );

    DBUpdateTransaction($link,'tb_monitoria_resultado', $dados, "id_monitoria_resultado = $id_monitoria_resultado");
    registraLogTransaction($link, 'Alteração no resultado monitoria.', 'a', 'tb_monitoria_resultado', $id_monitoria_resultado, "resultado: $resultado");
}else{

    $dados = array(
        'data_referencia' => $data_referencia,
        'resultado' => $resultado,
        'id_usuario' => $id_usuario_atendente,
        'id_monitoria_mes' => $id_monitoria_mes
    );

    $insertID = DBCreateTransaction($link, 'tb_monitoria_resultado', $dados, true);
    registraLogTransaction($link, 'Inserção de monitoria resultado.','i','tb_monitoria_resultado',$insertID,"data_referencia: $data_referencia | resultado: $resultado | id_usuario:  $id_usuario | id_monitoria_mes: $id_monitoria_mes");
}

DBCommit($link);

$result = 1;

echo json_encode($result);

?>