<?php
require_once(__DIR__."/../class/System.php");

if(isset($_GET['alterar'])){
    
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_integracao_parametro', "WHERE id_integracao_parametro = $id");
    if($dados){

        $tituloPainel = 'Alterar';
        $operacao = 'alterar';
        $codigo = $dados[0]['codigo'];
        $nome = $dados[0]['nome'];
        $tipo = $dados[0]['tipo'];
        $obrigatorio = $dados[0]['obrigatorio'];
        $id_integracao = $dados[0]['id_integracao'];

    }else{

<<<<<<< HEAD
        echo "<div class='alert alert-danger text-center'><i class='fa fa-window-close' aria-hidden='true'></i> Erro! Não foi possível localizar os dados. <a href='/api/iframe?token=<?php echo $request->token ?>&view=quadro-informativo'>Clique para voltar.</a></div>";
=======
        echo "<div class='alert alert-danger text-center'><i class='fa fa-window-close' aria-hidden='true'></i> Erro! Não foi possível localizar os dados. <a href='/api/iframe?token=".$request->token."&view=quadro-informativo'>Clique para voltar.</a></div>";
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
        exit;
    }
    
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $codigo = "";
    $nome = "";
    $tipo = "";
    $obrigatorio = 0;
}
?>
<div class="container-fluid">

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> parâmetros de integração:</h3>
                </div>
                <form method="post" action="/api/ajax?class=ParametrosIntegracao.php" id="parametros_integracao_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label>Sistema de gestão:</label>
                                    <select class="form-control" id="id_integracao" name="id_integracao">
                                        <?php
                                        $sistemas_integrados = DBRead('', 'tb_integracao', "ORDER BY nome ASC");
                                        foreach($sistemas_integrados as $sistema){
                                            $idIntegracao = $sistema['id_integracao'];
                                            $selected = $id_integracao == $idIntegracao ? "selected" : "";
                                            echo "<option value=".$sistema['id_integracao']." ".$selected.">".$sistema['nome']."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label style="display: block">Obrigatório:</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="obrigatorio" value="1" <?=$obrigatorio == 1 ? 'checked' : ''?>> Sim
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="obrigatorio" value="0" <?=$obrigatorio == 0 ? 'checked' : ''?>> Não
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Código (name):</label>
                                    <input type="text" class="form-control" name="codigo" id="codigo" value="<?=$codigo?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nome:</label>
                                    <input type="text" class="form-control" name="nome" id="nome" value="<?=$nome?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo (type):</label>
                                    <select class="form-control" name="tipo" id="tipo">
                                        <option value="text" <?=$tipo == 'text' ? 'selected' : ''?>>text</option>
                                        <option value="radio" <?=$tipo == 'radio' ? 'selected' : ''?>>radio</option>
                                        <option value="select" <?=$tipo == 'select' ? 'selected' : ''?>>select</option>
                                        <option value="checkbox" <?=$tipo == 'checkbox' ? 'selected' : ''?>>checkbox</option>
                                        <option value="select_recurso" <?=$tipo == 'select_recurso' ? 'selected' : ''?>>select recurso da API</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row" id="valores">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading clearfix">
                                        <h3 class="panel-title text-left pull-left">Valores:</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class='table-responsive'>
                                            <table class='table table-bordered' style='font-size: 14px;'>
                                                <thead>
                                                    <tr>
                                                        <th class='col-md-11'>*Valor</th>
                                                        <th class='col-md-1'>Ação</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if($operacao == "alterar"){
                                                        $dados_parametros_integracao = DBRead('', 'tb_integracao_valores_tipo_parametro', "WHERE id_integracao_parametro = $id");
                                                        if($dados_parametros_integracao){
                                                            foreach($dados_parametros_integracao as $conteudo){
                                                                echo "<tr class='linha_titulo'>";
                                                                    echo "<td><input required class='form-control input-sm titulo' name='titulo[]' value='".$conteudo['titulo']."' /></td>";
                                                                    echo "<td><button type='button' class='center-block btn btn-danger btn-sm removeLinha' role='button'><i class='fa fa-trash-o' aria-hidden='true'></i></button></td>";
                                                                echo "</tr>";
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td></td>
                                                        <td><button type="button" class='center-block btn btn-warning btn-sm' id='adiciona-titulo' role='button'><i class='fa fa-plus' aria-hidden='true'></i></button></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>" />
                                <button class='btn btn-primary' name='salvar' id='ok' type='submit'><i class='fa fa-floppy-o'></i> Salvar</button>
                            </div>
                        </div>
                    </div>

                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    $("#adiciona-titulo").on('click', function(){
        $("tbody").append("<tr class='linha_titulo'><td><input required class='form-control input-sm titulo' name='titulo[]' /></td><td><button class='center-block btn btn-danger btn-sm removeLinha' role='button'><i class='fa fa-trash-o' aria-hidden='true'></i></button></td></tr>");

        $(".titulo").focus();
    });

    <?php
    if($tipo == "text" || !$_GET['alterar']):
    ?>
    $("#valores").hide();
    $(".bloco-recurso").hide();
    <?php
    endif;
    ?>

    $("#tipo").on('change', function(){
        if($(this).val() == 'text'){
            $("#valores").hide();
            $(".bloco-recurso").hide();
            $(".linha_acesso_equipamentos").remove();
        }else if($(this).val() == 'select_recurso'){
            $(".bloco-recurso").show();
            $("#valores").hide();
            $(".linha_acesso_equipamentos").remove();
        }else if($(this).val() != 'text'){
            $(".bloco-recurso").hide();
            $("#valores").show();
        }
    });

    $(document).on('click', '.removeLinha', function(){
        if(confirm('Deseja excluir o valor?')){
            $(this).parent().parent().remove();
        }
        return false;
    });
</script>