<?php
require_once "System.php";

$acao = (isset($_POST['acao'])) ? $_POST['acao'] : '';
$parametros = (isset($_POST['parametros'])) ? $_POST['parametros'] : '';
$id_treinamento = $parametros['id_treinamento'];

if ($acao == 'excluir') {

    $dados = array(
        'status' => 2
    );

    DBUpdate('', 'tb_treinamento', $dados, "id_treinamento = $id_treinamento");
    registraLog('Alteração de status do treinamento', 'a', 'tb_treinamento', $id_treinamento, "status: 2");

    $alert = ('Treinamento excluído com sucesso!', 's');

    echo json_encode(1);

} else if ($acao == 'busca_obs') {

    $id_treinamento_participante = $parametros['id_treinamento_participante'];

    $dados = DBRead('', 'tb_treinamento_participante', "WHERE id_treinamento_participante = $id_treinamento_participante");

    if ($dados) {
        if ($dados[0]['obs']) {
            $obs = $dados[0]['obs'];

            echo json_encode($obs);

        } else {
            echo json_encode(0);
        }

    } else {
        echo json_encode(0);
    }
}
