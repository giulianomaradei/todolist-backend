<?php
require_once(__DIR__."/System.php");

$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$id_usuario = $_SESSION['id_usuario'];

$id_atendimento = (!empty($_POST['id_atendimento'])) ? $_POST['id_atendimento'] : 0;
$id_arvore = (!empty($_POST['id_arvore'])) ? $_POST['id_arvore'] : 1;
$id_falha = (!empty($_POST['id_falha'])) ? $_POST['id_falha'] : 0;

$assinante = (!empty($_POST['assinante'])) ? $_POST['assinante'] : '';
$contato = (!empty($_POST['contato'])) ? $_POST['contato'] : '';
$fone1 = (!empty($_POST['fone1'])) ? preg_replace("/[^0-9]/", "", $_POST['fone1']) : '';
$fone2 = (!empty($_POST['fone2'])) ? preg_replace("/[^0-9]/", "", $_POST['fone2']) : '';
$cpf_cnpj = (!empty($_POST['cpf_cnpj'])) ? preg_replace("/[^0-9]/", "", $_POST['cpf_cnpj']) : '';
$protocolo = (!empty($_POST['protocolo'])) ? $_POST['protocolo'] : '';

$label_dado_adicional = (!empty($_POST['label_solicitacao'])) ? $_POST['label_solicitacao'] : '';
$dado_adicional = (!empty($_POST['solicitacao'])) ? $_POST['solicitacao'] : '';
$data_inicio = (!empty($_POST['data_inicio'])) ? $_POST['data_inicio'] : '';
$data_fim = getDataHora();
$anotacao = (!empty($_POST['anotacao'])) ? $_POST['anotacao'] : '';
$os = (!empty($_POST['os'])) ? $_POST['os'] : '';
$situacao = (!empty($_POST['situacao'])) ? $_POST['situacao'] : '';
$elogio = (!empty($_POST['elogio'])) ? $_POST['elogio'] : 0;
$irritado = (!empty($_POST['irritado'])) ? $_POST['irritado'] : 0;
$canal_atendimento = (!empty($_POST['canal_atendimento'])) ? $_POST['canal_atendimento'] : '';

$sistema_gestao = (!empty($_POST['sistema_gestao'])) ? $_POST['sistema_gestao'] : 0;
$descricao_chamado = (!empty($_POST['descricao_chamado'])) ? $_POST['descricao_chamado'] : 0;
$chamado = (!empty($_POST['chamado'])) ? $_POST['chamado'] : 0;

$enviar_email = (!empty($_POST['enviar-email'])) ? $_POST['enviar-email'] : '';

$descricao_dado_adicional = (!empty($_POST['descricao_dado_adicional'])) ? $_POST['descricao_dado_adicional'] : '';


