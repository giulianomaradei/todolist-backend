<?php
require_once(__DIR__."/../class/System.php");


if(isset($_GET['alterar'])){
    
    $id = (int)$_GET['alterar'];

    $dados_grupo = DBRead('', 'tb_grupo_ferramenta', "WHERE id_grupo_ferramenta = $id");

    if($dados){
        $tituloPainel = 'Alterar';
        $operacao = 'alterar';
        $nome_grupo = $dados_grupo[0]['nome'];
    }else{
        echo "<div class='alert alert-danger text-center'><i class='fa fa-window-close' aria-hidden='true'></i> Erro! Não foi possível localizar os dados. <a href='/api/iframe?token=<?php echo $request->token ?>&view=quadro-informativo'>Clique para voltar.</a></div>";
        exit;
    }
    
}else {
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $nome_grupo = '';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> ferramenta:</h3>
                    <?php if (isset($_GET['alterar'])) {
                        
                        echo "<div class=\"panel-title text-right pull-right\"><a href=\"/api/ajax?class=Ferramenta.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; }else{ modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; 
                    } ?>
                </div>
                <form method="post" action="/api/ajax?class=Ferramenta.php" id="grupo_ferramenta_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Nome do Grupo:</label>
                                    <input type="text" class="form-control input-sm" name="nome_grupo" value="<?=$nome_grupo?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading clearfix">
                                        <h3 class="panel-title text-left pull-left">Itens:</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class='table-responsive'>
                                            <table class='table table-bordered' style='font-size: 14px;'>
                                                <thead>
                                                    <tr>
                                                        <th class='col-md-3'>*Nome</th>
                                                        <th class='col-md-4'>Link</th>
                                                        <th class="col-md-4">Observação</th>
                                                        <th class='col-md-1'>Ação</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if($operacao == "alterar"){
                                                        $dados_itens = DBRead('', 'tb_grupo_ferramenta_item', "WHERE id_grupo_ferramenta = $id");
                                                        foreach($dados_itens as $conteudo){
                                                            echo "<tr class='linha_item'>";
                                                                echo "<td><input class='form-control input-sm nome' name='nome[]' required value='".$conteudo['nome']."' /></td>";
                                                                echo "<td><input class='form-control input-sm link' name='link[]' value='".$conteudo['link']."' /></td>";
                                                                echo "<td><textarea class='form-control input-sm observacao' name='observacao[]'>".$conteudo['observacao']."</textarea></td>";
                                                                echo "<td><button type='button' class='center-block btn btn-danger btn-sm removeLinha' role='button'><i class='fa fa-trash-o' aria-hidden='true'></i></button></td>";
                                                            echo "</tr>";
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><button type="button" class='center-block btn btn-warning btn-sm' id='adiciona-item' role='button'><i class='fa fa-plus' aria-hidden='true'></i></button></td>
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
    $("#adiciona-item").on('click', function(){
        $("tbody").append("<tr class='linha_item'><td><input required class='form-control input-sm nome' name='nome[]' /></td><td><input class='form-control input-sm link' name='link[]' /></td><td><textarea class='form-control input-sm observacao' name='observacao[]'></textarea></td><td><button class='center-block btn btn-danger btn-sm removeLinha' role='button'><i class='fa fa-trash-o' aria-hidden='true'></i></button></td></tr>");

        $(".nome").focus();
    });

    $(document).on('click', '.removeLinha', function(){
        if(confirm('Deseja excluir o usuário?')) {
            $(this).parent().parent().remove();
        }
        return false;
    });
   
    $(document).on('submit', '#grupo_ferramenta_form', function () {
        var id_contrato_plano_pessoa = $("#id_contrato_plano_pessoa").val();
        var nome = $(".nome").val();
        if(!nome){
            alert("Deve-se escrever um item válido!");
            return false;
        }

        if(!$("tr.linha_item").length){
            alert("Deve haver pelo menos um item!");
            return false;
        }

        modalAguarde();
    });
</script>