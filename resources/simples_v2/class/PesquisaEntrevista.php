<?php
require_once(__DIR__."/System.php");


$id_contatos_pesquisa = (!empty($_POST['id_contatos_pesquisa'])) ? $_POST['id_contatos_pesquisa'] : '';
$id_pergunta_pesquisa = (!empty($_POST['id_pergunta_pesquisa'])) ? $_POST['id_pergunta_pesquisa'] : '';
$pergunta = (!empty($_POST['pergunta'])) ? $_POST['pergunta'] : '';
$resposta = (!empty($_POST['resposta'])) ? $_POST['resposta'] : '';
$id_pesquisa = (!empty($_POST['id_pesquisa'])) ? $_POST['id_pesquisa'] : '';
$id_native = (!empty($_POST['id_native'])) ? $_POST['id_native'] : '';
$id_usuario = $_SESSION['id_usuario'];

$data_agendar = (!empty($_POST['data_agendar'])) ? $_POST['data_agendar'] : '';
$hora_agendar = (!empty($_POST['hora_agendar'])) ? $_POST['hora_agendar'] : '';
$telefone_agendar = (!empty($_POST['telefone_agendar'])) ? preg_replace("/[^0-9]/", "", $_POST['telefone_agendar']) : '';

$falha = (!empty($_POST['falha'])) ? $_POST['falha'] : '';

$operacao = $_POST['operacao'];

$adicionar = (!empty($_POST['adicionar'])) ? $_POST['adicionar'] : '';
$contato = (!empty($_POST['contato'])) ? $_POST['contato'] : '';
$telefone = (!empty($_POST['telefone'])) ? $_POST['telefone'] : '';

$dado1 = (!empty($_POST['dado_adicional1'])) ? $_POST['dado_adicional1'] : '';
$dado2 = (!empty($_POST['dado_adicional2'])) ? $_POST['dado_adicional2'] : '';
$dado3 = (!empty($_POST['dado_adicional3'])) ? $_POST['dado_adicional3'] : '';

$obs_nao_ligar = (!empty($_POST['obs_nao_ligar'])) ? $_POST['obs_nao_ligar'] : '';
$obs_ligar_mais_tarde = (!empty($_POST['obs_ligar_mais_tarde'])) ? $_POST['obs_ligar_mais_tarde'] : '';

