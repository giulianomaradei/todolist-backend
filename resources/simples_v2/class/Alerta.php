<?php
require_once(__DIR__."/System.php");

dd('chegou');

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$acao = (!empty($_POST['acao'])) ? $_POST['acao'] : '';

$id_contrato_plano_pessoa  = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : NULL;

$data_inicio = (!empty($_POST['data_inicio'])) ? $_POST['data_inicio'] : '';
$data_inicio = converteData($data_inicio);
$hora_inicio = (!empty($_POST['hora_inicio'])) ? $_POST['hora_inicio'] : '';

$data_vencimento = (!empty($_POST['data_vencimento'])) ? $_POST['data_vencimento'] : '';
$data_vencimento = converteData($data_vencimento);
$hora_vencimento = (!empty($_POST['hora_vencimento'])) ? $_POST['hora_vencimento'] : '';

$id_categoria = (!empty($_POST['id_categoria'])) ? $_POST['id_categoria'] : '';
$conteudo = (!empty($_POST['conteudo'])) ? $_POST['conteudo'] : '';

$exibicao = (!empty($_POST['exibicao'])) ? $_POST['exibicao'] : '';
$id_alerta_painel = (!empty($_POST['id_alerta_painel'])) ? $_POST['id_alerta_painel'] : '';

if(!$data_inicio && !$hora_inicio){
    $data_inicio = getDataHora();
}else if(!$data_inicio && $hora_inicio){
    $data_inicio = getDataHora('data');
    $data_inicio = $data_inicio . ' ' .  $hora_inicio;
}else if($data_inicio && !$hora_inicio){
    $hora_inicio = getDataHora('hora');
    $data_inicio = $data_inicio . ' ' . $hora_inicio;
}else{
    $data_inicio = $data_inicio . ' ' . $hora_inicio;
}

if(!$data_vencimento && !$hora_vencimento){
    $data_vencimento = NULL;   
}else if(!$data_vencimento && $hora_vencimento){
    $data_vencimento = getDataHora('data');
    $data_vencimento = $data_vencimento . ' ' . $hora_vencimento;
}else if($data_vencimento && !$hora_vencimento){
    $hora_vencimento = getDataHora('hora');
    $data_vencimento = $data_vencimento . ' ' . $hora_vencimento;
}else{
    $data_vencimento = $data_vencimento . ' ' . $hora_vencimento;
}
//var_dump($_POST);

if(!empty($_POST['inserir'])){
   
    inserir($id_contrato_plano_pessoa, $data_inicio, $data_vencimento, $id_categoria, $conteudo, $exibicao, $id_alerta_painel);

}else if(!empty($_POST['alterar'])){

    $id = (int)$_POST['alterar'];
    alterar($id, $id_contrato_plano_pessoa, $data_inicio, $data_vencimento, $id_categoria, $conteudo, $exibicao);

}else if(isset($_GET['excluir'])){

    $id = (int)$_GET['excluir'];
    excluir($id);

}else if(isset($_GET['vencido'])){

    $id = (int)$_GET['vencido'];
    vencido($id);

}else if(!empty($_POST['cancelar'])){
   
    cancelado($id_alerta_painel);

}else if(!empty($_POST['reprovar'])){

    $justificativa = (!empty($_POST['justificativa'])) ? $_POST['justificativa'] : '';
    reprovar($id_alerta_painel, $justificativa);

}else{
    header("location: ../adm.php");
    exit;
}

