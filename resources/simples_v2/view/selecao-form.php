<?php
require_once(__DIR__."/../class/System.php");

    if (isset($_GET['alterar'])) {
        $tituloPainel = 'Alterar';
        $operacao = 'alterar';
        $id = (int)$_GET['alterar'];

    }else{
        $tituloPainel = 'Inserir';
        $operacao = 'inserir';
        $id = 1;
    }

?>
<style>
    .select2{
        width: 100% !important;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="row">
                        <h3 class="panel-title text-left pull-left col-md-3"><?= $tituloPainel ?> seleção:</h3>
                    </div>
                </div>
                <form method="post" action="/api/ajax?class=Selecao.php" id="selecao" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Nome:</label>
                                    <input class="form-control " name="nome" id="nome" maxlength="300" required/>
                                </div>                                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Descrição:</label>
                                    <textarea class="form-control " name="descricao" id="descricao" style="resize: vertical; height: 100px;" required></textarea>
                                </div>                                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Setor:</label>
                                    <select name="setor" id="setor" class="form-control" >
                                        <?php
                                            $dados_setor = DBRead('', 'tb_setor', "ORDER BY descricao ASC");
                                            foreach ($dados_setor as $conteudo_setor) {
                                        ?>
                                                <option value="<?=$conteudo_setor['id_setor']?>"><?=$conteudo_setor['descricao']?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>                                                
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Cargo:</label>
                                    <select name="cargo" id="cargo" class="form-control" >
                                        <?php
                                            $dados_cargo = DBRead('', 'tb_cargo', "WHERE id_setor = 1 ORDER BY descricao ASC");
                                            foreach ($dados_cargo as $conteudo_cargo) {
                                        ?>
                                                <option value="<?=$conteudo_cargo['id_cargo']?>"><?=$conteudo_cargo['descricao']?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>                                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Número de vagas:</label>
                                    <input class="form-control number_int" name="nvagas" id="nvagas" maxlenght="2" type="text" required>
                                </div>                                                
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Número de etapas:</label>
                                    <input class="form-control number_int" name="netapas" id="netapas" maxlenght="1" type="text" required>
                                </div>                                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Vaga:</label>
                                <select class="js-example-basic-multiple" id="id_vaga" name="id_vaga" multiple="multiple">
                                    <?php
                                    $dados_vaga = DBRead('', 'tb_vaga a', "INNER JOIN tb_cargo b ON a.id_cargo = b.id_cargo WHERE a.status = 1 ORDER BY id_vaga DESC", 'a.id_vaga, a.descricao as descricao_vaga, b.descricao as descricao_cargo');
                                    if($dados_vaga){
                                        foreach($dados_vaga as $conteudo){
                                            $id_vaga = $conteudo['id_vaga'];
                                            $descricao_cargo = $conteudo['descricao_cargo'];
                                            $descricao_vaga = limitarTexto($conteudo['descricao_vaga'], 70);
                                            echo "<option value='$id_vaga'>(#ID: $id_vaga | $descricao_cargo) Descrição:  $descricao_vaga</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info text-center" role="alert">
                                    Seleção só entrará em vigor após a criação das etapas!
                                </div>
                            </div>
                        </div>
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
    $(document).ready(function(){
        $('.js-example-basic-multiple').select2({
            maximumSelectionLength: 1,
            language: {
                maximumSelected: function (e) {
                    var t = "Só é possivel selecionar " + e.maximum + " opção";
                    e.maximum != 1 && (t += "s");
                    return t;
                }
            }
        });
    });

    $(document).on('submit', '#selecao', function () {

        var descricao = $('#descricao').val();
        var netapas = $('#netapas').val();
        var nvagas = $('#nvagas').val();

        if (descricao == '') {
            alert('Preencha o campo DESCRIÇÂO');
            return false;
        }

        if (netapas == '') {
            alert('Preencha o campo NÚMERO DE ETAPAS');
            return false;
        }
        
        if (nvagas == '') {
            alert('Preencha o campo NÚMERO DE VAGAS');
            return false;
        }

        modalAguarde();
    });

    function selectCargo(id_setor, id_cargo){        
        //$("select[name=setor]").html('<option value="">Carregando...</option>');
        $.post("/api/ajax?class=SelectCargo.php",
            {setor: id_setor,
            token: '<?= $request->token ?>'},
            function(valor){
                $("select[name=cargo]").html(valor);
                if(id_cargo != undefined){
                    $('#cargo').val(id_cidade);
                }
            }
        )        
    }

    $(document).on('change', 'select[name=setor]', function(){
        selectCargo($(this).val());
    });

</script>