<?php
require_once(__DIR__."/System.php");
require_once(__DIR__."/QuadroInformativoHistorico.php");


$solicitacao_dados = (!empty($_POST['solicitacao_dados'])) ? $_POST['solicitacao_dados'] : 0;
$solicitacao_dados_descricao = (!empty($_POST['solicitacao_dados_descricao'])) ? $_POST['solicitacao_dados_descricao'] : '';
$solicitacao_cpf = (!empty($_POST['solicitacao_cpf'])) ? $_POST['solicitacao_cpf'] : 0;
$ramal_retorno = (!empty($_POST['ramal_retorno'])) ? $_POST['ramal_retorno'] : '';
$prefixo_telefone = (!empty($_POST['prefixo_telefone'])) ? $_POST['prefixo_telefone'] : '';
$id_tipo_central_telefonica = (!empty($_POST['id_tipo_central_telefonica'])) ? $_POST['id_tipo_central_telefonica'] : '';
$controle_fila = (!empty($_POST['controle_fila'])) ? $_POST['controle_fila'] : 0;

//Configuração para dizer se os monitoramentos são registrados em sistema de gestão, sem relação com integração
$registra_monitormento_sistema_gestao = (!empty($_POST['registra_monitormento_sistema_gestao'])) ? $_POST['registra_monitormento_sistema_gestao'] : 0;

//Configuração para dizer se realizamos atendimento via texto
$atendimento_via_texto = (!empty($_POST['atendimento_via_texto'])) ? $_POST['atendimento_via_texto'] : 0;

//Numero do Botmaker
$channel_name = (!empty($_POST['channel_name'])) ? $_POST['channel_name'] : '';

//Campo que valida se o número de retorno é para fixo, celular, ou ambos.
$retorno_valido_para = (!empty($_POST['retorno_valido_para'])) ? $_POST['retorno_valido_para'] : '';

if(count($retorno_valido_para) >= 2){
    $retorno_valido_para = 3;
}else if($retorno_valido_para[0] == 1){
    $retorno_valido_para = 1;
}else if($retorno_valido_para[0] == 2){
    $retorno_valido_para = 2;
}else{
    $retorno_valido_para = 3;
}

$enviar_email = (!empty($_POST['enviar_email'])) ? $_POST['enviar_email'] : 0;
//$enviar_ativo_email = (!empty($_POST['enviar_ativo_email'])) ? $_POST['enviar_ativo_email'] : 0;

$exibir_protocolo = (!empty($_POST['exibir_protocolo'])) ? $_POST['exibir_protocolo'] : 0;
$id_asterisk = (!empty($_POST['id_asterisk'])) ? $_POST['id_asterisk'] : '';

$email_envio = (!empty($_POST['email_envio'])) ? $_POST['email_envio'] : '';
//$email_envio_ativos = (!empty($_POST['email_envio_ativos'])) ? $_POST['email_envio_ativos'] : '';

$id_contrato_plano_pessoa  = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';

$ativacao = (!empty($_POST['ativacao'])) ? $_POST['ativacao'] : 0;
$pular = (!empty($_POST['pular'])) ? $_POST['pular'] : '';
$voltar = (!empty($_POST['voltar'])) ? $_POST['voltar'] : '';
$salvar = (!empty($_POST['salvar'])) ? $_POST['salvar'] : 0;
$exclui_ativacao = (!empty($_GET['exclui-ativacao'])) ? $_GET['exclui-ativacao'] : 0;
$id_contrato = (!empty($_GET['id-contrato'])) ? $_GET['id-contrato'] : '';

$horario_belluno  = (!empty($_POST['horario_belluno'])) ? $_POST['horario_belluno'] : '';

if($id_asterisk && $id_asterisk != ''){
    $valida_asterisk = "OR id_asterisk ='$id_asterisk'";
}else{
    $valida_asterisk = "";
}

if($channel_name && $channel_name != ''){
    $valida_channel_name = "OR channel_name ='$channel_name'";
}else{
    $valida_channel_name = "";
}

