<?php
    require_once(__DIR__."/../class/System.php");

    $id_candidato = (int)$_GET['idcandidato'];
    $id_selecao = (int)$_GET['idselecao'];

    $dados = DBRead('', 'tb_pessoa a', "INNER JOIN tb_pessoa_rh_dados_pessoais b ON a.id_pessoa = b.id_pessoa WHERE a.id_pessoa = $id_candidato");

    $id_pessoa = $dados[0]['id_pessoa'];

    $dados_selecao = DBRead('', 'tb_selecao', "WHERE id_selecao = $id_selecao");
    $dados_candidato = DBRead('', 'tb_selecao_candidato', "WHERE id_pessoa_candidato = $id_candidato AND id_selecao = $id_selecao");
    $dados_etapa = DBRead('', 'tb_selecao_etapa', "WHERE id_selecao = $id_selecao AND num_etapa = '".$dados_candidato[0]['etapa']."' ");
    $n_etapa = $dados_candidato[0]['etapa'];
    $id_selecao_candidato = $dados_candidato[0]['id_selecao_candidato'];
    $id_selecao_etapa = $dados_etapa[0]['id_selecao_etapa'];

    $dados_etapa_avaliador = DBRead('', 'tb_selecao_etapa_avaliador', "WHERE id_selecao_etapa = $id_selecao_etapa");

    $cont = sizeof($dados_etapa_avaliador);

    $verifica_avaliador = 0; //se é um avaliador da etapa
    $verifica_avaliacao = 0; //se ja avaliou
    $cont_avaliacao = 0; //quantas avaliou
    $check = 0;
    foreach ($dados_etapa_avaliador as $conteudo_etapa_avaliador) {
        
        $id_selecao_etapa_avaliador = $conteudo_etapa_avaliador['id_selecao_etapa_avaliador'];

        $verifica_avaliacao = DBRead('', 'tb_selecao_avaliador_candidato', "WHERE id_selecao_etapa_avaliador = $id_selecao_etapa_avaliador AND id_selecao_candidato = $id_selecao_candidato");

        if ($conteudo_etapa_avaliador['id_usuario_avaliador'] == $_SESSION['id_usuario']) {
            $verifica_avaliador++;
            
            if ($verifica_avaliacao) {
                $check = 1; //avaliador da etapa ja avaliou
            }
        }

        if ($verifica_avaliacao) {
            $cont_avaliacao++;
        }
    }

    /* $verifica_avaliacao_usuario = '';
    if ($cont == $cont_avaliacao) {
        $check = 2; //todos ja avaliaram
    } else {

        $resultado = $cont - $cont_avaliacao;

        if ($resultado == 1) {
            $verifica_avaliacao_usuario = DBRead('', 'tb_selecao_avaliador_candidato', "WHERE id_selecao_etapa_avaliador = $id_selecao_etapa_avaliador");

            if (!$verifica_avaliacao_usuario) {
                //echo 'falta essa avaliação!';
            }
        }
    } */

    //if (($verifica_avaliador != 0 && $cont == 1) || ($resultado == 1 && $verifica_avaliacao_usuario == '')) {
    
    if ($verifica_avaliador) {
        
        if ($dados_selecao[0]['n_etapas'] == $dados_candidato[0]['etapa']) {
            $style_aprovado = "style='display: block'";
            $style_prox_etapa = "style='display: none'";
            $style_reprovado = "style='display: block'";
            $style_em_selecao = "style='display: block'";
            $style_pre_aprovado = "style='display: block'";
            $style_compareceu = "";
        } else {
            $style_aprovado = "style='display: none'";
            $style_prox_etapa = "style='border-top-right-radius: 6px; border-bottom-right-radius: 6px; display:block'";
            $style_reprovado = "style='display: block'";
            $style_em_selecao = "style='display: block'";
            $style_pre_aprovado = "style='display: block'";
        }
    } else {
        $style_aprovado = "style='display: none'";
        $style_reprovado = "style='display: none'";
        $style_prox_etapa = "style='display: none'";
        $style_compareceu = "style='border-top-right-radius: 6px; border-bottom-right-radius: 6px;'";
        $style_pre_aprovado = "style='display: block'";
    }

    $dados_avaliador_candidato = DBRead('', 'tb_selecao_avaliador_candidato a', "INNER JOIN tb_selecao_etapa_avaliador b ON a.id_selecao_etapa_avaliador = b.id_selecao_etapa_avaliador INNER JOIN tb_selecao_etapa c ON b.id_selecao_etapa = c.id_selecao_etapa INNER JOIN tb_usuario d ON b.id_usuario_avaliador = d.id_usuario INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa WHERE id_selecao_candidato = '".$dados_candidato[0]['id_selecao_candidato']."' ", 'a.*, b.*, c.*, e.nome');

    $class_primary = 'btn-default';
    $class_success = 'btn-default';
    $class_danger = 'btn-default';
    $class_warning = 'btn-default';
    $class_info = 'btn-default';

    if ($dados_candidato[0]['status'] == 1) {
        $status = '<span class="label label-primary" style="font-size: 13px; min-width: 110px; display: inline-block;">Em seleção</span>';
        $class_primary = 'btn-primary';

    } else if ($dados_candidato[0]['status'] == 2) {
        $status = '<span class="label label-success" style="font-size: 13px; min-width: 110px; display: inline-block;">Aprovado</span>';
        $class_success = 'btn-success';
        
    } else if ($dados_candidato[0]['status'] == 3) {
        $status = '<span class="label label-danger" style="font-size: 13px; min-width: 110px; display: inline-block;">Reprovado</span>';
        $class_danger = 'btn-danger';

    } else if ($dados_candidato[0]['status'] == 4) {
        $status = '<span class="label label-warning" style="font-size: 13px; min-width: 110px; display: inline-block;">Não compareceu</span>';
        $class_warning = 'btn-warning';
    } else if ($dados_candidato[0]['status'] == 5) {
        $status = '<span class="label label-info" style="font-size: 13px; min-width: 110px; display: inline-block;">Pré-aprovado</span>';
        $class_info = 'btn-info';
    }

    $n_etapas = $dados_selecao[0]['n_etapas'];
    $precisa_nota = $dados_etapa[0]['precisa_nota'];
    $precisa_parecer = $dados_etapa[0]['precisa_parecer'];