function cancelado($id){
    $data_agora = getDataHora();
    $id_usuario_sessao = $_SESSION['id_usuario'];

    $dados_alerta_painel = DBRead('', 'tb_alerta_painel', "WHERE id_alerta_painel = '".$id."' AND status = 4");
    if($dados_alerta_painel){
       
        $dados_alerta = DBRead('', 'tb_alerta', "WHERE id_alerta_painel = '".$id."' ");
        if($dados_alerta){
            $dados = array(
                'data_vencimento' => $data_agora,
                'id_usuario_ultima_acao' => $id_usuario_sessao,
                'ultima_acao' => 'Alteração',
                'data_ultima_acao' => $data_agora
            );
            DBUpdate('', 'tb_alerta', $dados, "id_alerta_painel = ".$id);
            registraLog('Alteração de status, vencido.','e','id_alerta_painel',$id,"data_vencimento: $data_agora | id_usuario_ultima_acao: $id_usuario_sessao | ultima_acao: Alteração | data_ultima_acao: $data_agora");
        }
        
        $dados_painel = array(
            'status' => 3,
            'data_resposta' => $data_agora,
            'justificativa' => 'Cancelado',
            'id_usuario_resposta' => $id_usuario_sessao
        );
        DBUpdate('', 'tb_alerta_painel', $dados_painel, "id_alerta_painel = ".$id);
        registraLog('Alteração de status, vencido.','e','id_alerta_painel',$id,"status: 3 | data_resposta: $data_agora | justificativa: Cancelado | id_usuario_resposta: $id_usuario_sessao");

        $alert = ('Item alterado com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=alerta-painel-busca");
        exit;
    }else{
        $alert = ('O status do alerta foi alterado antes desta atualização!','w');
        header("location: /api/iframe?token=$request->token&view=alerta-painel-busca");
        exit;  
    }
}

function inserir($id_contrato_plano_pessoa, $data_inicio, $data_vencimento, $id_categoria, $conteudo, $exibicao, $id_alerta_painel = NULL){
    $data_agora = getDataHora();
    
    if($id_alerta_painel){
        $dados_alerta = DBRead('', 'tb_alerta', "WHERE id_alerta_painel = '".$id_alerta_painel."'");
        $dados_alerta_painel = DBRead('', 'tb_alerta_painel', "WHERE id_alerta_painel = '".$id_alerta_painel."' AND status = 1");
    }
    if($dados_alerta_painel || !$id_alerta_painel){

        if($id_categoria != "" && $conteudo != "" && $exibicao != ""){
            $id_usuario_sessao = $_SESSION['id_usuario'];
            
            if($dados_alerta){
                if($data_vencimento){
                    $dados = array(
                        'data_inicio' => $data_inicio,
                        'data_vencimento' => $data_vencimento,
                        'id_categoria' => $id_categoria,
                        'conteudo' => $conteudo,
                        'exibicao' => $exibicao,
                        'id_usuario_criou' => $id_usuario_sessao,
                        'id_usuario_ultima_acao' => $id_usuario_sessao,
                        'ultima_acao' => 'Alteração',
                        'data_ultima_acao' => $data_agora
                    );
                    // $insertID = $id_alerta_painel;
                    DBUpdate('', 'tb_alerta', $dados, "id_alerta_painel = $id_alerta_painel");
                    registraLog('Alteração de alerta via painel.','a','tb_alerta',$id_alerta_painel,"data_inicio: $data_inicio | data_vencimento: $data_vencimento | id_categoria: $id_categoria | conteudo: $conteudo | exibicao: $exibicao | id_usuario_criou: $id_usuario_sessao | id_usuario_ultima_acao: $id_usuario_sessao | ultima_acao: Alteração | data_ultima_acao: $data_agora");     
                }else{
                    
                    $dados = array(
                        'data_inicio' => $data_inicio,
                        'id_categoria' => $id_categoria,
                        'conteudo' => $conteudo,
                        'exibicao' => $exibicao,
                        'id_usuario_criou' => $id_usuario_sessao,
                        'id_usuario_ultima_acao' => $id_usuario_sessao,
                        'ultima_acao' => 'Criação',
                        'data_ultima_acao' => $data_agora
                    );
                    // $insertID = $id_alerta_painel;
                    DBUpdate('', 'tb_alerta', $dados, "id_alerta_painel = $id_alerta_painel");
                    registraLog('Alteração de alerta via painel.','a','tb_alerta',$id_alerta_painel,"data_inicio: $data_inicio | id_categoria: $id_categoria | conteudo: $conteudo | exibicao: $exibicao | id_usuario_criou: $id_usuario_sessao | id_usuario_ultima_acao: $id_usuario_sessao | ultima_acao: Alteração | data_ultima_acao: $data_agora");     
                }
            }else{
                if($data_vencimento){
                    $dados = array(
                        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                        'data_criacao' => $data_agora,
                        'data_inicio' => $data_inicio,
                        'data_vencimento' => $data_vencimento,
                        'id_categoria' => $id_categoria,
                        'conteudo' => $conteudo,
                        'exibicao' => $exibicao,
                        'id_usuario_criou' => $id_usuario_sessao,
                        'id_usuario_ultima_acao' => $id_usuario_sessao,
                        'ultima_acao' => 'Criação',
                        'data_ultima_acao' => $data_agora
                    );
                    
                    $insertID = DBCreate('', 'tb_alerta', $dados, true);
                    registraLog('Inserção de novo alerta.','i','tb_alerta',$insertID,"id_contrato_plano_pessoa: $id_contrato_plano_pessoa | data_criacao: $data_agora | data_inicio: $data_inicio | data_vencimento: $data_vencimento | id_categoria: $id_categoria | conteudo: $conteudo | exibicao: $exibicao | id_usuario_criou: $id_usuario_sessao | id_usuario_ultima_acao: $id_usuario_sessao | ultima_acao: Criação | data_ultima_acao: $data_agora");         
                    
                }else{
                    
                    $dados = array(
                        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                        'data_criacao' => $data_agora,
                        'data_inicio' => $data_inicio,
                        'id_categoria' => $id_categoria,
                        'conteudo' => $conteudo,
                        'exibicao' => $exibicao,
                        'id_usuario_criou' => $id_usuario_sessao,
                        'id_usuario_ultima_acao' => $id_usuario_sessao,
                        'ultima_acao' => 'Criação',
                        'data_ultima_acao' => $data_agora
                    );
        
                    $insertID = DBCreate('', 'tb_alerta', $dados, true);
                    registraLog('Inserção de novo alerta.','i','tb_alerta',$insertID,"id_contrato_plano_pessoa: $id_contrato_plano_pessoa | data_criacao: $data_agora | data_inicio: $data_inicio | id_categoria: $id_categoria | conteudo: $conteudo | exibicao: $exibicao | id_usuario_criou: $id_usuario_sessao | id_usuario_ultima_acao: $id_usuario_sessao | ultima_acao: Criação | data_ultima_acao: $data_agora");   
                }
            }
            
        
            if($id_alerta_painel){
                $dados_verificacao_id_painel = DBRead('', 'tb_alerta_painel', "WHERE status = 1 AND id_alerta_painel = '".$id_alerta_painel."' ");
                if($dados_verificacao_id_painel){

                    $dados = array(
                        'status' => 2,
                        'data_resposta' => $data_agora,
                        'id_usuario_resposta' => $id_usuario_sessao
                    );
                    
                    DBUpdate('', 'tb_alerta_painel', $dados, "id_alerta_painel = $id_alerta_painel");
                    registraLog('Alteração de status de alerta do painel.','a','tb_alerta_painel',$id_alerta_painel,"status: 2 | data_resposta: $data_agora | id_usuario_resposta: $id_usuario_sessao");
                    
                    if(!$dados_alerta){
                        $dados = array(
                            'id_alerta_painel' => $id_alerta_painel
                        );
                        // $insertID = $dados_alerta[0]['id_alerta'];
    
                        DBUpdate('', 'tb_alerta', $dados, "id_alerta = $insertID");
                        registraLog('Inserção de id_alerta_painel no alerta.','a','tb_alerta',$insertID,"id_alerta_painel: $id_alerta_painel");
                    }
                    
                    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';                }else{
                    $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';                        }
                
                header("location: /api/iframe?token=$request->token&view=alerta-painel-busca ");
            }else{
                header("location: /api/iframe?token=$request->token&view=alerta-busca");
            }

        }else{

            $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';                    if($id_alerta_painel){
                header("location: /api/iframe?token=$request->token&view=alerta-form&publicar=".$id_alerta_painel." ");
            }else{
                header("location: /api/iframe?token=$request->token&view=alerta-form");
            }
        }
    }else{
        $alert = ('O status do alerta foi alterado antes desta atualização!','w');
        header("location: /api/iframe?token=$request->token&view=alerta-painel-busca ");
    }
    exit;
}

function alterar($id, $id_contrato_plano_pessoa, $data_inicio, $data_vencimento, $id_categoria, $conteudo, $exibicao){
    $data_agora = getDataHora();
    $dados = array(
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'data_inicio' => $data_inicio,
        'data_vencimento' => $data_vencimento,
        'id_categoria' => $id_categoria,
        'conteudo' => $conteudo,
        'exibicao' => $exibicao,
        'id_usuario_ultima_acao' => $_SESSION['id_usuario'],
        'ultima_acao' => 'Alteração',
        'data_ultima_acao' => $data_agora
    );

    if($id_categoria != "" && $conteudo != "" && $exibicao != ""){

        DBUpdate('', 'tb_alerta', $dados, "id_alerta = $id");
        registraLog('Alteração de alerta.','a','tb_alerta',$id,"id_contrato_plano_pessoa: $id_contrato_plano_pessoa | data_inicio: $data_inicio | data_vencimento: $data_vencimento | id_categoria: $id_categoria | conteudo: $conteudo | exibicao: $exibicao");
        $alert = ('Item alterado com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=alerta-busca");

    }else{

        $alert = 'Não foi possível alterar o item!' ;
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=alerta-form&alterar=$id");
    }
    
    exit;
}

function excluir($id){
    $query = "DELETE FROM tb_alerta WHERE id_alerta = ".$id;
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de alerta.', 'e', 'tb_alerta', $id, '');
    if(!$result){
        $alert = ('Erro ao excluir item!', 'd');
    }else{
        $alert = ('Item excluído com sucesso!', 's');
    }
    header("location: /api/iframe?token=$request->token&view=alerta-busca");
    exit;
}

function vencido($id){
    $data_agora = getDataHora();
    $id_usuario_sessao = $_SESSION['id_usuario'];
    $dados = array(
        'data_vencimento' => $data_agora,
        'id_usuario_ultima_acao' => $id_usuario_sessao,
        'ultima_acao' => 'Alteração',
        'data_ultima_acao' => $data_agora
    );
    DBUpdate('', 'tb_alerta', $dados, "id_alerta = ".$id);
    registraLog('Alteração de status, vencido.','e','id_alerta',$id,"data_vencimento: $data_agora | id_usuario_ultima_acao: $id_usuario_sessao | ultima_acao: Alteração | data_ultima_acao: $data_agora");

    $dados_alerta = DBRead('', 'tb_alerta', "WHERE id_alerta = '".$id."'");
    if($dados_alerta[0]['id_alerta_painel']){
        $dados_alerta_painel = DBRead('', 'tb_alerta_painel', "WHERE id_alerta_painel = '".$dados_alerta[0]['id_alerta_painel']."' AND status = 2 ");
        if($dados_alerta_painel){
            $id_alerta_painel = $dados_alerta[0]['id_alerta_painel'];
            $dados_painel = array(
                'status' => 3,
                'data_resposta' => $data_agora,
                'justificativa' => 'Vencido',
                'id_usuario_resposta' => $id_usuario_sessao
            );
            DBUpdate('', 'tb_alerta_painel', $dados_painel, "id_alerta_painel = ".$id_alerta_painel);
            registraLog('Alteração de status, vencido.','e','id_alerta_painel',$id_alerta_painel,"status: 3 | data_resposta: $data_agora | justificativa: Vencido | id_usuario_resposta: $id_usuario_sessao");
        }
        
    }

    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=alerta-busca");
    exit;
}

function reprovar($id, $justificativa){
    $dados_alerta_painel = DBRead('', 'tb_alerta_painel', "WHERE id_alerta_painel = '".$id."' AND status = 1");
    
    if($dados_alerta_painel){
        $data_agora = getDataHora();
        $id_usuario_sessao = $_SESSION['id_usuario'];
        $dados = array(
            'status' => 5,
            'justificativa' => $justificativa,
            'data_resposta' => $data_agora,
            'id_usuario_resposta' => $id_usuario_sessao
        );
        DBUpdate('', 'tb_alerta_painel', $dados, "id_alerta_painel = ".$id);
        registraLog('Alteração de status, descartado.','e','id_alerta_painel',$id,"status: 5 | justificativa: $justificativa | data_resposta: $data_agora | id_usuario_resposta: $id_usuario_sessao");
        
        // $dados_alerta = DBRead('', 'tb_alerta', "WHERE id_alerta_painel = '".$id."'");
        // if($dados_alerta){
        //     $dados = array(
        //         'data_vencimento' => $data_agora,
        //         'id_usuario_ultima_acao' => $id_usuario_sessao,
        //         'ultima_acao' => 'Alteração',
        //         'data_ultima_acao' => $data_agora
        //     );
        //     DBUpdate('', 'tb_alerta', $dados, "id_alerta_painel = ".$id);
        //     registraLog('Alteração de status, vencido.','e','id_alerta_painel',$id,"data_vencimento: $data_agora | id_usuario_ultima_acao: $id_usuario_sessao | ultima_acao: Alteração | data_ultima_acao: $data_agora");
        // }
        $alert = ('Alerta descartado com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=alerta-painel-busca");
        exit;
    }else{
        $alert = ('O status do alerta foi alterado antes desta atualização!','w');
        header("location: /api/iframe?token=$request->token&view=alerta-painel-busca");
        exit;
    }    
}

?>