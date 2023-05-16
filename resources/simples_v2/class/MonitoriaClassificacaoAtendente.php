<?php
require_once(__DIR__."/System.php");


$id_usuario = (!empty($_POST['id_usuario'])) ? $_POST['id_usuario'] : '';
$classificacao = (!empty($_POST['classificacao'])) ? $_POST['classificacao'] : '';
$voz = (!empty($_POST['voz'])) ? $_POST['voz'] : 2;
$texto = (!empty($_POST['texto'])) ? $_POST['texto'] : 2;
$analista_telefone = (!empty($_POST['analista_telefone'])) ? $_POST['analista_telefone'] : null;
$analista_texto = (!empty($_POST['analista_texto'])) ? $_POST['analista_texto'] : null;

if (!empty($_POST['inserir'])) {
    inserir($id_usuario, $classificacao, $voz, $texto, $analista_telefone, $analista_texto);

} else if (!empty($_POST['alterar'])) {
    $id_monitoria_classificacao_usuario = (int)$_POST['alterar'];
    alterar($id_monitoria_classificacao_usuario, $id_usuario, $classificacao, $voz, $texto, $analista_telefone, $analista_texto);
} 

function inserir($id_usuario, $classificacao, $voz, $texto, $analista_telefone, $analista_texto) {

   /*  echo 'id_usuario: '.$id_usuario.'<br>';
    echo 'classificacao: '.$classificacao.'<br>';
    echo 'voz: '.$voz.'<br>';
    echo 'texto: '.$texto.'<br>';
    echo 'analista_telefone: '.$analista_telefone.'<br>';
    echo 'analista_texto: '.$analista_texto.'<br>';
    die(); */
    
    if ($id_usuario != '' && $classificacao !='' && $voz != '' && $texto !='') {

        $dados = array(
            'id_usuario' => $id_usuario,
            'tipo_classificacao' => $classificacao,
            'voz' => $voz,
            'texto' => $texto,
            'id_analista_telefone' => $analista_telefone,
            'id_analista_texto' => $analista_texto
        );

        $insertID = DBCreate('', 'tb_monitoria_classificacao_usuario', $dados, true);
        registraLog('Inserção de classificacao atendente.','i','tb_monitoria_classificacao_usuario',$insertID,"id_usuario: $id_usuario | tipo_classificacao: $classificacao | voz: $voz | texto: $texto | id_analista_telefone: $analista_telefone | id_analista_texto: $analista_texto");
        
        $alert = ('Classificação de atendente realizada com sucesso!','s');
	    header("location: /api/iframe?token=$request->token&view=monitoria-classificacao-atendente-busca");

    } else {
        $alert = ('Erro ao classificar atendente!','s');
	    header("location: /api/iframe?token=$request->token&view=monitoria-classificacao-atendente-busca");
    }
}

function alterar($id_monitoria_classificacao_usuario, $id_usuario, $classificacao, $voz, $texto, $analista_telefone, $analista_texto) {

    if ($id_usuario != '' && $classificacao !='' && $voz != '' && $texto !='') {

        $data_hoje = getDataHora();
        $data_hoje = explode(" ", $data_hoje);
        $data_hoje = explode("-", $data_hoje[0]);
        $data_referencia = "01/".$data_hoje[1]."/".$data_hoje[0];
        $data_referencia =  convertedata($data_referencia);

        $dados = array(
            'tipo_classificacao' => $classificacao,
            'voz' => $voz,
            'texto' => $texto,
            'id_analista_telefone' => $analista_telefone,
            'id_analista_texto' => $analista_texto
        );

        $mensagem = "Todas opções alteradas com sucesso!";
        $alert = 's';

        $verifica = DBRead('', 'tb_monitoria_avaliacao_audio', "WHERE id_usuario_atendente = $id_usuario AND data_referencia = '$data_referencia'");

        if ($verifica) {
            $dados = array(
                'voz' => $voz,
                'texto' => $texto,
                'id_analista_telefone' => $analista_telefone,
                'id_analista_texto' => $analista_texto
            );

            $mensagem = "Classificação NÃO pôde ser alterada devido ao atendente já ter sido avaliado este mês. Demais opções alteradas com sucesso!";
            $alert = 'w';
        }

        DBUpdate('', 'tb_monitoria_classificacao_usuario', $dados, "id_monitoria_classificacao_usuario = $id_monitoria_classificacao_usuario");
        registraLog('Alteração de classificacao atendente.','a','tb_monitoria_classificacao_usuario',$id_monitoria_classificacao_usuario,"tipo_classificacao: $classificacao | voz: $voz | texto: $texto | id_analista_telefone: $analista_telefone | id_analista_texto: $analista_texto");
        
        $alert = ($mensagem,$alert);
	    header("location: /api/iframe?token=$request->token&view=monitoria-classificacao-atendente-busca");
    }
}
