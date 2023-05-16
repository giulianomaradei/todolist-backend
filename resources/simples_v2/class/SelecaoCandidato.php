<?php 
require_once(__DIR__."/System.php");


$candidatos = (!empty($_POST['candidatos'])) ? $_POST['candidatos'] : '';
$id_selecao = (!empty($_POST['id_selecao'])) ? $_POST['id_selecao'] : '';

if (!empty($_POST['inserir'])){
    inserir($candidatos, $id_selecao);

} else if (!empty($_GET['excluir'])) {
    $id_selecao_candidato = (!empty($_GET['excluir'])) ? $_GET['excluir'] : '';
    $id_selecao = (!empty($_GET['idselecao'])) ? $_GET['idselecao'] : '';

    excluir($id_selecao, $id_selecao_candidato);
}
 
function inserir($candidatos, $id_selecao)
{
    if ($candidatos != '' && $id_selecao != '') {

        foreach ($candidatos as $conteudo) {

            $id_pessoa_candidato = $conteudo;

            $verifica = DBRead('', 'tb_selecao_candidato', "WHERE id_selecao = $id_selecao AND id_pessoa_candidato = $id_pessoa_candidato ");

            if (!$verifica) {

                $dados = array(
                    'id_selecao' => $id_selecao,
                    'id_pessoa_candidato' => $id_pessoa_candidato,
                    'status' => 1
                );

                $insertID = DBCreate('', 'tb_selecao_candidato', $dados, true);
                registraLog('Inserção de candidato seleção rh.', 'i', 'tb_selecao_candidato', $insertID, "id_selecao: $id_selecao | id_pessoa_candidato: $id_pessoa_candidato | status: 1");
            }
        }

        $alert = ('Candidato(s) adicionado(s) com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=selecao-informacoes&idselecao=$id_selecao");
        exit;

    } else {
        $alert = ('Não foi possivel salvar!','d');
		header("location: /api/iframe?token=$request->token&view=selecao-candidato-form&idselecao=$id_selecao");
		exit;
    }
}

function excluir($id_selecao, $id_selecao_candidato)
{
    if ($id_selecao != '' && $id_selecao_candidato !='') {

        $check = DBRead('', 'tb_selecao_avaliador_candidato', "WHERE id_selecao_candidato = $id_selecao_candidato");

        if ($check) {
            $alert = ('Não foi possível excluir o candidato pois o mesmo já foi avaliado!','d');
            header("location: /api/iframe?token=$request->token&view=selecao-informacoes&idselecao=$id_selecao");
            exit;

        } else {

            DBDelete('', 'tb_selecao_candidato', "id_selecao_candidato = $id_selecao_candidato");
            registraLog('Exclusão de candidato seleção rh.', 'i', 'tb_selecao_candidato', $id_selecao_candidato, "id_selecao_candidato: $id_selecao_candidato");

            $alert = ('Candidato excluido com sucesso!','s');
            header("location: /api/iframe?token=$request->token&view=selecao-informacoes&idselecao=$id_selecao");
            exit;
        }
        
    } else {
        $alert = ('Não foi possível excluir o candidato!','d');
        header("location: /api/iframe?token=$request->token&view=selecao-informacoes&idselecao=$id_selecao");
        exit;
    }
}

