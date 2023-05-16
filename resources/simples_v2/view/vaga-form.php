<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar']) || isset($_GET['clonar'])) {

    if (isset($_GET['alterar'])) {

        $tituloPainel = 'Alterar';
        $operacao = 'alterar';
        $id = (int) $_GET['alterar'];

    } else {
        $tituloPainel = 'Clonar';
        $operacao = 'clonar';
        $id = (int) $_GET['clonar'];      
    }

    $dados_vaga = DBRead('', 'tb_vaga', "WHERE id_vaga = $id");
    $data_inicio = converteData($dados_vaga[0]['data_inicio']);
    $data_fim = converteData($dados_vaga[0]['data_fim']);
    $tipo = $dados_vaga[0]['tipo'];
    $id_cargo = $dados_vaga[0]['id_cargo'];
    $descricao = $dados_vaga[0]['descricao'];

} else {
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $dados_vaga = '';
    $data_inicio = '';
    $data_fim = '';
    $objetivo = '';
    $id_cargo = '';
    $descricao = '';
}

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> vaga:</h3>
                    <?php if (isset($_GET['alterar'])) {
                        echo "<div class=\"panel-title text-right pull-right\"><a href=\"/api/ajax?class=Vagas.php?excluir= $id&token=". $request->token ."\" onclick=\"if(!confirm('Tem certeza que deseja excluir essa vaga?')){ return false; } else { modalAguarde(); }\"></a></div>";
                    } ?>
                </div>
                <form method="post" action="/api/ajax?class=Vaga.php" id="vagas_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class='col-md-6'>
                                <div class="form-group some-container">
                                    <label class="control-label">*Cargo:</label>
                                    <select class="form-control" name="cargo" id="cargo">
                                        <?php 
                                            $dados_cargo = DBRead('', 'tb_cargo', "ORDER BY descricao ASC");
                                            foreach ($dados_cargo as $conteudo) {
                                                $selected = $id_cargo == $conteudo['id_cargo'] ? "selected" : "";
                                        ?>
                                                <option value="<?=$conteudo['id_cargo']?>" <?= $selected?>><?=$conteudo['descricao']?></option>
                                        <?php
                                            }                                      
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <div class="form-group some-container">
                                    <label class="control-label">*Tipo:</label>
                                    <select class="form-control" name="tipo" id="tipo">
                                    
                                    <?php 
                                        $sel_tipo[$tipo] = 'selected';
                                    ?>

                                        <option value="1" <?=$tipo == "1" ? "selected" : "";?>>Efetivo (CLT)</option>
                                        <option value="2" <?=$tipo == "2" ? "selected" : "";?>>Estágio</option>
                                        <option value="6" <?=$tipo == "6" ? "selected" : "";?>>Estágio PCD</option>
                                        <option value="3" <?=$tipo == "3" ? "selected" : "";?>>Jovem aprendiz</option>
                                        <option value="4" <?=$tipo == "4" ? "selected" : "";?>>PCD</option>
                                        <option value="5" <?=$tipo == "5" ? "selected" : "";?>>Terceirizado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="avaliar_em">*Data início:</label>
                                    <input class="form-control date calendar hasDatepicker" id="data_inicio" name="data_inicio" value="<?= $data_inicio ?>" autocomplete="off" required>
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="avaliar_em">*Data fim:</label>
                                    <input class="form-control date calendar hasDatepicker" id="data_fim" name="data_fim" value="<?= $data_fim ?>" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Descrição da vaga:</label>
                                    <textarea required name="descricao" id="descricao" class="form-control ckeditor conteudo" rows="13"><?= $descricao ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>" />
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).on('submit', '#vagas_form', function() {

        var data_inicio = $('#data_inicio').val();
        var data_fim = $('#data_fim').val();
        var descricao = $('#descricao').val();

        if (data_inicio == '') {
            alert('Informe a data de início do treinamento!');
            return false;
        }

        if (data_fim == '') {
            alert('Informe a data do fim do treinamento!');
            return false;
        }

        if (descricao == '') {
            alert('Informe a descrição do treinamento!');
            return false;
        }

        modalAguarde();
    });

   

</script>