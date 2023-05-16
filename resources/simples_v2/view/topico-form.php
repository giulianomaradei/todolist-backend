<?php
require_once(__DIR__."/../class/System.php");


$origem_topico = (int) $_GET['origem_topico'];

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_topico', "WHERE id_topico = '$id'");
    $titulo = $dados[0]['titulo'];
    $conteudo = $dados[0]['conteudo'];
    $id_pai = $dados[0]['id_pai'];
    $id_categoria = $dados[0]['id_categoria'];
    $id_usuario = $dados[0]['id_usuario'];

    if($id_usuario != $_SESSION['id_usuario']){
        echo '<div class="alert alert-warning text-center"><strong>Você não possui permisssão para alterar este tópico!</strong></div>';
        exit;
    }

    $dados_perfis_select2 = DBRead('', 'tb_perfil_topico a', "INNER JOIN tb_perfil_sistema b ON a.id_perfil_sistema = b.id_perfil_sistema WHERE a.id_topico = '$id'");

    if($dados[0]['permissao_comentario'] == 1){
        $checked = "checked";
    }else{
        $checked = "";
    }
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $titulo = '';
    $conteudo = '';
    $id_pai = 0;
    $id_categoria = 1;
    $id_usuario = 1;
    $data_criacao = '';

    $checked = "checked";
}
?>
<script src="inc/ckeditor/ckeditor.js"></script>
<style>
.select2{
    width: 100% !important;
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> tópico:</h3>
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a href=\"/api/ajax?class=Topico.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
                </div>
                <form method="post" action="/api/ajax?class=Topico.php" id="topico_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <input type="hidden" id='origem_topico' name="origem_topico" value="<?=$origem_topico?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class='row'>
                            <div class='col-md-4'>
                                <div class="form-group">
                                    <label>*Título:</label>
                                    <input name="titulo" autofocus type="text" id="titulo" class="form-control" value="<?= $titulo; ?>" autocomplete="off" required>
                                </div>
                            </div>
                            <div class='col-md-4'>
                                <div class="form-group">
                                    <label>*Categoria:</label>
                                    <select class="form-control" id="id_categoria" name="id_categoria" required>
                                        <option value=""></option>
                                        <?php
                                            $dados_categoria = DBRead('', 'tb_categoria', "WHERE exibe_topico = 1 ORDER BY nome ASC");
                                            if($dados_categoria){
                                                foreach($dados_categoria as $categoria){
                                                    $idCategoria = $categoria['id_categoria'];
                                                    $nomeSelect = $categoria['nome'];
                                                    $selected = $id_categoria == $idCategoria ? "selected" : "";
                                                    echo "<option value='$idCategoria'".$selected.">$nomeSelect</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <input <?= $checked?> type="checkbox" name="permissao_comentario" id="permissao_comentario" value="1">
                                        </span>
                                        <input type="text" class="form-control" aria-label="..." disabled value="Permitir comentários" style="cursor: context-menu; background-color: white;">
                                    </div><!-- /input-group -->
                                </div>
                            </div>

                        </div>
                            
                        <div class='row'>
                            <div class='col-md-12'>
                                <div class="form-group">
                                    <div class='table-responsive' style="max-height: 520px; overflow-y:auto;">

                                        <label>*Perfil:</label>
                                        <label class='pull-right' style='margin: 0 !important;'>Todos &darr;</label>

                                        <div class='input-group'>
                                            <select class="js-example-basic-multiple" id="perfil_sistema" name="perfil_sistema[]" multiple="multiple" required>
                                                <?php
                                                $dados_perfil_sistema = DBRead('', 'tb_perfil_sistema',"WHERE status = 1 AND id_perfil_sistema != '19' ORDER BY nome ASC");
                                                if($dados_perfil_sistema){
                                                    foreach($dados_perfil_sistema as $perfil_sistema){
                                                        $id_perfil_sistema = $perfil_sistema['id_perfil_sistema'];
                                                        $nome = $perfil_sistema['nome'];
                                                        if($operacao == 'alterar'){
                                                            $dados = DBRead('', 'tb_perfil_topico', "WHERE id_perfil_sistema = '$id_perfil_sistema' AND id_topico = '$id'");
                                                            if($dados){
                                                                $ckecked = 'checked';
                                                            }
                                                        }
                                                        echo "<option value='$id_perfil_sistema'>$nome</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <span class="input-group-addon">
                                                <?php
                                                    $checked = '';
                                                    $cont_perfis_topico = DBRead('', 'tb_perfil_topico', "WHERE id_topico = '$id'", "COUNT(id_perfil_sistema) AS cont_perfis_topico");
                                                    
                                                    $cont_perfis = DBRead('', 'tb_perfil_sistema', "WHERE status = 1 AND id_perfil_sistema != 19", "COUNT(id_perfil_sistema) AS cont_perfis");
                                                    if($cont_perfis_topico[0]['cont_perfis_topico'] === $cont_perfis[0]['cont_perfis']){
                                                        $checked = 'checked';
                                                    }
                                                ?>
                                                <input type="checkbox" <?=$checked?>  id="checkbox" >
                                            </span>
                                        </div>
                                    </div>                                    
                                </div>
                            </div>
                            
                        </div>
                        <div class='row'>
                            <div class='col-md-12'>
                                <div class="form-group">
                                    <label>*Conteúdo:</label>
                                        <textarea rows="12" cols="50" required name="conteudo" class="form-control ckeditor conteudo" id="conteudo"><?= $conteudo ?></textarea>
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

    select2Perfis = $('.js-example-basic-multiple').select2();

    function inserePerfis(){
        //verifica quais estão marcados
        dadosCampoPerfisJson = <?php echo json_encode($dados_perfis_select2) ?>;
        dadosCampoPerfisArray = [];
        dadosCampoPerfisJson.forEach(function(i){
            dadosCampoPerfisArray.push(i.id_perfil_sistema);
        });
        select2Perfis.val(dadosCampoPerfisArray).trigger("change");
    }

    <?php
        if($operacao == 'alterar'){
            echo "inserePerfis();";
        }
    ?>

    $("#checkbox").click(function(){
        if($("#checkbox").is(':checked')){
            $(".js-example-basic-multiple > option").prop("selected","selected");
            $(".js-example-basic-multiple").trigger("change");
        }else{
            $(".js-example-basic-multiple > option").removeAttr("selected");
            $(".js-example-basic-multiple").trigger("change");
        }
    });
    

    CKEDITOR.replace('conteudo', {
        height: 450
    });

    $(document).on('click', '#checkTodos', function(){
        if($(this).is(':checked')){
            $('input:checkbox').prop("checked", true);
        }else{
            $('input:checkbox').prop("checked", false);
        }
    });

    $(document).on('submit', '#topico_form', function(){
        var id_categoria = $("#id_categoria").val();
        var conteudo = $(".conteudo").val();
        if(!id_categoria || id_categoria == ''){
            alert("Deve-se inserir uma categoria válida");
            return false;
        }
        if(!conteudo){
            alert("Deve-se inserir um conteúdo válido");
            return false;
        }
        modalAguarde();
    });
</script>