<?php
require_once(__DIR__."/System.php");

$assinante = (!empty($_POST['assinante'])) ? $_POST['assinante'] : '';
$id_tipo_erro = (!empty($_POST['id_tipo_erro'])) ? $_POST['id_tipo_erro'] : 0;
$descricao_cliente = (!empty($_POST['descricao_cliente'])) ? $_POST['descricao_cliente'] : '';
$justificativa = (!empty($_POST['justificativa'])) ? $_POST['justificativa'] : '';
$protocolo = (!empty($_POST['protocolo'])) ? $_POST['protocolo'] : '';
$data_erro = (!empty($_POST['data_erro'])) ? $_POST['data_erro'] : '0000-00-00';
$data_erro = converteDataHora($data_erro);
$hora_erro = (!empty($_POST['hora_erro'])) ? $_POST['hora_erro'] : '00:00:00';
$origem = (!empty($_POST['origem'])) ? $_POST['origem'] : '2';
$canal_atendimento = (!empty($_POST['canal_atendimento'])) ? $_POST['canal_atendimento'] : '';

$precaucao_futura = (!empty($_POST['precaucao_futura'])) ? $_POST['precaucao_futura'] : '';
$id_pessoa_funcionario = (!empty($_POST['id_pessoa_funcionario'])) ? $_POST['id_pessoa_funcionario'] : '';
$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';

$id_usuario = (!empty($_POST['id_usuario'])) ? $_POST['id_usuario'] : '';

$dia_justificativa = (!empty($_POST['dia_justificativa'])) ? $_POST['dia_justificativa'] : '0000-00-00';
$dia_justificativa = converteDataHora($dia_justificativa);
$hora_justificativa = (!empty($_POST['hora_justificativa'])) ? $_POST['hora_justificativa'] : '00:00:00';


