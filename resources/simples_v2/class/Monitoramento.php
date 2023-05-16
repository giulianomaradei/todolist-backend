<?php
require_once(__DIR__."/System.php");


$contrato = (!empty($_POST['contrato'])) ? $_POST['contrato'] : '';
$data_queda = (!empty($_POST['data_queda'])) ? $_POST['data_queda'] : '';
$hora_queda = (!empty($_POST['hora_queda'])) ? $_POST['hora_queda'] : '';
$status_contato = (!empty($_POST['status_contato'])) ? $_POST['status_contato'] : '';
$nome_tecnico = (!empty($_POST['nome_tecnico'])) ? $_POST['nome_tecnico'] : '';
$telefone = (!empty($_POST['telefone'])) ? preg_replace("/[^0-9]/", "", $_POST['telefone']) : '';
$informacao = (!empty($_POST['informacao'])) ? $_POST['informacao'] : '';

$nomes = (!empty($_POST['nomes'])) ? $_POST['nomes'] : '';

$enviar_email = (!empty($_POST['enviar-email'])) ? $_POST['enviar-email'] : '';

if(!empty($_POST['inserir'])) {

    //Verifica se há uma integração de sistema de gestão para o cliente que está salvando o atendimento e qual é o sistema.
    //$tem_integracao = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = '$contrato'"); //comentado por causa do chamado 50478
 
    if($tem_integracao && $tem_integracao[0]['id_integracao'] == '1'){
        require_once "./AtendimentoIntegracao.php";
        require_once "./OrdemServicoIntegracao.php";
        require_once './integracoes/MonitoramentoIxc.php';
    }

    $cont = 0;
    $dados_monitoramento = array();
    foreach($nomes as $conteudo){
        $dados_monitoramento[$cont]['nome'] = $conteudo;
        $cont++;
    }

    inserir($contrato, $data_queda, $hora_queda, $status_contato, $nome_tecnico, $telefone, $informacao, $dados_monitoramento, $enviar_email);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($contrato, $data_queda, $hora_queda, $status_contato, $nome_tecnico, $telefone, $informacao, $dados_monitoramento, $enviar_email){

    $data_queda = converteDataHora($data_queda) . ' ' . $hora_queda . ':00';
    $tipo_alerta_msg_email_envio = 's';
    $id_usuario = $_SESSION['id_usuario'];
    
    $dados = array(
        'id_contrato_plano_pessoa' => $contrato,
        'data_queda' => $data_queda,
        'nome_tecnico' => $nome_tecnico,
        'telefone' => $telefone,
        'status_contato' => $status_contato,
        'informacao' => $informacao,
        'id_usuario' => $id_usuario,
        'data_registro' => getDataHora()
    );

    $insertID = DBCreate('', 'tb_monitoramento_queda', $dados, true);
    registraLog('Inserção de novo monitoramento queda.','i','tb_monitoramento_queda',$insertID,"id_contrato_plano_pessoa: $contrato | data_queda: $data_queda | nome_tecnico: $nome_tecnico | telefone: $telefone | status_contato: $status_contato | informacao: $informacao");

    foreach($dados_monitoramento as $conteudo){
        if($conteudo['nome']){
            $nome = $conteudo['nome'];
            $dadosMonitoramento = array(
                'nome' => $nome,
                'id_monitoramento_queda' => $insertID
            );
            
            $insertMonitoramento = DBCreate('', 'tb_pop_queda', $dadosMonitoramento, true);
            registraLog('Inserção de novo monitoramento.','i','tb_pop_queda',$insertMonitoramento,"nome: $nome | id_monitoramento_queda: $insertID");
        }
    }

    //INICIO ENVIO DO EMAIL
    $dados_parametros = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = '$contrato'");
    
  
    $nome_status_contato = array(
        "1" => "Com sucesso",
        "2" => "Sem sucesso"
        );
    $dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario'",'b.nome'); 
    $corpo = "
        <table style='border: 1px solid black;border-collapse: collapse;margin-bottom:10px;width:90%;' align='center'>
            <thead style='border: 1px solid #808080;'>
                <tr>
                    <th style='padding: 5px;color: #fff;background-color:#263868' colspan='2'><strong>Belluno - Monitoramento</strong></th>
                </tr>
            </thead>
            <tbody>";
            $corpo .= "
                <tr style='border: 1px solid #808080;padding: 5px;'>
                    <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth' colspan='2'><strong>Data e hora da queda: </strong>".converteData($data_queda).' '.$hora_queda."</td>
                </tr>
            ";
            $corpo .= "<tr style='border: 1px solid #808080;padding: 5px;'>
                    <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth' colspan='2'><strong>Contato com o técnico: </strong> ".$nome_status_contato[$status_contato]."</td>
                </tr>";
            if($status_contato == '1'){
                $corpo .= "
                <tr style='border: 1px solid #808080;padding: 5px;'>
                    <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth' colspan='2'><strong>Nome do técnico: </strong> ".$nome_tecnico."</td>
                </tr>";
                $corpo .= "
                <tr style='border: 1px solid #808080;padding: 5px;'>
                    <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth' colspan='2'><strong>Telefone: </strong> ".$telefone."</td>
                </tr>";
            }                
            $corpo .= "
                <tr style='border: 1px solid #808080;padding: 5px;'>
                    <td style='border: 1px solid #808080;padding: 5px;text-align:rigth' colspan='2'><strong>Informações adicionais: </strong><br>".nl2br($informacao)."</td>
                </tr>";
            $corpo .= "
                <tr style='border: 1px solid #808080;padding: 5px;'>
                    <td style='border: 1px solid #808080;padding: 5px;text-align:rigth' colspan='2'><strong>POP(s): </strong><br>";
                    $cont = 1;
                    foreach($dados_monitoramento as $conteudo){
                        if($conteudo['nome']){                        
                            $corpo .= $conteudo['nome']."<br>";
                            $cont++;  
                        }
                    }
            $corpo .= 
                    "</td>
                </tr>";                
            
            $corpo .= "                    
                <tr style='border: 1px solid #808080;padding: 5px;'>
                    <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth' colspan='2'><strong>Atendente: </strong>".$dados_usuario[0]['nome']."</td>
                </tr>                    
            </tbody>
        </table>
        <div style='text-align:center;margin-top:12px'><p style='text-align:center;margin:1px'>Não responda este e-mail. Este endereço é utilizado apenas para envio dos atendimentos. Em caso de dúvidas ou dificuldades faça contato com a supervisão ou monitoria do call center através do endereço cs@bellunotec.com.br Para assuntos urgentes, contato por telefone pelo número (55) 3281-9200 Opção 3.</p></div>
    ";
    envia_email("Belluno - Monitoramento", $corpo, $dados_parametros[0]['email_envio']);

    if(!$dados_parametros[0]['email_envio'] || $dados_parametros[0]['email_envio'] == ' ' || $dados_parametros[0]['email_envio'] == ''){
        $msg_email_envio = ' Obs: Não há um e-mail cadastrado para envio!';
        $tipo_alerta_msg_email_envio = 'w';
    }
    //FIM DO ENVIO DO EMAIL
    
    $alert = ('Monitoramento gravado com sucesso!'.$msg_email_envio,$tipo_alerta_msg_email_envio);
    header("location: /api/iframe?token=$request->token&view=monitoramento-busca");
    exit;
}

?>