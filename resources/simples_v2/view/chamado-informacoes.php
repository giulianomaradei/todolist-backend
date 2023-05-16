<?php
    require_once(__DIR__."/../class/System.php");

    $id_chamado = $_GET['chamado'];

    $_SESSION['id_chamado_busca_time_line'] = $id_chamado;

    $data = getDataHora();

    $chamados = DBRead('', 'tb_chamado a', "INNER JOIN tb_chamado_origem b ON a.id_chamado_origem = b.id_chamado_origem INNER JOIN tb_chamado_status c ON a.id_chamado_status = c.id_chamado_status WHERE a.id_chamado = '$id_chamado'", 'a.*, b.descricao AS origem, c.descricao as status');

    if($chamados[0]['id_chamado_origem'] == '4'){
        $dados_remetente_origem = DBRead('', 'tb_usuario_painel a', " INNER JOIN tb_pessoa b ON a.id_pessoa_usuario = b.id_pessoa WHERE a.id_usuario_painel = '".$chamados[0]['id_usuario_remetente']."' ", " b.nome as remetente");
        $nome_remetente = $dados_remetente_origem[0]['remetente']." (Painel do Cliente)";

    }else{
        $dados_remetente_origem = DBRead('', 'tb_usuario a', " INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$chamados[0]['id_usuario_remetente']."' ", " b.nome as remetente");
        $nome_remetente = $dados_remetente_origem[0]['remetente'];
    }

    if(!$chamados){
        echo '<div class="alert alert-danger text-center"><strong>Você não possui permisssão para visualizar este chamado!</strong></div>';
        exit;
    }

    $ultima_acao_lida = DBRead('', 'tb_chamado_visualizacao', "WHERE id_usuario = '$id_usuario' AND id_chamado = '$id_chamado' ORDER BY id_chamado_acao DESC LIMIT 1");

    if($ultima_acao_lida){
        $id_ultima_acao_lida = $ultima_acao_lida[0]['id_chamado_acao'];
    }else{
        $id_ultima_acao_lida = 0;
    }

    $chamados_acao = DBRead('', 'tb_chamado_acao', "WHERE id_chamado = '$id_chamado' AND id_chamado_acao > $id_ultima_acao_lida");
    $acoes_nao_lidas = array();
    foreach($chamados_acao as $conteudo){       
        array_push($acoes_nao_lidas, $conteudo['id_chamado_acao']);
    }

    $id_remetente = $chamados[0]['id_usuario_remetente'];
    $id_responsavel = $chamados[0]['id_usuario_responsavel'];
    $visibilidade = $chamados[0]['visibilidade'];
    $id_chamado_status = $chamados[0]['id_chamado_status'];
    $id_categoria = $chamados[0]['id_categoria'];
    $id_origem = $chamados[0]['id_chamado_origem'];

    $prazo_encerramento = converteDataHora($chamados[0]['prazo_encerramento']);

    $id_perfil_remetente = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_remetente'", 'id_perfil_sistema');

    if ($visibilidade == 1) {
        $perfis = DBRead('', 'tb_chamado_perfil', "WHERE id_chamado = $id_chamado", 'id_perfil_sistema');
        $perfis = mudaVinculoArray($perfis);

        $check = '';
        $check = verificaVinculo($perfil_usuario, $perfis);

        if (in_array($perfil_usuario, $perfis) || $check == true) {
            $pass = true;
        } else {
            $pass = false;
        }

        if ($pass == false && $chamados[0]['id_usuario_remetente'] != $_SESSION['id_usuario'] && $chamados[0]['id_usuario_responsavel'] != $_SESSION['id_usuario']) {
            echo '<div class="alert alert-danger text-center"><strong>Você não possui permisssão para visualizar este chamado!</strong></div>';
            exit;

        }
    } else if ($visibilidade == 2) {
        $usuario = DBRead('', 'tb_chamado_usuario', "WHERE id_usuario = '$id_usuario' AND id_chamado= '$id_chamado'");

        if (!$usuario && $chamados[0]['id_usuario_remetente'] != $_SESSION['id_usuario'] && $chamados[0]['id_usuario_responsavel'] != $_SESSION['id_usuario']) {
            echo '<div class="alert alert-danger text-center"><strong>Você não possui permisssão para visualizar este chamado!</strong></div>';
            exit;
        }
    }

    $dados_usuarios = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa AND a.status = 1 AND id_usuario != '$id_remetente' AND id_usuario != '$id_usuario' AND id_usuario != '$id_responsavel' ORDER BY b.nome ASC");

    if(isset($_GET['chamado'])){
        $id = (int)$_GET['chamado'];
    }

    $usuario_remetente = $chamados[0]['id_usuario_remetente'];

    $ultima_acao = DBRead('','tb_chamado_acao', "WHERE id_chamado = $id_chamado ORDER BY id_chamado_acao DESC LIMIT 1");
    if($id_ultima_acao_lida != $ultima_acao[0]['id_chamado_acao']){
        $dados_pendencia = DBRead('', 'tb_chamado_pendencia', "WHERE id_chamado_acao = '".$ultima_acao[0]['id_chamado_acao']."'");
        if(($dados_pendencia && $dados_pendencia[0]['data'] < $data) || (!$dados_pendencia)){
            DBDelete('','tb_chamado_visualizacao', "id_usuario = '$id_usuario' AND id_chamado = '$id_chamado'");
            $dados_visualizacao = array(
                'id_chamado' => $id_chamado,
                'id_chamado_acao' => $ultima_acao[0]['id_chamado_acao'],
                'id_usuario' => $id_usuario,
                'data' => getDataHora()
            );
            DBCreate('','tb_chamado_visualizacao', $dados_visualizacao);
        }
    }
    
    $chamado_notificacao = DBRead('', 'tb_chamado_ignora', "WHERE id_usuario = '".$_SESSION['id_usuario']."' AND id_chamado = '".$id_chamado."' ", "COUNT(*) AS contador");

    $contrato_plano_pessoa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa = '".$chamados[0]['id_contrato_plano_pessoa']."'", "b.id_pessoa, a.id_contrato_plano_pessoa, a.nome_contrato, b.nome, b.cpf_cnpj, b.razao_social, c.cod_servico AS 'servico', c.nome AS 'plano', a.id_responsavel, a.id_responsavel_tecnico");

    if($contrato_plano_pessoa[0]['nome_contrato']){
        $nome_contrato = " (".$contrato_plano_pessoa[0]['nome_contrato'].") ";
    }else{
        $nome_contrato = "";
    }

    if($contrato_plano_pessoa[0]['nome']){
        $nome_responsavel_contrato = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$contrato_plano_pessoa[0]['id_responsavel']."' ");
        $nome_responsavel_contrato_tecnico = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$contrato_plano_pessoa[0]['id_responsavel_tecnico']."' ");
        $contrato = $contrato_plano_pessoa[0]['nome'] . " ". $nome_contrato ." - " . getNomeServico($contrato_plano_pessoa[0]['servico']) . " - " . $contrato_plano_pessoa[0]['plano'] . " (" . $contrato_plano_pessoa[0]['id_contrato_plano_pessoa'] . ")";

        $texto_contrato = 'Responsável pelo Relacionamento: '.$nome_responsavel_contrato[0]['nome'].'<br>Responsável Técnico: '.$nome_responsavel_contrato_tecnico[0]['nome'];
    }else{
        $contrato = "";
        $texto_contrato = '';
    }
?>

<style>
    /* Timeline */
    .timeline,.timeline-horizontal{
        list-style: none;
        padding: 0;
        position: relative;
    }
    .timeline:before{
        top: 40px;
        bottom: 4px;
        position: absolute;
        content: " ";
        width: 3px;
        background-color: #eeeeee;
        left: 50%;
        margin-left: -1.5px;
    }
    .timeline .timeline-item{
        margin-bottom: 10px;
        position: relative;
    }
    .timeline .timeline-item:before,
    .timeline .timeline-item:after{
        content: "";
        display: inline-block;
    }
    .timeline .timeline-item:after{
        clear: both;
    }
    .timeline .timeline-item .timeline-badge{
        color: #fff;
        width: 58px;
        height: 58px;
        line-height: 52px;
        font-size: 17px;
        text-align: center;
        position: absolute;
        top: 18px;
        left: 50%;
        margin-left: -25px;
        background-color: #7c7c7c;
        border: 3px solid #ffffff;
        z-index: 100;
        border-top-right-radius: 50%;
        border-top-left-radius: 50%;
        border-bottom-right-radius: 50%;
        border-bottom-left-radius: 50%;
    }
    .timeline .timeline-item .timeline-badge i,
    .timeline .timeline-item .timeline-badge .fa,
    .timeline .timeline-item .timeline-badge .glyphicon{
        top: 2px;
        left: 0px;
    }
    .timeline .timeline-item .timeline-badge.primary{
        background-color: #265a88;
    }
    .timeline .timeline-item .timeline-badge.info{
        background-color: #5bc0de;
    }
    .timeline .timeline-item .timeline-badge.success{
        background-color: #59ba1f;
    }
    .timeline .timeline-item .timeline-badge.warning {
        background-color: #FFC125;
    }
    .timeline .timeline-item .timeline-badge.danger {
        background-color: #ba1f1f;
    }
    .timeline .timeline-item .timeline-badge.block {
        background-color: #DF7401;
    }
    .timeline .timeline-item .timeline-badge.nota_interna {
        background-color: #363636;
    }
    .timeline .timeline-item .timeline-badge.alt {
        background-color: #9370DB;
    }
    .timeline .timeline-item .timeline-badge.ger {
        background-color: #20B2AA;
    }
    .timeline .timeline-item .timeline-badge.prazo {
        background-color: #ff3399;
    }
    .timeline .timeline-item .timeline-badge.pendencia {
        background-color: #EE8262;
    }

    .timeline .timeline-item .timeline-badge.usuario_painel {
        background-color: #FF4000;
    }

    .timeline .timeline-item .timeline-panel {
        position: relative;
        width: 46%;
        float: left;
        right: 16px;
        border: 1px solid #c0c0c0;
        background: #ffffff;
        border-radius: 2px;
        padding: 15px;
        -webkit-box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
    }
    .timeline .timeline-item .timeline-panel:before {
        position: absolute;
        top: 26px;
        right: -16px;
        display: inline-block;
        border-top: 16px solid transparent;
        border-left: 16px solid #c0c0c0;
        border-right: 0 solid #c0c0c0;
        border-bottom: 16px solid transparent;
        content: " ";
    }
    .timeline .timeline-item .timeline-panel .timeline-title {
        margin-top: 0;
        color: inherit;
    }
    .timeline .timeline-item .timeline-panel .timeline-body > p,
    .timeline .timeline-item .timeline-panel .timeline-body > ul {
        margin-bottom: 0;
    }
    .timeline .timeline-item .timeline-panel .timeline-body > p + p {
        margin-top: 5px;
    }
    .timeline .timeline-item:last-child:nth-child(even) {
        float: right;
    }
    .timeline .timeline-item:nth-child(even) .timeline-panel {
        float: right;
        left: 16px;
    }
    .timeline .timeline-item:nth-child(even) .timeline-panel:before {
        border-left-width: 0;
        border-right-width: 14px;
        left: -14px;
        right: auto;
    }
    .timeline-horizontal {
        list-style: none;
        position: relative;
        padding: -px 0px 0px 0px;
        display: inline-block;
    }
    .timeline-horizontal:before {
        height: 3px;
        top: auto;
        bottom: 10x;
        left: 56px;
        right: 0;
        width: 100%;
        margin-bottom: 20px;
    }
    .timeline-horizontal .timeline-item{
        display: table-cell;
        height: 240px;
        width: 20%;
        min-width: 320px;
        float: none !important;
        padding-left: 0px;
        padding-right: 20px;
        margin: 0 auto;
        vertical-align: bottom;
    }
    .timeline-horizontal .timeline-item .timeline-panel{
        top: auto;
        bottom: 64px;
        display: inline-block;
        float: none !important;
        left: 30px !important;
        right: 0 !important;
        width: 430px;
        margin-bottom: 20px;
    }
    .timeline-horizontal .timeline-item .timeline-panel:before{
        top: auto;
        bottom: -16px;
        left: 0px !important;
        right: auto;
        border-right: 16px solid transparent !important;
        border-top: 16px solid #c0c0c0 !important;
        border-bottom: 0 solid #c0c0c0 !important;
        border-left: 16px solid transparent !important;
    }
    .timeline-horizontal .timeline-item:before,
    .timeline-horizontal .timeline-item:after {
        display: none;
    }
    .timeline-horizontal .timeline-item .timeline-badge{
        top: auto;
        bottom: 0px;
        left: 43px;
    }
    .conteudo-editor img{
        max-width: 100% !important;
        max-height: 100% !important;
        height: 100% !important;
    }
    .td-table{
        font-size:15px;
    }
    .timeline1{
        overflow-y: hidden;
        overflow-x: scroll;
    }
    .some-container .tooltip .tooltip-inner{
        width: 40em;
        max-width: 100%;
        white-space: pre-line;
    }
    #btn-assumir{
        border: 0 !important;
        background-color: transparent !important;
        width: 100% !important;
        padding: 3px 30px 3px 0;
    }
    #btn-assumir:hover{
        background-color: #e8e8e8 !important;
        background-image: linear-gradient(to bottom,#f5f5f5 0,#e8e8e8 100%) !important;
        background-repeat: repeat-x !important;
    }
    .select2{
        width: 100% !important;
    }
    ul.dropdown-menu{
        min-width: 195px !important;
    }
