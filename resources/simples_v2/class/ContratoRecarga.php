<?php
require_once(__DIR__."/System.php");

$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$quantidade_atendimentos = (!empty($_POST['quantidade_atendimentos'])) ? $_POST['quantidade_atendimentos'] : 0;
$data_recarga = (!empty($_POST['data_recarga'])) ? converteDataHora($_POST['data_recarga']) : '';

if (!empty($_POST['inserir'])) {
    
    inserir($id_contrato_plano_pessoa, $quantidade_atendimentos, $data_recarga);
    
} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];

    //alterar($id, $id_contrato_plano_pessoa, $quantidade_atendimentos, $data_recarga);

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id);

} else {
    header("location: ../adm.php");
    exit;
}

function inserir($id_contrato_plano_pessoa, $quantidade_atendimentos, $data_recarga){

    if ($id_contrato_plano_pessoa !='' && $quantidade_atendimentos != 0 && $data_recarga !='') {

        $data_hoje = getDataHora();
        
        $dateTime = new DateTime($data_hoje);
        $dateTime->modify('first day of this month');
        $data_referencia = $dateTime->format("Y-m-d");

        $dados_faturamento = DBRead('', 'tb_faturamento a', "INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.faturamento INNER JOIN tb_conta_receber c ON a.id_faturamento = c.id_faturamento WHERE a.data_referencia = '".$data_referencia."' AND b.id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND c.situacao = 'quitada' ");

        if ($dados_faturamento) {

            $data_cadastro = getDataHora();
            $id_usuario = $_SESSION['id_usuario'];

            $dados = array(
                'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                'data_recarga' => $data_recarga,
                'quantidade_atendimentos' =>  $quantidade_atendimentos,
                'data_cadastro' =>  $data_cadastro,
                'id_usuario' =>  $id_usuario
            );

            $insertID = DBCreate('', 'tb_contrato_recarga', $dados, true);
            registraLog('Inserção de nova recarga.','i','tb_contrato_recarga',$insertID,"id_contrato_plano_pessoa: $id_contrato_plano_pessoa | data_recarga: $data_recarga | quantidade_atendimentos: $quantidade_atendimentos | data_cadastro: $data_cadastro | id_usuario: $id_usuario");

            desbloquearContrato($id_contrato_plano_pessoa);

            $alert = ('Item inserido com sucesso!');
    $alert_type = 's';            header("location: /api/iframe?token=$request->token&view=contrato-recarga-busca");
            exit;

        } else {
            $alert = ('Este contrato não possui uma conta a receber quitada!','d');
            header("location: /api/iframe?token=$request->token&view=contrato-recarga-busca");
            exit;
        }

    } else {
        $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=contrato-recarga-form");
        exit;
    }
}

/* function alterar($id, $id_contrato_plano_pessoa, $quantidade_atendimentos, $data_recarga){

    if ($id && $id_contrato_plano_pessoa !='' && $quantidade_atendimentos != 0 && $data_recarga !='') {
        
        $data_cadastro = getDataHora();
        $id_usuario = $_SESSION['id_usuario'];

        $dados = array(
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
            'data_recarga' => $data_recarga,
            'quantidade_atendimentos' =>  $quantidade_atendimentos,
            'data_cadastro' =>  $data_cadastro,
            'id_usuario' =>  $id_usuario
        );

        DBUpdate('', 'tb_contrato_recarga', $dados, "id_contrato_recarga = $id");
        registraLog('Alteração de recarga.','a','tb_contrato_recarga',$id,"id_contrato_plano_pessoa: $id_contrato_plano_pessoa | data_recarga: $data_recarga | quantidade_atendimentos: $quantidade_atendimentos | data_cadastro: $data_cadastro | id_usuario: $id_usuario");
        $alert = ('Item alterado com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=contrato-recarga-busca");
        exit;

    } else {
        $alert = 'Não foi possível alterar o item!' ;
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=contrato-recarga-form&alterar=$id");
    }
    
    exit;
} */

