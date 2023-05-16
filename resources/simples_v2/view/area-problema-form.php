<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_area_problema', "WHERE id_area_problema = $id");
    $nome = $dados[0]['nome'];
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $nome = '';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> área de problema:</h3>
<<<<<<< HEAD
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=AreaProblema.php&excluir=$id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
=======
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a href=\"/api/ajax?class=AreaProblema.php?excluir=$id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
                </div>
                <form method="post" action="/api/ajax?class=AreaProblema.php" id="area_problema_form" style="margin-bottom: 0;">
                    <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Nome:</label>
                                    <input name="nome" id="nome" autofocus type="text" class="form-control input-sm" value="<?= $nome; ?>" autocomplete="off" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading clearfix">
                                        <h3 class="panel-title text-left pull-left">Subárea de problema:</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class='table-responsive'>
                                            <table class='table table-bordered' style='font-size: 14px;'>
                                                <thead>
                                                    <tr>
                                                        <th class='col-md-11'>Descrição</th>
                                                        <th class='col-md-1'>Ação</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if($operacao == "alterar"){
                                                        $dados_subarea = DBRead('', 'tb_subarea_problema', "WHERE id_area_problema = $id");
                                                        foreach($dados_subarea as $conteudo){
                                                            echo "<tr class='linha_subarea'>";
                                                                echo "<input type='hidden' name='id_subarea_problema[]' value='".$conteudo['id_subarea_problema']."' />";
                                                                echo "<td><input required class='form-control input-sm descricao' name='descricao[]' value='".$conteudo['descricao']."' /></td>";
                                                                echo "<td><button type='button' class='center-block btn btn-danger btn-sm removeLinha' dt-id='".$conteudo['id_subarea_problema']."' role='button'><i class='fa fa-trash-o' aria-hidden='true'></i></button></td>";
                                                            echo "</tr>";
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td></td>
                                                        <td><button type="button" class='center-block btn btn-warning btn-sm' id='adiciona-subarea' role='button'><i class='fa fa-plus' aria-hidden='true'></i></button></td>
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

    $("#adiciona-subarea").on('click', function(){
        $("tbody").append("<tr class='linha_subarea'><input type='hidden' name='id_subarea_problema[]' id='' value='-1' /><td><input required class='form-control input-sm descricao' name='descricao[]' /></td><td><button class='center-block btn btn-danger btn-sm removeLinha' role='button'><i class='fa fa-trash-o' dt-id='' aria-hidden='true'></i></button></td></tr>");

        $(".usuario").focus();
    });
    $(document).on('click', '.removeLinha', function(){
        var linha = $(this);
        if(confirm('Deseja excluir a subárea?')) {
            if($(this).attr('dt-id')){
                subarea_problema = $(this).attr('dt-id');
<<<<<<< HEAD
                 $.post("/api/ajax/class=AreaProblema.php",
                    {   excluir_subarea: subarea_problema},
=======
                 $.post("/api/ajax?class=AreaProblema.php",
                    {   excluir_subarea: subarea_problema,
                        token: '<?= $request->token ?>'},
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
                    function(valor){
                        if(valor == 0){
                            alert('Não foi posssível excluir a subárea!');
                        }else{
                            linha.parent().parent().remove();
                        }
                    }
                ) 
            }else{
                linha.parent().parent().remove();
            }            
        }
        
    });

    $(document).on('submit', '#area_problema_form', function (){
        var nome = $("#nome").val();
        if(!nome){
            alert("Deve-se descrever um nome válido!");
            return false;
        }

        modalAguarde();
    });
</script>