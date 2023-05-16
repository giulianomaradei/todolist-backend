<?php
require_once(__DIR__."/System.php");

$parecer = (!empty($_POST['parecer'])) ? $_POST['parecer'] : '';
$id_erro = (!empty($_POST['id_erro_atendimento'])) ? $_POST['id_erro_atendimento'] : '';
$dados_lido = DBRead('', 'tb_erro_atendimento_lider', "WHERE id_erro_atendimento = '".$id_erro."'");
$id_erro_atendimento_lider = $dados_lido[0]['id_erro_atendimento_lider'];
$id_usuario = $_SESSION['id_usuario'];

if(!empty($_POST['inserir'])){
	
	inserir($parecer, $dados_lido, $id_erro_atendimento_lider, $id_usuario);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($parecer, $dados_lido, $id_erro_atendimento_lider, $id_usuario){

$data_parecer = getDataHora();
  
   if($dados_lido){
		$dados_lido = array(
            'id_usuario' => $id_usuario,
		    'lido' => '1',
            'parecer' => $parecer,
            'data_parecer' => $data_parecer
		);
		DBUpdate('', 'tb_erro_atendimento_lider', $dados_lido, "id_erro_atendimento_lider = '$id_erro_atendimento_lider'");

		registraLog('Atualizacao erro visualizado pelo líder e parecer.','a','tb_erro_atendimento_lider',$id_erro_atendimento_lider,"id_usuario: $id_usuario | lido: 1 | parecer: $parecer | data_parecer: $data_parecer");
	
        $alert = ('Parecer inserido com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=home");
    }else{
        $alert = ('Erro ao inserir parecer!','e');
        header("location: /api/iframe?token=$request->token&view=home");
    }
    exit;
}


?>
