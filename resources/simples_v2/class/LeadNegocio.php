<?php
    require_once(__DIR__."/System.php");
    

    $data = getDataHora();
    $id_usuario = $_SESSION['id_usuario'];
    $status = (!empty($_POST['id_status'])) ? $_POST['id_status'] : '';
    $id_plano = (!empty($_POST['id_plano'])) ? $_POST['id_plano'] : 0;
    $responsavel = (!empty($_POST['responsavel'])) ? $_POST['responsavel'] : '';
    $valor_contrato = (!empty($_POST['valor_contrato'])) ? converteMoeda($_POST['valor_contrato'],'banco') : 0;
    $valor_adesao = (!empty($_POST['valor_adesao'])) ? converteMoeda($_POST['valor_adesao'],'banco') : 0;
    $valor_reducao = (!empty($_POST['valor_reducao'])) ? converteMoeda($_POST['valor_reducao'],'banco') : 0;
    $valor_aumento = (!empty($_POST['valor_aumento'])) ? converteMoeda($_POST['valor_aumento'],'banco') : 0;
    $data_inicio = (!empty($_POST['data_inicio'])) ? $_POST['data_inicio'] : '';
    $data_conclusao = (!empty($_POST['data_conclusao'])) ? $_POST['data_conclusao'] : '';
    $descricao = (!empty($_POST['descricao'])) ? $_POST['descricao'] : '';
    $id_pessoa = (!empty($_POST['id_pessoa'])) ? $_POST['id_pessoa'] : '';
    $tipo_negocio = (!empty($_POST['tipo_negocio'])) ? $_POST['tipo_negocio'] : '';
    $pagina_origem = (!empty($_POST['pagina_origem'])) ? $_POST['pagina_origem'] : '';
    $id_rd_conversao = (!empty($_POST['id_rd_conversao'])) ? $_POST['id_rd_conversao'] : '';

    if($id_plano == 'N/D'){
        $id_plano = 0;
    }


if (!empty($_POST['inserir'])) {

    inserir($data, $id_pessoa, $status, $id_plano, $responsavel, $valor_contrato, $valor_adesao, $data_inicio, $data_conclusao, $descricao, $tipo_negocio, $id_usuario, $id_rd_conversao, $valor_reducao, $valor_aumento);

} else if (!empty($_POST['alterar'])) {
    
    $id = (int)$_POST['alterar'];

    alterar($data, $id_pessoa, $status, $id_plano, $responsavel, $valor_contrato, $valor_adesao, $data_inicio, $data_conclusao, $descricao, $tipo_negocio, $id, $pagina_origem, $valor_reducao, $valor_aumento);

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id);
}

