<?php
require_once(__DIR__."/../class/System.php");
<<<<<<< HEAD
require_once(__DIR__."/class/RdApi.php");
=======
require_once(__DIR__."/../class/RdApi.php");
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b

$id_lead_negocio = (!empty($_GET['id_lead_negocio'])) ? $_GET['id_lead_negocio'] : '';

if ($id_lead_negocio) {
    
    $dados_negocio = DBRead('', 'tb_lead_negocio', "WHERE id_lead_negocio = $id_lead_negocio");

    if ($dados_negocio[0]['sinalizacao_rd'] == '1') {
        $sinalizado = 'checked';

    } else {
        $sinalizado = '';
    }

    $dados_conversao = DBRead('', 'tb_rd_conversao', "WHERE id_lead_negocio = $id_lead_negocio");
    
    if ($dados_conversao) {

        $uuid = $dados_conversao[0]['uuid'];
        //$uuid = '90b5864c-d40f-46f8-829a-6b9fa3ee3737';

        if (!$uuid) {
            echo "<div class=\"container-fluid text-center\">
                <div class=\"alert alert-danger\">
                    <i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i> 
                    Lead não possui UUID (identificador RD) cadastrado em nossa base!
                </div>
            </div>";

            die();
        
        } else {
            
            $get_lead = getLead($uuid);

            if ($get_lead['alert'] == 'success') {
                $dados_lead = $get_lead['dados'];

                $tags_select = $dados_lead['tags'];

                $tags_banco = DBRead('', 'tb_lead_tag');
                $todas_tags = array();

                foreach($tags_banco as $conteudo) {
                    array_push($todas_tags, strtolower($conteudo['descricao']));
                }

                if ($tags_select) {
                    foreach($tags_select as $key => $conteudo) {
                        if (!in_array(strtolower($conteudo), $todas_tags)) {
                            array_push($todas_tags, $conteudo);
                        }
                    }
                }

                asort($todas_tags);

            } else {
                echo "<div class=\"container-fluid text-center\">
                        <div class=\"alert alert-danger\">
                            <i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i> 
                            Ops! Ocorreu um erro! ".$check_code['message']."
                        </div>
                    </div>";
                
                die();
            } //end get_lead

        } //end else uuid

    } else {
        echo "<div class=\"container-fluid text-center\">
            <div class=\"alert alert-danger\">
                <i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i> 
                Ops! Não foi encontrada uma conversão para este negócio!
            </div>
          </div>";

         die();
    } //end else conversao

} else {
    echo "<div class=\"container-fluid text-center\">
            <div class=\"alert alert-danger\">
                <i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i> 
                Negócio não encontrado!
            </div>
          </div>";

    die();
} //end else id_lead_negocio

?>

<style>
    .select2{
        width: 100% !important;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Alterar tags RD:</h3>
                </div>
                <form method="post" action="/api/ajax?class=Rd.php" id="lead_tag_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <input type="hidden" name="id_lead_negocio" value="<?= $id_lead_negocio ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">Tags marcadas</label>
                                <select class="js-example-basic-multiple tags_teste" name="tags[]" id="tags" multiple="multiple">
                                    <?php if ($todas_tags) { 
                                            foreach($todas_tags as $conteudo) {
                                    ?>
                                            <option value='<?= $conteudo ?>'><?= $conteudo ?></option>
                                    <?php 
                                            } 
                                        } 
                                    ?>
                                </select>
                                <br><br>
                            </div>                         
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Marcar negócio como sinalizado:</label>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="input-group">
                                            <span class="input-group-addon">
                                                <input type="checkbox" name="negocio_sinalizado" id="negocio_sinalizado" value="1" <?= $sinalizado ?>>
                                            </span>
                                            <input type="text" class="form-control mensagem" aria-label="..." disabled="" value="Sim" style="cursor: context-menu; background-color: white;">
                                            </div><!-- /input-group -->
                                        </div><!-- /.col-lg-6 -->   
                                    </div><!-- /.row -->
                                </div>
                            </div>
						</div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>"/>
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

    /* /* function createOptions(){

        select2Tags = $('#tags').select2();
        //verifica quais estão marcados
        dadosCampoTagsJson = <?php echo json_encode($tags_select) ?>;
        dadosCampoTagsJson.forEach(function(i){
            var newOption = new Option(i, i, false, false);
            $('#tags').append(newOption).trigger('change');
        });
    } */

    //createOptions();

    function insereTags(){
        select2Tags = $('#tags').select2();
        //verifica quais estão marcados
        dadosCampoTagsJson = <?php echo json_encode($tags_select) ?>;
        dadosCampoTagsArray = [];
        dadosCampoTagsJson.forEach(function(i){
            dadosCampoTagsArray.push(i);
        });
        console.log(dadosCampoTagsArray);
        select2Tags.val(dadosCampoTagsArray).trigger("load");
    }

    insereTags();
</script>