</style>
               
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="row">
                        <div class="col-md-4">
                            <h3 class="panel-title text-left pull-left">Chamado: #<?=$chamados[0]['id_chamado']?>
                            <?php
                                if($chamados[0]['data_pendencia'] != ''){

                                $data = $chamados[0]['data_pendencia'];

                                if( converteDataHora($data) <= converteDataHora(getDataHora()) ){
                                    $icone_pendencia = '<i class="fa fa-thumb-tack" title="Pendência vencida: '.converteDataHora($data).'" style="color: #8B1A1A"></i>';
                                }else{
                                    $icone_pendencia = '<i class="fa fa-thumb-tack" title="Pendência para: '.converteDataHora($data).'" "></i>';
                                }
                            ?>
                                <?=$icone_pendencia?>
                            <?php
                                }
                            ?>
                            </h3>
                        </div>
                        <div class="col-md-4">
                            <h3 class="panel-title text-center ">Criado em: <?=converteDataHora($chamados[0]['data_criacao'])?>
                            </h3>
                        </div>
                        <div class="col-md-4">
                            <div class="panel-title text-right">
                            <input type="hidden" id='id_chamado' value="<?=$id_chamado?>" />
                                <?php
                                    if($_SESSION['id_usuario'] != $chamados[0]['id_usuario_responsavel'] && $_SESSION['id_usuario'] != $chamados[0]['id_usuario_remetente']){
                                        echo "<button id='btn-notificacao'></button>";
                                    }
                                ?>
                                <div class="btn-group">
                                    <?php

                                    if($id_chamado_status != 3 && $id_chamado_status != 4){

                                    ?>
                                    <button type="button" class="btn btn-primary btn-xs dropdown-toggle dropdown-menu-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-list-ul"></i>
                                        Ações <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        
                                        <li><a href="#" data-toggle="modal" data-target="#modal_nota"><i class="fa fa-plus"></i> Adicionar nota</a></li>
                                        

                                        <?php
                                        if($_SESSION['id_usuario'] != $chamados[0]['id_usuario_responsavel']):
                                        ?>
                                        <li>
                                            <a href="#" class="btn-assumir"><i class="fa fa-gavel"></i> Assumir chamado</a>                        
                                        </li>
                                        <?php
                                        endif;
                                        
                                            if($chamados[0]['bloqueado'] == 1){
                                        ?>
                                            <li><a href="#" data-toggle="modal" data-target="#modal_desbloquear"><i class="fa fa-unlock"></i> Desbloquear</a></li>

                                        <?php
                                            }
                                            if($chamados[0]['bloqueado'] == 1){

                                                if($_SESSION['id_usuario'] == $chamados[0]['id_usuario_responsavel'] || $_SESSION['id_usuario'] == $chamados[0]['id_usuario_remetente']){
                                        ?>          
                                                    <li role="separator" class="divider"></li>
                                                    
                                                    <?php
                                                    //___________________________AQUI____________________________________
                                                        //if($chamados[0]['id_chamado_origem'] != 1 && $chamados[0]['id_chamado_origem'] != 4){
                                                        if($chamados[0]['id_chamado_origem'] != 1){

                                                            echo '<li><a href="#" data-toggle="modal" data-target="#modal_alterar"><i class="fa fa-edit"></i> Alterar chamado</a></li>';

                                                        }
                                                    ?>

                                                    <li><a href="#" data-toggle="modal" data-target="#modal_envolvidos"><i class="fa fa-cog" aria-hidden="true"></i> Gerenciar envolvidos</a></li>
                                                    <?php if($chamados[0]['id_chamado_origem'] != 4){?>
                                                        <li><a href="#" data-toggle="modal" data-target="#modal_pendencia"><i class="fa fa-thumb-tack"></i> Inserir pendência</a></li>
                                                    <?php } ?>
                                                    <li><a href="#" data-toggle="modal" data-target="#trocar_responsavel"><i class="fa fa-exchange"></i> Trocar responsável</a></li>
                                                    <li><a href="#" data-toggle="modal" data-target="#modal_alteracao_prazo_encerramento"><i class="fas fa-hourglass-start"></i> Alteração de prazo de encerramento</a></li>
                                                    <li role="separator" class="divider"></li>
                                                    <li><a href="#" data-toggle="modal" data-target="#modal_encerrar"><i class="fa fa-check"></i> Encerrar</a></li>
                                        <?php
                                                }

                                            }else if($chamados[0]['bloqueado'] == 2){
                                        ?>
                                                <li role="separator" class="divider"></li>
                                                <li><a href="#" data-toggle="modal" data-target="#modal_bloquear"><i class="fa fa-lock"></i> Bloquear</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#modal_alterar"><i class="fa fa-edit"></i> Alterar chamado</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#modal_envolvidos"><i class="fa fa-cog" aria-hidden="true"></i> Gerenciar envolvidos</a></li>
                                                <?php if($chamados[0]['id_chamado_origem'] != 4){?>
                                                    <li><a href="#" data-toggle="modal" data-target="#modal_pendencia"><i class="fa fa-thumb-tack"></i> Inserir pendência</a></li>
                                                <?php } ?>
                                                <li><a href="#" data-toggle="modal" data-target="#trocar_responsavel"><i class="fa fa-exchange"></i> Trocar responsável</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#modal_alteracao_prazo_encerramento"><i class="fas fa-hourglass-start"></i> Alteração de prazo de encerramento</a></li>
                                                <li role="separator" class="divider"></li>
                                                <li><a href="#" data-toggle="modal" data-target="#modal_encerrar"><i class="fa fa-check"></i> Encerrar</a></li>
                                        <?php
                                             }
                                        }else{
                                        ?>  
                                            <button type="button" class="btn btn-primary btn-xs dropdown-toggle dropdown-menu-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-list-ul"></i>
                                            Ações <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li id='opcao-reabrir'><a href='#' data-toggle='modal' data-target='#modal_reabrir'><i class='fa fa-undo' aria-hidden='true'></i> Reabrir chamado</a></li>                                                
                                        <?php
                                        }
                                        if($perfil_usuario == 20){
                                            echo "<li><a href='/api/ajax?class=Chamado.php?cancelar_envolvimento=$id_chamado&token=". $request->token ."' style='color:#b92c28;' onclick=\"if (!confirm('Tem certeza que deseja cancelar seu envolvimento com este chamado?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-sign-out' aria-hidden='true'></i> Cancelar envolvimento</a></li>";
                                        }
                                        ?>                                          
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div><!-- end row -->
                </div><!-- end panel-heading -->

                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-6" style="margin-left: -5px;">
                                <table class="table table-striped">
                                    <tbody><br> 
                                        <tr>
                                            <td class="td-table"><strong>Título:</strong></td>
                                            <td ><?=$chamados[0]['titulo']?></td>
                                        </tr>
                                        <tr>
                                            <td class="td-table"><strong>Origem:</strong></td>
                                            
                                            <?php 

                                                if($chamados[0]['id_chamado_origem'] == 4){
                                                    echo '<td>'.$chamados[0]['origem'].' <i class="fa fa-exclamation-circle faa-flash animated" style="color: #eea236;"></i></td>';
                                                }else{
                                                    echo '<td>'.$chamados[0]['origem'].'</td>';
                                                }    
                                            ?>


                                        </tr>
                                        <tr>
                                            <td class="td-table"><strong>Remetente:</strong></td>
                                            <td><?=$nome_remetente?></td>
                                        </tr>
                                        <tr>
                                            <td class="td-table"><strong>Status:</strong></td>
                                            <td><?=$chamados[0]['status']?></td>
                                        </tr>
                                        <tr>
                                          <?php
                                            $visibilidade = array(
                                              "1" => "Público",
                                              "2" => "Privado"
                                            );
                                            ?>
                                            <td class="td-table"><strong>Visibilidade:</strong></td>
                                            <td><?=$visibilidade[$chamados[0]['visibilidade']]?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div><!-- end col -->

                            <div class="col-md-6">
                                <table class="table table-striped">
                                    <tbody><br>
                                        <?php
                                        if($chamados[0]['id_contrato_plano_pessoa'] != 0):

                                            $nome_responsavel_contrato_inicio = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$chamados[0]['id_contrato_plano_pessoa']."' ");
                                                                                        
                                            $popover_responsavel_contrato = ' <a tabindex="0" role="button" style="cursor:pointer;" data-toggle="popover" data-placement="bottom" data-html="true" data-trigger="focus" title="Responsáveis" data-content="Relacionamento: '.$nome_responsavel_contrato[0]['nome'].'<br>Técnico: '.$nome_responsavel_contrato_tecnico[0]['nome'].'"><i class="fa fa-question-circle"></i></a><br>';

                                        ?>
                                        <tr> 
                                            <td class="td-table"><strong>Contrato (cliente):</strong></td>
                                            <td><?=$contrato."".$popover_responsavel_contrato?></td>
                                        </tr> 
                                        <?php
                                        endif;
                                        ?>
                                        <tr>
                                          <?php
                                            $responsavel = DBRead('', 'tb_usuario a', "INNER JOIN tb_chamado_acao b ON a.id_usuario = b.id_usuario_responsavel INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE b.id_chamado = '$id_chamado' ORDER BY b.data DESC LIMIT 1");
                                          ?>
                                            <input type="hidden" id="id_responsavel_chamado" value="<?=$responsavel[0]['id_usuario_responsavel']?>" />

                                            <td class="td-table"><strong>Responsável:</strong></td>
                                            <td><?=$responsavel[0]['nome']?></td>
                                        </tr>
                                        <tr>
                                            <?php
                            
                                            $categoria = DBRead('', 'tb_categoria a', "INNER JOIN tb_chamado_categoria b ON a.id_categoria = b.id_categoria WHERE b.id_chamado = $id_chamado");
                                            ?>
                                            <td class="td-table"><strong>Categoria:</strong></td>
                                            <td>
                                                <div style="max-height: 60px; overflow: auto;">
                                                    <?php
                                                        foreach($categoria as $cat){
                                                            echo $cat['nome'] . "<br>";
                                                        }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                          <?php
                                          if($chamados[0]['visibilidade'] == 1){

                                            $envolvidos = DBRead('', 'tb_chamado_perfil a', "INNER JOIN tb_perfil_sistema b ON a.id_perfil_sistema = b.id_perfil_sistema WHERE a.id_chamado = '$id_chamado' ORDER BY nome");
                                            
                                            $aux_envolvidos = '';
                                            foreach($envolvidos as $conteudo){
                                                $aux_usuarios_setor = '';

                                                $dados_usuarios_perfil = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_perfil_sistema = '".$conteudo['id_perfil_sistema']."' AND a.status = '1' ORDER BY b.nome",'b.nome');
                                                if($dados_usuarios_perfil){
                                                    foreach($dados_usuarios_perfil as $conteudo_usuario_perfil){
                                                        $aux_usuarios_setor .= $conteudo_usuario_perfil['nome'].'<br>';
                                                    }
                                                }   
                                               $aux_envolvidos .= $conteudo['nome'].' <a tabindex="0" role="button" style="cursor:pointer;" data-toggle="popover" data-html="true" data-trigger="focus" title="Usuários:" data-content="'.$aux_usuarios_setor.'"><i class="fa fa-question-circle"></i></a><br>';
                                            }
                                          }else{

                                            $envolvidos = DBRead('', 'tb_chamado_usuario a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_chamado = '$id_chamado' ORDER BY nome");

                                            $aux_envolvidos = '';
                                            foreach($envolvidos as $conteudo){
                                               $aux_envolvidos .= $conteudo['nome']."<br>";
                                            }
                                          }
                                          ?>
                                            <td class="td-table"><strong>Setor(es)/Envolvido(s):</strong></td>
                                            <td><div style="max-height: 60px; overflow: auto;"><?=$aux_envolvidos ?></div></td>
                                        </tr>
                                        <tr>
                                            <?php 
                                            $tempo_gasto = DBRead('', 'tb_chamado_acao', "WHERE id_chamado = '$id'", "SUM(tempo) AS tempo_gasto"); 
                                            $tempo = explode(':',converteSegundosHoras($tempo_gasto[0]['tempo_gasto'] * 60));
                                            $horas = intval($tempo[0]);
                                            $minutos = intval($tempo[1]);
                                            
                                            if($horas < '1'){
                                                $tempo_total = $minutos." m";
                                            }else{
                                                $tempo_total = $horas." h ".$minutos." m";
                                            }
                                            ?>
                                            <td class='td-table'><strong>Tempo total:</strong></td>
                                            <td><?= $tempo_total?> </td>
                                        </tr>
                                        <tr>
                                            <td class='td-table'><strong>Prazo de Encerramento:</strong></td>
                                            <td><?php
                                                if($prazo_encerramento != "00/00/0000 00:00"){
                                                    echo $prazo_encerramento;
                                                }
                                            ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div><!-- end col -->

                        </div><!-- end row -->
                        <hr>
                                            
                        <div class="row">
                            <div class="col-md-12" style="margin-left: 2px;">
                                <label>Descrição:</label>
                                <?php 
                                if($chamados[0]['id_chamado_origem'] == 4){
                                    $descricao = nl2br($chamados[0]['descricao']);
                                } else {
                                    $descricao = $chamados[0]['descricao'];
                                }
                                ?>
                                <span class="conteudo-editor"><?= $descricao ?></span>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="container-fluid">
                                <hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label><h4><i class="fa fa-chevron-right"></i>Timeline do chamado</h4></label>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group has-feedback">
                                            <label class="control-label sr-only">Hidden label</label>
                                            <input class="form-control" type="text" name="busca_item_time_line" id="busca_item_time_line" placeholder="Informe a descrição ou o nome do autor..." autocomplete="off">
                                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                                        </div>
                                    </div>
                                </div><!-- end row -->

                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="alert-timeline" class="alert alert-warning text-center" style='display:none; margin-top:20px;'></div>                                      
                                        <div class="timeline1">
                                            <ul class="timeline timeline-horizontal">
                                              <?php         
                                              
                                              $chamados_acao = DBRead('', 'tb_chamado_acao', "WHERE id_chamado = '".$id_chamado."'");
                                              
                                              $count = count($chamados_acao);
                                              $ancora = 0;
                                              $nota_interna_css = "";
                                              $verifica = 0;

                                              echo '<input type="hidden" id="count" value="'.$count.'">';

                                              foreach($chamados_acao as $conteudo):
                                                $ancora++;

                                                if(in_array($conteudo['id_chamado_acao'], $acoes_nao_lidas)){

                                                    if($conteudo['id_usuario_acao'] != $id_usuario){

                                                        if($verifica == 0){
                                                        ?>
                                                            <script>
                                                                var ancora = '<?php echo $ancora ?>';
                                                                $('#count').val(ancora);
                                                            </script>

                                                        <?php
                                                         $verifica++;
                                                        }

                                                        $notifica = '<i id="i_exclamation_'.$conteudo['id_chamado_acao'].'" class="fa fa-exclamation-circle faa-flash animated" style="color: #eea236;"></i>';
                                                    }

                                                }else{
                                                    $notifica = "";
                                                }
                                              ?>                                             
                                                <li class="timeline-item item-acao" id_acao="<?=$conteudo['id_chamado_acao']?>" onclick="preencheModal(<?=$conteudo['id_chamado_acao']?>)" style="cursor: pointer;" id="timeline_item_<?=$conteudo['id_chamado_acao']?>">
                                                    <a href="#" id="<?=$ancora?>" style="display:block;"></a>
                                                    <?php

                                                    if($conteudo['arquivo']){
                                                        $anexo = "<i class='fas fa-paperclip'></i>";
                                                    }else{
                                                        $anexo = "";
                                                    }
                                                    
                                                    if($conteudo['acao'] == "criacao"){
                                                        
                                                        $acao = "Criação do chamado";
                                                        $badge = "<div class='timeline-badge primary'>";
                                                        $badge_i = "<i class='fa fa-tag' aria-hidden='true'></i></div>";

                                                        $css = 'border-left: 5px solid #265a88 !important;';

                                                    }else if($conteudo['acao'] == "encerrar"){
                                                        
                                                        $acao = "Chamado encerrado";

                                                        if($conteudo['id_chamado_status'] == 3){
                                                            
                                                            $badge = "<div class='timeline-badge success'>";
                                                            $badge_i = "<i class='fa fa-check' aria-hidden='true'></i></div>";

                                                            $css = 'border-left: 5px solid #59ba1f !important;';
                                                        }

                                                        if($conteudo['id_chamado_status'] == 4){
                                                            
                                                            $badge = "<div class='timeline-badge danger'>";
                                                            $badge_i = "<i class='fa fa-check' aria-hidden='true'></i></div>";

                                                            $css = 'border-left: 5px solid #ba1f1f !important;';
                                                        }

                                                    }else if($conteudo['acao'] == "encaminhar"){
                                                        
                                                        $acao = "Troca de responsável";
                                                        $badge = "<div class='timeline-badge warning'>";
                                                        $badge_i = "<i class='fa fa-exchange' aria-hidden='true'></i></div>";

                                                        $css = 'border-left: 5px solid #FFC125 !important;';

                                                    }else if($conteudo['acao'] == "nota_geral"){
                                                        
                                                        $acao = "Nota adicionada";
                                                        $badge = "<div class='timeline-badge info'>";
                                                        $badge_i = "<i class='fa fa-file' aria-hidden='true'></i></div>";

                                                        $css = 'border-left: 5px solid #5bc0de !important;';

                                                    }else if($conteudo['acao'] == "nota_interna"){
                                                        
                                                        $acao = "Nota adicionada";
                                                        $badge = "<div class='timeline-badge nota_interna'>";
                                                        $badge_i = "<i class='fa fa-file' aria-hidden='true'></i></div>";

                                                        $css = 'border-left: 5px solid #363636 !important;';

                                                    }else if($conteudo['acao'] == "desbloquear"){
                                                        
                                                        $acao = "Chamado desbloqueado";
                                                        $badge = "<div class='timeline-badge block'>";
                                                        $badge_i = "<i class='fa fa-unlock' aria-hidden='true'></i></div>";

                                                        $css = 'border-left: 5px solid #DF7401 !important;';

                                                    }else if($conteudo['acao'] == "bloquear"){
                                                        
                                                        $acao = "Chamado bloqueado";
                                                        $badge = "<div class='timeline-badge block'>";
                                                        $badge_i = "<i class='fa fa-lock' aria-hidden='true'></i></div>";

                                                        $css = 'border-left: 5px solid #DF7401 !important;';

                                                    }else if($conteudo['acao'] == "reabrir"){
                                                        
                                                        $acao = "Chamado reaberto";
                                                        $badge = "<div class='timeline-badge primary'>";
                                                        $badge_i = "<i class='fa fa-undo' aria-hidden='true'></i></div>";

                                                        $css = 'border-left: 5px solid #265a88 !important;';

                                                    }else if($conteudo['acao'] == "gerenciar"){
                                                        
                                                        $acao = "Gerenciamento dos envolvidos";
                                                        $badge = "<div class='timeline-badge ger'>";
                                                        $badge_i = "<i class='fa fa-cog' aria-hidden='true'></i></div>";

                                                        $css = 'border-left: 5px solid #20B2AA !important;';

                                                    }else if($conteudo['acao'] == "alterar"){
                                                        
                                                        $acao = "Alteração do chamado";
                                                        $badge = "<div class='timeline-badge alt'>";
                                                        $badge_i = "<i class='fa fa-edit' aria-hidden='true'></i></div>";

                                                        $css = 'border-left: 5px solid #9370DB
                                                        !important;';

                                                    }else if($conteudo['acao'] == "assumir"){
                                                        
                                                        $acao = "Assumiu responsabilidade";
                                                        $badge = "<div class='timeline-badge warning'>";
                                                        $badge_i = "<i class='fa fa-exchange' aria-hidden='true'></i></div>";

                                                        $css = 'border-left: 5px solid #FFC125 !important;';

                                                    }else if($conteudo['acao'] == "pendencia"){
                                                       
                                                        $acao = "Adicionada uma pendência";
                                                        $badge = "<div class='timeline-badge pendencia'>";
                                                        $badge_i = "<i class='fa fa-calendar-minus-o' aria-hidden='true'></i></div>";

                                                        $css = 'border-left: 5px solid #EE8262 !important;';
                                                    }else if($conteudo['acao'] == "alteracao_prazo_encerramento"){
                                                       
                                                        $acao = "Alteração de prazo";
                                                        $badge = "<div class='timeline-badge prazo'>";
                                                        $badge_i = "<i class='fas fa-hourglass-half'></i></div>";

                                                        $css = 'border-left: 5px solid #ff3399 !important;';
                                                    }
                                                    ?>
                                                    <?php
                                                        if($conteudo['acao_painel'] == 1){
                                                            $dados_acao_painel = DBRead('', 'tb_usuario_painel a', " INNER JOIN tb_pessoa b ON a.id_pessoa_usuario = b.id_pessoa WHERE a.id_usuario_painel = '".$conteudo['id_usuario_acao']."' ", " b.nome as nome_usuario_acao");
                                                            
                                                            $nome_usuario_acao = $dados_acao_painel[0]['nome_usuario_acao']." (Painel do Cliente)";

                                                            $css = 'border-left: 5px solid #FF4000 !important;';
                                                            $badge = "<div class='timeline-badge usuario_painel'>";
                                                        }else{
                                                            $dados_acao_painel = DBRead('', 'tb_usuario a', " INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo['id_usuario_acao']."' ", " b.nome as nome_usuario_acao");

                                                            $nome_usuario_acao = $dados_acao_painel[0]['nome_usuario_acao'];
                                                        }
                                                    
                                                        echo $badge."".$badge_i;

                                                    ?>

                                                    <div class="timeline-panel" style="<?=$css?>">
                                                        <div class="timeline-heading">
                                                            <span class="timeline-title" id="acao_title_<?=$conteudo['id_chamado_acao']?>" style="font-size: 16px;"><strong><?=$notifica." ".$ancora." - ".$acao." ".$anexo?></strong>
                                                            </span>
                                                            <small class="text-muted pull-right"><i class="glyphicon glyphicon-time"></i>
                                                            <?=calcula_idade_data($conteudo['data'], getDataHora())?>atrás
                                                            </small>
                                                        </div>
                                                        <div class="timeline-body">
                                                            
                                                            <p><br></p>
                                                            <p><strong>Feito por: </strong> <?=$nome_usuario_acao?></p>
                                                            <a href="#" class="pull-right" data-toggle="modal modal" onclick="preencheModal(<?=$conteudo['id_chamado_acao']?>)"><br><i class="fa fa-eye" aria-hidden="true"></i> Ver mais</a>
                                                        </div>
                                                    </div>
                                                </li>
                                                
                                              <?php  
                                              endforeach;
                                              ?>

                                            </ul>
                                        </div>
                                        <br>
                                    </div><!-- end col -->
                                </div><!-- end row -->

                            </div><!-- end container -->
						</div><!-- end row -->                                               
                    </div><!-- end panel body --> 
            </div><!-- end panel -->
        </div> <!-- end col -->
    </div><!-- end row -->
</div><!-- end container-fluid -->

<!-- modal_alteracao_prazo_encerramento -->
<div class="modal fade" id="modal_alteracao_prazo_encerramento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Alteração de prazo de encerramento</h4>
      </div>
      <form method="post" action="/api/ajax?class=Chamado.php" id="chamado_alteracao_form" class='form-modal' style="margin-bottom: 0;">
        <input type="hidden" name="token" value="<?php echo $request->token ?>">
        <input type="hidden" value="<?= $id_contrato_plano_pessoa; ?>" name="id_contrato_plano_pessoa" />
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>*Prazo (data):</label>
                        <input name="prazo_encerramento_data" type="text" class="form-control input-sm date calendar hasDatepicker" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>*Prazo (hora):</label>
                        <input name="prazo_encerramento_hora" type="time" class="form-control input-sm" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>*Tempo:</label>
                        <input name="tempo" type="text" class="form-control input-sm" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>*Justificativa:</label>
                        <textarea class="form-control ckeditor" id="alteracao_prazo" required name="justificativa_alteracao" style="z-index: 9000;"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" name="alteracao_prazo" id="alteracao_submit" value="<?= $id ?>" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
        </div>
     </form>
    </div>
  </div>
</div> <!-- end modal -->

<!-- Modal visualizar acao -->
<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
  <div class="modal-dialog modal-lg" role="document" >
    <div class="modal-content" id="teste">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel3"></h4>
        </div>
        <div class="modal-body">
          <div class="row">
                <div class="col-md-12 bloco-conteudo" style="margin-left: -6px;">
                    
                    <div id="conteudo">

                    </div>
                </div><!-- end col -->
            </div><!-- end row -->
        </div>
      <div class="modal-footer">

        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div style="margin: 0 auto;width:145px;">
                <button id="anterior" title="Voltar uma nota" class="btn btn-default btn-xs"><i class="fa fa-chevron-left" aria-hidden="true"></i> Anterior</button>
                <button id="proximo" title="Avançar uma nota" class="btn btn-default btn-xs">Próximo <i class="fa fa-chevron-right" aria-hidden="true"></i></button>
            </div>
        </div>

        <div class="col-md-4">

            <div class="btn-group">
                <?php

                if($id_chamado_status != 3 && $id_chamado_status != 4){

                ?>
                <button type="button" class="btn btn-primary btn-xs dropdown-toggle dropdown-menu-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-list-ul"></i>
                    Ações <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    
                    <li><a href="#" data-toggle="modal" data-target="#modal_nota"><i class="fa fa-plus"></i> Adicionar nota</a></li>
                    
                    <?php
                    if($_SESSION['id_usuario'] != $chamados[0]['id_usuario_responsavel']):
                    ?>
                    <li>
                        <a href="#" class="btn-assumir"><i class="fa fa-gavel"></i> Assumir chamado</a>                        
                    </li>
                    <?php
                    endif;
                    
                        if($chamados[0]['bloqueado'] == 1){
                    ?>
                        <li><a href="#" data-toggle="modal" data-target="#modal_desbloquear"><i class="fa fa-unlock"></i> Desbloquear</a></li>

                    <?php
                        }
                        if($chamados[0]['bloqueado'] == 1){

                            if($_SESSION['id_usuario'] == $chamados[0]['id_usuario_responsavel'] || $_SESSION['id_usuario'] == $chamados[0]['id_usuario_remetente']){
                    ?>          
                                <li role="separator" class="divider"></li>
                                
                                <?php
                                    if($chamados[0]['id_chamado_origem'] != 1 && $chamados[0]['id_chamado_origem'] != 4){
                                ?>
                                        <li><a href="#" data-toggle="modal" data-target="#modal_alterar"><i class="fa fa-edit"></i> Alterar chamado</a></li>
                                <?php
                                    }
                                ?>

                                <li><a href="#" data-toggle="modal" data-target="#modal_envolvidos"><i class="fa fa-cog" aria-hidden="true"></i> Gerenciar envolvidos</a></li>
                                <?php if($chamados[0]['id_chamado_origem'] != 4){?>
                                    <li><a href="#" data-toggle="modal" data-target="#modal_pendencia"><i class="fa fa-thumb-tack"></i> Inserir pendência</a></li>
                                <?php } ?>
                                <li><a href="#" data-toggle="modal" data-target="#trocar_responsavel"><i class="fa fa-exchange"></i> Trocar responsável</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#" data-toggle="modal" data-target="#modal_encerrar"><i class="fa fa-check"></i> Encerrar</a></li>
                    <?php
                            }

                        }else if($chamados[0]['bloqueado'] == 2){
                    ?>
                            <li role="separator" class="divider"></li>
                            <li><a href="#" data-toggle="modal" data-target="#modal_bloquear"><i class="fa fa-lock"></i> Bloquear</a></li>
                            <li><a href="#" data-toggle="modal" data-target="#modal_alterar"><i class="fa fa-edit"></i> Alterar chamado</a></li>
                            <li><a href="#" data-toggle="modal" data-target="#modal_envolvidos"><i class="fa fa-cog" aria-hidden="true"></i> Gerenciar envolvidos</a></li>
                            <?php if($chamados[0]['id_chamado_origem'] != 4){?>
                                <li><a href="#" data-toggle="modal" data-target="#modal_pendencia"><i class="fa fa-thumb-tack"></i> Inserir pendência</a></li>
                            <?php } ?>
                            <li><a href="#" data-toggle="modal" data-target="#trocar_responsavel"><i class="fa fa-exchange"></i> Trocar responsável</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#" data-toggle="modal" data-target="#modal_encerrar"><i class="fa fa-check"></i> Encerrar</a></li>
                    <?php
                            }
                    }else{
                    ?>  
                        <button type="button" class="btn btn-primary btn-xs dropdown-toggle dropdown-menu-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-list-ul"></i>
                        Ações <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li id='opcao-reabrir'><a href='#' data-toggle='modal' data-target='#modal_reabrir'><i class='fa fa-undo' aria-hidden='true'></i> Reabrir chamado</a></li>
                    <?php
                    }
                    if($perfil_usuario == 20){
                        echo "<li><a href='/api/ajax?class=Chamado.php?cancelar_envolvimento=$id_chamado&token=". $request->token ."' style='color:#b92c28;' onclick=\"if (!confirm('Tem certeza que deseja cancelar seu envolvimento com este chamado?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-sign-out' aria-hidden='true'></i> Cancelar envolvimento</a></li>";
                    }
                    ?>
                        
                </ul>
            </div>

        </div><!-- Fim da div col md-4 -->

      </div>
    </div>
  </div>
</div><!-- end modal -->

<!-- Modal encerrar chamado -->
<div class="modal fade" id="modal_encerrar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Encerrar chamado</h4>
      </div>

      <form enctype='multipart/form-data' id="chamado_encerrar_form" action='/api/ajax?class=Chamado.php' method='POST' class='form-modal' style="margin-bottom: 0;">
        <input type="hidden" name="token" value="<?php echo $request->token ?>">
        <input type="hidden" name="id_responsavel" value="<?= $chamados[0]['id_usuario_responsavel']; ?>">
        <input type="hidden" name="visibilidade" value="<?=$chamados[0]['visibilidade']; ?>">

        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>*Descrição:</label>
                        <textarea name="solucao" id="solucao" class="form-control ckeditor conteudo" required style="z-index: 9000;"></textarea>
                    </div>
                </div><!-- end col -->
            </div><!-- end row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>*Tempo(minutos):</label>
                        <input class="form-control input-sm number_int modal-tempo" name="tempo_encerramento" id="tempo_encerramento" required />
                    </div>
                </div><!-- end col -->
                <div class="col-md-6">
                    <div class="form-group">

                        <label>*Status:</label>
                        <select class="form-control input-sm" name="id_chamado_status" required>
                            <option></option>
                            <?php
                            $dados_status = DBRead('', 'tb_chamado_status', "WHERE id_chamado_status = 3 OR id_chamado_status = 4");
                            foreach($dados_status as $conteudo):
                            ?>
                            <option value="<?=$conteudo['id_chamado_status']?>"><?=$conteudo['descricao']?></option>
                            <?php 
                            endforeach;
                            ?>
                        </select> 
                    </div>
                </div><!-- end col -->
          </div><!-- end row -->
            <?php if($chamados[0]['id_chamado_origem'] == 4){ ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                        <label>Anexar arquivo: <i class="fa fa-question-circle" data-toggle="tooltip" title="Formatos aceitos: .csv, .doc, .docx, .pdf, .ppt, .pptx, .rtf, .txt, .xls, .xlsx, .zip, .rar, .bmp, .jpg, .jpeg, .jpe, .tiff, .png, .gif" style="color: #337ab7;"></i></label>
                                <div class="form-group">
                                <input size='50' type='file' id="anexo_encerrar" name='anexo_encerrar' accept=".csv, .doc, .docx, .pdf, .ppt, .pptx, .rtf, .txt, .xls, .xlsx, .zip, .rar, .bmp, .jpg, .jpeg, .jpe, .tiff, .png, .gif">
                            </div>
                        </div>
                    </div>
                </div>

            <?php } ?>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" name="encerrar" id="encerrar_submit" value="<?= $id ?>" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
        </div>
        </div>
      </form>
    </div>
  </div>
</div> <!-- end modal -->

<!--Modal reabrir chamado-->
<div class="modal fade" id="modal_reabrir" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Reabrir chamado</h4>
      </div>

      <form method="post" action="/api/ajax?class=Chamado.php" id="chamado_reabrir" class='form-modal' style="margin-bottom: 0;">
        <input type="hidden" name="token" value="<?php echo $request->token ?>">
        <input type="hidden" name="id_responsavel" value="<?=$chamados[0]['id_usuario_responsavel']; ?>">
        <input type="hidden" name="visibilidade" value="<?=$chamados[0]['visibilidade']; ?>">

        <div class="modal-body">
          <div class="row">
              <div class="col-md-12">
                  <div class="form-group">
                      <label>*Justificativa:</label>
                      <textarea name="solucao" id="reabrir" class="form-control ckeditor conteudo" required></textarea>
                  </div>
              </div><!-- end col -->
          </div><!-- end row -->
          <div class="row">
              <div class="col-md-12">
                  <label>*Tempo(minutos):</label>
                  <input class="form-control input-sm number_int modal-tempo" name="tempo_reabrir" required />
              </div><!-- end col -->
          </div><!-- end row -->
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" name="reabrir" id="reabrir_submit" value="<?= $id ?>" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
        </div>

      </form>
    </div>
  </div>
</div> <!-- end modal -->

<!-- Modal Gerenciar envolvidos chamado -->
<div class="modal fade" id="modal_envolvidos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Gerenciar envolvidos</h4>
      </div>

      <form method="post" action="/api/ajax?class=Chamado.php" id="chamado_gerenciar_form" class='form-modal' style="margin-bottom: 0;">
        <input type="hidden" name="token" value="<?php echo $request->token ?>">
        <input type="hidden" name="visibilidade" id="id_visibilidade_gerenciar" value="<?=$chamados[0]['visibilidade'] ?>">
        <div class="modal-body">

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group some-container">
                                <label>*Visibilidade:</label>
                                <?php
                                $sel_visibilidade[$chamados[0]['visibilidade']] = 'selected';
                                ?>
                                <select class="form-control input-sm" id="id_visibilidade" name="id_visibilidade" data-toggle="tooltip" data-placement="top" title="Público - Todas pessoas do(s) setor(es) escolhido(s) terão acesso ao chamado. &#013; Privado - Somente a(s) pessoa(s) escolhida(s) terá(ão) acesso ao chamado." required />
                                    <option value='1' <?=$sel_visibilidade[1]?>>Público</option>
                                    <option value='2' <?=$sel_visibilidade[2]?>>Privado</option>
                                </select>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->

                    <div class="row">
                        <div class="col-md-12">

                        <div class="form-group">

                            <div id='container-perfil'>
                                <label>*Selecionar:</label><br />
                                <select class="js-example-basic-multiple chamado_perfil" name="perfil_sistema[]" multiple="multiple" id="select_perfil">
                                    <?php
                                    if($perfil_usuario == '3'){
                                        $dados_perfil_sistema = DBRead('', 'tb_perfil_sistema'," WHERE status = 1 AND  id_perfil_sistema != 19 AND id_perfil_sistema != 3 ORDER BY nome ASC");
                                    }else{
                                        $dados_perfil_sistema = DBRead('', 'tb_perfil_sistema'," WHERE status = 1 AND  id_perfil_sistema != 19 ORDER BY nome ASC");
                                    }
                                        if($dados_perfil_sistema){
                                            foreach($dados_perfil_sistema as $perfil_sistema){
                                                $id_perfil_sistema = $perfil_sistema['id_perfil_sistema'];
                                                $nome = $perfil_sistema['nome'];
                                                $ckecked = '';
                                                if($operacao == 'alterar'){
                                                    $dados = DBRead('', 'tb_chamado_perfil', "WHERE status = 1 AND  id_perfil_sistema = '$id_perfil_sistema' AND id_chamado = '$id'");
                                                    if($dados){
                                                        $ckecked = 'checked';
                                                    }
                                                }
                                                echo "<option value='$id_perfil_sistema'>$nome</option>";
                                            }
                                        }
                                        ?>
                                </select>
                            </div>
                        
                            <div id='container-usuarios'>
                                <label>*Selecionar:</label><br />
                                <select class="js-example-basic-multiple chamado_usuario" name="usuarios[]" multiple="multiple" id="select_usuario">
                                    <?php

                                        $dados_usuarios = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = 1 AND a.id_usuario != '".$id_usuario."' ORDER BY b.nome ASC");

                                        if($dados_usuarios){
                                            foreach($dados_usuarios as $conteudo){
                                                $id_usuario = $conteudo['id_usuario'];
                                                $nome = $conteudo['nome'];
                                                $ckecked = '';
                                                echo "<option value='$id_usuario'>$nome</option>";
                                            }
                                        }
                                        ?>
                                </select>
                            </div>
                        </div>

                        </div><!-- end col -->
                    </div><!-- end row -->

                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>*Justificativa:</label>
                                <textarea name="justificativa" id="justificativa_gerenciar" class="form-control ckeditor conteudo" required></textarea>
                            </div>
                        </div><!-- end col -->
                    </div><!-- end row -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>*Tempo(minutos):</label>
                                <input class="form-control input-sm number_int modal-tempo" id="tempo_gerenciar" name="tempo" required>
                            </div>
                        </div><!-- end col -->
                    </div><!-- end row -->  
                </div><!-- end col -->
            </div><!-- end row --> 
        </div><!-- modal body -->

        <div class="modal-footer">
            <div class="row">
                <div class="col-md-10">
                </div>
                <div class="col-md-2" style="text-align: center">
                    <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>" />
                    <button class="btn btn-primary" id="gerenciar_submit" name="gerenciar" value="<?= $id ?>" id="ok" type="submit"><i class="fa fa-floppy-o" aria-hidden="true"></i> Salvar</button>
                </div>
            </div>
        </div>

    </form>
    </div><!-- modal content -->
  </div><!-- modal dialog-->
</div><!-- end modal -->

<!-- Modal trocar responsavel chamado -->
<div class="modal fade" id="trocar_responsavel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Trocar responsável do chamado</h4>
      </div>

      <form enctype='multipart/form-data' id="chamado_trocar_responsavel_form" action='/api/ajax?class=Chamado.php' method='POST' class='form-modal' style="margin-bottom: 0;">
      <input type="hidden" name="token" value="<?php echo $request->token ?>">

      <div class="modal-body">

        <div class="row">
            <div class="col-md-12">
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-md-6">
                        <label>*Perfil:</label>
                        <select class="form-control input-sm" id="id_responsavel_perfil">
                            <option></option>
                            <?php
                                $perfis = DBRead('', 'tb_perfil_sistema', "WHERE status = 1 AND id_perfil_sistema != 19 ORDER BY nome");
                                
                                foreach($perfis as $conteudo){
                                    echo "<option value=".$conteudo['id_perfil_sistema'].">".$conteudo['nome']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>*Responsavel:</label>
                        <select class="form-control input-sm" id="id_responsavel_troca" name="id_responsavel_troca" required>
                            <option value=""></option>
                        </select>
                    </div>                
                </div><!-- end row -->
            </div><!-- end col -->
        </div><!-- end row -->
        <div class="row" style="margin-top: 10px;">
            <div class="col-md-12">
                <div class="form-group">
                    <label>*Justificativa:</label>
                    <textarea name="justificativa" id="justificativa" class="form-control ckeditor conteudo" required></textarea>
                </div>
            </div><!-- end col -->
        </div><!-- end row -->
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>*Tempo(minutos):</label>
                    <input class="form-control input-sm number_int" id="tempo" name="tempo" autocomplete="off" required>
                </div>
            </div><!-- end col -->
        </div><!-- end row -->

        <?php if($chamados[0]['id_chamado_origem'] == 4){ ?>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                <label>Anexar arquivo: <i class="fa fa-question-circle" data-toggle="tooltip" title="Formatos aceitos: .csv, .doc, .docx, .pdf, .ppt, .pptx, .rtf, .txt, .xls, .xlsx, .zip, .rar, .bmp, .jpg, .jpeg, .jpe, .tiff, .png, .gif" style="color: #337ab7;"></i></label>
                        <div class="form-group">
                        <input size='50' type='file' id="anexo_troca_responsavel" name='anexo_troca_responsavel' accept=".csv, .doc, .docx, .pdf, .ppt, .pptx, .rtf, .txt, .xls, .xlsx, .zip, .rar, .bmp, .jpg, .jpeg, .jpe, .tiff, .png, .gif">
                    </div>
                </div>
            </div>
        </div>

        <?php } ?>
        

      </div><!-- modal body -->

      <div class="modal-footer">
        <button class="btn btn-primary" name="trocaResponsavel" id="trocar_responsavel_submit" value="<?= $id ?>" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
      </div>

    </form>
    </div><!-- modal content -->
  </div><!-- modal dialog-->
</div><!-- end modal -->

<!-- Modal adicionar nota chamado -->
<div class="modal fade" id="modal_nota" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Adicionar nota</h4>
      </div>
      <!-- <form method="post" action="class/Chamado.php" id="chamado_nota_form" class='form-modal' style="margin-bottom: 0;"> -->
      <form enctype='multipart/form-data' id="chamado_nota_form" action='/api/ajax?class=Chamado.php' method='POST' class='form-modal'>
        <input type="hidden" name="token" value="<?php echo $request->token ?>">
        <div class="modal-body">
               
            <input type="hidden" name="id_responsavel" value="<?= $chamados[0]['id_usuario_responsavel']; ?>">
            <input type="hidden" name="visibilidade" value="<?=$chamados[0]['visibilidade']; ?>">

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>*Descrição:</label>
                        <textarea name="descricao_nota" id="nota" class="form-control ckeditor conteudo" required></textarea>
                    </div>
                </div><!-- end col -->
            </div><!-- end row -->

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>*Tempo(minutos):</label>
                        <input class="form-control input-sm number_int modal-tempo" name="tempo_nota" id="tempo_nota" autocomplete="off" required>
                    </div>
                </div><!-- end col -->
                <div class="col-md-6">
                    <div class="form-group">

                        <label>*Tipo de Visibilidade da Nota:</label>
                        <select class="form-control input-sm" name="tipo" id="tipo" required>
                            <option value='interno'>Somente Belluno</option>
                            <option value='geral'>Belluno e Cliente</option>
                        </select>
                    </div>
                </div><!-- end col -->
            </div><!-- end row -->
            
            <?php if($chamados[0]['id_chamado_origem'] == 4){ ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                    <label>Anexar arquivo: <i class="fa fa-question-circle" data-toggle="tooltip" title="Formatos aceitos: .csv, .doc, .docx, .pdf, .ppt, .pptx, .rtf, .txt, .xls, .xlsx, .zip, .rar, .bmp, .jpg, .jpeg, .jpe, .tiff, .png, .gif" style="color: #337ab7;"></i></label>
                            <div class="form-group">
                            <input size='50' type='file' id="anexo_nota" name='anexo_nota' accept=".csv, .doc, .docx, .pdf, .ppt, .pptx, .rtf, .txt, .xls, .xlsx, .zip, .rar, .bmp, .jpg, .jpeg, .jpe, .tiff, .png, .gif">
                        </div>
                    </div>
                </div>
            </div>

            <?php } ?>
       
        </div><!-- end modal body -->
        <div class="modal-footer">
          <button type="submit" name="nota" id="nota_submit" value="<?= $id; ?>" class="btn btn-primary">
            <i class="fa fa-floppy-o"></i> Salvar
          </button>
        </div>
      </form> 
    </div>
  </div>
</div> <!-- end modal -->

<!-- Modal desbloquear chamado -->
<div class="modal fade" id="modal_desbloquear" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Desbloquear chamado</h4>
      </div>
      <form method="post" action="/api/ajax?class=Chamado.php" id="chamado_desbloquear_form" class='form-modal' style="margin-bottom: 0;">
        <input type="hidden" name="token" value="<?php echo $request->token ?>">
        <div class="modal-body">

            <input type="hidden" name="id_responsavel" value="<?= $chamados[0]['id_usuario_responsavel']; ?>">
            <input type="hidden" name="visibilidade" value="<?=$chamados[0]['visibilidade']; ?>">
               
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>*Justificativa:</label>
                        <textarea name="descricao_desbloquear" id="desbloquear" class="form-control ckeditor conteudo" required></textarea>
                    </div>
                </div><!-- end col -->
            </div><!-- end row -->

            <div class="row">
                <div class="col-md-12">
                    <label>*Tempo(minutos):</label>
                    <input class="form-control input-sm number_int modal-tempo" id="tempo_desbloquear" name="tempo_desbloquear" autocomplete="off" required>
                </div><!-- end col -->
            </div><!-- end row -->
       
        </div><!-- end modal body -->
        <div class="modal-footer">
          <button type="submit" name="desbloquear" id="desbloquear_submit" value="<?= $id; ?>" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Salvar</button>
        </div>
      </form> 
    </div>
  </div>
</div> <!-- end modal -->

<!-- Modal bloquear chamado -->
<div class="modal fade" id="modal_bloquear" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Bloquear chamado</h4>
      </div>
      <form method="post" action="/api/ajax?class=Chamado.php" id="chamado_bloquear_form" class='form-modal' style="margin-bottom: 0;">
        <input type="hidden" name="token" value="<?php echo $request->token ?>">
        <div class="modal-body">

            <input type="hidden" name="id_responsavel" value="<?= $chamados[0]['id_usuario_responsavel']; ?>">
            <input type="hidden" name="visibilidade" value="<?=$chamados[0]['visibilidade']; ?>">
               
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>*Justificativa:</label>
                        <textarea name="descricao_bloquear" id="bloquear" class="form-control ckeditor conteudo" required></textarea>
                    </div>
                </div><!-- end col -->
            </div><!-- end row -->

            <div class="row">
                <div class="col-md-12">
                    <label>*Tempo(minutos):</label>
                    <input class="form-control input-sm number_int modal-tempo" id="tempo_bloquear" name="tempo_bloquear" autocomplete="off" required>
                </div><!-- end col -->
            </div><!-- end row -->
       
        </div><!-- end modal body -->
        <div class="modal-footer">
          <button type="submit" name="bloquear" id="bloquear_submit" value="<?= $id; ?>" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Salvar</button>
        </div>
      </form> 
    </div>
  </div>
</div> <!-- end modal -->

<!-- Modal pendencia chamado -->
<div class="modal fade" id="modal_pendencia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Inserir pendência</h4>
      </div>
      <form method="post" action="/api/ajax?class=Chamado.php" id="chamado_pendencia_form" class='form-modal' style="margin-bottom: 0;">
        <input type="hidden" name="token" value="<?php echo $request->token ?>">
        <div class="modal-body">
            
            <input type="hidden" id="getdatahora" value="<?= getDataHora() ?>"/>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>*Data:</label>
                        <input type="text" class="form-control input-sm date calendar hasDatepicker" id="data_pendencia" name="data" autocomplete="off" value="" required>
                    </div>
                </div><!-- end col -->

                <div class="col-md-6">
                    <div class="form-group">
                        <label>*Hora:</label>
                        <input type="time" class="form-control input-sm" id="hora_pendencia" value="" name="hora" autocomplete="off" required>
                    </div>
                </div><!-- end col -->
            </div><!-- end row -->

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>*Descrição:</label>
                        <textarea name="descricao_pendencia" id="pendencia" class="form-control ckeditor conteudo" required></textarea>
                    </div>
                </div><!-- end col -->
            </div><!-- end row -->

            <div class="row">
                <div class="col-md-12">
                    <label>*Tempo(minutos):</label>
                    <input class="form-control input-sm number_int modal-tempo" id="tempo_pendencia" name="tempo_pendencia" autocomplete="off" required>
                </div><!-- end col -->
            </div><!-- end row -->
  
        </div><!-- end modal body -->
        <div class="modal-footer">
          <button type="submit" name="pendencia" id="pendencia_submit" value="<?= $id; ?>" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Salvar</button>
        </div>
      </form> 
    </div>
  </div>
</div> <!-- end modal -->

<!-- Modal alterar chamado -->
<div class="modal fade" id="modal_alterar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Alterar chamado</h4>
            </div>
            <form method="post" action="/api/ajax?class=Chamado.php" id="chamado_alterar_form" class='form-modal' style="margin-bottom: 0;">
                <input type="hidden" name="token" value="<?php echo $request->token ?>">    
                <div class="modal-body">
                <!-- ______________________________AQUI______________________________ -->
                <?php 
                if ($chamados[0]['id_chamado_origem'] != 4){ ?>
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label id="id_contrato">Contrato (cliente)</label>
                                <div class="input-group">
                                    <input class="form-control input-sm ui-autocomplete-input" id="busca_contrato" type="text" name="busca_contrato" value="<?= $contrato ?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly="">

                                    <div class="input-group-btn">
                                        <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                                <input type="hidden" name="id_contrato_plano_pessoa" id="id_contrato_plano_pessoa" value="<?=$contrato_plano_pessoa[0]['id_contrato_plano_pessoa']?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>*Origem:</label>
                                <select class="form-control input-sm" id="id_origem" name="id_origem" required>
                                    <option value=""></option>
                                    <?php
                                        $dados_origem = DBRead('', 'tb_chamado_origem', "WHERE id_chamado_origem != 1 AND id_chamado_origem != 4 ORDER BY descricao ASC");
                                        if($dados_origem){
                                            foreach($dados_origem as $origem){
                                                $idOrigem = $origem['id_chamado_origem'];
                                                $nomeSelect = $origem['descricao'];
                                                $selected = $id_origem == $idOrigem ? "selected" : "";
                                                echo "<option value='$idOrigem'".$selected.">$nomeSelect</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class='col-md-12'>
                            <div class="form-group">
                                <label>                       
                                    <div id="responsaveis"><?php echo $texto_contrato; ?></div>
                                </label>
                            </div>
                        </div>
                       
                    </div>
                    
                <?php 
                }else{
                    echo '
                    <input type="hidden" name="id_contrato_plano_pessoa" id="id_contrato_plano_pessoa" value="'.$contrato_plano_pessoa[0]['id_contrato_plano_pessoa'].'">
                    <input type="hidden" name="id_origem" id="id_origem" value="4">';
                } ?>

                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>*Categoria:</label>
                                <select class="js-example-basic-multiple categorias  form-control" id="id_categoria" name="id_categoria[]">
                                    <?php
                                        $dados_categoria = DBRead('', 'tb_categoria', "WHERE exibe_chamado = 1 ORDER BY nome ASC");
                                        if($dados_categoria){
                                            foreach($dados_categoria as $categoria){
                                                $idCategoria = $categoria['id_categoria'];
                                                $nomeSelect = $categoria['nome'];
                                                echo "<option value='$idCategoria'>$nomeSelect</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
               
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>*Justificativa:</label>
                                <textarea name="justificativa_alterar" id="alterar" class="form-control ckeditor conteudo" required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>*Tempo(minutos):</label>
                                <input class="form-control input-sm number_int modal-tempo" id="tempo_alterar" name="tempo_alterar" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
            
                </div>

                <div class="modal-footer">
                    <button type="submit" name="alterar" id="alterar_submit" value="<?= $id; ?>" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div> <!-- end modal -->

<form method="post" action="/api/ajax?class=Chamado.php" id="assumir_chamado_form" style="margin-bottom: 0; margin-left: 0px;">
    <input type="hidden" name="token" value="<?php echo $request->token ?>">
    <input type='hidden' name="assumirChamado" value="<?= $id ?>">
</form>

<?php
    //consulta para testar os marcados
    $dados_categorias = DBRead('', 'tb_chamado_categoria', "WHERE id_chamado = '".$id."'");

    if($chamados[0]['visibilidade'] == 1){
        $dados_perfis_select2 = DBRead('', 'tb_chamado_perfil', "WHERE id_chamado = '".$id."'");
    }else if($chamados[0]['visibilidade'] == 2){
        $dados_usuarios_select2 = DBRead('', 'tb_chamado_usuario', "WHERE id_chamado = '".$id."'");
    }
?>

<script type="text/javascript" src="https://yandex.st/jquery/mousewheel/3.0.6/jquery.mousewheel.min.js"></script>
<script src="inc/ckeditor/ckeditor.js"></script>
<script>

    $('.btn-assumir').on('click', function(e){
        var confirmacao = confirm("Você se tornará responsável por este chamado!");
        if(confirmacao == false){
            return false;
        }
        $('#assumir_chamado_form').submit();
        modalAguarde();
    });

    //window.onbeforeunload = areYouSure;
    
    select2Perfil = $('.chamado_perfil').select2();
    select2Usuario = $('.chamado_usuario').select2();
    
    function inserePerfis(){
        select2Perfis = $('.chamado_perfil').select2();
        //verifica quais estão marcados
        dadosCampoPerfisJson = <?php echo json_encode($dados_perfis_select2) ?>;
        dadosCampoPerfisArray = [];
        dadosCampoPerfisJson.forEach(function(i){
            dadosCampoPerfisArray.push(i.id_perfil_sistema);
        });
        select2Perfis.val(dadosCampoPerfisArray).trigger("load");
    }
    
    function insereUsuarios(){
        select2Usuarios = $('.chamado_usuario').select2();
        //verifica quais estão marcados
        dadosCampoUsuariosJson = <?php echo json_encode($dados_usuarios_select2) ?>;
        dadosCampoUsuariosArray = [];
        dadosCampoUsuariosJson.forEach(function(i){
            dadosCampoUsuariosArray.push(i.id_usuario);
        });
        select2Usuarios.val(dadosCampoUsuariosArray).trigger("load");
    }

    <?php
        if($chamados[0]['visibilidade'] == 1){
            echo "inserePerfis();";
        }else if($chamados[0]['visibilidade'] == 2){
            echo "insereUsuarios();";
        }
    ?>

    $('#modal_envolvidos').on('shown.bs.modal', function(e){
        if($('#id_visibilidade').val() == '1'){
            inserePerfis();
        }else{
            insereUsuarios();
        }
    });

    //função que insere as categorias no select2
    function insereCategorias(){
        select2Categorias = $('.categorias').select2();
        //verifica quais estão marcados
        dadosCampoCategoriaJson = <?php echo json_encode($dados_categorias) ?>;
        dadosCampoCategoriaArray = [];
        dadosCampoCategoriaJson.forEach(function(i){
            dadosCampoCategoriaArray.push(i.id_categoria);
        });
        select2Categorias.val(dadosCampoCategoriaArray).trigger("load");
    }

    insereCategorias();

    $('#modal_alterar').on('shown.bs.modal', function(e){
        insereCategorias();
    });

    $(function(){
        $('[data-toggle="tooltip"]').tooltip({ boundary: 'window' })
    })

    $(function(){
        $('[data-toggle="popover"]').popover();
    })

    $(document).on({'show.bs.modal': function(){
        $(this).removeAttr('tabindex');
    } }, '.modal');

    $("#busca_contrato").css("z-index", "3500");

    CKEDITOR.replace('pendencia', {
        height: 200
    });

    CKEDITOR.replace('nota', {
        height: 200
    });

    CKEDITOR.replace('justificativa', {
        height: 250
    });
    
    CKEDITOR.replace('justificativa_gerenciar', {
        height: 250
    });

    CKEDITOR.replace('solucao', {
        height: 300
    });

    CKEDITOR.replace('desbloquear', {
        height: 300
    });

    CKEDITOR.replace('bloquear', {
        height: 300
    });

    CKEDITOR.replace('reabrir', {
        height: 300
    });

    CKEDITOR.replace('alterar', {
        height: 250
    });

    CKEDITOR.replace('alteracao_prazo', {
        height: 250
    });

    $(document).ready(function() {
        var count = $('#count').val();
        window.location.href = '#'+count;

        $(".timeline1").mousewheel(function(event, delta){
            this.scrollLeft -= (delta * 30);
            event.preventDefault();
        });
    });

    $(document).on('keyup', '#busca_item_time_line', function(){
        var inicia_busca = 1;
        var descricao = $(this).val();
        if (descricao.length < inicia_busca && descricao.length >=1){
            return false;
        }
        $.ajax({
            url: "/api/ajax?class=ChamadoTimelineBusca.php",            
            type: "POST",
            dataType: "json",
            data: {
                parametros: { 
                    'descricao' : descricao
                },
                token: '<?= $request->token ?>'
            },
            success: function (data) {
                var cont = 0;
                $('.timeline-item').each(function(){
                    var item = $(this).attr('id');
                    if(jQuery.inArray(item, data ) >= 0){
                        $(this).show();
                        cont++;
                    }else{
                        $(this).hide();
                    }
                });
                if(cont == 0){
                    $('#alert-timeline').html('Nenhum resultado encontrado na busca por "<strong>'+descricao+'</strong>"');
                    $('#alert-timeline').show();
                }else{
                    $('#alert-timeline').html('');
                    $('#alert-timeline').hide();
                }
            }
        });
    });

    $(document).on('submit', '#chamado_trocar_responsavel_form', function(){
        
        $('#trocar_responsavel_submit').hide();
        
        var tempo = $("#tempo").val();
        var id_responsavel = $('#id_responsavel').val();
        var justificativa = $('#justificativa').val();

        if(id_responsavel == ""){
            alert('Informe um responsavel!');
            $('#trocar_responsavel_submit').show();
            return false;
        }

        if(justificativa == ""){
            alert('Informe uma justificativa!');            
            $('#trocar_responsavel_submit').show();
            return false;
        }

        if(!tempo || tempo == '' || tempo <= 0 || tempo > 99999){
            alert("Deve-se inserir um tempo válido! (Max - 99999/ Min - 0)");
            $('#trocar_responsavel_submit').show();
            return false;
        }

        //________________________________________________________________________________MEU
        if($('#anexo_troca_responsavel').val()){
            var values = $('#anexo_troca_responsavel').val();

            var formato = values.split(".");

            if (values.length > 0 && formato[1] != 'csv'  && formato[1] != 'doc'  && formato[1] != 'docx'  && formato[1] != 'pdf'  && formato[1] != 'ppt'  && formato[1] != 'pptx'  && formato[1] != 'rtf'  && formato[1] != 'txt'  && formato[1] != 'xls'  && formato[1] != 'xlsx'  && formato[1] != 'zip'  && formato[1] != 'rar'  && formato[1] != 'bmp'  && formato[1] != 'jpg'  && formato[1] != 'jpeg'  && formato[1] != 'jpe'  && formato[1] != 'tiff'  && formato[1] != 'png' && formato[1] != 'gif'){
                alert ('Formato inválido! Você pode enviar apenas arquivos .csv, .doc, .docx, .pdf, .ppt, .pptx, .rtf, .txt, .xls, .xlsx, .zip, .rar, .bmp, .jpg, .jpeg, .jpe, .tiff, png ou .gif!');
                return false;
            }
        }
        
        //________________________________________________________________________________MEU
      
        modalAguarde();
    });

    $(document).on('submit', '#chamado_gerenciar_form', function(){
        
        $('#gerenciar_submit').hide();
        
        var tempo = $("#tempo_gerenciar").val();
        var justificativa = $('#justificativa_gerenciar').val();
        var visibilidade = $('#id_visibilidade').val();

        var cont = 0;

        if(justificativa == ""){
            alert('Informe uma justificativa!');
            $('#gerenciar_submit').show();
            return false;
        }

        if(!tempo || tempo == '' || tempo <= 0 || tempo > 99999){
            alert("Deve-se inserir um tempo válido! (Max - 99999/ Min - 0)");
            $('#gerenciar_submit').show();
            return false;
        }
      
        modalAguarde();
    }); 
    
    $(document).on('submit', '#chamado_nota_form', function(){       

        var tempo = $("#tempo_nota").val();
        var descricao_nota = $("#nota").val();
        var tipo = $("#tipo").val();

        //________________________________________________________________________________MEU
        if($('#anexo_nota').val()){
            var values = $('#anexo_nota').val();

            var formato = values.split(".");

            if (values.length > 0 && formato[1] != 'csv'  && formato[1] != 'doc'  && formato[1] != 'docx'  && formato[1] != 'pdf'  && formato[1] != 'ppt'  && formato[1] != 'pptx'  && formato[1] != 'rtf'  && formato[1] != 'txt'  && formato[1] != 'xls'  && formato[1] != 'xlsx'  && formato[1] != 'zip'  && formato[1] != 'rar'  && formato[1] != 'bmp'  && formato[1] != 'jpg'  && formato[1] != 'jpeg'  && formato[1] != 'jpe'  && formato[1] != 'tiff'  && formato[1] != 'png' && formato[1] != 'gif'){
                alert ('Formato inválido! Você pode enviar apenas arquivos .csv, .doc, .docx, .pdf, .ppt, .pptx, .rtf, .txt, .xls, .xlsx, .zip, .rar, .bmp, .jpg, .jpeg, .jpe, .tiff, png ou .gif!');
                return false;
            }
        }
       
        //________________________________________________________________________________MEU
        
        if(tipo == "geral"){
            var confirmacao = confirm("Você está adicionando uma nota para o cliente!");
            if(confirmacao == false){
                $('#nota_submit').show();
                return false;
            }
        }
        if(descricao_nota == ""){
            alert('Informe a descrição da nota!');
            $('#nota_submit').show();
            return false;
        }

        if(!tempo || tempo == '' || tempo <= 0 || tempo > 99999){
            alert("Deve-se inserir um tempo válido! (Max - 99999/ Min - 0)");
            $('#nota_submit').show();
            return false;
        }
        $('#nota_submit').hide();
        modalAguarde();

    });

    $(document).on('submit', '#chamado_encerrar_form', function(){
        
        $('#encerrar_submit').hide();
        
        var tempo = $("#tempo_encerramento").val();
        var solucao = $('#solucao').val();

        if(solucao == ""){
            alert('Informe a solução do chamado!');
            $('#encerrar_submit').show();
            return false;
        }

        if(!tempo || tempo == '' || tempo <= 0 || tempo > 99999){
            alert("Deve-se inserir um tempo válido! (Max - 99999/ Min - 0)");
            $('#encerrar_submit').show();
            return false;
        }

        //________________________________________________________________________________MEU
        if($('#anexo_encerrar').val()){
            var values = $('#anexo_encerrar').val();

            var formato = values.split(".");

            if (values.length > 0 && formato[1] != 'csv'  && formato[1] != 'doc'  && formato[1] != 'docx'  && formato[1] != 'pdf'  && formato[1] != 'ppt'  && formato[1] != 'pptx'  && formato[1] != 'rtf'  && formato[1] != 'txt'  && formato[1] != 'xls'  && formato[1] != 'xlsx'  && formato[1] != 'zip'  && formato[1] != 'rar'  && formato[1] != 'bmp'  && formato[1] != 'jpg'  && formato[1] != 'jpeg'  && formato[1] != 'jpe'  && formato[1] != 'tiff'  && formato[1] != 'png' && formato[1] != 'gif'){
                alert ('Formato inválido! Você pode enviar apenas arquivos .csv, .doc, .docx, .pdf, .ppt, .pptx, .rtf, .txt, .xls, .xlsx, .zip, .rar, .bmp, .jpg, .jpeg, .jpe, .tiff, png ou .gif!');
                return false;
            }
        }
        
        //________________________________________________________________________________MEU
      
        modalAguarde();
    });

    $(document).on('submit', '#chamado_desbloquear_form', function(){
        
        $('#desbloquear_submit').hide();
        
        var tempo = $("#tempo_desbloquear").val();
        var descricao = $('#desbloquear').val();

        if(descricao == ""){
            alert('Informe uma justificava!');
            $('#desbloquear_submit').show();
            return false;
        }

        if(!tempo || tempo == '' || tempo <= 0 || tempo > 99999){
            alert("Deve-se inserir um tempo válido! (Max - 99999/ Min - 0)");
            $('#desbloquear_submit').show();
            return false;
        }
      
        modalAguarde();
    });

    $(document).on('submit', '#chamado_bloquear_form', function(){
        
        $('#bloquear_submit').hide();
        
        var tempo = $("#tempo_bloquear").val();
        var descricao = $('#bloquear').val();

        if(descricao == ""){
            alert('Informe a justificativa!');
            $('#bloquear_submit').show();
            return false;
        }

        if(!tempo || tempo == '' || tempo <= 0 || tempo > 99999){
            alert("Deve-se inserir um tempo válido! (Max - 99999/ Min - 0)");
            $('#bloquear_submit').show();
            return false;
        }
      
        modalAguarde();
    });
    
    $(document).on('submit', '#chamado_reabrir_form', function(){
        
        $('#reabrir_submit').hide();
        
        var tempo = $("#tempo_reabrir").val();
        var descricao = $('#reabrir').val();

        if(descricao == ""){
            alert('Informe a justificativa!');
            $('#reabrir_submit').show();
            return false;
        }

        if(!tempo || tempo == '' || tempo <= 0 || tempo > 99999){
            alert("Deve-se inserir um tempo válido! (Max - 99999/ Min - 0)");
            $('#reabrir_submit').show();
            return false;
        }
      
        modalAguarde();
    });

    $(document).on('submit', '#chamado_pendencia_form', function(){
        
        $('#pendencia_submit').hide();
        
        var data = $("input[name=data]").val();
        var hora = $('#hora_pendencia').val();
        var getdatahora = $('#getdatahora').val();
        var descricao = $('#pendencia').val();
        var tempo = $('#tempo_pendencia').val();
        var data_pendencia = data + hora;

        data_pendencia = data_pendencia[6]+data_pendencia[7]+data_pendencia[8]+data_pendencia[9]+"-"+data_pendencia[3]+data_pendencia[4]+"-"+data_pendencia[0]+data_pendencia[1]+" "+data_pendencia[10]+data_pendencia[11]+data_pendencia[12]+data_pendencia[13]+data_pendencia[14]+":00";

        if(data_pendencia <= getdatahora){
            alert('Data da pendência inválida! Informe uma data maior!');
            $('#pendencia_submit').show();
            return false;
        }

        if(descricao == ""){
            alert("Informe uma descrição");
            $('#pendencia_submit').show();
            return false;
        }

        if(!tempo || tempo == '' || tempo <= 0 || tempo > 99999){
            alert("Deve-se inserir um tempo válido! (Max - 99999/ Min - 0)");
            $('#pendencia_submit').show();
            return false;
        }
        
        modalAguarde();
    });

    $(document).on('submit', '#chamado_alterar_form', function(){
        
        $('#alterar_submit').hide();

        var tempo = $("#tempo_alterar").val();
        var justificativa = $('#alterar').val();
        var id_categoria = $('#id_categoria').val();
        var id_origem = $('#id_origem').val();
        var id_contrato_plano_pessoa = $('#id_contrato_plano_pessoa').val();
        var nome_contrato = $('#busca_contrato').val();

        if(id_contrato_plano_pessoa != "" && id_origem != '4'){
            if(!confirm('Contrato escolhido: '+nome_contrato+'. Deseja continuar?')) {
                $('#alterar_submit').show();
                return false;
            }
        }

        if(justificativa == ""){
            alert('Informe a justificativa!');
            $('#alterar_submit').show();
            return false;
        }

        if(id_categoria == ""){
            alert('Informe a categoria!');
            $('#alterar_submit').show();
            return false;
        }

        if(id_origem == ""){
            alert('Informe a origem!');
            $('#alterar_submit').show();
            return false;
        }

        if(!tempo || tempo == '' || tempo <= 0 || tempo > 99999){
            alert("Deve-se inserir um tempo válido! (Max - 99999/ Min - 0)");
            $('#alterar_submit').show();
            return false;
        }
      
        modalAguarde();
    });

    $("#anterior").on("click", function(){
        id = $("#anterior").attr("id_anterior");
        preencheModal(id);
    });

    $("#proximo").on("click", function(){
        id = $("#proximo").attr("id_proximo");
        preencheModal(id);
    });

    //busca as acoes e suas informacoes
    function preencheModal(id){

        //////// Bloco responsável pelo comportamento dos botões "Anterior" e "Próximo" das notas do chamado. ////////////////////
        var itens = [];
        $(".item-acao").each(function(i, e){
            itens.push($(this).attr("id_acao"));
        });

        $.each(itens, function(i){
            if(itens.length > 1){
                if(itens[i] == id){
                    $("#anterior").attr("id_anterior", itens[parseInt(i) - parseInt(1)]);
                    $("#proximo").attr("id_proximo", itens[parseInt(i) + parseInt(1)]);
                    if(itens[parseInt(i) - parseInt(1)] == undefined){
                        $("#anterior").attr("disabled", true);
                        $("#proximo").attr("disabled", false);
                    }else if(itens[parseInt(i) + parseInt(1)] == undefined){
                        $("#anterior").attr("disabled", false);
                        $("#proximo").attr("disabled", true);
                    }else{
                        $("#anterior").attr("disabled", false);
                        $("#proximo").attr("disabled", false);
                    }
                }
            }else{
                $("#anterior").attr("disabled", true);
                $("#proximo").attr("disabled", true);
            }
        });
        //////////////////////////////////////////////////////////////////////////////////////////////////////

       function call_busca_ajax(id){
           busca_ajax('<?= $request->token ?>' , 'class/ChamadoModal', 'conteudo', id);
       }

       $('#i_exclamation_'+id).remove();
       $('#myModalLabel3').text($('#acao_title_'+id).text());
       call_busca_ajax(id);
       $('#myModal3').modal('show');
    };

    //verifica botao da notificacao
    $(document).ready(function(){
      var notificacao = <?php echo $chamado_notificacao[0]['contador']; ?>;

      if(notificacao == 1){
        $("#btn-notificacao").addClass("btn btn-xs btn-danger").html("<i class='fa fa-bell-slash-o'></i> Não recebendo notificações");
      }else if(notificacao == 0){
        $("#btn-notificacao").addClass("btn btn-xs btn-primary").html("<i class='fa fa-bell'></i> Recebendo notificações");
      }
    });

    //botao para deixar de receber notificacoes do chamado
    $('#btn-notificacao').on('click', function(){
        var obj = $(this);

        var id_chamado = $("#id_chamado").val();

        $.ajax({
            type: "POST",
            url: "/api/ajax?class=ChamadoNotificacao.php",
            dataType: "json",
            data: {
                id_chamado: id_chamado,
                token: '<?= $request->token ?>'
            },
            
            success: function(data){
                if(data == 'false'){
                    obj.removeClass('btn-primary').addClass('btn-danger').html('<i class="fa fa-bell-slash-o"></i> Não recebendo notificações');
                    
                }else if(data == 'true'){
                    obj.removeClass('btn-danger').addClass('btn-primary').html('<i class="fa fa-bell"></i> Recebendo notificações');
                }
            }
        });
    });

    //marca checkbox de acordo com a visibilidade
    $("#container-usuarios").hide();

    $("#id_visibilidade").on('change', function(){

        var selecionado = $('#id_visibilidade').find(":selected").val();
        if(selecionado == 1){

            $("#container-perfil").show();
            $("#container-usuarios").hide();

            $('#select_perfil').prop('required',true);
            $('#select_usuario').prop('required',false);

            $('.chamado_perfil').each(function(){
                $('#id_visibilidade').on('change', function(){
                    $('#container-perfil').val(null).trigger("change");
                });
            });

        }else if(selecionado == 2){
            
            $("#container-usuarios").show();
            $("#container-perfil").hide();
            $('#select_usuario').prop('required',true);
            $('#select_perfil').prop('required',false);
            
            $('.chamado_usuario').each(function(){
                $('#id_visibilidade').on('change', function(){
                    $('#container-usuarios').val(null).trigger("change");
                });
            });
        }
    });

    //esconde lista perfil se visibilidade for privado
    if($("#id_visibilidade option:selected").val() == 2){
        $("#container-usuarios").show();
        $("#container-perfil").hide();
        $('#select_usuario').prop('required',true);
    }

    if($("#id_visibilidade option:selected").val() == 1){
        $('#select_perfil').prop('required',true);
    }
    //
    
    //buscas usuarios de acordo com o perfil
    $("#id_responsavel_perfil").on('change', function(){

        var id_perfil = $("#id_responsavel_perfil option:selected").val();
        $("#id_responsavel_troca").empty();
    
        var id_responsavel_chamado = $("#id_responsavel_chamado").val();

        $.ajax({
            type: "POST",
            url: "/api/ajax?class=SelectResponsavel.php",
             dataType: "json",
            data: {
                id_perfil: id_perfil,
                id_responsavel_chamado: id_responsavel_chamado,
                token: '<?= $request->token ?>'
            },
            success: function(data){
                $("#id_responsavel_troca").html(data['dados']);
            }
        });
    });

    // Atribui evento e função para limpeza dos campos
    $('#busca_contrato').on('input', limpaCamposContrato);

    // Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_contrato").autocomplete({
            minLength: 2,
            source: function(request, response){
                $.ajax({
                    url: "/api/ajax?class=ContratoAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'autocompleteresponsavelcontrato',
                        parametros: { 
                            'nome' : $('#busca_contrato').val(),
                            'pagina' : 'chamado-form'
                        },
                        token: '<?= $request->token ?>'
                    },
                    success: function (data) {
                        response(data);

                    }
                });
            },
            focus: function (event, ui) {
                $("#busca_contrato").val(ui.item.nome + " " + ui.item.nome_contrato +" - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
                carregarDadosContrato(ui.item.id_contrato_plano_pessoa);
                return false;
            },
            select: function (event, ui) {
                $("#busca_contrato").val(ui.item.nome + " "+ ui.item.nome_contrato + " - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
                $('#busca_contrato').attr("readonly", true);

                var texto_contrato = $("#id_contrato").text();
                $("#responsaveis").html('Responsável pelo Relacionamento: '+ui.item.nome_responsavel+'<br>Responsável Técnico: '+ui.item.nome_responsavel_tecnico);

                return false;
            }
        })
    .autocomplete("instance")._renderItem = function(ul, item){

        ul.css({"z-index": "10000"});

        if(!item.razao_social){
            item.razao_social = '';
        }
        if(!item.cpf_cnpj){
            item.cpf_cnpj = '';
        }
        if(!item.nome_contrato){
            item.nome_contrato = '';
        }else{
            item.nome_contrato = ' ('+item.nome_contrato+') '; 
        }
        return $("<li>").append("<a><strong>"+item.id_contrato_plano_pessoa + " - " + item.nome + ""+item.nome_contrato+"</strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "<br>" + item.servico + " - " + item.plano + " (" + item.id_contrato_plano_pessoa + ")" + "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
    };

    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosContrato(id){
        var busca = $('#busca_contrato').val();
        if(busca != "" && busca.length >= 2){
            $.ajax({
                url: "/api/ajax?class=ContratoAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta',
                    parametros: {
                        'id' : id,
                    },
                    token: '<?= $request->token ?>'
                },
                success: function(data){
                    $('#id_contrato_plano_pessoa').val(data[0].id_contrato_plano_pessoa);
                }
            });
        }
    }

    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposContrato(){
        var busca = $('#busca_contrato').val();
        if (busca == ""){
            $('#id_contrato_plano_pessoa').val('');
        }
    }

    $(document).on('click', '#habilita_busca_contrato', function (){
        $('#id_contrato_plano_pessoa').val('');
        $('#busca_contrato').val('');
        $('#busca_contrato').attr("readonly", false);
        $('#busca_contrato').focus();
    });

</script>

