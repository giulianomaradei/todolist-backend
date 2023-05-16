<?php
require_once(__DIR__."/System.php");


$opcao = (!empty($_POST['opcao'])) ? $_POST['opcao'] : '';
$texto_os = (!empty($_POST['texto_os'])) ? $_POST['texto_os'] : '';
$exibicao = (!empty($_POST['exibicao'])) ? (int)$_POST['exibicao'] : '';
$faturar = (!empty($_POST['faturar'])) ? (int)$_POST['faturar'] : 0;
$status = (!empty($_POST['status'])) ? (int)$_POST['status'] : 0;
$resolvido = (!empty($_POST['resolvido'])) ? (int)$_POST['resolvido'] : 2;


if (!empty($_POST['inserir'])) {
    
    inserir($opcao, $texto_os, $exibicao, $faturar, $status, $resolvido);

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];
   
    alterar($id, $opcao, $texto_os, $exibicao, $faturar, $status, $resolvido);

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($opcao, $texto_os, $exibicao, $faturar, $status, $resolvido){

if($opcao != ""){

    $dados = array(
        'opcao' => $opcao,
        'texto_os' => $texto_os,
        'exibicao' => $exibicao,
        'faturar' => $faturar,
        'status' => $status,
        'resolvido' => $resolvido
    );
    $insertID = DBCreate('', 'tb_tipo_falha_atendimento', $dados, true);
    registraLog('Inserção de tipo de falha atendimento.','i','tb_tipo_falha_atendimento',$insertID,"opcao: $opcao | texto_os: $texto_os | exibicao: $exibicao | faturar: $faturar | status: $status | resolvido: $resolvido");
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=tipo-falha-atendimento-busca");

}else{

        $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=tipo-falha-atendimento-form");

}
    
    exit;

}

function alterar($id, $opcao, $texto_os, $exibicao, $faturar, $status, $resolvido){

if($opcao != "" && $texto_os != ""){

    $dados = array(
        'opcao' => $opcao,
        'texto_os' => $texto_os,
        'exibicao' => $exibicao,
        'faturar' => $faturar,
        'status' => $status,
        'resolvido' => $resolvido
    );
    DBUpdate('', 'tb_tipo_falha_atendimento', $dados, "id_tipo_falha_atendimento = $id");
    registraLog('Alteração do tipo de falha do atendimento.','a','tb_tipo_falha_atendimento',$id,"opcao: $opcao | texto_os: $texto_os | exibicao: $exibicao | faturar: $faturar | status: $status | resolvido: $resolvido");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=tipo-falha-atendimento-busca");

}else{

        $alert = 'Não foi possível alterar o item!' ;
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=tipo-falha-atendimento-form&alterar=$id");
}
    
    exit;

}

function excluir($id) {
	$dados = array(
		'status' => '2',
	);
	DBUpdate('', 'tb_tipo_falha_atendimento', $dados, "id_tipo_falha_atendimento = '$id'");
	registraLog('Exclusão do tipo falha atendimento.', 'e', 'tb_tipo_falha_atendimento', $id, '');
	$alert = ('Item excluído com sucesso!', 'd');
	header("location: /api/iframe?token=$request->token&view=tipo-falha-atendimento-busca");
	exit;
}

?>
