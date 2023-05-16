<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_ferias', "WHERE id_ferias = $id");
    $usuario = $dados[0]['id_usuario'];
    $data_de = converteDataHora($dados[0]['data_de']);
    $data_ate = converteDataHora($dados[0]['data_ate']);
    $disabled = 'disabled';
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $texto = '';
    $disabled = '';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> férias/afastamento:</h3>
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=Ferias.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
                </div>
                <form method="post" action="/api/ajax?class=Ferias.php" id="resposta_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <?php if($operacao == 'alterar'){
                                echo '<input type="hidden" id="usuario" value="'.$usuario.'" name="usuario">';
                        }?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">*Atendente:</label>
                                    <select name="usuario" class="form-control input-sm" <?= $disabled?>>
                                        <option value=""></option>
                                            <?php
                                            $dados_usuarios = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = '1' AND (id_perfil_sistema = '13' OR id_perfil_sistema = '3' OR id_perfil_sistema = '15') ORDER BY b.nome ASC", "a.id_usuario, b.nome");
                                            if ($dados_usuarios) {

                                                foreach ($dados_usuarios as $conteudo_usuarios) {
                                                    $selected = $usuario == $conteudo_usuarios['id_usuario'] ? "selected" : "";

                                                    echo "<option value='" . $conteudo_usuarios['id_usuario'] . "' ".$selected.">" . $conteudo_usuarios['nome'] . "</option>";
                                                }
                                            }
                                            ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" >
                                    <label>*Data Inicial das Férias/Afastamento:</label>
                                    <input type="text" class="form-control date calendar input-sm" name="data_de" value="<?=$data_de?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Data Final das Férias/Afastamento:</label>
                                    <input type="text" class="form-control date calendar input-sm" name="data_ate" value="<?=$data_ate?>" required>
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

    $(document).on('submit', '#resposta_form', function () {
        modalAguarde();
    });

    $(document).on('click', '#ok', function(){
        
        var data_de = $( "input[name='data_de']" ).val();
        var data_ate = $( "input[name='data_ate']" ).val();

        var ano1 = data_de.split("/")[2].toString();
        var mes1 = data_de.split("/")[1].toString();
        var dia1 = data_de.split("/")[0].toString();

        var ano2 = data_ate.split("/")[2].toString();
        var mes2 = data_ate.split("/")[1].toString();
        var dia2 = data_ate.split("/")[0].toString();

        var data1 = ano1+''+mes1+''+dia1;
        var data2 = ano2+''+mes2+''+dia2;

        if(!data_de){
            alert("Deve-se incluir uma data inicial válida!");
            return false;
        }
        if(!data_ate){
            alert("Deve-se incluir uma data final válida!");
            return false;
        }

        if (data1 > data2) {
            alert('Deve-se colocar uma data inicial maior que a data final!');
            return false;
        }

        modalAguarde();
    });

</script>