if(!empty($_POST['inserir'])){

    $verificacao_contrato = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'  ".$valida_asterisk." ".$valida_channel_name." ");

    if(!$verificacao_contrato){

        inserir($solicitacao_dados, $solicitacao_dados_descricao, $solicitacao_cpf, $ramal_retorno, $prefixo_telefone, $enviar_email, $exibir_protocolo, $id_asterisk, $email_envio, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar, $horario_belluno, $id_tipo_central_telefonica, $retorno_valido_para, $registra_monitormento_sistema_gestao, $atendimento_via_texto, $channel_name, $controle_fila);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=parametro-form");
        exit;
    }

}else if(!empty($_POST['alterar'])){
    $id = (int)$_POST['alterar'];

    $verificacao_contrato = DBRead('', 'tb_parametros', "WHERE id_parametros != '".$id."' AND (id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ".$valida_asterisk." ".$valida_channel_name.")");

    if(!$verificacao_contrato){
        alterar($id, $solicitacao_dados, $solicitacao_dados_descricao, $solicitacao_cpf, $ramal_retorno, $prefixo_telefone, $enviar_email, $exibir_protocolo, $id_asterisk, $email_envio, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar, $horario_belluno, $id_tipo_central_telefonica, $retorno_valido_para, $registra_monitormento_sistema_gestao, $atendimento_via_texto, $channel_name, $controle_fila);
    }else{
        $alert = ('Item já existe na base de dados!', 'w');
        header("location: /api/iframe?token=$request->token&view=parametro-form&alterar=$id");
        exit;
    }
    $alert = ('Item já existe na base de dados!', 'w');
    header("location: /api/iframe?token=$request->token&view=parametro-form&alterar=$id");
    exit;
    
}else if(isset($_GET['excluir'])){

    $id = (int)$_GET['excluir'];
    excluir($id, $exclui_ativacao, $id_contrato);
}else if(isset($_GET['teste_email'])){

    $id = (int)$_GET['teste_email'];
    teste_email($id);
}else{
    header("location: ../adm.php");
    exit;
}