if($operacao == 'inserir'){
  
    $dados_perguntas = DBRead('', 'tb_pergunta_pesquisa', "WHERE id_pesquisa = $id_pesquisa AND status != 0");

    $dados_pesquisa = DBRead('', 'tb_pesquisa', "WHERE id_pesquisa = '$id_pesquisa'");
    $label1 = $dados_pesquisa[0]['dado1'];
    $label2 = $dados_pesquisa[0]['dado2'];
    $label3 = $dados_pesquisa[0]['dado3'];

    if($adicionar == 1){

        $data_inclusao = getDataHora();

        $dados = array(
            'nome' => $contato,
            'telefone' => $telefone,
            'data_inclusao' => $data_inclusao,
            'dado1' => $dado1,
            'dado2' => $dado2,
            'dado3' => $dado3,
            'label1' => $label1,
            'label2' => $label2,
            'label3' => $label3,
            'qtd_tentativas_cliente' => 1,
            'data_ultimo_contato' => '0000-00-00 00:00:00',
            'status_pesquisa' => 1,
            'id_pesquisa' => $id_pesquisa
        );

        $novo_contato = DBCreate('', 'tb_contatos_pesquisa', $dados, true);

        registraLog('Inserção de novo contato.', 'i', 'tb_contatos_pesquisa', $novo_contato, "nome: $nome | telefone: $telefone | data_inclusao: $data_inclusao | dado1: $dado1 | dado2: $dado2 | dado3: $dado3 | label1: $label1 | label2: $label2 | label3: $label3 | qtd_tentativas_cliente: 1 | data_ultimo_contato: 0000-00-00 00:00:00 | status_pesquisa: 1 | id_pesquisa: $id_pesquisa");
    }

    foreach($dados_perguntas as $dado){

        $var = $_POST["pergunta_".$dado['id_pergunta_pesquisa']];
        $obs = $_POST["observacao_".$dado['id_pergunta_pesquisa']];

        if($adicionar == 1){
            inserir($novo_contato, $dado['descricao'], $dado['id_pergunta_pesquisa'], implode(",", $var), $id_pesquisa, $id_usuario, implode(",", $obs));
            $contato = $novo_contato;
        }else{
            inserir($id_contatos_pesquisa, $dado['descricao'], $dado['id_pergunta_pesquisa'], implode(",", $var), $id_pesquisa, $id_usuario,  implode(",", $obs));
            $contato = $id_contatos_pesquisa;
        }
    }
    $dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario'", "a.*, b.nome");
    $dados_contato = DBRead('', 'tb_contatos_pesquisa', "WHERE id_contatos_pesquisa = '".$contato."'");
    
    $pesquisas = DBRead('', 'tb_pesquisa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_pesquisa = $id_pesquisa", "c.nome, a.titulo, a.id_pesquisa, b.qtd_contratada, a.id_contrato_plano_pessoa");
    $titulo_pesquisa = $pesquisas[0]['titulo'];
    $nome_empresa = $pesquisas[0]['nome'];
    $id_contrato_plano_pessoa = $pesquisas[0]['id_contrato_plano_pessoa'];

    $parametros = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND enviar_email = 1");
    $email_retorno = $parametros[0]['email_envio'];

    if($email_retorno){
        $dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario'", "a.*, b.nome");
        $dados_contato = DBRead('', 'tb_contatos_pesquisa', "WHERE id_contatos_pesquisa = '".$contato."'");

           $corpo = "
                <table style='border: 1px solid black;border-collapse: collapse;margin-bottom:10px;width:90%;' align='center'>
                    <thead style='border: 1px solid #808080;'>
                        <tr>
                            <th style='padding: 5px;color: #fff;background-color:#263868'><strong>Belluno - ".$titulo_pesquisa."</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style='border: 1px solid #808080;padding: 5px;'>
                            <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth'><strong>Contato:</strong> ".$dados_contato[0]['nome']."</td>
                        </tr>
                        <tr style='border: 1px solid #808080;padding: 5px;'>
                            <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth'><strong>Telefone:</strong> ".$dados_contato[0]['telefone']."</td>
                        </tr>";

                        if($dados_contato[0]['dado1']){
                            $corpo = $corpo."<tr style='border: 1px solid #808080;padding: 5px;'>
                                <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth'><strong>".$dados_contato[0]['label1'].":</strong> ".$dados_contato[0]['dado1']."</td>
                            </tr>";
                        }
                        if($dados_contato[0]['dado2']){
                            $corpo = $corpo."<tr style='border: 1px solid #808080;padding: 5px;'>
                                <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth'><strong>".$dados_contato[0]['label2'].":</strong> ".$dados_contato[0]['dado2']."</td>
                            </tr>";
                        }
                        if($dados_contato[0]['dado3']){
                            $corpo = $corpo."<tr style='border: 1px solid #808080;padding: 5px;'>
                                <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth'><strong>".$dados_contato[0]['label3'].":</strong> ".$dados_contato[0]['dado3']."</td>
                            </tr>";
                        }

                    $corpo = $corpo."<tr style='border: 1px solid #808080;padding: 5px;'>
                            <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth'><strong>Data:</strong> ".converteDataHora($dados_contato[0]['data_ultimo_contato'])."</td>
                        </tr>
                        <tr style='border: 1px solid #808080;padding: 5px;'>
                            <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth'><strong>Atendente:</strong> ".$dados_usuario[0]['nome']."</td>
                        </tr>
                ";

        foreach($dados_perguntas as $dado){

            $respostas = DBRead('', 'tb_entrevista_pesquisa', "WHERE id_pergunta_pesquisa = '".$dado['id_pergunta_pesquisa']."' AND id_contatos_pesquisa = '".$contato."'");

            $corpo = $corpo."
                        <tr style='border: 1px solid black; padding: 5px;'>
                            <td style='border: 1px solid #808080; padding: 5px;'><strong> - ".$dado['descricao']."
                        ";
            foreach($respostas as $resposta){

            $corpo = $corpo."</strong> ".$resposta['resposta']."<br><strong>Observação: </strong>".$resposta['obs_entrevista']."</td>   
                    </tr>";
            }
        }
        $corpo = $corpo."
                </tbody>
            </table>
            <div style='text-align:center;margin-top:12px'><p style='text-align:center;margin:1px'>Não responda este e-mail. Este endereço é utilizado apenas para envio dos atendimentos. Em caso de dúvidas ou dificuldades faça contato com a supervisão ou monitoria do call center através do endereço cs@bellunotec.com.br Para assuntos urgentes, contato por telefone pelo número (55) 3281-9200 Opção 3.</p>
            </div>
        ";

        envia_email("Belluno - Entrevista ", $corpo, $email_retorno);

    }else{
            echo"Não enviar";
    }

    verificaLimite($id_pesquisa);
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';
    //integração native, avisa se foi gravado uma pesquisa
        if($id_native){
            $dados_envio = '
				{
					"id_native":"'.$id_native.'"
				}
            ';
            
            $ch = curl_init('https://native.bellunotec.com.br/api/public/endcall');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dados_envio);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
            $result = curl_exec($ch);
            curl_close($ch);
        }
    //fim inegração native

    if($adicionar == 1){
        header("location: /api/iframe?token=$request->token&view=pesquisa-entrevistar-form&id_pesquisa=".$id_pesquisa."&adicionar=1");
    }else{
        header("location: /api/iframe?token=$request->token&view=pesquisa-entrevistar-form&id_pesquisa=".$id_pesquisa);
    }
    exit;
    
}else if($operacao == 'agendar'){

    $pesquisas = DBRead('', 'tb_pesquisa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_pesquisa = $id_pesquisa", "c.nome, a.titulo, a.id_pesquisa, a.qtd_tentativas_cliente, a.id_contrato_plano_pessoa");
    $titulo_pesquisa = $pesquisas[0]['titulo'];
    $nome_empresa = $pesquisas[0]['nome'];
    $id_contrato_plano_pessoa = $pesquisas[0]['id_contrato_plano_pessoa'];

    $qtd_contratada = $pesquisas[0]['qtd_tentativas_cliente'];

    $parametros = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND enviar_email = '1'");
    $email_retorno = $parametros[0]['email_envio'];

    if($email_retorno){
        $dados_contato = DBRead('', 'tb_contatos_pesquisa', "WHERE id_contatos_pesquisa = '".$id_contatos_pesquisa."'");
        $dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario'", "a.*, b.nome");

        $qtd_falhas = DBRead('', 'tb_falhas_pesquisa', "WHERE id_contatos_pesquisa = '".$dados_contato[0]['id_contatos_pesquisa']."'", "COUNT(*) AS cont");

        $f = array(
            "1" => 'Chamou até cair',
            "2" => 'Número não existe',
            "3" => 'Número errado',
            "4" => 'Caixa postal/fora de área',
            "5" => 'Número ocupado',
            "6" => 'Ligação caiu',
            "7" => 'Ligar mais tarde',
            "8" => 'Não ligar novamente',
            "9" => 'Interferência'
        );

        if((int)$qtd_falhas[0]['cont'] + 1 == $qtd_contratada){

            $corpo = "
                <table style='border: 1px solid black; border-collapse: collapse; margin-bottom: 10px; width: 90%;' align='center'>
                    <thead style='border: 1px solid #808080;'>
                        <tr>
                            <th style='padding: 5px;color: #fff;background-color:#263868'><strong>Belluno - ".$titulo_pesquisa."</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style='padding: 10px; text-align: center;'>Falhas completas. Feitas as ".$qtd_contratada." tentativas de contato</span></td>
                        </tr>
                        <tr style='border: 1px solid #808080;padding: 5px;'>
                            <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth'><strong>Contato:</strong> ".$dados_contato[0]['nome']."</td>
                        </tr>
                        <tr style='border: 1px solid #808080;padding: 5px;'>
                            <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth'><strong>Telefone:</strong> <span class='phone'></span>".$dados_contato[0]['telefone']."</td>
                        </tr>";

                        $falhas = DBRead('', 'tb_falhas_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa INNER JOIN tb_usuario c ON a.id_usuario = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.id_contatos_pesquisa = '".$dados_contato[0]['id_contatos_pesquisa']."'", "a.*, b.*, c.*, d.nome AS nome_usuario");

                        foreach ($falhas as $falhaa) {

                            $tipo_falha = $falhaa['falha'];
                            $data = $falhaa['data_falha'];
                            $usuario = $falhaa['nome_usuario'];

                            $corpo = $corpo."<tr style='border: 1px solid #808080;padding: 5px;'>
                                <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth'><strong>Atendente:</strong> ".$usuario."</td>
                            </tr>
                            <tr style='border: 1px solid #808080;padding: 5px;'>
                                <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth'><strong>Falha: </strong>".$f[$tipo_falha]." (".converteDataHora($data).")</td>
                            </tr>";

                        }
                        $corpo = $corpo."<tr style='border: 1px solid #808080;padding: 5px;'>
                                <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth'><strong>Atendente:</strong> ".$dados_usuario[0]['nome']."</td>
                            </tr>
                            <tr style='border: 1px solid #808080;padding: 5px;'>
                                <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth'><strong>Falha: </strong>".$f[$falha]." (".converteDataHora(getDataHora()).")</td>
                            </tr>";

                    $corpo = $corpo."</tbody>
                    </table>
                ";
                envia_email("Belluno - Falha na entrevista!", $corpo, $email_retorno);
        }
    }

    $pesquisas = DBRead('', 'tb_pesquisa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_pesquisa = $id_pesquisa", "c.nome, a.titulo, a.id_pesquisa, b.qtd_contratada, a.id_contrato_plano_pessoa");
    $titulo_pesquisa = $pesquisas[0]['titulo'];
        
    if($adicionar == 1){

        $data_inclusao = getDataHora();

        $dados = array(
            'nome' => $contato,
            'telefone' => $telefone,
            'data_inclusao' => $data_inclusao,
            'qtd_tentativas_cliente' => 0,
            'data_ultimo_contato' => '0000-00-00 00:00:00',
            'status_pesquisa' => 0,
            'id_pesquisa' => $id_pesquisa
        );

        $novo_contato = DBCreate('', 'tb_contatos_pesquisa', $dados, true);

        registraLog('Inserção de novo contato.','i','tb_contatos_pesquisa', $novo_contato, "nome: $nome | telefone: $telefone | data_inclusao: $data_inclusao | qtd_tentativas_cliente: 1 | data_ultimo_contato: 0000-00-00 00:00:00 | status_pesquisa: 1 | id_pesquisa: $id_pesquisa");
        
        $data_agendar = converteDataHora($data_agendar);
        $data_hora = $data_agendar." ".$hora_agendar.":00";

        inserirAgendamento($data_hora, $telefone_agendar, $novo_contato, $id_pesquisa, $id_usuario, $obs_ligar_mais_tarde);

    }else{

        $dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario'", "a.*, b.nome");
        $agenda = DBRead('', 'tb_agendamento_pesquisa', "WHERE id_contatos_pesquisa = '".$id_contatos_pesquisa."'");
    
        if($agenda){
            $dados = array(
                'status_agendamento' => '1'
            );
            DBUpdate('', 'tb_agendamento_pesquisa', $dados, "id_contatos_pesquisa = '".$id_contatos_pesquisa."'");
        }
        $data_agendar = converteDataHora($data_agendar);
        $data_hora = $data_agendar." ".$hora_agendar.":00";
        $agora = getDataHora();

        verificaLimite($id_pesquisa);
       
        if(strtotime($data_hora) > strtotime($agora)){
            inserirAgendamento($data_hora, $telefone_agendar, $id_contatos_pesquisa, $id_pesquisa, $id_usuario, $obs_ligar_mais_tarde);
            $alert = ('Item inserido com sucesso!');
    $alert_type = 's';
        }else{
            $alert = ('Data de agendamento incorreto!','d');
            header("location: /api/iframe?token=$request->token&view=pesquisa-entrevistar-busca");
        }

        if($adicionar == 1){
            header("location: /api/iframe?token=$request->token&view=pesquisa-entrevistar-form&id_pesquisa=".$id_pesquisa."&adicionar=1");
        }else{
            header("location: /api/iframe?token=$request->token&view=pesquisa-entrevistar-form&id_pesquisa=".$id_pesquisa);
        }

        exit;
    }
}else if($operacao == 'falhou'){

    if($adicionar == 1){

        $data_inclusao = getDataHora();

        $dados = array(
            'nome' => $contato,
            'telefone' => $telefone,
            'data_inclusao' => $data_inclusao,
            'qtd_tentativas_cliente' => 0,
            'data_ultimo_contato' => '0000-00-00 00:00:00',
            'status_pesquisa' => 0,
            'id_pesquisa' => $id_pesquisa
        );

        $novo_contato = DBCreate('', 'tb_contatos_pesquisa', $dados, true);

        registraLog('Inserção de novo contato.','i','tb_contatos_pesquisa', $novo_contato, "nome: $contato | telefone: $telefone | data_inclusao: $data_inclusao | qtd_tentativas_cliente: 1 | data_ultimo_contato: 0000-00-00 00:00:00 | status_pesquisa: 1 | id_pesquisa: $id_pesquisa");
        $id_contatos_pesquisa = $novo_contato;  
    }


    //################meu
    $pesquisas = DBRead('', 'tb_pesquisa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_pesquisa = $id_pesquisa", "c.nome, a.titulo, a.id_pesquisa, a.qtd_tentativas_cliente, a.id_contrato_plano_pessoa");
    $titulo_pesquisa = $pesquisas[0]['titulo'];
    $nome_empresa = $pesquisas[0]['nome'];
    $id_contrato_plano_pessoa = $pesquisas[0]['id_contrato_plano_pessoa'];
    
    $qtd_contratada = $pesquisas[0]['qtd_tentativas_cliente'];
    
    $parametros = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND enviar_email = '1'");
    $email_retorno = $parametros[0]['email_envio'];
    //#########################
    if($email_retorno){
        $f = array(
            "1" => 'Chamou até cair',
            "2" => 'Número não existe',
            "3" => 'Número errado',
            "4" => 'Caixa postal/fora de área',
            "5" => 'Número ocupado',
            "6" => 'Ligação caiu',
            "7" => 'Ligar mais tarde',
            "8" => 'Não ligar novamente',
            "9" => 'Interferência',
            "10" => 'Contato solicitou para ligar em outro horário, porém era a última tentativa'
        );
            
        //$p = DBRead('', 'tb_pesquisa', "WHERE id_pesquisa = '".$id_pesquisa."'");
        $dados_contato = DBRead('', 'tb_contatos_pesquisa', "WHERE id_contatos_pesquisa = '".$id_contatos_pesquisa."'");
        $dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario'", "a.*, b.nome");

        $qtd_falhas = DBRead('', 'tb_falhas_pesquisa', "WHERE id_contatos_pesquisa = '".$dados_contato[0]['id_contatos_pesquisa']."'", "COUNT(*) AS cont");
        if($falha == 8){

            $corpo = "
                <table style='border: 1px solid black;border-collapse: collapse;margin-bottom:10px;width:90%;' align='center'>
                    <thead style='border: 1px solid #808080;'>
                        <tr>
                            <th style='padding: 5px;color: #fff;background-color:#263868'><strong>Belluno - ".$titulo_pesquisa."</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style='padding: 10px; text-align: center;'>Falha eliminatória. Cliente não deseja realizar a entrevista!</span></td>
                        </tr>
                        <tr style='border: 1px solid #808080;padding: 5px;'>
                            <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth'><strong>Contato:</strong> ".$dados_contato[0]['nome']."</td>
                        </tr>
                        <tr style='border: 1px solid #808080;padding: 5px;'>
                            <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth'><strong>Telefone:</strong> <span class='phone'></span>".$dados_contato[0]['telefone']."</td>
                        </tr>
                        <tr style='border: 1px solid #808080;padding: 5px;'>
                            <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth'><strong>Atendente:</strong> ".$dados_usuario[0]['nome']."</td>
                        </tr>
                        <tr style='border: 1px solid #808080;padding: 5px;'>
                            <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth'><strong>Falha: </strong>Não ligar novamente (".converteDataHora(getDataHora()).")</td>
                        </tr>
                    </tbody>
                </table>
            ";
            envia_email("Falha na entrevista!", $corpo, $email_retorno);
        }
        else if((int)$qtd_falhas[0]['cont'] + 1 == $qtd_contratada){

            $corpo = "
                <table style='border: 1px solid black;border-collapse: collapse;margin-bottom:10px;width:90%;' align='center'>
                    <thead style='border: 1px solid #808080;'>
                        <tr>
                            <th style='padding: 5px;color: #fff;background-color:#263868'><strong>Belluno - ".$titulo_pesquisa."</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style='padding: 10px; text-align: center;'>Falhas completas. Feitas as ".$qtd_contratada." tentativas de contato</span></td>
                        </tr>
                        <tr style='border: 1px solid #808080;padding: 5px;'>
                            <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth'><strong>Contato:</strong> ".$dados_contato[0]['nome']."</td>
                        </tr>
                        <tr style='border: 1px solid #808080;padding: 5px;'>
                            <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth'><strong>Telefone:</strong> <span class='phone'></span>".$dados_contato[0]['telefone']."</td>
                        </tr>";

                        $falhas = DBRead('', 'tb_falhas_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa INNER JOIN tb_usuario c ON a.id_usuario = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.id_contatos_pesquisa = '".$dados_contato[0]['id_contatos_pesquisa']."'", "a.*, b.*, c.*, d.nome AS nome_usuario");

                        foreach ($falhas as $falhaa) {
                            
                            $tipo_falha = $falhaa['falha'];
                            $data = $falhaa['data_falha'];
                            $usuario = $falhaa['nome_usuario'];
                            
                            $corpo = $corpo."<tr style='border: 1px solid #808080;padding: 5px;'>
                                <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth'><strong>Atendente:</strong> ".$usuario."</td>
                            </tr>
                            <tr style='border: 1px solid #808080;padding: 5px;'>
                                <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth'><strong>Falha: </strong>".$f[$tipo_falha]." (".converteDataHora($data).")</td>
                            </tr>";
                        }
                        $corpo = $corpo."<tr style='border: 1px solid #808080;padding: 5px;'>
                                <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth'><strong>Atendente:</strong> ".$dados_usuario[0]['nome']."</td>
                            </tr>
                            <tr style='border: 1px solid #808080;padding: 5px;'>
                                <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth'><strong>Falha: </strong>".$f[$falha]." (".converteDataHora(getDataHora()).")</td>
                            </tr>";

                $corpo = $corpo."</tbody>
                </table>
            ";

                envia_email("Falha na entrevista!", $corpo, $email_retorno);
        }
    }
            
    verificaLimite($id_pesquisa); 

    if($adicionar == 1){
        inserirFalha($falha, $id_pesquisa, $novo_contato, $id_usuario, $obs_nao_ligar);
        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=pesquisa-entrevistar-form&id_pesquisa=".$id_pesquisa."&adicionar=1");
    }else{
        inserirFalha($falha, $id_pesquisa, $id_contatos_pesquisa, $id_usuario, $obs_nao_ligar);
        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=pesquisa-entrevistar-form&id_pesquisa=".$id_pesquisa);
    }

    exit;

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($id_contatos_pesquisa, $pergunta, $id_pergunta_pesquisa, $resposta, $id_pesquisa, $id_usuario, $obs){

    $data_ultimo_contato = getDataHora();

        $dados = array(
            'id_contatos_pesquisa' => $id_contatos_pesquisa,
            'pergunta' => $pergunta,
            'id_pergunta_pesquisa' => $id_pergunta_pesquisa,
            'resposta' => $resposta,
            'id_pesquisa' => $id_pesquisa,
            'id_usuario' => $id_usuario,
            'obs_entrevista' => $obs
        );
        
       $insertID = DBCreate('', 'tb_entrevista_pesquisa', $dados, true);
       registraLog('Inserção de nova entrevista.','i','tb_entrevista_pesquisa', $insertID, "id_contatos_pesquisa: $id_contatos_pesquisa | id_pergunta_pesquisa: $id_pergunta_pesquisa | pergunta: $pergunta | resposta: $resposta | id_pesquisa: $id_pesquisa | id_usuario: $id_usuario");

    $dados_contato_pesquisa = array(
        'data_ultimo_contato' => $data_ultimo_contato,
        'status_pesquisa' => '1'
    );

    DBUpdate('', 'tb_contatos_pesquisa', $dados_contato_pesquisa, "id_contatos_pesquisa = $id_contatos_pesquisa");
    registraLog('Alteração de contatos pesquisa.', 'a', 'tb_contatos_pesquisa', $id_contatos_pesquisa, "status_pesquisa: 1");

    $agendado = DBRead('', 'tb_agendamento_pesquisa', "WHERE id_contatos_pesquisa = '".$id_contatos_pesquisa."' AND status_agendamento = 0");
    if($agendado){
        
        $data_retorno = getDataHora();
        
        $dados = array(
            'status_agendamento' => '1',
            'data_retorno' => $data_retorno
        );

        DBUpdate('', 'tb_agendamento_pesquisa', $dados, "id_contatos_pesquisa = $id_contatos_pesquisa");
    }

}

function inserirAgendamento($data_hora, $telefone_agendar, $id_contatos_pesquisa, $id_pesquisa, $id_usuario, $obs_ligar_mais_tarde = null){

    $data_ultimo_contato = getDataHora();

    if($telefone_agendar){
     $dados = array(
            'data_hora' => $data_hora,
            'id_contatos_pesquisa' => $id_contatos_pesquisa,
            'status_agendamento' => '0',
            'telefone' => $telefone_agendar
        );
    }else{
         $dados = array(
            'data_hora' => $data_hora,
            'id_contatos_pesquisa' => $id_contatos_pesquisa,
            'status_agendamento' => '0'
        );
    }

    $insertID = DBCreate('', 'tb_agendamento_pesquisa', $dados, true);
    registraLog('Inserção de novo agendamento.','i','tb_agendamento_pesquisa', $insertID, "data: $data_hora | telefone: $telefone_agendar | id_contatos_pesquisa: $id_contatos_pesquisa");

    $contato = DBRead('', 'tb_contatos_pesquisa', "WHERE id_contatos_pesquisa = '".$id_contatos_pesquisa."'");
    $qtd_tentativas = $contato[0]['qtd_tentativas_cliente'] + 1;

    $qtd_tentativas_pesquisa = DBRead('', 'tb_pesquisa', "WHERE id_pesquisa = $id_pesquisa");
    $qtd_tentativas_pesquisa = $qtd_tentativas_pesquisa[0]['qtd_tentativas_cliente'];

    $dados_contato_pesquisa = array(
        'qtd_tentativas_cliente' => $qtd_tentativas,
        'data_ultimo_contato' => $data_ultimo_contato,
        'obs_falha' => $obs_ligar_mais_tarde
    );
    $data_ultimo_contato = getDataHora();

    $dados = array(
        'falha' => 7,
        'id_pesquisa' => $id_pesquisa,
        'id_contatos_pesquisa' => $id_contatos_pesquisa,
        'data_falha' => $data_ultimo_contato,
        'id_usuario' => $id_usuario
    );

    $insertID = DBCreate('', 'tb_falhas_pesquisa', $dados, true);
    registraLog('Inserção de nova falha.','i','tb_falhas_pesquisa', $insertID, "falha: 7 | id_pesquisa: $id_pesquisa | id_contatos_pesquisa: $id_contatos_pesquisa");

    DBUpdate('', 'tb_contatos_pesquisa', $dados_contato_pesquisa, "id_contatos_pesquisa = $id_contatos_pesquisa");
    registraLog('Alteração de contatos pesquisa.', 'a', 'tb_contatos_pesquisa', $id_contatos_pesquisa, "qtd_tentativas_cliente: qtd_tentativas_cliente");

    if($qtd_tentativas == $qtd_tentativas_pesquisa){

        $dados_contato_pesquisa = array(
            'status_pesquisa' => '2'
        );

        DBUpdate('', 'tb_contatos_pesquisa', $dados_contato_pesquisa, "id_contatos_pesquisa = $id_contatos_pesquisa");
        registraLog('Alteração de contatos pesquisa.', 'a', 'tb_contatos_pesquisa', $id_contatos_pesquisa, "status_pesquisa: status_pesquisa");

        $dados = array(
            'status_agendamento' => '1'
        );

        DBUpdate('', 'tb_agendamento_pesquisa', $dados, "id_contatos_pesquisa = $id_contatos_pesquisa");
        registraLog('Alteração de contatos pesquisa.', 'a', 'tb_agendamento_pesquisa', $id_contatos_pesquisa, "status_agendamento");
     
    }

    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=pesquisa-entrevistar-form&id_pesquisa=".$id_pesquisa);
}

function inserirFalha($falha, $id_pesquisa, $id_contatos_pesquisa, $id_usuario, $obs_nao_ligar){

    $data_ultimo_contato = getDataHora();

    $dados = array(
        'falha' => $falha,
        'id_pesquisa' => $id_pesquisa,
        'id_contatos_pesquisa' => $id_contatos_pesquisa,
        'data_falha' => $data_ultimo_contato,
        'id_usuario' => $id_usuario
    );

    $insertID = DBCreate('', 'tb_falhas_pesquisa', $dados, true);
    registraLog('Inserção de nova falha.','i','tb_falhas_pesquisa', $insertID, "falha: $falha | id_pesquisa: $id_pesquisa | id_contatos_pesquisa: $id_contatos_pesquisa");

    $qtd_tentativas = DBRead('', 'tb_contatos_pesquisa', "WHERE id_contatos_pesquisa = '".$id_contatos_pesquisa."'");
    $qtd_tentativas = $qtd_tentativas[0]['qtd_tentativas_cliente'] + 1;

    $qtd_tentativas_pesquisa = DBRead('', 'tb_pesquisa', "WHERE id_pesquisa = $id_pesquisa");
    $qtd_tentativas_pesquisa = $qtd_tentativas_pesquisa[0]['qtd_tentativas_cliente'];

    if($falha == 8){
        if($obs_nao_ligar != ''){
            $dados_contato_pesquisa = array(
                'qtd_tentativas_cliente' => $qtd_tentativas,
                'data_ultimo_contato' => $data_ultimo_contato,
                'status_pesquisa' => '3',
                'obs_falha' => $obs_nao_ligar
            );
        }else{
            $dados_contato_pesquisa = array(
                'qtd_tentativas_cliente' => $qtd_tentativas,
                'data_ultimo_contato' => $data_ultimo_contato,
                'status_pesquisa' => '3'
            );
        }
        
    }else{
        $dados_contato_pesquisa = array(
            'qtd_tentativas_cliente' => $qtd_tentativas,
            'data_ultimo_contato' => $data_ultimo_contato
        );
    }

    DBUpdate('', 'tb_contatos_pesquisa', $dados_contato_pesquisa, "id_contatos_pesquisa = $id_contatos_pesquisa");
    registraLog('Alteração de contatos pesquisa.', 'a', 'tb_contatos_pesquisa', $id_contatos_pesquisa, "qtd_tentativas_cliente: qtd_tentativas_cliente");

    $agendado = DBRead('', 'tb_agendamento_pesquisa', "WHERE id_contatos_pesquisa = '".$id_contatos_pesquisa."'");
        
        if($agendado){
            $data_retorno = getDataHora();
            
            $dados = array(
                'status_agendamento' => '1',
                'data_retorno' => $data_retorno
            );

            DBUpdate('', 'tb_agendamento_pesquisa', $dados, "id_contatos_pesquisa = $id_contatos_pesquisa");
            registraLog('Alteração de contatos pesquisa.', 'a', 'tb_agendamento_pesquisa', $id_contatos_pesquisa, "status_agendamento");
        }

    if($qtd_tentativas == $qtd_tentativas_pesquisa){
        
        $dados_contato_pesquisa = array(
            'status_pesquisa' => '2'
        );

        DBUpdate('', 'tb_contatos_pesquisa', $dados_contato_pesquisa, "id_contatos_pesquisa = $id_contatos_pesquisa");
        registraLog('Alteração de contatos pesquisa.', 'a', 'tb_contatos_pesquisa', $id_contatos_pesquisa, "status_pesquisa: status_pesquisa");
    }

    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=pesquisa-entrevistar-form&id_pesquisa=".$id_pesquisa);
    exit;
}

function verificaLimite($id_pesquisa){    

    $pesquisas = DBRead('', 'tb_pesquisa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_pesquisa = '".$id_pesquisa."'", "a.id_pesquisa, b.qtd_contratada, b.tipo_cobranca, a.status");

    //############ Verifica se realizados = contratados
    
    $qtd_contratada = $pesquisas[0]['qtd_contratada'];
    $tipo_cobranca = $pesquisas[0]['tipo_cobranca'];

    $hoje = getDataHora();
    $hoje = converteDataHora($hoje);
    $data_hoje = explode("/", $hoje);
    $hoje = $data_hoje[0];
    $data_de_hoje = getDataHora();
    $data_de_hoje = converteDataHora($data_de_hoje);
    $mes_atual = "01/".$data_hoje[1]."/".$data_hoje[2];
    $mes_agora = explode(" ", $mes_atual);
    $mes_atual = $mes_agora[0]." 00:00";
    $data_de_hoje = converteDataHora($data_de_hoje);
    $mes_atual = converteDataHora($mes_atual);

        if($tipo_cobranca == 'mensal'){

            $cont_total_contato = DBRead('', 'tb_contatos_pesquisa', "WHERE status_pesquisa != 0 AND status_pesquisa != 4 AND id_pesquisa = '".$id_pesquisa."' AND data_ultimo_contato >= '".$mes_atual."' GROUP BY id_pesquisa", "COUNT(*) AS cont, id_pesquisa");
            $cont_total = $cont_total_contato[0]['cont'];

            if($qtd_contratada <= $cont_total && $pesquisas[0]['status'] != 5){

                $dados_contato_pesquisa = array(
                    'status' => 4,
                );
                DBUpdate('', 'tb_pesquisa', $dados_contato_pesquisa, "id_pesquisa = '".$id_pesquisa."'");
                registraLog('Alteração de pesquisa, atingiu limite', 'a', 'tb_pesquisa', $id_pesquisa, "status_pesquisa: 0");
                $alert = ('Item inserido com sucesso! Esta pesquisa atingiu o limite de contatos.','s');
                header("location: /api/iframe?token=$request->token&view=pesquisa-entrevistar-busca");
                exit;

            }
        }else{

            $cont_total_contato = DBRead('', 'tb_contatos_pesquisa', "WHERE status_pesquisa != 0 AND status_pesquisa != 4 AND id_pesquisa = '".$id_pesquisa."' GROUP BY id_pesquisa", "COUNT(*) AS cont, id_pesquisa");
            $cont_total = $cont_total_contato[0]['cont'];

            if($qtd_contratada <= $cont_total && $pesquisas[0]['status'] != 5){

                $dados_contato_pesquisa = array(
                    'status' => 4,
                );
                DBUpdate('', 'tb_pesquisa', $dados_contato_pesquisa, "id_pesquisa = '".$id_pesquisa."'");
                registraLog('Alteração de pesquisa, atingiu limite', 'a', 'tb_pesquisa', $id_pesquisa, "status_pesquisa: 0");
                $alert = ('Item inserido com sucesso! Esta pesquisa atingiu o limite de contatos.','s');
                header("location: /api/iframe?token=$request->token&view=pesquisa-entrevistar-busca");
                exit;

            }
        } 

}

?>