<?php
    require_once(__DIR__."/../class/System.php");
    
    $id = (int) $_GET['idselecao'];

    $dados = DBRead('', 'tb_selecao_etapa', "WHERE id_selecao = $id");
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
                    <h3 class="panel-title">Inserir avaliador:</h3>
                </div>
                <form method="post" action="/api/ajax?class=Selecao.php" id="selecao_avaliador" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <input type="hidden" name="id_selecao" value="<?=$id?>" />
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Etapa:</label>
                                    <select class="form-control input-sm" name="etapa" id="etapa">
                                        <option value="">Selecione</option>
                                        <?php
                                            foreach ($dados as $conteudo) {
                                        ?>
                                                <option value="<?= $conteudo['id_selecao_etapa'] ?>">Etapa <?= $conteudo['num_etapa'] ?> - <?= $conteudo['descricao'] ?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Avaliador:</label>
                                    <select class="form-control js-example-basic-multiple" name="avaliador" id="avaliador">
                                        <option value="">Selecione</option>
                                        <?php
                                            $usuarios = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE b.status = 1 AND a.status = 1 ORDER BY b.nome ASC", 'a.id_usuario, b.nome');
                                            
                                            foreach ($usuarios as $conteudo) {
                                        ?>
                                                <option value="<?= $conteudo['id_usuario'] ?>"><?= $conteudo['nome'] ?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="inserir_avaliador"/>
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>  

<script>

    $(document).ready(function(){
        $('.js-example-basic-multiple').select2();
    });

    $(document).on('submit', '#selecao_avaliador', function () {
        var etapa = $('#etapa').val();
        var etapa_text = $("#etapa option[value='"+etapa+"']").text();

        var avaliador = $('#avaliador').val();
        var avaliador_text = $("#avaliador option[value='"+avaliador+"']").text();

        var status = confirm("Confirmar o avaliador " + avaliador_text + " para a etapa " + etapa_text + "?");
        if (status == false){
           return false;

        } else {
            
            if (etapa == '' || avaliador == '') {
                return false;
            }
        }
    });
</script>