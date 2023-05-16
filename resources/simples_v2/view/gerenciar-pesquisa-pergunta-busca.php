<?php
require_once(__DIR__."/../class/System.php");


$id_pesquisa = (int)$_GET['alterar'];
$id_empresa = DBRead('', 'tb_pessoa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_pesquisa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa WHERE c.id_pesquisa = '".$id_pesquisa."'");
$empresa = $id_empresa[0]['nome'];
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Gerenciar Perguntas - <?= $empresa ?></h3>
                    <div class="panel-title text-right pull-right"><a href="/api/iframe?token=<?php echo $request->token ?>&view=gerenciar-pesquisa-pergunta-form&id_pesquisa=<?=$id_pesquisa?>"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Novo</button></a></div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label class="control-label sr-only">Hidden label</label>
                                <input class="form-control" type="text" name="titulo" id="titulo" onKeyUp="call_busca_ajax();" placeholder="Informe o nome da pergunta" autocomplete="off" autofocus>
                                <input type="hidden" name="id_pesquisa" id="id_pesquisa" value="<?=$id_pesquisa?>">
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="resultado_busca"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function call_busca_ajax(pagina){
        var inicia_busca = 1;
        var titulo = $('#titulo').val();
        var id_pesquisa = $('#id_pesquisa').val();
        if (titulo.length < inicia_busca && titulo.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'titulo': titulo,
            'pagina': pagina,
            'id_pesquisa': id_pesquisa
        };
        busca_ajax('<?= $request->token ?>' , 'GerenciarPesquisaPerguntaBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>