function excluir($id){

    if ($id) {

        $dados_conta_receber = DBRead('', 'tb_contrato_recarga', "WHERE id_conta_receber IS NULL AND id_contrato_recarga = $id");

        if ($dados_conta_receber) {
        
            $id_usuario = $_SESSION['id_usuario'];

            $dados = array(
                'id_usuario' =>  $id_usuario,
                'status' => '2'
                
            );
            DBUpdate('', 'tb_contrato_recarga', $dados, "id_contrato_recarga = $id");
            registraLog('Exclusão de recarga.','a','tb_contrato_recarga',$id,"id_usuario: $id_usuario | status: 2");
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';            header("location: /api/iframe?token=$request->token&view=contrato-recarga-busca");
            exit;

        } else {
            $alert = ('Esta recarga possui uma conta a receber!','d');
            header("location: /api/iframe?token=$request->token&view=contrato-recarga-busca");
            exit;
        }
    }
}

function verificaContratoPrePago ($id_contrato_plano_pessoa) {

    if ($id_contrato_plano_pessoa) {

        $contrato = DBRead('', 'tb_contrato_plano_pessoa', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");

        $qtd_contratada_voz = $contrato[0]['qtd_contratada'];

        $data_hoje = getDataHora();
        
        $dateTime = new DateTime($data_hoje);
        $dateTime->modify('first day of this month');
        $data_de = $dateTime->format("Y-m-d");

        $dateTime = new DateTime($data_hoje);
        $dateTime->modify('last day of this month');
        $data_ate = $dateTime->format("Y-m-d");

        $dados_recarga = DBRead('', 'tb_contrato_recarga a', "INNER JOIN tb_conta_receber b ON a.id_conta_receber = b.id_conta_receber WHERE b.situacao = 'quitada' AND a.id_conta_receber AND (a.data_recarga >= '".$data_de."' AND a.data_recarga <= '".$data_ate."')");

        if ($dados_recarga) {
            foreach ($dados_recarga as $conteudo) {
                $qtd_contratada_voz += $conteudo['quantidade_atendimentos'];
            }
        }

        $atendimentos_voz = DBRead('', 'tb_atendimento', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND gravado = 1 AND (data_inicio >= '".$data_de." 00:00:00' AND data_inicio <= '".$data_ate." 23:59:59' )", "count(*) as count_atendimentos");

        if ($atendimentos_voz >= $qtd_contratada_voz) {
            bloquearContrato($id_contrato_plano_pessoa);

        } else {

            $percetual_usado = (100 * $atendimentos_voz) / $qtd_contratada_voz;

            if ($percetual_usado >= 90) {
                notificaChamado($id_contrato_plano_pessoa, $percetual_usado);
            }
        }

    } else {
        return false;
    }
}

function bloquearContrato ($id_contrato_plano_pessoa) {

    if ($id_contrato_plano_pessoa) {
        $dados = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa", 'id_asterisk');
        $id_asterisk = $dados[0]['id_asterisk'];

        $dados = array(
            'aceita_ligacao' => '0',
        );

        DBUpdate('snep', 'empresas', $dados, "id = $id_asterisk");
        registraLog('Alteração de prefixo.','a','empresas',$id_asterisk," aceita_ligacao: 0");
    }
}

function desbloquearContrato ($id_contrato_plano_pessoa) {

    if ($id_contrato_plano_pessoa) {
        $dados = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa", 'id_asterisk');
        $id_asterisk = $dados[0]['id_asterisk'];

        $dados = array(
            'aceita_ligacao' => '1',
        );

        DBUpdate('snep', 'empresas', $dados, "id = $id_asterisk");
        registraLog('Alteração de prefixo.','a','empresas',$id_asterisk," aceita_ligacao: 0");
    }
}

//notificar via email

function notificaChamado ($id_contrato_plano_pessoa, $percentual_usado){

    $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa", 'id_responsavel');
    $responsavel = $dados_contrato[0]['id_responsavel'];

    $perfil = DBRead('', 'tb_usuario', "WHERE id_usuario = $responsavel", 'id_perfil_sistema');
    $perfil = $perfil[0]['id_perfil_sistema'];

    $nome_contrato = getContrato($id_contrato_plano_pessoa);

    $data_criacao = getDataHora();

    $prazo_encerramento = date('Y-m-d H:m', strtotime($data_criacao. ' + 1 days'));

    $titulo = 'Atendimentos do contrato pré-pago chegando ao fim';

    $descricao = 'O contrato pré-pago da '.$nome_contrato.' atingiu o percentual de '.$percentual_usado.'% de atendimentos realizados.';

    $remetente = $responsavel;
    $id_chamado_status = '1';
    $bloqueado = '1';
    $visibilidade = '1';
    $id_chamado_origem = '3';

    $dados = array(
        'data_criacao' => $data_criacao,
        'titulo' => $titulo,
        'descricao' => $descricao,
        'bloqueado' => $bloqueado,
        'id_usuario_remetente' => $remetente,
        'id_chamado_status' => $id_chamado_status,
        'visibilidade' => $visibilidade,
        'id_usuario_responsavel' => $responsavel,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'id_chamado_origem' => $id_chamado_origem,
        'prazo_encerramento' => $prazo_encerramento
    );

    $link = DBConnect('');
    DBBegin($link);

    $insertIDchamado = DBCreateTransaction($link, 'tb_chamado', $dados, true);
    registraLogTransaction($link, 'Inserção de chamado.', 'i', 'tb_chamado', $insertIDchamado, "data_criacao: $data_criacao | titulo: $titulo | descricao: $descricao | bloqueado: $bloqueado | id_usuario_remetente: $remetente | status: $id_chamado_status | visibilidade: $visibilidade | id_usuario_responsavel: $responsavel | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_chamado_origem: $id_chamado_origem | prazo_encerramento: $prazo_encerramento");
        
    $acao = "criacao";

    $id_categoria = '28';

    $dadosCategoria = array(
        'id_categoria' => $id_categoria,
        'id_chamado' => $insertIDchamado,
    );
    
    $insertCategoria = DBCreateTransaction($link, 'tb_chamado_categoria', $dadosCategoria, true);
    registraLogTransaction($link, 'Inserção de categoria chamado.','i','tb_chamado_categoria',$insertCategoria,"id_categoria: $id_categoria | id_chamado: $insertIDchamado");
    
    $tempo = 1;
    $id_usuario_acao = $responsavel;

    $dados_acao = array(
        'data' => $data_criacao,
        'descricao' => $descricao,
        'id_chamado_status' => $id_chamado_status,
        'visibilidade' => $visibilidade,
        'acao' => $acao,
        'tempo' => $tempo,
        'id_chamado' => $insertIDchamado,
        'id_usuario_responsavel' => $responsavel,
        'id_usuario_acao' => $id_usuario_acao,
        'bloqueado' => $bloqueado,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );

    $insertAcao = DBCreateTransaction($link, 'tb_chamado_acao', $dados_acao, true);
    registraLogTransaction($link, 'Inserção de ação.','i','tb_chamado_acao',$insertAcao,"data: $data_criacao | descricao: $descricao | id_chamado_status: $id_chamado_status | visibilidade: $visibilidade | acao: $acao | tempo: $tempo | id_chamado: $insertIDchamado | id_usuario_responsavel: $responsavel | id_usuario_acao: $id_usuario_acao | bloqueado: $bloqueado | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");
    
    $perfis = [$perfil, '2']; //perfil 2 (desenvolvimento) só para teste

    foreach($perfis as $perfil){
        $dados = array(
            'id_chamado' => $insertIDchamado,
            'id_perfil_sistema' => $perfil
        );

        $insertChamadoPerfil = DBCreateTransaction($link, 'tb_chamado_perfil', $dados, true);
        registraLogTransaction($link, 'Inserção de chamado perfil.','i','tb_topico',$insertChamadoPerfil,"id_chamado: $insertIDchamado | id_perfil_sistema: $perfil");
    }

    DBCommit($link);

}