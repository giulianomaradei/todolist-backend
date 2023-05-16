<?php
    require_once(__DIR__."/../class/System.php");
    
    $netapas = (int) $_GET['netapas'];
    $id = (int) $_GET['idselecao'];

    $dados = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE b.status = 1 AND a.status = 1 ORDER BY b.nome ASC", 'a.id_usuario, b.nome');
?>

<style>
    .select2-dropdown--above {
        border: 1px solid#E6E6E6 !important;
        border-bottom: none !important;  
    }
    .select2-dropdown--below{
        border: 1px solid #E6E6E6 !important;
        border-top: none !important;     
    }
    .select2-selection{
        border: 1px solid #ccc !important;
    }
    .select2{
        width: 100% !important;
    }
    .select2-selection__rendered{
        max-width: 550px !important;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title">Inserir etapas da seleção:</h3>
                </div>
                <form method="post" action="/api/ajax?class=SelecaoEtapa.php" id="selecao_etapa" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <input type="hidden" name="netapas" id="netapas" value="<?=$netapas?>" />
                    <input type="hidden" name="id_selecao" value="<?=$id?>" />
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info text-center" role="alert">
                                    Seleção só entrará em vigor após a criação das etapas!
                                </div>
                            </div>
                        </div>

                        <?php
                            for ($i = 0; $i < $netapas; $i++) {
                        ?>      
                                <div class="panel" style="background-color: #E6E6E6; border-color: #BDBDBD;">
                                    <div class="panel-heading">
                                        <span class="label label-default" style="font-size: 14px; font-weight: 500 !important;">
                                           <i class="fab fa-buffer"></i> Etapa <?=$i+1?>
                                        </span>
                                    </div>
                                    <div class="panel-body" style="background-color: #FAFAFA;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Descrição da etapa:</label>
                                                    <input class="form-control input-sm" name="descricaoetapa<?=$i+1?>" id="descricaoetapa<?=$i+1?>" required/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Tipo:</label>
                                                    <select class="form-control input-sm" name="tipoetapa<?=$i+1?>" id="tipoetapa<?=$i+1?>" required>
                                                        <option value=""></option>
                                                        <option value="1">Entrevista</option>
                                                        <option value="2">Prova</option>
                                                        <option value="3">Diretoria</option>
                                                        <option value="4">Decisão</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Avaliar com nota:</label>
                                                    <select class="form-control input-sm avaliarnota<?=$i+1?>" name="avaliarnotaetapa<?=$i+1?>" id="avaliarnotaetapa<?=$i+1?>" required>
                                                        <option value=""></option>
                                                        <option value="1">Sim</option>
                                                        <option value="2">Não</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                               <label for="">Dar parecer:</label>
                                                <select class="form-control input-sm darparecer<?=$i+1?>" name="darpareceretapa<?=$i+1?>" id="darpareceretapa<?=$i+1?>" required>
                                                    <option value="1" selected>Sim</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- row table-->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="tooltip_container"></div>
                                                <div class='table-responsive' >
                                                    <table class='table table-bordered' style='font-size: 14px; background-color: #F2F2F2;' id="myTable<?=$i+1?>>" id="verifica_elemento">
                                                        <thead>
                                                            <tr>
                                                                <th class="col-md-11"><i class="fas fa-user-edit"></i> Avaliador</th>
                                                                <th class="col-md-1">Adicionar</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="teste<?=$i+1?>">
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td></td>
                                                                <td>
                                                                    <button type="button" class='center-block btn btn-warning btn-sm adiciona-quesito' cont="<?=$i+1?>" role='button'>
                                                                        <i class='fa fa-plus' aria-hidden='true'></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end row table-->
                                    </div>
                                </div>
                                <hr>
                        <?php
                            }
                        ?>

                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>"/>
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar e continuar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>  

<script>
    $(".adiciona-quesito").on('click', function(){

        var cont = $(this).attr('cont');
        var rowCount = parseInt($('#myTable'+cont+' tr').length) - parseInt(1);

        teste = <?php echo json_encode($dados); ?>;
        var option = '';

        $.each( teste, function( key, value ) {
            option += '<option value="'+value.id_usuario+'">'+value.nome+'</option>';
        });

        $("#teste"+cont).append("<tr class='check linha_avaliador"+cont+"'>" + 
            "<td><select class='form-control input-sm js-example-basic-multiple avaliador"+cont+"' name='avaliadoretapa"+cont+"[]' required>"+option+"</select></td>"+
            "<td><button class='center-block btn btn-danger btn-sm removeLinha' role='button'><i class='fa fa-trash-o' aria-hidden='true'></i></button></td></tr>");

            $('.js-example-basic-multiple').select2();
    });

    $(document).on('click', '.removeLinha', function(){
        if(confirm('Deseja excluir o avaliador?')){
            $(this).parent().parent().remove();
            
            $('.posicao').each(function (i) {
                $(this).val(i + 1);
            });
        }
        return false;
    });

    $(document).on('submit', '#selecao_etapa', function () {
        var n_etapas = $('#netapas').val();

        var naoSalva = 0;
        for (i = 1; i <= n_etapas; i++) {

            var avaliarnota = $('#avaliarnotaetapa'+i).val();
            var darpareceretapa = $('#darpareceretapa'+i).val();

            if (avaliarnota == '' && darpareceretapa == '') {
                ++naoSalva;
            }
            
            $("tr.linha_avaliador"+i).each(function(index_primeiro){
                avaliador_primeiro = $(this).find(".avaliador"+i).val().toUpperCase();

                $("tr.linha_avaliador"+i).each(function(index_segundo){
                    avaliador_segundo = $(this).find(".avaliador"+i).val().toUpperCase();

                    if(index_primeiro != index_segundo){
                        if(avaliador_primeiro == avaliador_segundo){
                            ++naoSalva;
                        }
                    }
                });
            });
        }

        if (naoSalva != 0) {
            alert('Verifique se os campos estão preenchidos corretamente');
            return false;
        }

    });
</script>