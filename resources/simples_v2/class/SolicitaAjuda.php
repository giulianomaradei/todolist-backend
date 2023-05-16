<?php

require_once "System.php";


if(!empty($_POST['encerrar'])){

	$operacao = 'encerrar';
    $id = (int)$_POST['encerrar'];
    $id_motivo_solicitacao_ajuda = $_POST['id_motivo_solicitacao_ajuda'];
    
	$dados = array(
		'data_encerramento' => getDataHora(),
		'ajudante' => $_SESSION['id_usuario'],
		'id_motivo_solicitacao_ajuda' => $id_motivo_solicitacao_ajuda
	);

	DBUpdate('', 'tb_solicitacao_ajuda', $dados, "id_solicitacao_ajuda = '".$id."'");
	registraLog('Solicitação de ajuda encerrada.','a','tb_solicitacao_ajuda', $id, "data_encerramento: $data_encerramento | ajudante: $ajudante | id_motivo_solicitacao_ajuda: $id_motivo_solicitacao_ajuda");

	$alert = ('Solicitação encerrada com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=solicitacoes-ajuda");

}else if(!empty($_POST['verificar'])){	
	$atendente = $_SESSION['id_usuario'];
	$dados_ajuda = DBRead('','tb_solicitacao_ajuda',"WHERE atendente = '$atendente' AND data_encerramento IS NULL");
	if($dados_ajuda){
		echo 1;
	}else{
		echo 0;
	}	
}else{
    if($_SESSION['id_usuario']){
        $id_contrato_plano_pessoa = (isset($_GET['id_contrato_plano_pessoa'])) ? $_GET['id_contrato_plano_pessoa'] : '';
        $id_atendimento = (isset($_GET['id_atendimento'])) ? $_GET['id_atendimento'] : 0;
        $atendente = $_SESSION['id_usuario'];
        $dados_plano = DBRead('','tb_contrato_plano_pessoa',"WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
        $id_plano = $dados_plano[0]['id_plano'];
        $data_inicio = getDataHora();
    
        $dados = array(
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
            'id_atendimento' => $id_atendimento,
            'data_inicio' => $data_inicio,
            'atendente' => $atendente,
            'id_plano' => $id_plano
        );
    
        $dados_ajuda = DBRead('','tb_solicitacao_ajuda',"WHERE atendente = '$atendente' AND data_encerramento IS NULL");
        if(!$dados_ajuda){
            $insertID = DBCreate('', 'tb_solicitacao_ajuda', $dados, true);
            registraLog('Solicitação de ajuda criada.','i','tb_solicitacao_ajuda', $insertID, "id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_atendimento: $id_atendimento | data_inicio: $data_inicio | atendente: $atendente | id_plano: $id_plano");
        }
    
        echo 1;
    }else{
        echo 0;
    }
	
}


?>