if(!empty($_POST['inserir'])){

    inserir($assinante, $contato, $fone1, $fone2, $cpf_cnpj, $data_inicio, $label_dado_adicional, $dado_adicional, $id_contrato_plano_pessoa, $id_usuario);

} else if(!empty($_POST['atualizar'])){

    $id = (int)$_POST['atualizar'];
    atualizar($id, $id_atendimento, $data_fim, $anotacao);
   
} else if(!empty($_POST['gravar'])){

    //Verifica se há uma integração de sistema de gestão para o cliente que está salvando o atendimento e qual é o sistema.
    $tem_integracao = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");

    if($tem_integracao && $tem_integracao[0]['id_integracao'] == '1'){
        require_once "./AtendimentoIntegracao.php";
        require_once "./OrdemServicoIntegracao.php";
        require_once './integracoes/AtendimentoIxc.php';
    }

    $dados_parametro = DBRead('','tb_parametros',"WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
    $situacao_descricao = DBRead('', 'tb_situacao', "WHERE id_situacao = '$situacao'");
    $atendente = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario'");
    
    $descricao = "<p>";
    $descricao = $descricao."Contato: ".$contato."<br>";
    $descricao = $descricao."Assinante: ".$assinante."<br>";

    if($fone2){
        $descricao = $descricao."Telefone 1: <span class = 'phone'>".$fone1."</span><br>";
        $descricao = $descricao."Telefone 2: <span class = 'phone'>".$fone2."</span><br>";
    }else{
        $descricao = $descricao."Telefone: <span class = 'phone'>".$fone1."</span><br>";
    }
    if($cpf_cnpj){
        $descricao = $descricao."CPF/CNPJ: ".$cpf_cnpj."<br>";
    }
    if($dados_parametro[0]['exibir_protocolo'] == '1'){
        $descricao = $descricao."Protocolo: ".$protocolo."<br>";
    }
    if($dado_adicional){
        $descricao = $descricao."".$descricao_dado_adicional.": ".$dado_adicional."<br>";
    }

    $descricao = $descricao."Atendente: ".$atendente[0]['nome']."<br>";
    
    $descricao = $descricao."<br>Ordem de serviço:<br>".nl2br($os)."<br><br>".$situacao_descricao[0]['nome'];
    $descricao = $descricao."<hr>";

    if($sistema_gestao == '1'){
        $descricao = $descricao."Cadastrado no sistema de gestão: SIM<br>";
    }else{
        $descricao = $descricao."Cadastrado no sistema de gestão: NÃO<br>";
    }

    $descricao = $descricao."<br>Motivo: ".$descricao_chamado."</p>";

    // echo $descricao;

   gravar($id_atendimento, $id_arvore, $id_falha, $data_fim, $os, $situacao, $elogio, $irritado, $sistema_gestao, $descricao_chamado, $chamado, $descricao, $enviar_email, $canal_atendimento);

} else if(!empty($_POST['reiniciar'])){

    reiniciar($id_atendimento);
   
} else if(!empty($_POST['voltar'])){

    voltar($id_arvore, $id_atendimento);
   
} else if(!empty($_POST['cancelar'])){

    $alert = ('Atendimento cancelado!','d');
    header("location: /api/iframe?token=$request->token&view=atendimento-busca");
    exit;
   
} else if(!empty($_POST['falha_inicio'])){

    $falha = (!empty($_POST['falha_inicio'])) ? $_POST['falha_inicio'] : 0;

    inserirFalhaInicio($assinante, $contato, $fone1, $fone2, $cpf_cnpj, $data_inicio, $data_fim, $falha, $label_dado_adicional, $dado_adicional, $id_contrato_plano_pessoa, $id_usuario);

} else {
    header("location: ../adm.php");
    exit;
}

function inserir($assinante, $contato, $fone1, $fone2, $cpf_cnpj, $data_inicio, $label_dado_adicional, $dado_adicional, $id_contrato_plano_pessoa, $id_usuario){

    $dados_arvore = DBRead('', 'tb_arvore', "WHERE id_arvore = 1", "resolvido");
    $resolvido = $dados_arvore[0]['resolvido'];    

    $dados = array(
        'assinante' => $assinante,
        'contato' => $contato,
        'fone1' => $fone1,
        'fone2' => $fone2,
        'cpf_cnpj' => $cpf_cnpj,
        'data_inicio' => $data_inicio,
        'resolvido' => $resolvido,
        'gravado' => 0,
        'falha' => 0,
        'enviado' => 0,
        'elogio' => 0,
        'irritado' => 0,
        'descricao_dado_adicional' => $label_dado_adicional,
        'dado_adicional' => $dado_adicional,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'id_usuario' => $id_usuario
    );

    if($id_usuario && $id_contrato_plano_pessoa){

        $insertID = DBCreate('', 'tb_atendimento', $dados, true);
        registraLog('Inserção de atendimento.','i','tb_atendimento',$insertID,"assinante: $assinante | contato: $contato | fone1: $fone1 | fone2: $fone2 | cpf_cnpj: $cpf_cnpj | data_inicio: $data_inicio | resolvido: $resolvido | gravado: 0 | enviado: 0 | elogio: 0 | irritado: 0 | falha: 0 | label_dado_adicional: $label_dado_adicional | dado_adicional: $dado_adicional | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_usuario: $id_usuario");
        
        //Gera o protocolo de atendimento e altera a tb_atendimento para persistir.
        $protocolo = str_replace('-', "", getDataHora("data")) . $insertID;
        $dado_protocolo = array(
            'protocolo' => $protocolo
        );
        DBUpdate('', 'tb_atendimento', $dado_protocolo, "id_atendimento = '".$insertID."'");
        //Final da inserção de protocolo

        header("location: /api/iframe?token=$request->token&view=atendimento-form&id_atendimento=".$insertID);

    }else{
        $alert = ('Erro ao iniciar atendimento!','e');
        header("location: /api/iframe?token=$request->token&view=atendiento-inicio-form&contrato=".$id_contrato_plano_pessoa);
    }
    exit;
}

function atualizar($id_arvore, $id_atendimento, $data_fim, $anotacao){
    $dados_atendimento = DBRead('', 'tb_atendimento', "WHERE id_atendimento = '$id_atendimento' ");
    if($id_atendimento && $dados_atendimento){

        $id_contrato_plano_pessoa = $dados_atendimento[0]['id_contrato_plano_pessoa'];

        $dados_arvore = DBRead('', 'tb_arvore a', "INNER JOIN tb_arvore_contrato b ON a.id_arvore = b.id_arvore INNER JOIN tb_texto_os c ON a.id_texto_os = c.id_texto_os WHERE a.id_arvore = '$id_arvore' AND b.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "c.nome, b.exibe_texto_os, a.cliques, a.resolvido");
        $cliques = $dados_arvore[0]['cliques']+1;
        $texto_os = $dados_arvore[0]['nome'];
        $exibe_texto_os = $dados_arvore[0]['exibe_texto_os'];
        $resolvido = $dados_arvore[0]['resolvido'];

        $dados = array(
            'cliques' => $cliques
        );

        DBUpdate('', 'tb_arvore', $dados, "id_arvore = '".$id_arvore."'");

        
        $dados_atendimento = array(            
            'resolvido' => $resolvido
        );

        DBUpdate('', 'tb_atendimento', $dados_atendimento, "id_atendimento = '".$id_atendimento."'");
        
        $dados = array(
            'id_atendimento' => $id_atendimento,
            'id_arvore' => $id_arvore,
            'data' => $data_fim,
            'texto_os' => $texto_os,
            'anotacao' => $anotacao, 
            'exibe_texto_os' => $exibe_texto_os
        );

        $insertID = DBCreate('', 'tb_atendimento_arvore', $dados, true);

        header("location: /api/iframe?token=$request->token&view=atendimento-form&id_atendimento=$id_atendimento&id_arvore=$id_arvore");
        exit;
    }else{
        $alert = ('Erro ao registrar o passo!','e');
        header(sprintf('location: %s', $_SERVER['HTTP_REFERER']));
        exit;
    }
    
}

function gravar($id_atendimento, $id_arvore, $id_falha, $data_fim, $os, $situacao, $elogio, $irritado, $sistema_gestao, $descricao_chamado, $chamado, $descricao, $enviar_email, $canal_atendimento){

    if($id_falha){
        $tipo_falha = DBRead('', 'tb_tipo_falha_atendimento', "WHERE id_tipo_falha_atendimento = '".$id_falha."'"); 
        if($tipo_falha[0]['faturar']){
            $falha_atendimento = '1';
        }else{
            $falha_atendimento = '2';
        }           

        $dados_falha_atendimento = array(
            'id_atendimento' => $id_atendimento,
            'id_tipo_falha_atendimento' => $id_falha
        );

        DBDelete('', 'tb_falha_atendimento', "id_atendimento = '$id_atendimento'");
        $idFalha = DBCreate('', 'tb_falha_atendimento', $dados_falha_atendimento, true);
        registraLog('Inserção de falha do atendimento.','i','tb_falha_atendimento',$idFalha,"id_atendimento: $id_atendimento | id_tipo_falha_atendimento: $id_falha");

        $alert = ('Atendimento incompleto registrado com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=atendimento-busca");

    }else{
        $dados_arvore = DBRead('', 'tb_arvore', "WHERE id_arvore = '".$id_arvore."'"); 
        $id_subarea_problema = $dados_arvore[0]['id_subarea_problema'];
        $falha_atendimento = '0';

        $dados_subarea_problema = array(
            'id_subarea_problema' => $id_subarea_problema,
            'id_atendimento' => $id_atendimento
        );

        DBDelete('', 'tb_subarea_problema_atendimento', "id_atendimento = '$id_atendimento'");
        $insertID_subarea = DBCreate('', 'tb_subarea_problema_atendimento', $dados_subarea_problema, true);
        registraLog('Inserção de subarea de probema de atendimento.','i','tb_subarea_problema_atendimento',$insertID_subarea,"id_subarea_problema: $id_subarea_problema | id_atendimento: $id_atendimento");
    }
    
    $msg_email_envio = '';
    $tipo_alerta_msg_email_envio = 's';    

    $dados_situacao = array(
        'id_situacao' => $situacao,
        'id_atendimento' => $id_atendimento
    );

    DBDelete('', 'tb_situacao_atendimento', "id_atendimento = '$id_atendimento'");
    $insertID_situacao = DBCreate('', 'tb_situacao_atendimento', $dados_situacao, true);
    registraLog('Inserção de situacao de atendimento.','i','tb_situacao_atendimento',$insertID_situacao,"id_situacao: $situacao | id_atendimento: $id_atendimento");

    //INICIO ENVIO DO EMAIL
    $dados_atendimento = DBRead('', 'tb_atendimento a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE id_atendimento = '$id_atendimento'", "a.contato, a.fone1, a.fone2, a.assinante, a.protocolo, a.id_contrato_plano_pessoa, a.descricao_dado_adicional, a.dado_adicional, c.nome AS nome_atendente, a.cpf_cnpj AS cpf_atendimento");

    $dados_parametros = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = '".$dados_atendimento[0]['id_contrato_plano_pessoa']."'");
    $nome_situacao = DBRead('', 'tb_situacao', "WHERE id_situacao = '$situacao'");

    if($dados_parametros[0]['enviar_email'] == "1" || $enviar_email){    
        $corpo = "
            <table style='border: 1px solid black;border-collapse: collapse;margin-bottom:10px;width:90%;' align='center'>
                <thead style='border: 1px solid #808080;'>
                    <tr>
                        <th style='padding: 5px;color: #fff;background-color:#263868' colspan='2'><strong>Belluno - Atendimento</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr style='border: 1px solid #808080;padding: 5px;'>
                        <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth' colspan='2'><strong>Contato: </strong> ".$dados_atendimento[0]['contato']."</td>
                    </tr>
                    <tr style='border: 1px solid #808080;padding: 5px;'>
                        <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth' colspan='2'><strong>Fone 1: </strong>".$dados_atendimento[0]['fone1']."</td>
                    </tr>";

                  
                if($dados_atendimento[0]['fone2']){
                    $corpo .= "<tr style='border: 1px solid #808080;padding: 5px;'>
                        <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth' colspan='2'><strong>Fone 2: </strong> ".$dados_atendimento[0]['fone2']."</td>
                    </tr>";

                }
                if($dados_atendimento[0]['cpf_atendimento']){
                    $corpo .= "<tr style='border: 1px solid #808080;padding: 5px;'>
                        <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth' colspan='2'><strong>CPF/CNPJ: </strong> ".$dados_atendimento[0]['cpf_atendimento']."</td>
                    </tr>";

                }
                    
                $corpo .= "
                    <tr style='border: 1px solid #808080;padding: 5px;'>
                        <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth' colspan='2'><strong>Assinante: </strong>".$dados_atendimento[0]['assinante']."</td>
                    </tr>";
                

                $corpo .= "
                    <tr style='border: 1px solid #808080;padding: 5px;'>
                    <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth' colspan='2'><strong>Protocolo: </strong>".$dados_atendimento[0]['protocolo']."</td>
                </tr>";                

                if($dados_atendimento[0]['dado_adicional']){
                    $corpo .= "<tr style='border: 1px solid #808080;padding: 5px;'>
                        <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth' colspan='2'><strong>".$dados_atendimento[0]['descricao_dado_adicional'].": </strong>".$dados_atendimento[0]['dado_adicional']."</td>
                    </tr>";


                }


                $corpo .= "
                    <tr style='border: 1px solid #808080;padding: 5px;'>
                        <td style='border: 1px solid #808080;padding: 5px;text-align:rigth' colspan='2'><strong>OS: </strong><br>".nl2br($os)."<br>".$nome_situacao[0]['nome']."</td>
                    </tr>
                    <tr style='border: 1px solid #808080;padding: 5px;'>
                        <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth' colspan='2'><strong>Atendente: </strong>".$dados_atendimento[0]['nome_atendente']."</td>
                    </tr>
                    <tr style='border: 1px solid #808080;padding: 5px;'>
                        <td style='border: 1px solid #808080;padding: 5px;background-color: #e5e5e5;text-align:rigth' colspan='2'><strong>Data e hora do atendimento: </strong>".converteDataHora($data_fim)."</td>
                    </tr>
                </tbody>
            </table>
            <div style='text-align:center;margin-top:12px'><p style='text-align:center;margin:1px'>Não responda este e-mail. Este endereço é utilizado apenas para envio dos atendimentos. Em caso de dúvidas ou dificuldades faça contato com a supervisão ou monitoria do call center através do endereço cs@bellunotec.com.br Para assuntos urgentes, contato por telefone pelo número (55) 3281-9200 Opção 3.</p></div>
        ";

        
        envia_email("Belluno - Atendimento - ".$dados_atendimento[0]['assinante']." - ".$dados_atendimento[0]['protocolo'], $corpo, $dados_parametros[0]['email_envio'], '');

        // envia_email('Teste', 'Mensagem de teste do belluno.company', 'matheus.desouza@belluno.company; matheus.dasilva@belluno.company', '');

        if(!$dados_parametros[0]['email_envio'] || $dados_parametros[0]['email_envio'] == ' ' || $dados_parametros[0]['email_envio'] == ''){
            $msg_email_envio = ' Obs: Não há um e-mail cadastrado para envio!';
            $tipo_alerta_msg_email_envio = 'w';
            $enviado = 0;
        }else{
            $enviado = 1;
        }

    }else{
        $enviado = 0;
    }
    //FIM DO ENVIO DO EMAIL

    if($canal_atendimento == 'texto'){
        $via_texto = 1;
    }else{
        $via_texto = 0;
    }

    $dados_atendimento = array(
        'data_fim' => $data_fim,
        'falha' => $falha_atendimento,
        'gravado' => 1,
        'enviado' => $enviado,
        'elogio' => $elogio,
        'irritado' => $irritado,
        'os' => $os,
        'via_texto' => $via_texto
    );

    DBUpdate('', 'tb_atendimento', $dados_atendimento, "id_atendimento = '".$id_atendimento."'");
    registraLog('Gravação de atendimento.','a','tb_atendimento',$id_atendimento,"data_fim: $data_fim | gravado: 1 | enviado: $enviado | elogio: $elogio | irritado: $irritado | os: $os | via_texto: $via_texto");

    if($chamado == '10' && $descricao_chamado != '0'){

        $empresa = DBRead('', 'tb_atendimento a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_atendimento = '".$id_atendimento."'", "a.id_contrato_plano_pessoa, c.nome, a.protocolo");
        $origem = '3'; 
        $titulo = "Chamado atendimento - ".$empresa[0]['nome'];
        $id_contrato_plano_pessoa = $empresa[0]['id_contrato_plano_pessoa'];

        $visibilidade = '1';
        $remetente = $_SESSION['id_usuario'];

        $id_responsavel = $_SESSION['id_usuario'];
        $id_chamado_status = '1';
        $bloqueado = '1';
        $id_chamado_origem = '1';

        $tempo = "1";

        $perfis = array(
            '15',
            '13',         
        );
        $data_hoje = getDataHora();
        
        $dados = array(
            'titulo' => $titulo,
            'data_criacao' => $data_hoje,
            'visibilidade' => $visibilidade,
            'descricao' => $descricao,
            'bloqueado' => $bloqueado,
            'id_usuario_remetente' => $remetente,
            'id_usuario_responsavel' => $id_responsavel,
            'id_chamado_status' => $id_chamado_status,
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
            'id_chamado_origem' => $id_chamado_origem,
        );

        $link = DBConnect('');
        DBBegin($link);

        $insertID = DBCreateTransaction($link, 'tb_chamado', $dados, true);
        
        registraLogTransaction($link, 'Inserção de chamado.', 'i', 'tb_chamado', $insertID, "origem: $origem | titulo: $titulo | descricao: $descricao | visibilidade: $visibilidade | id_usuario_remetente: $remetente | id_usuario_responsavel: $id_responsavel | id_chamado_status: $id_chamado_status");
    
        // insere categoria
        $id_categoria = 33;
        $dados = array(
            'id_chamado' => $insertID,
            'id_categoria' => $id_categoria
        );
        $insertID_categoria = DBCreateTransaction($link, 'tb_chamado_categoria', $dados, true);
        
        registraLogTransaction($link, 'Inserção de categoria.', 'i', 'tb_chamado_categoria', $insertID_categoria, "id_chamado: $insertID | id_categoria: $id_categoria");
        //Adiciona usuário na tabela ignora do chamado.

        $dados = array(
            'id_chamado' => $insertID,
            'id_atendimento' => $id_atendimento
        );
        
        $insertIDchamado_atendimento = DBCreateTransaction($link, 'tb_chamado_atendimento', $dados, true);
        //registraLog($link, 'Inserção de chamado_atendimento.', '', 'tb_chamado_atendimento', $insertIDchamado_atendimento, "id_chamado: $id_chamado | id_atendimento: $id_atendimento");


        $usuarios_call = DBReadTransaction($link, 'tb_usuario', "WHERE (id_perfil_sistema = 3 OR id_perfil_sistema = 18) AND id_usuario != '$remetente'");

        if($usuarios_call){
            foreach($usuarios_call as $conteudo){
                $dados_ignora = [
                    'id_usuario' => $conteudo['id_usuario'],
                    'id_chamado' => $insertID
                ];
                DBCreateTransaction($link, 'tb_chamado_ignora', $dados_ignora);
            }
        }

        $data_criacao = getDataHora();

        $acao = "criacao";
        $tempo = "1";

        $id_usuario_acao = $_SESSION['id_usuario'];

        $dados_acao = array(
            'data' => $data_criacao,
            'descricao' => $descricao,
            'id_chamado_status' => $id_chamado_status,
            'visibilidade' => $visibilidade,
            'acao' => $acao,
            'tempo' => $tempo,
            'id_chamado' => $insertID,
            'id_usuario_responsavel' => $id_responsavel,
            'id_usuario_acao' => $id_usuario_acao
        );

        $insertAcao = DBCreateTransaction($link, 'tb_chamado_acao', $dados_acao, true);

        registraLogTransaction($link, 'Inserção de ação.','i','tb_chamado_acao',$insertAcao,"data: $data_criacao | descricao: $descricao | id_chamado_status: $id_chamado_status | visibilidade: $visibilidade | acao: $acao | tempo: $tempo | id_chamado: $insertID | id_usuario_responsavel: $id_responsavel | id_usuario_acao: $id_usuario_acao");

        $notifica = 1;
        $id_chamado_status_envolvido = 1;
        
        if($visibilidade == '1'){

            foreach($perfis as $perfil){
                $dados = array(
                    'id_chamado' => $insertID,
                    'id_perfil_sistema' => $perfil
                );
                $insertChamadoPerfil = DBCreateTransaction($link, 'tb_chamado_perfil', $dados, true);

                registraLogTransaction($link, 'Inserção de chamado perfil.','i','tb_topico',$insertChamadoPerfil,"id_chamado: $insertID | id_perfil_sistema: $perfil");
            }
        }
        

        DBCommit($link);

        $link = DBConnect('');
        DBBegin($link);
        $usuarios_call = DBReadTransaction($link, 'tb_usuario', "WHERE (id_perfil_sistema = 3) AND id_usuario != '$remetente'");
        foreach($usuarios_call as $conteudo){
            $chamado_acao = DBReadTransaction($link, 'tb_chamado_acao', "WHERE id_chamado = '$insertID'", "id_chamado_acao");
            $dados_visualizado = [
                'data' => getDataHora(),
                'id_usuario' => $conteudo['id_usuario'],
                'id_chamado_acao' => $chamado_acao[0]['id_chamado_acao'],
                'id_chamado' => $insertID,
            ];
            DBCreateTransaction($link, 'tb_chamado_visualizacao', $dados_visualizado);
        }
        DBCommit($link);
    }

    $alert = ('Atendimento gravado com sucesso!'.$msg_email_envio,$tipo_alerta_msg_email_envio);
    header("location: /api/iframe?token=$request->token&view=atendimento-busca");
    exit;

}

function reiniciar($id_atendimento){

    $dados_arvore_pai = DBRead('', 'tb_arvore', "WHERE id_arvore = 1", "resolvido");
    $resolvido = $dados_arvore_pai[0]['resolvido'];
    $dados_atendimento = array(            
        'resolvido' => $resolvido
    );
    DBUpdate('', 'tb_atendimento', $dados_atendimento, "id_atendimento = '".$id_atendimento."'");

    DBDelete('', 'tb_atendimento_arvore', "id_atendimento = '$id_atendimento'");
    registraLog('Reinicio de atendimento.','e','tb_atendimento_arvore',$id_atendimento,'');
    header("location: /api/iframe?token=$request->token&view=atendimento-form&id_atendimento=$id_atendimento");
    exit;
}

function voltar($id_arvore, $id_atendimento){

    $dado_arvore = DBRead('', 'tb_arvore', "WHERE id_arvore = '$id_arvore'", "id_pai");
    $id_pai = $dado_arvore[0]['id_pai'];

    if(!$id_pai){
        $id_pai = 1;
    }

    $dados_arvore_pai = DBRead('', 'tb_arvore', "WHERE id_arvore = '$id_pai'", "resolvido");
    $resolvido = $dados_arvore_pai[0]['resolvido'];
    $dados_atendimento = array(            
        'resolvido' => $resolvido
    );
    DBUpdate('', 'tb_atendimento', $dados_atendimento, "id_atendimento = '".$id_atendimento."'");

    DBDelete('', 'tb_atendimento_arvore', "id_arvore = '$id_arvore' AND id_atendimento = '$id_atendimento'");
    registraLog('Retorno de um passo na arvore.','e','tb_atendimento_arvore',$id_atendimento,"id_arvore: $id_arvore");
    header("location: /api/iframe?token=$request->token&view=atendimento-form&id_atendimento=$id_atendimento&id_arvore=$id_pai");
    exit;
}

function inserirFalhaInicio($assinante, $contato, $fone1, $fone2, $cpf_cnpj, $data_inicio, $data_fim, $falha, $label_dado_adicional, $dado_adicional, $id_contrato_plano_pessoa, $id_usuario){

    $tipo_falha = DBRead('', 'tb_tipo_falha_atendimento', "WHERE id_tipo_falha_atendimento = '".$falha."'");  
    $os = $tipo_falha[0]['texto_os'];
    $resolvido = $tipo_falha[0]['resolvido'];

    if($tipo_falha[0]['faturar']){
        $falha_atendimento = '1';
    }else{
        $falha_atendimento = '2';
    }

    $dados = array(
        'assinante' => $assinante,
        'contato' => $contato,
        'fone1' => $fone1,
        'fone2' => $fone2,
        'cpf_cnpj' => $cpf_cnpj,
        'data_inicio' => $data_inicio,
        'data_fim' => $data_fim,
        'falha' => $falha_atendimento,
        'os' => $os,
        'resolvido' => $resolvido,
        'gravado' => 1,
        'descricao_dado_adicional' => $label_dado_adicional,
        'dado_adicional' => $dado_adicional,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'id_usuario' => $id_usuario
    );

    if($falha && $id_contrato_plano_pessoa){
        $insertID = DBCreate('', 'tb_atendimento', $dados, true);
        registraLog('Inserção de atendimento.','i','tb_atendimento',$insertID,"assinante: $assinante | contato: $contato | fone1: $fone1 | fone2: $fone2 | cpf_cnpj: $cpf_cnpj | data_inicio: $data_inicio | data_fim: $data_fim | falha: $falha | os: $os | resolvido: $resolvido | gravado: 1 | descricao_dado_adicional: $label_dado_adicional | dado_adicional: $dado_adicional | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_usuario: $id_usuario");

        //Gera o protocolo de atendimento e altera a tb_atendimento para persistir.
        $protocolo = str_replace('-', "", getDataHora("data")) . $insertID;
        $dado_protocolo = array(
            'protocolo' => $protocolo
        );
        DBUpdate('', 'tb_atendimento', $dado_protocolo, "id_atendimento = '".$insertID."'");
        //Final da inserção de protocolo


        $dados_falha_atendimento = array(
            'id_atendimento' => $insertID,
            'id_tipo_falha_atendimento' => $falha
        );
        DBDelete('', 'tb_falha_atendimento', "id_atendimento = '$insertID'");
        $idFalha = DBCreate('', 'tb_falha_atendimento', $dados_falha_atendimento, true);
        registraLog('inserção de falha do atendimento.','i','tb_falha_atendimento',$idFalha,"id_atendimento: $insertID | id_tipo_falha_atendimento: $falha");

        $alert = ('Atendimento incompleto registrado com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=atendimento-busca");
    }else{
        $alert = ('Erro ao inserir item!','e');
        header("location: /api/iframe?token=$request->token&view=atendimento-busca");
    }
    exit;
}
?>
