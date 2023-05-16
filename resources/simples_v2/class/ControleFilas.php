<?php
require_once(__DIR__."/System.php");

$tempo_fila1 = (!empty($_POST['tempo_fila1'])) ? $_POST['tempo_fila1'] : 1200;
$tempo_fila2 = (!empty($_POST['tempo_fila2'])) ? $_POST['tempo_fila2'] : 600;
$qtd_dias_calculo = (!empty($_POST['qtd_dias_calculo'])) ? $_POST['qtd_dias_calculo'] : 7;
$porcentagem_prioridade_alta = (!empty($_POST['porcentagem_prioridade_alta'])) ? $_POST['porcentagem_prioridade_alta'] : 50;
$porcentagem_prioridade_baixa = (!empty($_POST['porcentagem_prioridade_baixa'])) ? $_POST['porcentagem_prioridade_baixa'] : 150;

$dados = array(
    'tempo_fila1' => $tempo_fila1,
    'tempo_fila2' => $tempo_fila2,
    'qtd_dias_calculo' => $qtd_dias_calculo,
    'porcentagem_prioridade_alta' => $porcentagem_prioridade_alta,
    'porcentagem_prioridade_baixa' => $porcentagem_prioridade_baixa
);
DBUpdate('snep', 'queue_automacao', $dados, "id = 1");
registraLog('Alteração de parÂmetros de controle automático de filas.','a','queue_automacao',1,"tempo_fila1: $tempo_fila1 | tempo_fila2: $tempo_fila2 | qtd_dias_calculo: $qtd_dias_calculo | porcentagem_prioridade_alta: $porcentagem_prioridade_alta | porcentagem_prioridade_baixa: $porcentagem_prioridade_baixa");

$alert = ('Item alterado com sucesso!','s');
header("location: /api/iframe?token=$request->token&view=prefixo-central-busca");     
exit;
?>