if (!empty($_POST['inserir'])) {

    $data_justificativa = '0000-00-00 00:00:00';
    $status = 1;
    $id_usuario_cadastrou = $_SESSION['id_usuario'];
    $data_cadastrado = getDataHora();
    
    inserir($assinante, $id_tipo_erro, $data_erro, $hora_erro, $descricao_cliente, $protocolo, $status, $data_justificativa, $id_usuario, $id_contrato_plano_pessoa, $id_usuario_cadastrou, $data_cadastrado, $origem, $canal_atendimento);

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];
   
    $status = 1;
    $id_usuario_cadastrou = $_SESSION['id_usuario'];

    $tem_justificativa = DBRead('', 'tb_erro_atendimento', "WHERE justificativa IS NULL AND id_erro_atendimento = '".$id."'");

    if ($tem_justificativa) {
        alterar($id, $assinante, $id_tipo_erro, $data_erro, $hora_erro, $descricao_cliente, $protocolo, $status, $id_usuario, $id_contrato_plano_pessoa, $id_usuario_cadastrou, $origem, $canal_atendimento);

    } else if (!$tem_justificativa) {
        $alert = ('Erro ao alterar item, notificação já respondida!','e');
        header("location: /api/iframe?token=$request->token&view=erro-atendimento-busca");
        exit;
    }

} else if (!empty($_POST['inserir_justificativa'])) {

    $id = (int)$_POST['inserir_justificativa'];
    $data_justificativa = getDataHora();
    inserirJustificativa($id, $justificativa, $precaucao_futura, $data_justificativa);

} else if (!empty($_POST['inserir_justificativa_completa'])) {

    $data_cadastrado = getDataHora();
    $id_usuario_cadastrou = $_SESSION['id_usuario'];
    $data_justificativa = $dia_justificativa. ' '. $hora_justificativa;

    inserirJustificativaCompleta($assinante, $id_tipo_erro, $data_erro, $hora_erro, $descricao_cliente, $protocolo, $data_justificativa, $status, $id_usuario, $id_contrato_plano_pessoa, $id_usuario_cadastrou, $data_cadastrado, $justificativa, $precaucao_futura, $dia_justificativa, $hora_justificativa, $origem);

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];

    $tem_justificativa = DBRead('', 'tb_erro_atendimento', "WHERE justificativa IS NULL AND id_erro_atendimento = '".$id."'");

    if($tem_justificativa){
        excluir($id);
    }else if(!$tem_justificativa){
        $alert = ('Erro ao excluir item, notificação já respondida!','e');
        header("location: /api/iframe?token=$request->token&view=erro-atendimento-busca");
        exit;
    }

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($assinante, $id_tipo_erro, $data_erro, $hora_erro, $descricao_cliente, $protocolo, $status, $data_justificativa, $id_usuario, $id_contrato_plano_pessoa, $id_usuario_cadastrou, $data_cadastrado, $origem, $canal_atendimento){

    $dados = array(
        'assinante' => $assinante,
        'id_tipo_erro' => $id_tipo_erro,
        'data_erro' => $data_erro,
        'hora_erro' => $hora_erro,
        'descricao_cliente' => $descricao_cliente,
        'protocolo' => $protocolo,
        'status' => $status,
        'data_justificativa' => $data_justificativa,
        'id_usuario_cadastrou' => $id_usuario_cadastrou,
        'id_usuario' => $id_usuario,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'data_cadastrado' => $data_cadastrado,
        'origem' => $origem,
        'canal_atendimento' => $canal_atendimento
    );

    if($assinante !='' && $id_tipo_erro !='' && $data_erro !='' && $hora_erro !='' && $descricao_cliente !='' && $data_justificativa !='' && $id_usuario_cadastrou !='' && $id_usuario !='' && $id_contrato_plano_pessoa !='' && $origem !='' && $canal_atendimento !=''){

        $insertID = DBCreate('', 'tb_erro_atendimento', $dados, true);
        registraLog('Inserção de erro no atendimento.','i','tb_erro_atendimento',$insertID,"assinante: $assinante | id_tipo_erro: $id_tipo_erro | data_erro: $data_erro | hora_erro: $hora_erro | descricao_cliente: $descricao_cliente | protocolo: $protocolo | status: $status | data_justificativa: $data_justificativa | id_usuario_cadastrou: $id_usuario_cadastrou | id_usuario: $id_usuario | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | data_cadastrado: $data_cadastrado | origem: $origem | canal_atendimento: $canal_atendimento");

            $dados = array(
                'id_erro_atendimento' => $insertID,
                'lido' => '0'            
            );

            $insertLider = DBCreate('', 'tb_erro_atendimento_lider', $dados, true);
            registraLog('Inserção de erro atendimento lider.','i','tb_erro_atendimento_lider',$insertLider,"id_erro_atendimento: $insertID | lido: '0'"); 

        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=erro-atendimento-busca");
    }else{
        $alert = ('Erro ao inserir item!','e');
        header("location: /api/iframe?token=$request->token&view=erro-atendimento-cadastro-form");
    }
    exit;
}

function inserirJustificativa($id, $justificativa, $precaucao_futura, $data_justificativa){

    $dados = array(
        'justificativa' => $justificativa,
        'precaucao_futura' => $precaucao_futura,
        'data_justificativa' => $data_justificativa
    );

    if($justificativa && $precaucao_futura && $data_justificativa){
        
        DBUpdate('', 'tb_erro_atendimento', $dados, "id_erro_atendimento = '$id'");
        registraLog('Inserção de justificativa de erro no atendimento.','a','tb_erro_atendimento',$id,"justificativa: $justificativa | precaucao_futura: $precaucao_futura | data_justificativa: $data_justificativa");

        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=home");

    }else{
        $alert = ('Erro ao inserir item!','d');
        header("location: /api/iframe?token=$request->token&view=erro-atendimento-justificativa-form&inserir_justificativa=$id");
    }
    exit;
}

