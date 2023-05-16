<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_feriado', "WHERE id_feriado = $id");
    $nome = $dados[0]['nome'];
    $fixo = $dados[0]['fixo'];
    $data = explode('-', $dados[0]['data']);
    $dia = $data[1];
    $mes = $data[0];
    $tipo = $dados[0]['tipo'];
    $cidade = $dados[0]['id_cidade'] ? $dados[0]['id_cidade'] : '9999999';
	$estado = $dados[0]['id_estado'] ? $dados[0]['id_estado'] : '99';
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $nome = '';
    $fixo = 1;
    $dia = '01';
    $mes = '01';
    $tipo = 'Nacional';
    $cidade = '9999999';
	$estado = '99';
}

if($tipo == 'Nacional'){
    $display_col_estado = 'style="display:none;"';
    $col_estado = 12;
    $display_col_cidade = 'style="display:none;"';
    $col_cidade = 12;
}else if($tipo == 'Estadual'){
    $display_col_estado = '';
    $col_estado = 12;
    $display_col_cidade = 'style="display:none;"';
    $col_cidade = 9;
}else{
    $display_col_estado = '';
    $col_estado = 3;
    $display_col_cidade = '';
    $col_cidade = 9;
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> feriado:</h3>
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=Feriado.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
                </div>
                <form method="post" action="/api/ajax?class=Feriado.php" id="feriado_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">                        

                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>*Nome:</label>
                                    <input name="nome" autofocus id="nome" type="text" class="form-control input-sm" value="<?= $nome; ?>" autocomplete="off" required>
                                </div>
                            </div>
						</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Dia:</label>
                                    <select class="form-control input-sm" name="dia" required>
                                        <?php
                                            $cont = 1;
                                            while($cont <= 31){
                                                $cont_zero = sprintf('%02d', $cont);
                                                $selected = ($cont_zero == $dia) ? 'selected' : '';
                                                echo "<option value='$cont_zero' $selected>$cont_zero</option>";
                                                $cont++;
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Mês:</label>
                                    <select class="form-control input-sm" name="mes" required>
                                        <?php
                                            $meses = array(
                                                '01' => 'Janeiro',
                                                '02' => 'Fevereiro',
                                                '03' => 'Março',
                                                '04' => 'Abril',
                                                '05' => 'Maio',
                                                '06' => 'Junho',
                                                '07' => 'Julho',
                                                '08' => 'Agosto',
                                                '09' => 'Setembro',
                                                '10' => 'Outubro',
                                                '11' => 'Novembro',
                                                '12' => 'Dezembro',
                                            );
                                            $cont = 1;
                                            while($cont <= 12){
                                                $cont_zero = sprintf('%02d', $cont);
                                                $selected = ($cont_zero == $mes) ? 'selected' : '';
                                                echo "<option value='$cont_zero' $selected>".$meses[$cont_zero]."</option>";
                                                $cont++;
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
						</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*É fixo:</label>
                                    <select class="form-control input-sm" name="fixo" required>
                                        <option value='1' <?php if($fixo == 1){echo 'selected';}?>>Sim</option>
                                        <option value='0' <?php if($fixo == 0){echo 'selected';}?>>Não</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Tipo:</label>
                                    <select class="form-control input-sm" name="tipo" required>
                                        <option value='Nacional' <?php if($tipo == 'Nacional'){echo 'selected';}?>>Nacional</option>
                                        <option value='Estadual' <?php if($tipo == 'Estadual'){echo 'selected';}?>>Estadual</option>
                                        <option value='Municipal' <?php if($tipo == 'Municipal'){echo 'selected';}?>>Municipal</option>
                                    </select>
                                </div>
                            </div>
						</div>
                        <div class="row">
                            <div class="col-md-<?=$col_estado?>" id="col_estado" <?=$display_col_estado?>>
                                <div class="form-group">
                                    <label>UF:</label>
                                    <select class="form-control input-sm" name="estado" id="estado" required>
                                        <option value='99'>ND</option>
                                        <option value='' disabled>----------</option>
                                        <?php
                                        $dados = DBRead('', 'tb_estado', "WHERE id_estado != '99' ORDER BY nome ASC");
                                        if($dados){
                                            foreach($dados as $conteudo){
                                                $idSelect = $conteudo['id_estado'];
                                                $selected = $estado == $idSelect ? "selected" : "";
                                                $estadoSelect = $conteudo['nome'];

                                                echo "<option value='$idSelect' $selected >$estadoSelect</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-<?=$col_cidade?>" id="col_cidade" <?=$display_col_cidade?>>
                                <div class="form-group">
                                    <label>Cidade:</label>
                                    <select class="form-control input-sm" id="cidade" name="cidade" required>
                                        <?php
                                        if($estado){
                                            $dados = DBRead('', 'tb_cidade', "WHERE id_estado = '$estado' ORDER BY nome ASC");
                                            if($dados){
                                              
                                                $selected = $idSelect == $cidade ? "selected" : "";
                                                foreach($dados as $conteudo){
                                                    $idSelect = $conteudo['id_cidade'];
                                                    $cidadeSelect = $conteudo['nome'];
                                                    echo "<option value='$idSelect' $selected>$cidadeSelect</option>";
                                                }
                                            }
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
</div>
<script>
    $(document).on('submit', '#feriado_form', function () {
        modalAguarde();
    });

    function selectUfEstado(id_estado, id_cidade){
        $("select[name=cidade]").html('<option value="">Carregando...</option>');
        $.post("/api/ajax?class=SelectUfCidade.php",
            {estado: id_estado,
            token: '<?= $request->token ?>'},
            function(valor){
                $("select[name=cidade]").html(valor);
                if(id_cidade != undefined){
                    $('#cidade').val(id_cidade);
                }
            }
        )
    }

    $(document).on('change', 'select[name=estado]', function(){
        selectUfEstado($(this).val());
    });

    $(document).on('change', 'select[name=tipo]', function(){
        if($(this).val() == 'Nacional'){
            $('#col_estado').hide();
            $('#col_cidade').hide();
        }else if($(this).val() == 'Estadual'){
            $('#col_estado').removeClass('col-md-12').removeClass('col-md-3').addClass('col-md-12');
            $('#col_estado').show();
            $('#col_cidade').hide();
        }else{
            $('#col_estado').removeClass('col-md-12').removeClass('col-md-3').addClass('col-md-3');
            $('#col_cidade').removeClass('col-md-12').removeClass('col-md-9').addClass('col-md-9');
            $('#col_estado').show();
            $('#col_cidade').show();
        }
    });
</script>