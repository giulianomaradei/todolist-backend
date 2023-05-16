<?php
require_once "System.php";

$acao = (isset($_POST['acao'])) ? $_POST['acao'] : '';
$parametros = (isset($_POST['parametros'])) ? $_POST['parametros'] : '';
$id_selecao = $parametros['id_selecao'];

if ($acao == 'encerrar_selecao') {

   $dados_selecao = DBRead('', 'tb_selecao', "WHERE id_selecao = $id_selecao");

   if ($dados_selecao[0]['status'] == 1) {

        $dados = array(
            'status' => 2
        );

        DBUpdate('', 'tb_selecao', $dados, "id_selecao = $id_selecao");
        registraLog('Alteração de status seleção','a','tb_selecao',$id_selecao,"status: 2");

        $alert = ('Item alterado com sucesso!');
    $alert_type = 's';
        echo json_encode(2);
    
   } else {

        $dados = array(
            'status' => 1
        );

        DBUpdate('', 'tb_selecao', $dados, "id_selecao = $id_selecao");
        registraLog('Alteração de status seleção','a','tb_selecao',$id_selecao,"status: 1");

        $alert = ('Item alterado com sucesso!');
    $alert_type = 's';
        echo json_encode(1);
   }

} else if ($acao == 'excluir_selecao') {

    $dados = array(
        'status' => 3
    );

    DBUpdate('', 'tb_selecao', $dados, "id_selecao = $id_selecao");
    registraLog('Alteração de status seleção','a','tb_selecao',$id_selecao,"status: 3");

    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';
    echo json_encode(1);
}