?>
<style>
    #imagem{
        -moz-background-size: 100% 100%;
        -webkit-background-size: 100% 100%;
        background-size: cover;
        height: 146px;
        width: 100%;
        resize: both;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        background-position: center;
    }
    #img-relatorio{
        border-radius: 80px;
        width: 146px;
        height: 146px;
        object-fit: cover;
    }
    .timeline{
        --uiTimelineMainColor: var(--timelineMainColor, #222);
        --uiTimelineSecondaryColor: var(--timelineSecondaryColor, #F2F2F2);

        position: relative;
        padding-top: 0rem;
        padding-bottom: 3rem;
    }
    .timeline:before{
        content: "";
        width: 4px;
        height: 100%;
        background-color: #337ab7;

        position: absolute;
        top: 0;
    }
    .timeline__group{
        position: relative;
    }
    .timeline__group:not(:first-of-type){
        margin-top: 4rem;
    }
    .timeline__year{
        padding: .5rem 1.5rem;
        color: var(--uiTimelineSecondaryColor);
        background-color: var(--uiTimelineMainColor);

        position: absolute;
        left: 0;
        top: 0;
    }
    .timeline__box{
        position: relative;
    }
    .timeline__box:not(:last-of-type){
        margin-bottom: 30px;
    }
    .timeline__box:before{
        content: "";
        width: 100%;
        height: 2px;
        background-color: var(--uiTimelineMainColor);

        position: absolute;
        left: 0;
        z-index: -1;
    }
    .timeline__date{
        min-width: 65px;
        position: absolute;
        left: 0;
        border-radius: 10px;
        border: 0.5px solid #BDBDBD;
        box-sizing: border-box;
        padding: .4rem 1.5rem;
        text-align: center;
        margin-left: -18px;
        background-color: var(--uiTimelineMainColor);
        color: var(--uiTimelineSecondaryColor);
    }
    .timeline__day{
        font-size: 14px;
        font-weight: 500;
        display: block;
    }
    .timeline__month{
        display: block;
        font-size: .8em;
        text-transform: uppercase;
    }
    .timeline__post{
        padding: 1.5rem 2rem;
        margin-left: 27px;
        border-radius: 2px;
        border-left: 3px solid #337ab7;
        box-shadow: 1px 1px 3px 1px rgba(0, 0, 0, .12), 0 1px 2px 0 rgba(0, 0, 0, .24);
        background-color: var(--uiTimelineSecondaryColor);
    }
    @media screen and (min-width: 641px){
        .timeline:before{
            left: 30px;
        }
        .timeline__group{
            padding-top: 30px;
        }
        .timeline__box{
            padding-left: 80px;
        }
        .timeline__box:before{
            top: 50%;
            transform: translateY(-50%);  
        }  
        .timeline__date{
            top: 50%;
            margin-top: -27px;
        }
    }
    @media screen and (max-width: 640px){
        .timeline:before{
            left: 0;
        }
        .timeline__group{
            padding-top: 40px;
        }
        .timeline__box{
            padding-left: 20px;
            padding-top: 70px;
        }
        .timeline__box:before{
            top: 90px;
        }    
        .timeline__date{
            top: 0;
        }
    }
    .timeline{
        --timelineMainColor: #337ab7;
        font-size: 16px;
    }
    @media (min-width: 768px){
        html{
            font-size: 62.5%;
        }
    }
    @media (max-width: 767px){

        html{
            font-size: 55%;
        }
    }
    p{
        margin-top: 0;
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }
    p:last-child{
        margin-bottom: 0;
    }
    .page{
        max-width: 100%;
        padding: 0rem 2rem 0rem;
        margin-left: auto;
        margin-right: auto;
        order: 1;
    }
    .div-span-timeline{
        font-size: 15px;
        padding-bottom: 5px;
    }

</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Candidato:</h3>
                    <div class="panel-title text-right pull-right">
                        <a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-pessoa-rh&id_pessoa=<?=$id_pessoa?>" target="_blank">
                            <button class="btn btn-xs btn-primary">
                                <i class="fas fa-address-book"></i> Ver currículo
                            </button>
                        </a>
                        <a href="/api/iframe?token=<?php echo $request->token ?>&view=selecao-informacoes&idselecao=<?=$id_selecao?>">
                            <button class="btn btn-xs btn-primary">
                                <i class="fas fa-arrow-left"></i> Voltar para seleção
                            </button>
                        </a>
                    </div>
                </div>
                <form method="post" action="/api/ajax?class=SelecaoAvaliar.php" id="selecao_avaliar_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="row" id="row-imagem-relatorio" style="display: block; padding-top:  10px;">
                                    <div class="col-md-12 text-center" style="padding-top: 10px;">
                                        <img src="<?=$dados[0]['foto']?>" class="center text-center" id="img-relatorio" style="border: 4px solid #337ab7;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <table class="table table-striped">
                                    <tbody><br> 
                                        <tr>
                                            <td class="td-table"><strong>Nome:</strong></td>
                                            <td><?=$dados[0]['nome']?></td>
                                        </tr>
                                        <tr>
                                            <td class="td-table"><strong>CPF:</strong></td>
                                            <td><?=formataCampo('cpf_cnpj',$dados[0]['cpf_cnpj'])?></td>
                                        </tr>
                                        <tr>
                                            <td class="td-table"><strong>Status atual:</strong></td>
                                            <td><?=$status?></td>
                                        </tr>
                                        <tr>
                                            <td class="td-table"><strong>Etapa atual:</strong></td>
                                            <td>
                                                <span class="label label-default" style="font-size: 12px;"><?=$n_etapa?></span> - <?=$dados_etapa[0]['descricao']?>

                                                <!-- <div class="btn-group" role="group" aria-label="...">

                                                <?php

                                                    for ($i = 1; $i <= $n_etapas; $i++) {

                                                        if ($i == $dados_candidato[0]['etapa']) {
                                                            $class = "btn-info";
                                                        } else {
                                                            $class = "btn-default";
                                                        }

                                                        echo '<button type="button" class="btn '.$class.' btn-sm" style="min-width: 60px;"><strong>'.$i.'</strong></button>';
                                                    }

                                                ?>
                        
                                                </div> -->
                                            </td>
                                        </tr>                                                         
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr>
                        
                        <?php if ($dados_avaliador_candidato) { ?>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div style="padding-bottom: 5px;">
                                        <span><strong>Informações do candidato na(s) etapa(s):</strong></span>
                                    </div>

                                    <div class="page">
                                        <div class="timeline">
                                            <div class="timeline__group">

                                                <?php
                                                        foreach ($dados_avaliador_candidato as $conteudo) {

                                                            $data = converteDataHora($conteudo['data_avaliacao']);
                                                            $data = explode(" ", $data);
                                                            $dia = $data[0];
                                                            $hora = $data[1];
                                                ?>
                                                        <div class="timeline__box">
                                                            <div class="timeline__date">
                                                                <span class="timeline__day"><?=$dia?></span>
                                                                <span class="timeline__month"><?=$hora?></span>
                                                            </div>
                                                            <div class="timeline__post">
                                                                <div class="timeline__content">
                                                                    <div class="div-span-timeline">
                                                                        <span>
                                                                            <strong>Etapa: </strong> <?=$conteudo['num_etapa']?> 
                                                                        </span>

                                                                        <?php 
                                                                            if ($conteudo['id_usuario_avaliador'] == $_SESSION['id_usuario']) {
                                                                        ?>
                                                                        <div class="pull-right">
                                                                             <a href="#" class="btn btn-xs btn-info" id="<?=$conteudo['id_selecao_avaliador_candidato']?>"  onclick="idModal(this.id)">
                                                                                <i class="fa fa-pencil"></i> Editar
                                                                            </a>
                                                                        </div>

                                                                        <?php 
                                                                            }
                                                                        ?>
                                                                    </div>
                                                                    <div class="div-span-timeline">
                                                                        <span>
                                                                            <strong>Descricao: </strong> <?=$conteudo['descricao']?>
                                                                        </span>
                                                                    </div>
                                                                    <div class="div-span-timeline">
                                                                        <span>
                                                                            <strong>Avaliador: </strong> <?=$conteudo['nome']?>
                                                                        </span><br>
                                                                    </div>

                                                                    <?php
                                                                        if ($conteudo['precisa_nota'] == 1) {
                                                                    ?>
                                                                    <div class="div-span-timeline">
                                                                        <span>
                                                                            <strong>Nota: </strong> <?=$conteudo['nota']?>
                                                                        </span><br>
                                                                    </div>
                                                                    <?php
                                                                        }

                                                                        if ($conteudo['precisa_parecer'] == 1) {
                                                                    ?>
                                                                    <div class="div-span-timeline">
                                                                        <span>
                                                                            <strong>Parecer: </strong> <?=$conteudo['parecer']?>
                                                                        </span><br>
                                                                    </div>
                                                                    <?php
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                                        
                                                <?php
                                                        }
                                                ?>
                                            </div>
                                        </div>
                                    </div>        
                                </div>
                            </div>
                            <hr>

                        <?php } if ($verifica_avaliador == 0 && $dados_candidato[0]['status'] == 1) { ?>
                            <div class="alert alert-info text-center" role="alert">
                                Candidato(a) não recebeu nota ou parecer do avaliador responsável!
                            </div>
                        <?php } ?>
                        
                        <!-- if ($verifica_avaliador != 0 && $check != 1 && $dados_candidato[0]['status'] == 1) { -->
                        <?php if ($verifica_avaliador != 0) { ?>
                        
                            <?php if ($dados_etapa[0]['precisa_nota'] == 1 && $dados_candidato[0]['status'] != 2 && $check != 1) { ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Nota:</label>
                                            <input type="text" name="nota" id="nota" class="form-control number_float" value="">
                                        </div>
                                    </div>
                                </div><br>
                            <?php } ?>

                            <?php if ($dados_etapa[0]['precisa_parecer'] == 1 && $dados_candidato[0]['status'] != 2 && $check != 1) { ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Parecer:</label>
                                            <textarea class="form-control" name="parecer" id="parecer" rows="4"></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                                    
                            <div class="row">
                                <div class="col-md-12 text-center">
                                <div class="btn-group" role="group" aria-label="">
                                        <button type="button" class="btn <?=$class_warning?> btn-etapa-status" attr-data="4" <?=$style_compareceu?>>
                                            <i class="far fa-thumbs-down"></i> Não Compareceu
                                        </button>
                                        <button type="button" class="btn <?=$class_primary?> btn-etapa-status" attr-data="1" <?=$style_em_selecao?>>
                                            <i class="fas fa-chalkboard-teacher"></i> Em seleção
                                        </button>
                                        <button type="button" class="btn <?=$class_danger?> btn-etapa-status" attr-data="3" <?=$style_reprovado?>>
                                            <i class="fas fa-times"></i> Reprovado
                                        </button>
                                        <button type="button" class="btn <?=$class_info?> btn-etapa-status" attr-data="5" <?=$style_pre_selecao?>>
                                            <i class="far fa-hand-point-right"></i></i> Pré-aprovado
                                        </button>
                                        <button type="button" class="btn btn-default btn-etapa-status" attr-data="6" <?=$style_prox_etapa?>>
                                            <i class="far fa-thumbs-up"></i> Passar para próxima etapa
                                        </button>
                                        <button type="button" class="btn <?=$class_success?> btn-etapa-status" attr-data="2"<?=$style_aprovado?>>
                                            <i class="far fa-handshake"></i> Aprovado
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="troca_status" id="troca_status" value="0">
                                <input type="hidden" name="avanca_etapa" id="avanca_etapa" value="0">
                                <input type="hidden" name="id_selecao" value="<?=$id_selecao?>" />
                                <input type="hidden" name="id_candidato" value="<?=$id_candidato?>" />
                                <input type="hidden" name="id_selecao_etapa" value="<?=$id_selecao_etapa?>" />
                                <input type="hidden" name="id_selecao_candidato" value="<?=$id_selecao_candidato?>" />
                            </div>
                            <br>

                        <?php } if ($check == 1) { ?>
                            <div class="alert alert-info text-center" role="alert">
                                Você já deu seu parecer ou nota para este candidato!
                            </div>
                        <?php } ?>

                    </div>
                    
                    <?php if ($verifica_avaliador != '') { ?>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-md-12" style="text-align: center">
                                    <input type="hidden" id="operacao" value="1" name="inserir">
                                    <button type="submit" class="btn btn-primary" name="salvar" id="ok" >
                                        <i class="fa fa-floppy-o"></i> Salvar
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Alterar parecer</h4>
      </div>
      <form method="post" action="/api/ajax?class=SelecaoAvaliar.php" id="selecao_avaliar_form" style="margin-bottom: 0;">
		<input type="hidden" name="token" value="<?php echo $request->token ?>">
        <div class="modal-body">
            <input type="hidden" name="id_avaliador_candidato_alterar" id="id_avaliador_candidato_alterar" value="">
            <input type="hidden" name="id_selecao" id="id_selecao" value="<?=$id_selecao?>">
            <input type="hidden" name="id_candidato" id="id_candidato" value="<?=$id_candidato?>">
            <div id="conteudo">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <input type="hidden" id="operacao" value="1" name="alterar">
            <button type="submit" class="btn btn-primary" name="salvar" id="ok" >
                <i class="fa fa-floppy-o"></i> Salvar
            </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    $(document).on('click', '.btn-etapa-status', function(){
       
        btn = $(this).attr('attr-data');

        $( ".btn-etapa-status" ).each(function() {

            $(this).removeClass('btn-default');
            $(this).removeClass('btn-success');
            $(this).removeClass('btn-danger');
            $(this).removeClass('btn-primary');
            $(this).removeClass('btn-warning');
            $(this).removeClass('btn-info');

            /* 1 - em andamento
            2 - Aprovado
            3 - Reprovado
            4 - nao compareceu */

            if ($(this).attr('attr-data') == btn) {
                opcao = $(this).attr('attr-data');
                
                if ($(this).attr('attr-data') != 6) {

                    marcado = $('#troca_status').val();

                    if (btn == marcado) {
                        $(this).addClass('btn-default');
                        $('#troca_status').val(0);
                    } else {
                        if ($(this).attr('attr-data') == 2) {
                            $(this).addClass('btn-success');
                        } else if ($(this).attr('attr-data') == 1) {
                            $(this).addClass('btn-primary');
                        } else if ($(this).attr('attr-data') == 4) {
                            $(this).addClass('btn-warning');
                        } else if ($(this).attr('attr-data') == 5) {
                            $(this).addClass('btn-info');
                        } else {
                            $(this).addClass('btn-danger');
                        }
                        $('#troca_status').val($(this).attr('attr-data'));
                        $('#avanca_etapa').val(0);
                    }
                    
                } else {

                    marcado = $('#avanca_etapa').val();

                    if (btn == marcado) {
                        $(this).addClass('btn-default');
                        $('#avanca_etapa').val(0);
                    } else {
                        $(this).addClass('btn-success');
                        $('#troca_status').val(0);
                        $('#avanca_etapa').val(1);
                    }
                }

            } else {
                $(this).addClass('btn-default');
            }
        });

    });

    function idModal(id){
        
        function call_busca_ajax(id){
           busca_ajax('<?= $request->token ?>' , 'SelecaoAvaliarAlterar', 'conteudo', id);
        }

        call_busca_ajax(id);

        $('#id_avaliador_candidato_alterar').val(id);

        $('#myModal').modal('show');
    }

    $(document).on('submit', '#selecao_avaliar_form', function () {
        modalAguarde();
    });
</script>
                    