function inserirJustificativaCompleta($assinante, $id_tipo_erro, $data_erro, $hora_erro, $descricao_cliente, $protocolo, $status, $data_justificativa, $id_usuario, $id_contrato_plano_pessoa, $id_usuario_cadastrou, $data_cadastrado, $justificativa, $precaucao_futura, $dia_justificativa, $hora_justificativa, $origem){

    $status = 1;
    $data_justificativa = $dia_justificativa. ' '. $hora_justificativa;
    $dados = array(
        'assinante' => $assinante,
        'id_tipo_erro' => $id_tipo_erro,
        'data_erro' => $data_erro,
        'hora_erro' => $hora_erro,
        'descricao_cliente' => $descricao_cliente,
        'protocolo' => $protocolo,
        'status' => $status,
        'id_usuario_cadastrou' => $id_usuario_cadastrou,
        'id_usuario' => $id_usuario,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'data_cadastrado' => $data_cadastrado,
        'justificativa' => $justificativa,
        'precaucao_futura' => $precaucao_futura,
        'data_justificativa' => $data_justificativa,
        'origem' => $origem
    );

    if($assinante !='' && $id_tipo_erro !='' && $data_erro !=''  && $hora_erro !=''  && $descricao_cliente !=''  && $data_justificativa !=''  && $id_usuario_cadastrou !=''  && $id_usuario !=''  && $id_contrato_plano_pessoa !=''  && $justificativa !=''  && $precaucao_futura !=''  && $origem !='' ){
 
        if(strtotime($data_justificativa) >= strtotime($data_erro)){
               
            $insertID = DBCreate('', 'tb_erro_atendimento', $dados, true);
            registraLog('Inserção de erro no atendimento.','i','tb_erro_atendimento',$insertID,"assinante: $assinante | id_tipo_erro: $id_tipo_erro | data_erro: $data_erro | hora_erro: $hora_erro | descricao_cliente: $descricao_cliente | protocolo: $protocolo | status: $status | data_justificativa: $data_justificativa | id_usuario_cadastrou: $id_usuario_cadastrou | id_usuario: $id_usuario | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | data_cadastrado: $data_cadastrado | origem: $origem");

            $alert = ('Item inserido com sucesso!');
    $alert_type = 's';            header("location: /api/iframe?token=$request->token&view=erro-atendimento-busca");

        }else{
            $alert = ('A data da justificativa não pode ser anterior a data do erro!','e');
            header("location: /api/iframe?token=$request->token&view=erro-atendimento-completo");
        }

    }else{
        $alert = ('Erro ao inserir item!','d');
        header("location: /api/iframe?token=$request->token&view=erro-atendimento-completo");
    }
    exit;
}

function alterar($id, $assinante, $id_tipo_erro, $data_erro, $hora_erro, $descricao_cliente, $protocolo, $status, $id_usuario, $id_contrato_plano_pessoa, $id_usuario_cadastrou, $origem, $canal_atendimento){

    $dados = array(
        'assinante' => $assinante,
        'id_tipo_erro' => $id_tipo_erro,
        'data_erro' => $data_erro,
        'hora_erro' => $hora_erro,
        'descricao_cliente' => $descricao_cliente,
        'protocolo' => $protocolo,
        'status' => $status,
        'id_usuario_cadastrou' => $id_usuario_cadastrou,
        'id_usuario' => $id_usuario,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'origem' => $origem,
        'canal_atendimento' => $canal_atendimento
    );

    if ($assinante !='' && $id_tipo_erro !='' && $data_erro !='' && $hora_erro !=''  && $descricao_cliente !='' && $id_usuario_cadastrou !=''  && $id_usuario !=''  && $id_contrato_plano_pessoa !=''  && $origem !='' && $canal_atendimento !='' ) {

        DBUpdate('', 'tb_erro_atendimento', $dados, "id_erro_atendimento = $id");
        registraLog('Alteração de erro no atendimento.','a','tb_erro_atendimento',$id,"assinante: $assinante | id_tipo_erro: $id_tipo_erro | $data_erro | $hora_erro | descricao_cliente: $descricao_cliente | protocolo: $protocolo | status: $status | id_usuario_cadastrou: $id_usuario_cadastrou | id_usuario: $id_usuario | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | origem: $origem | canal_atendimento: $canal_atendimento");

        $dados = array(
            'id_erro_atendimento' => $id,
            'lido' => '0'            
        );

        DBUpdate('', 'tb_erro_atendimento_lider', $dados, "id_erro_atendimento = $id");        

        $alert = ('Item alterado com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=erro-atendimento-busca");

    }else{
        $alert = ('Erro ao alterar item!','d');
        header("location: /api/iframe?token=$request->token&view=erro-atendimento-cadastro-form");
    }
    exit;

}

function excluir($id){

    $dados = array(
        'status' => '2'
    );
    DBUpdate('', 'tb_erro_atendimento',$dados,"id_erro_atendimento = $id");
    registraLog('Exclusão de erro atendimento.','e','tb_erro_atendimento',$id,'');

    $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=erro-atendimento-busca");
    exit;

}

?>