function inserir($data, $id_pessoa, $status, $id_plano, $responsavel, $valor_contrato, $valor_adesao,$data_inicio, $data_conclusao, $descricao, $tipo_negocio, $id_usuario, $id_rd_conversao, $valor_reducao, $valor_aumento){


    if($data != '' && $id_pessoa != '' && $status != ''  && $responsavel != '' && $valor_contrato != '' && $valor_adesao != '' && $data_inicio != '' && $tipo_negocio != ''){

        $link = DBConnect('');
        DBBegin($link);

        $data = getdatahora();

        $dados = array(
            'id_pessoa' => $id_pessoa,
            'id_lead_status' => $status,
            'id_usuario_responsavel' => $responsavel,
            'id_plano' => $id_plano,
            'descricao' => $descricao,
            'valor_contrato' => $valor_contrato,
            'data_inicio' => convertedata($data_inicio),
            'data_conclusao' => convertedata($data_conclusao),
            'tipo_negocio' => $tipo_negocio,
            'valor_adesao' => $valor_adesao,
            'valor_reducao' => $valor_reducao,
            'valor_aumento' => $valor_aumento
        );

        $insertNegocio = DBCreateTransaction($link, 'tb_lead_negocio', $dados, true);
        registraLogTransaction($link, 'Inserção de novo lead negocio.', 'i', 'tb_lead_negocio', $insertNegocio,"id_pessoa: $id_pessoa | id_lead_status: $status | id_usuario_responsavel: $responsavel | id_plano: $id_plano | descricao: $descricao | valor_contrato: $valor_contrato | data_inicio: $data_inicio | data_conclusao: $data_conclusao | tipo_negocio: $tipo_negocio | valor_adesao: $valor_adesao | valor_reducao: $valor_reducao | valor_aumento: $valor_aumento");

        if ($id_rd_conversao) {

            $dados_conversao = array(
                'id_lead_negocio' => $insertNegocio
            );

            DBUpdateTransaction($link, 'tb_rd_conversao', $dados_conversao, "id_rd_conversao = $id_rd_conversao");
            registraLogTransaction($link, 'Alteração de RD lead conversao.', 'a', 'tb_rd_conversao', $id_rd_conversao, " id_lead_negocio: $insertNegocio");
        }

        //insere na tabela status historico
        $dados_status = array(
            'data_troca' => $data,
            'id_lead_status' => $status,
            'id_usuario' => $id_usuario,
            'id_lead_negocio' => $insertNegocio
        );

        $insertStatus = DBCreateTransaction($link, 'tb_lead_status_historico', $dados_status, true);
        registraLogTransaction($link, 'Inserção de lead status historico.', 'i', 'tb_lead_status_historico', $insertStatus,"data_troca: $data | id_lead_status: $status | id_usuario: $id_usuario");
        //end insere na tabela status historico

        //cria um novo item na timeline
        $dados_timeline = array(
            'data' => $data,
            'descricao' => 'Novo negócio criado!'.PHP_EOL.PHP_EOL.$descricao,
            'id_lead_negocio' => $insertNegocio,
            'id_usuario' => $id_usuario,
            'contato_realizado' => 0,
            'id_lead_tipo_item_timeline' => 6
        );

        $insertItemTimeline = DBCreateTransaction($link, 'tb_lead_timeline', $dados_timeline, true);
        registraLogTransaction($link, 'Inserção de lead item timeline.', 'i', 'tb_lead_timeline',$insertItemTimeline, "descricao: Novo negócio criado! $descricao | data: $data | id_usuario: $id_usuario | id_lead_negocio: $insertNegocio | contato_realizado: 0 | id_lead_tipo_item_timeline: 6");
        //end cria um novo item na timeline

        DBCommit($link);

        $alert = ('Negócio criado com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=lead-negocio-informacoes&lead=$insertNegocio");

    } else {
        $alert = ('Não foi possível criar o negócio!','w');
        header("location: /api/iframe?token=$request->token&view=lead-negocio-form");
    }
    exit;
}

function alterar($data, $id_pessoa, $status, $id_plano, $responsavel, $valor_contrato, $valor_adesao,$data_inicio, $data_conclusao, $descricao, $tipo_negocio, $id,  $pagina_origem, $valor_reducao, $valor_aumento){
    
    if($id !='' && $data != '' && $id_pessoa != '' &&  $status != '' &&  $responsavel != '' && $valor_contrato != '' && $valor_adesao != '' && $tipo_negocio != ''){

        $dados_negocio = DBRead('', 'tb_lead_negocio', "WHERE id_lead_negocio = $id");

        $link = DBConnect('');
        DBBegin($link);
        
        $id_usuario = $_SESSION['id_usuario'];

        $data_troca = getdatahora();
        
        if($dados_negocio[0]['id_lead_status'] != $status){
            
            $dados_status = array(
                'data_troca' => $data_troca,
                'id_lead_status' => $status,
                'id_usuario' => $id_usuario,
                'id_lead_negocio' => $id
            );

            $insertAcao = DBCreateTransaction($link, 'tb_lead_status_historico', $dados_status, true);
            registraLogTransaction($link, 'Inserção de lead status historico.', 'i', 'tb_lead_status_historico', $insertAcao,"data_troca: $data_troca | id_lead_status: $status | id_usuario: $id_usuario | id_lead_negocio: $id");
        }

        $dados = array(
            'id_pessoa' => $id_pessoa,
            'id_lead_status' => $status,
            'id_usuario_responsavel' => $responsavel,
            'id_plano' => $id_plano,
            'descricao' => $descricao,
            'valor_contrato' => $valor_contrato,
            'data_conclusao' => convertedata($data_conclusao),
            'tipo_negocio' => $tipo_negocio,
            'valor_adesao' => $valor_adesao,
            'valor_reducao' => $valor_reducao,
            'valor_aumento' => $valor_aumento
        );

        DBUpdateTransaction($link, 'tb_lead_negocio', $dados, "id_lead_negocio = $id");
        registraLogTransaction($link, 'Alteração em um Negócio do lead.', 'a', 'tb_lead_negocio', $id, "id_pessoa: $id_pessoa | id_lead_status: $status | id_usuario_responsavel: $responsavel | id_plano: $id_plano | descricao: $descricao | valor_contrato: $valor_contrato |data_conclusao: $data_conclusao | tipo_negocio: $tipo_negocio | valor_adesao: $valor_adesao | valor_reducao: $valor_reducao | valor_aumento: $valor_aumento");

        DBCommit($link);

        $alert = ('Negócio alterado com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=lead-negocio-informacoes&lead=$id");

    }else{
        $alert = ('Não foi possível alterar o Negócio!','w');
        header("location: /api/iframe?token=$request->token&view=lead-negocio-form&alterar=$id");
    }
} 

function excluir($id){

    $dados = array(
        'excluido' => 2
    );

    DBUpdate('', 'tb_lead_negocio', $dados, "id_lead_negocio = $id");
    registraLog('Exclusão de um Negócio do lead.', 'e', 'tb_lead_negocio', $id, "excluido: 2");

    $alert = ('Negócio excluído com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=lead-negocios-busca");
}

?>