function inserir($solicitacao_dados, $solicitacao_dados_descricao, $solicitacao_cpf, $ramal_retorno, $prefixo_telefone, $enviar_email, $exibir_protocolo, $id_asterisk, $email_envio, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar, $horario_belluno, $id_tipo_central_telefonica, $retorno_valido_para, $registra_monitormento_sistema_gestao, $atendimento_via_texto, $channel_name, $controle_fila){

    $dados = array(
        'solicitacao_dados' => $solicitacao_dados,
        'solicitacao_dados_descricao' => $solicitacao_dados_descricao,
        'solicitacao_cpf' => $solicitacao_cpf,
        'ramal_retorno' => $ramal_retorno,
        'prefixo_telefone' => $prefixo_telefone,
        'enviar_email' => $enviar_email,
        'exibir_protocolo' => $exibir_protocolo,
        'id_asterisk' => $id_asterisk,
        'email_envio' => $email_envio,
        'horario_belluno' => $horario_belluno,
        'retorno_valido_para' => $retorno_valido_para,
        'registra_monitormento_sistema_gestao' => $registra_monitormento_sistema_gestao,
        'atendimento_via_texto' => $atendimento_via_texto,
        'id_tipo_central_telefonica' => $id_tipo_central_telefonica,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'channel_name' => $channel_name,
        'controle_fila' => $controle_fila
    );

    $insertID = DBCreate('', 'tb_parametros', $dados, true);
    registraLog('Inserção de novo parâmetro.','i','tb_parametros', $insertID, "solicitacao_dados: $solicitacao_dados | solicitacao_dados_descricao: $solicitacao_dados_descricao | solicitacao_cpf: $solicitacao_cpf | ramal_retorno: $ramal_retorno | prefixo_telefone: $prefixo_telefone | enviar_email: $enviar_email | exibir_protocolo: $exibir_protocolo | id_asterisk: $id_asterisk | email_envio: $email_envio | retorno_valido_para: $retorno_valido_para | registra_monitormento_sistema_gestao: $registra_monitormento_sistema_gestao | atendimento_via_texto: $atendimento_via_texto | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_tipo_central_telefonica: $id_tipo_central_telefonica | channel_name: $channel_name | controle_fila: $controle_fila");

    // QUADRO INFORMATIVO HISTORICO
    $solicitacao_dados_historico = "Não";
    $solicitacao_cpf_historico = "Não";
    $enviar_email_historico = "Não";
    $exibir_protocolo_historico = "Não";
    $retorno_valido_para_historico = "Ambos"; //1 - fixo; 2 - celular; 3 - ambos.
    $registra_monitormento_sistema_gestao_historico = "Não";
    $atendimento_via_texto_historico = "Não";
    $controle_fila_historico = "Não";
    if($solicitacao_dados == 1){
        $solicitacao_dados_historico = "Sim";
    }
    if($solicitacao_cpf == 1){
        $solicitacao_cpf_historico = "Sim";
    }
    if($enviar_email == 1){
        $enviar_email_historico = "Sim";
    }
    if($exibir_protocolo == 1){
        $exibir_protocolo_historico = "Sim";
    }
    if($retorno_valido_para != 3){
        if($retorno_valido_para == 1){
            $retorno_valido_para_historico = "Fixo";
        }else{
            $retorno_valido_para_historico = "Celular";
        }
    }
    if($registra_monitormento_sistema_gestao == 1){
        $registra_monitormento_sistema_gestao_historico = "Sim";
    }
    if($atendimento_via_texto == 1){
        $atendimento_via_texto_historico = "Sim";
    }
    if($controle_fila == 1){
        $controle_fila_historico = "Sim";
    }

    $dados_tipo_central_telefonica_historico = DBRead('','tb_tipo_central_telefonica', "WHERE id_tipo_central_telefonica = '".$id_tipo_central_telefonica."' LIMIT 1");
    
    inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novo parâmetro", "solicitacao dados: $solicitacao_dados_historico | solicitacao dados descricao: $solicitacao_dados_descricao | solicitacao cpf: $solicitacao_cpf_historico | ramal retorno: $ramal_retorno | prefixo telefone: $prefixo_telefone | enviar email: $enviar_email_historico | exibir protocolo: $exibir_protocolo_historico | id asterisk: $id_asterisk | email envio: $email_envio | retorno valido para: $retorno_valido_para_historico | registra monitormento sistema gestao: $registra_monitormento_sistema_gestao_historico | atendimento via texto: $atendimento_via_texto_historico | tipo central telefonica: ".$dados_tipo_central_telefonica_historico[0]['descricao']." | channel name: $channel_name | controle fila: $controle_fila_historico", 9);
    
    $alert = ('Item inserido com sucesso!', 's');

    if($ativacao == 1){
        if($pular){
            header("location: /api/iframe?token=$request->token&view=ura-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=velocidade-minima-encaminhar-form&alterar=$voltar&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=ura-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=parametro-busca");
    }
    exit;
}

function alterar($id, $solicitacao_dados, $solicitacao_dados_descricao, $solicitacao_cpf, $ramal_retorno, $prefixo_telefone, $enviar_email, $exibir_protocolo, $id_asterisk, $email_envio, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar, $horario_belluno, $id_tipo_central_telefonica, $retorno_valido_para, $registra_monitormento_sistema_gestao, $atendimento_via_texto, $channel_name, $controle_fila){

    $dados = array(
        'solicitacao_dados' => $solicitacao_dados,
        'solicitacao_dados_descricao' => $solicitacao_dados_descricao,
        'solicitacao_cpf' => $solicitacao_cpf,
        'ramal_retorno' => $ramal_retorno,
        'prefixo_telefone' => $prefixo_telefone,
        'enviar_email' => $enviar_email,
        'exibir_protocolo' => $exibir_protocolo,
        'id_asterisk' => $id_asterisk,
        'email_envio' => $email_envio,
        'horario_belluno' => $horario_belluno,
        'retorno_valido_para' => $retorno_valido_para,
        'registra_monitormento_sistema_gestao' => $registra_monitormento_sistema_gestao,
        'atendimento_via_texto' => $atendimento_via_texto,
        'id_tipo_central_telefonica' => $id_tipo_central_telefonica,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'channel_name' => $channel_name,
        'controle_fila' => $controle_fila
    );

    DBUpdate('', 'tb_parametros', $dados, "id_parametros = $id");

    registraLog('Alteração de parâmetro.','a','tb_parametros', $id, "solicitacao_dados: $solicitacao_dados | solicitacao_dados_descricao: $solicitacao_dados_descricao | solicitacao_cpf: $solicitacao_cpf | ramal_retorno: $ramal_retorno | prefixo_telefone: $prefixo_telefone | enviar_email: $enviar_email | exibir_protocolo: $exibir_protocolo | id_asterisk: $id_asterisk | email_envio: $email_envio | retorno_valido_para: $retorno_valido_para | registra_monitormento_sistema_gestao: $registra_monitormento_sistema_gestao | atendimento_via_texto: $atendimento_via_texto | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_tipo_central_telefonica: $id_tipo_central_telefonica | channel_name: $channel_name | controle_fila: $controle_fila");

    // QUADRO INFORMATIVO HISTORICO
    $solicitacao_dados_historico = "Não";
    $solicitacao_cpf_historico = "Não";
    $enviar_email_historico = "Não";
    $exibir_protocolo_historico = "Não";
    $retorno_valido_para_historico = "Ambos"; //1 - fixo; 2 - celular; 3 - ambos.
    $registra_monitormento_sistema_gestao_historico = "Não";
    $atendimento_via_texto_historico = "Não";
    $controle_fila_historico = "Não";
    if($solicitacao_dados == 1){
        $solicitacao_dados_historico = "Sim";
    }
    if($solicitacao_cpf == 1){
        $solicitacao_cpf_historico = "Sim";
    }
    if($enviar_email == 1){
        $enviar_email_historico = "Sim";
    }
    if($exibir_protocolo == 1){
        $exibir_protocolo_historico = "Sim";
    }
    if($retorno_valido_para != 3){
        if($retorno_valido_para == 1){
            $retorno_valido_para_historico = "Fixo";
        }else{
            $retorno_valido_para_historico = "Celular";
        }
    }
    if($registra_monitormento_sistema_gestao == 1){
        $registra_monitormento_sistema_gestao_historico = "Sim";
    }
    if($atendimento_via_texto == 1){
        $atendimento_via_texto_historico = "Sim";
    }
    if($controle_fila == 1){
        $controle_fila_historico = "Sim";
    }

    $dados_tipo_central_telefonica_historico = DBRead('','tb_tipo_central_telefonica', "WHERE id_tipo_central_telefonica = '".$id_tipo_central_telefonica."' LIMIT 1");
    
    inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de novo parâmetro", "solicitacao dados: $solicitacao_dados_historico | solicitacao dados descricao: $solicitacao_dados_descricao | solicitacao cpf: $solicitacao_cpf_historico | ramal retorno: $ramal_retorno | prefixo telefone: $prefixo_telefone | enviar email: $enviar_email_historico | exibir protocolo: $exibir_protocolo_historico | id asterisk: $id_asterisk | email envio: $email_envio | retorno valido para: $retorno_valido_para_historico | registra monitormento sistema gestao: $registra_monitormento_sistema_gestao_historico | atendimento via texto: $atendimento_via_texto_historico | tipo central telefonica: ".$dados_tipo_central_telefonica_historico[0]['descricao']." | channel name: $channel_name | controle fila: $controle_fila_historico", 9);

    $alert = ('Item alterado com sucesso!', 's');

    if($ativacao == 1){

        if($pular){
            header("location: /api/iframe?token=$request->token&view=ura-form&alterar=$pular&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=velocidade-minima-encaminhar-form&alterar=$voltar&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=ura-form&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=parametro-busca");
    }
    exit;
}

function excluir($id, $exclui_ativacao, $id_contrato){

    if($exclui_ativacao == 1){

        $dados = DBRead('', 'tb_ura_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato");

        $query = "DELETE FROM tb_parametros WHERE id_parametros = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de parâmetro.','e','tb_parametros',$id,'');
        
        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de parâmetro", "Excluiu dados", 9);

        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }

        if($dados){
            $dado_posterior = $dados[0]['id_ura_contrato'];
            header("location: /api/iframe?token=$request->token&view=ura-form&alterar=$dado_posterior&ativacao=1&id_contrato=$id_contrato");
        }else{
            header("location: /api/iframe?token=$request->token&view=ura-form&ativacao=1&id_contrato=$id_contrato");
        }

    }else{
        $id_contrato = DBRead('', 'tb_parametros', "WHERE id_parametros = ".$id."");
        $id_contrato = $id_contrato[0]['id_contrato_plano_pessoa'];

        $query = "DELETE FROM tb_parametros WHERE id_parametros = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de parâmetro.','e','tb_parametros',$id,'');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de parâmetro", "Excluiu dados", 9);

        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }
        header("location: /api/iframe?token=$request->token&view=parametro-busca");
    }

    

    exit;
}

