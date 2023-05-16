<?php
require_once(__DIR__."/../class/System.php");

$dados = DBRead('', 'tb_painel_horarios');

$data = converteDataHora($dados[0]['data_cadastro']);

if ($dados) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = 1;

} else {
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
}

?>
<div class="container-fluid">

    <?php if (!$dados) { ?>
    
        <div class="alert alert-warning alert-dismissible" role="alert" style="text-align: center">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button><strong>Não há horários cadastrados!</strong>
        </div>
    
    <?php } ?>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> horários (Painel do cliente):
                    </h3>
                    <div class="panel-title text-right pull-right">
                        <?php if ($dados) { ?>    
                            <span>Data da última atualização:<?= $data ?></span>
                        <?php } ?>
                    </div>
                </div>
                <form method="post" action="/api/ajax?class=PainelClienteHorarios.php" id="horario_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class='table-responsive'>
                                    <table class='table table-bordered' style='font-size: 14px;'>
                                        <thead>
                                            <tr>
                                                <th class='col-md-6'>*Descrição do Setor</th>
                                                <th class='col-md-6'>*Descrição do(s) horário(s)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if ($dados) {
                                                    foreach ($dados as $conteudo) {
                                            ?>
                                                    <tr class='linha_horario'>
                                                        <td class="col-md-6">
                                                            <input class='form-control descricao_setor' name='descricao_setor[]' value="<?= $conteudo['descricao_setor'] ?>"/>
                                                        </td>
                                                        <td class="col-md-6">
                                                            <textarea class='form-control descricao_horario' name='descricao_horario[]' rows="5"><?= $conteudo['descricao_horarios'] ?>
                                                            </textarea>
                                                        </td>
                                                        <td>
                                                            <button class='center-block btn btn-danger btn-sm removeLinha' role='button'><i class='fa fa-trash-o' aria-hidden='true'></i></button>
                                                        </td>
                                                    </tr>
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td><button type="button" class='center-block btn btn-warning btn-sm' id='adiciona-horario' role='button'><i class='fa fa-plus' aria-hidden='true'></i></button></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>                                
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>" />
                                <button class='btn btn-primary' name='salvar' id='ok' type='submit'>
                                    <i class='fa fa-floppy-o'></i> Salvar
                                </button>
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

    $("#adiciona-horario").on('click', function(){

        $("tbody").append("<tr class='linha_horario'><td><input class='form-control descricao_setor' name='descricao_setor[]'/></td><td><textarea class='form-control descricao_horario' name='descricao_horario[]' rows='5'></textarea></td><td><button class='center-block btn btn-danger btn-sm removeLinha' role='button'><i class='fa fa-trash-o' aria-hidden='true'></i></button></td></tr>");
    });

    $(document).on('click', '.removeLinha', function(){
        if(confirm('Deseja excluir o horário?')){
            $(this).parent().parent().remove();
        }
        return false;
    });;

    $(document).on('submit', '#horario_form', function(){
        var naoSalva = 0;
        
        if (!$("tr.linha_horario").length) {
            alert("Deve haver pelo menos um horário descrito!");
            return false;
        }

        $("tr.linha_horario").each(function(index_primeiro){
            descricao_setor = $(this).find(".descricao_setor").val();
            descricao_horario = $(this).find(".descricao_horario").val();

            if (descricao_setor == '' || descricao_horario == '') {
                naoSalva = 1;
            }
        });

        if (naoSalva != 0) {
            alert('Informe a Descrição do(s) setor(es) e do(s) horário(s)');
            return false;
        }

        modalAguarde();
    });

</script>

