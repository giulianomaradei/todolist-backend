<?php
require_once(__DIR__."/../class/System.php");

echo "
<script>
    modalAguarde();
</script>
";

function exibeArvore($id_pai = 0, $primeiro = 0, $nivel_atual = 0, $nivel_limite = 0, $id_inicio, $id_contrato_plano_pessoa, $request){
    if($nivel_atual <= $nivel_limite || $nivel_limite == 0){
        if($primeiro){
            $filtro = "a.id_arvore = '$id_pai'";
            $style = 'style="border-left: none; padding-left:0;"';
        }else{
            $filtro = "a.id_pai = '$id_pai'";
            $style = '';
        }
        $dados_arvore = DBRead('','tb_arvore a',"INNER JOIN tb_resposta b ON a.id_resposta = b.id_resposta INNER JOIN tb_pergunta c ON a.id_pergunta = c.id_pergunta INNER JOIN tb_texto_os d ON a.id_texto_os = d.id_texto_os WHERE $filtro", "a.id_arvore, b.nome AS 'nome_resposta', c.nome AS 'nome_pergunta', a.tag, d.nome AS 'texto_os'");
        if($dados_arvore){
            echo '<ul class="arvore_ul" '.$style.'>';
            foreach($dados_arvore as $conteudo){
                $id_arvore = $conteudo['id_arvore'];
                $nome_pergunta_title = $conteudo['nome_pergunta'];
                //$nome_pergunta = limitarTexto($nome_pergunta_title, 50);
                $nome_pergunta = $nome_pergunta_title;
                $nome_resposta_title = $conteudo['nome_resposta'];
                //$nome_resposta = limitarTexto($nome_resposta_title, 50);
                $nome_resposta = $nome_resposta_title;

                $texto_os = $conteudo['texto_os'];

                if($conteudo['tag'] && $conteudo['tag'] != ''){
                    $tag_tooltip = ' <i class="fa fa-question-circle" data-toggle="tooltip" title="" data-original-title="'.$conteudo['tag'].'" style="color: #ed9d2b;"></i>';
                    $tag = ' <text class="exibe_tag" style="color: #ed9d2b; display:none;">'.$conteudo['tag'].'</text>';
                }else{
                    $tag_tooltip = '';
                    $tag = '';
                }

                if($id_contrato_plano_pessoa && $id_contrato_plano_pessoa != ''){
                    $dados_id_contrato_plano_pessoa = DBRead('', 'tb_arvore_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND id_arvore = '".$id_arvore."' ");
                    $botao_contrato = '';
                    if($dados_id_contrato_plano_pessoa){

                        $botao_contrato = '&nbsp
                            <label style="min-height: 20px; padding-left: 20px; margin-bottom: 0; font-weight: 400; cursor: pointer;display: inline-block; max-width: 100%;">
                                <input class="checkbox_id_contrato_plano_pessoa" data-id = "'.$id_contrato_plano_pessoa.'|'.$id_arvore.'" checked type="checkbox" style="position: absolute; margin-left: -20px !important; width: 15px; height: 17px; margin: 0; line-height: normal;">
                                Opção
                            </label>
                        ';

                        $checked_exibe_texto_os = '';
                        if($dados_id_contrato_plano_pessoa[0]['exibe_texto_os'] == 1){
                            $checked_exibe_texto_os = 'checked';
                        }

                        $botao_contrato .= '&nbsp
                            <label style="min-height: 20px; padding-left: 20px; margin-bottom: 0; font-weight: 400; cursor: pointer;display: inline-block; max-width: 100%;" data-toggle="tooltip" title="" data-original-title="'.$texto_os.'">
                                <input class="checkbox_exibe_texto_os" data-id-arvore = "'.$id_contrato_plano_pessoa.'|'.$id_arvore.'" '.$checked_exibe_texto_os.' type="checkbox" style="position: absolute; margin-left: -20px !important; width: 15px; height: 17px; margin: 0; line-height: normal;">
                                Exibe Texto OS
                            </label>
                        ';
                        // $botao_contrato = $dados_id_contrato_plano_pessoa[0]['exibe_texto_os'];
                    }else{
                        $botao_contrato = '&nbsp
                            <label style="min-height: 20px; padding-left: 20px; margin-bottom: 0; font-weight: 400; cursor: pointer;display: inline-block; max-width: 100%;">
                                <input class="checkbox_id_contrato_plano_pessoa"  data-id = "'.$id_contrato_plano_pessoa.'|'.$id_arvore.'" type="checkbox" style="position: absolute; margin-left: -20px !important; width: 15px; height: 17px; margin: 0; line-height: normal;">
                                Opção
                            </label>
                        ';

                        $botao_contrato .= '&nbsp
                            <label style="min-height: 20px; padding-left: 20px; margin-bottom: 0; font-weight: 400; cursor: pointer;display: inline-block; max-width: 100%;" data-toggle="tooltip" title="" data-original-title="'.$texto_os.'">
                                <input class="checkbox_exibe_texto_os" data-id-arvore = "'.$id_contrato_plano_pessoa.'|'.$id_arvore.'" type="checkbox" style="position: absolute; margin-left: -20px !important; width: 15px; height: 17px; margin: 0; line-height: normal;">
                                Exibe Texto OS
                            </label>
                        ';
                    }

                }

                echo  '<li class="arvore_li" dt-li="'.$nivel_atual.'" dt-id-arvore="'.$id_arvore.'"><i class="botao_esconde" aria-hidden="true"></i> ('.$id_arvore.') <span title="Opção / Resposta: '.$nome_resposta_title.'"><strong>'.$nome_resposta.'</strong></span> - <span title="Instrução / Pergunta: '.$nome_pergunta_title.'">'.$nome_pergunta.'</span> '.$botao_contrato;
                echo '
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-arvore dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="/api/iframe?token='.$request->token.'view=arvore-opcao-form&inserir='.$id_arvore.'&id_inicio='.$id_inicio.'&nivel_limite='.$nivel_limite.'&contrato_select='.$id_contrato_plano_pessoa.'">Inserir</a></li>
                            <li><a href="/api/iframe?token='.$request->token.'&view=arvore-opcao-form&alterar='.$id_arvore.'&id_inicio='.$id_inicio.'&nivel_limite='.$nivel_limite.'&contrato_select='.$id_contrato_plano_pessoa.'">Alterar</a></li>
                            <li><a href="/api/iframe?token='.$request->token.'&view=arvore-estrutura-form&clonar='.$id_arvore.'&id_inicio='.$id_inicio.'&nivel_limite='.$nivel_limite.'&contrato_select='.$id_contrato_plano_pessoa.'">Clonar</a></li>
                            <li><a href="/api/iframe?token='.$request->token.'&view=arvore-estrutura-form&mover='.$id_arvore.'&id_inicio='.$id_inicio.'&nivel_limite='.$nivel_limite.'&contrato_select='.$id_contrato_plano_pessoa.'">Mover</a></li>
                            <li><a href="/api/ajax?class=Arvore.php?excluir='.$id_arvore.'&id_inicio='.$id_inicio.'&nivel_limite='.$nivel_limite.'&contrato_select='.$id_contrato_plano_pessoa.'&token='.$request->token.'" onclick="if (!confirm(\'Excluir um passo da árvore é irreversível, tem certeza que deseja excluir o registro?\')) { return false; } else { modalAguarde(); }">Excluir</a></li>
                        </ul>
                    </div>
                ';
                echo $tag_tooltip.' '.$tag;
                echo exibeArvore($conteudo['id_arvore'], 0, $nivel_atual+1, $nivel_limite, $id_inicio, $id_contrato_plano_pessoa, $request);
                echo '</li>';
            }
            echo '</ul>';
        }
    }
}

$id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : 1;
$nivel_limite = isset($_GET['nivel_limite']) ? $_GET['nivel_limite'] : 1;
$id_pai = 0;
$botoes = '';
$dados_arvore = DBRead('','tb_arvore',"WHERE id_arvore = '$id'");

$id_contrato_plano_pessoa = (isset($_GET['id_contrato_plano_pessoa']) && $_GET['id_contrato_plano_pessoa']) ? $_GET['id_contrato_plano_pessoa'] : '';

if($id_contrato_plano_pessoa && $id_contrato_plano_pessoa != ''){
    $dados_nome_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ", "b.nome");
    $nome_contrato = "<strong>".$dados_nome_contrato[0]['nome']."</strong>";
}

if($dados_arvore){
    $id_pai = ($dados_arvore[0]['id_pai'] != 0) ? $id_pai = $dados_arvore[0]['id_pai'] : 0;
}
if($id_pai != 0){
    if($id_contrato_plano_pessoa){
        $botoes = '<div class="panel-title text-right pull-right"><button class="btn btn-sm btn-warning botao_tag" value="0"><i class="fa fa-question-circle"></i> Exibir Tag</button> <a href="/api/iframe?token='.$request->token.'&view=arvore-exibe&id='.$id_pai.'&nivel_limite='.$nivel_limite.'&id_contrato_plano_pessoa='.$id_contrato_plano_pessoa.'"><button class="btn btn-sm btn-primary"><i class="fa fa-reply"></i> Exibir a partir do Pai</button></a></div>';
    }else{
        $botoes = '<div class="panel-title text-right pull-right"><button class="btn btn-sm btn-warning botao_tag" value="0"><i class="fa fa-question-circle"></i> Exibir Tag</button> <a href="/api/iframe?token='.$request->token.'&view=arvore-exibe&id='.$id_pai.'&nivel_limite='.$nivel_limite.'"><button class="btn btn-sm btn-primary"><i class="fa fa-reply"></i> Exibir a partir do Pai</button></a></div>';
    }
}else{
    $botoes = '<div class="panel-title text-right pull-right"><button class="btn btn-sm btn-warning botao_tag" value="0"><i class="fa fa-question-circle"></i> Exibir Tag</button></div>';
}

?>
<style>
    .arvore_ul{
        border-left: solid 1px;
        padding-left:100px;
        list-style-type: none;
    }
    .btn-arvore{
        border: 0;
        background: transparent;
        box-shadow: 0;
    }
    .arvore{
        white-space: nowrap;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="col-md-4" style="text-align: left">
                        <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Árvore:</h3>
                    </div>
                    <div class="col-md-4" style="text-align: center" id= 'div_botao'>
                        <h3 class="panel-title" style="margin-top: 2px;"><?=$nome_contrato?></h3>
                    </div>
                    <div class="col-md-4" style="text-align: right;">
                        <?=$botoes?>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="arvore">
                <?php
                if ($dados_arvore) {
                    exibeArvore($id, 1, 0, $nivel_limite, $id, $id_contrato_plano_pessoa, $request);
                }else{
                    echo '<div class="alert alert-warning text-center" style="margin-bottom:0;">Passo não encontrado!</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<script>

    $(document).on('click', '.checkbox_id_contrato_plano_pessoa', function(){
        var split_data = $(this).attr('data-id').split("|");
        var id_contrato_plano_pessoa = split_data[0];
        var id_arvore = split_data[1];

        if($(this).is(":checked")){

            // alert($(this).attr('data-id')+' create '+id_contrato_plano_pessoa+' '+id_arvore);
            $.ajax({
                url: "/api/ajax?class=ArvoreAtualiza.php",
                dataType: "json",
                data: {
                    acao: 'criacao',
                    parametros: {
                        'id_contrato_plano_pessoa' : id_contrato_plano_pessoa,
                        'id_arvore' : id_arvore
                    },
                    token: '<?= $request->token ?>'
                },
                success: function (data) {
                    response(data);
                }
            });

            $('[data-id-arvore="'+$(this).attr('data-id')+'"]').prop('checked', true)

        }else{
            // alert($(this).attr('data-id')+' delete');

            $.ajax({
                url: "/api/ajax?class=ArvoreAtualiza.php",
                dataType: "json",
                data: {
                    acao: 'exclusao',
                    parametros: {
                        'id_contrato_plano_pessoa' : id_contrato_plano_pessoa,
                        'id_arvore' : id_arvore
                    },
                    token: '<?= $request->token ?>'
                },
                success: function (data) {

                }
            });

            $('[data-id-arvore="'+$(this).attr('data-id')+'"]').prop('checked', false)
        }

    });

    $(document).on('click', '.checkbox_exibe_texto_os', function(){
        var split_data = $(this).attr('data-id-arvore').split("|");
        var id_contrato_plano_pessoa = split_data[0];
        var id_arvore = split_data[1];

        if($(this).is(":checked")){
            // alert($(this).attr('data-id-arvore')+' create '+id_contrato_plano_pessoa+' '+id_arvore);

            $.ajax({
                url: "/api/ajax?class=ArvoreAtualiza.php",
                dataType: "json",
                data: {
                    acao: 'exibe_texto_sim',
                    parametros: {
                        'id_contrato_plano_pessoa' : id_contrato_plano_pessoa,
                        'id_arvore' : id_arvore
                    },
                    token: '<?= $request->token ?>'
                },
                success: function (data) {
                    response(data);
                }
            });

            $('[data-id="'+$(this).attr('data-id-arvore')+'"]').prop('checked', true)

        }else{
            // alert($(this).attr('data-id-arvore')+' delete');

            $.ajax({
                url: "/api/ajax?class=ArvoreAtualiza.php",
                dataType: "json",
                data: {
                    acao: 'exibe_texto_nao',
                    parametros: {
                        'id_contrato_plano_pessoa' : id_contrato_plano_pessoa,
                        'id_arvore' : id_arvore
                    },
                    token: '<?= $request->token ?>'
                },
                success: function (data) {

                }
            });

            $('[data-id-arvore="'+$(this).attr('data-id')+'"]').prop('checked', false)
        }

    });

    $(document).on('click', '.botao_tag', function(){

        if($(this).val() == 0){
            $(this).val(1);
            $(this).html('');
            $(this).html('<i class="fa fa-question-circle"></i> Não Exibir Tag');
            $('.exibe_tag').show();
        }else{
            $(this).val(0);
            $(this).html('');
            $(this).html('<i class="fa fa-question-circle"></i> Exibir Tag');
            $('.exibe_tag').hide();
        }

    });


    function seleciona_contrato(id_contrato_plano_pessoa){
        $.ajax({
            type: "GET",
            url: "/api/ajax?class=ArvoreContratoBusca.php",
            dataType: "json",
            data: {
                id_contrato_plano_pessoa: id_contrato_plano_pessoa,
                token: '<?= $request->token ?>'
            },
            success: function (data){
                $(".arvore_li").children('span').attr('style','color:black;');
                for (var i = 0; i < data.length; i++) {
                    $('.arvore_li').each(function(index){
                        var passoArvore = $(this).attr('dt-id-arvore');
                        if(passoArvore == data[i]['id_arvore']){
                            $(this).children('span').attr('style','color:red;');
                        }
                    });
                }
            }
        });
    };


   function exibe_oculta( event ) {
      var target = $( event.target );
     if(target.is('.botao_esconde')){
        target.siblings('ul').slideToggle();
        if(target.attr('class') == 'fa fa-plus-square-o'){
            target.removeClass('fa fa-plus-square-o').addClass('fa fa-minus-square-o');
        }else if(target.attr('class') == 'fa fa-minus-square-o'){
            target.removeClass('fa fa-minus-square-o').addClass('fa fa-plus-square-o');
        }
      }
    }

    $(document).on('click','.arvore',function(event){
        exibe_oculta( event );
    });

    $(document).ready(function() {
        $('.arvore_li').each(function(index, element){
            if($(this).children('.arvore_ul').length){
                $(this).children('.botao_esconde').addClass('fa fa-minus-square-o');
                $(this).children('.botao_esconde').attr('style','cursor:pointer;');
            }else{
                $(this).children('.botao_esconde').addClass('fa fa-square-o');
            }
        });
    });



    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });

    $(window).on("load", function(){
        modalAguarde(false);
    });


</script>