function teste_email($id){

    $dados_parametros = DBRead('','tb_parametros',"WHERE id_parametros = '$id'");

    $corpo = "
        <table style='border: 1px solid black;border-collapse: collapse;margin-bottom:10px;width:90%;' align='center'>
            <thead style='border: 1px solid #808080;'>
                <tr>
                    <th style='padding: 5px;color: #fff;background-color:#263868' colspan='2'><strong>Belluno - Teste de e-mail</strong></th>
                </tr>
            </thead>
            <tbody>
                <tr style='border: 1px solid #808080;padding: 5px;'>
                    <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth' colspan='2'><strong>Teste: </strong> teste de e-mail.</td>
                </tr>
            </tbody>
        </table>
        <div style='text-align:center;margin-top:12px'><p style='text-align:center;margin:1px'>Não responda este e-mail. Este endereço é utilizado apenas para envio dos atendimentos. Em caso de dúvidas ou dificuldades faça contato com a supervisão ou monitoria do call center através do endereço cs@bellunotec.com.br Para assuntos urgentes, contato por telefone pelo número (55) 3281-9200 Opção 3.</p></div>
    ";

    envia_email("Belluno - Teste de e-mail", $corpo, $dados_parametros[0]['email_envio']);

    if(!$dados_parametros[0]['email_envio'] || $dados_parametros[0]['email_envio'] == ' ' || $dados_parametros[0]['email_envio'] == ''){
        $alert = ('Nenhum e-mail enviado!','d');
    }else{
        $alert = ('E-mail enviado com sucesso para o(s) endereço(s): '.$dados_parametros[0]['email_envio'],'s');
    }
    header("location: /api/iframe?token=$request->token&view=parametro-busca");
    exit;
}


?>