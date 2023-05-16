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

        $dados = DBRead('', 'tb_monitoria_mes', "WHERE id_monitoria_mes = '$id' ");
        
        $qtd_audios_monitoria_meio_turno_sn = $dados[0]['qtd_audios_monitoria_meio_turno_sn'];
        $qtd_audios_monitoria_meio_turno_n1 = $dados[0]['qtd_audios_monitoria_meio_turno_n1'];
        $qtd_audios_monitoria_meio_turno_n2 = $dados[0]['qtd_audios_monitoria_meio_turno_n2'];
        $qtd_audios_monitoria_meio_turno_n3 = $dados[0]['qtd_audios_monitoria_meio_turno_n3'];
        $qtd_audios_monitoria_meio_turno_n4 = $dados[0]['qtd_audios_monitoria_meio_turno_n4'];
        $qtd_audios_monitoria_meio_turno_n5 = $dados[0]['qtd_audios_monitoria_meio_turno_n5'];
        $qtd_audios_monitoria_integral_sn = $dados[0]['qtd_audios_monitoria_integral_sn'];
        $qtd_audios_monitoria_integral_n1 = $dados[0]['qtd_audios_monitoria_integral_n1'];
        $qtd_audios_monitoria_integral_n2 = $dados[0]['qtd_audios_monitoria_integral_n2'];
        $qtd_audios_monitoria_integral_n3 = $dados[0]['qtd_audios_monitoria_integral_n3'];
        $qtd_audios_monitoria_integral_n4 = $dados[0]['qtd_audios_monitoria_integral_n4'];
        $qtd_audios_monitoria_integral_n5 = $dados[0]['qtd_audios_monitoria_integral_n5'];
        $qtd_texto_monitoria_meio_turno = $dados[0]['qtd_texto_monitoria_meio_turno'];
        $qtd_texto_monitoria_integral = $dados[0]['qtd_texto_monitoria_integral'];

        $data_referencia = $dados[0]['data_referencia'];
        $qtd_audios = $dados[0]['qtd_audios_monitoria'];
        $tipo = $dados[0]['tipo_monitoria'];
        $classificacao = $dados[0]['classificacao_atendente'];

        if ($tipo == 1) {
            $display_telefone = 'block;';
            $display_texto = 'none;';

        } else if ($tipo == 2) {
            $display_telefone = 'none;';
            $display_texto = 'block;';
        }

        $arrayData = explode("-",$data_referencia);

        $mes = $arrayData[1];
        $ano = $arrayData[0];
        
        /* echo '<pre>';
        var_dump($dados);
        echo '</pre>'; */

    }else{
        $tituloPainel = 'Inserir';
        $operacao = 'inserir';
        $id = 1;
        $display_telefone = 'block;';
        $display_texto = 'none;';

        $readOnly = '';
    }

?>

<style>
    td:hover{
		cursor:move;
	}
    .select2-dropdown--above {
        border: 1px solid#E6E6E6 !important;
        border-bottom: none !important;  
    }
    .select2-dropdown--below{
        border: 1px solid #E6E6E6 !important;
        border-top: none !important;     
    }
    .select2-selection{
        border: 1px solid #ccc !important;
    }
    .select2{
        width: 100% !important;
    }
    .select2-selection__rendered{
        max-width: 550px !important;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> formulário de avaliação:</h3>
                    <div class="panel-title text-right pull-right">
                    </div>
                </div>
                <form method="post" action="/api/ajax?class=MonitoriaFormulario.php" id="monitoria_formulario" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body">
                        <!-- row busca-->
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Mês:</label>
                                    <?php 
                                        $sel_mes[$mes] = 'selected';
                                    ?>
                                    <select class="form-control input-sm" id="mes" name="mes" required>
                                        <option value="" <?=$sel_mes['']?>>Selecione</option>
                                        <option value="01" <?=$sel_mes['01']?>>Janeiro</option>
                                        <option value="02" <?=$sel_mes['02']?>>Fevereiro</option>
                                        <option value="03" <?=$sel_mes['03']?>>Março</option>
                                        <option value="04" <?=$sel_mes['04']?>>Abril</option>
                                        <option value="05" <?=$sel_mes['05']?>>Maio</option>
                                        <option value="06" <?=$sel_mes['06']?>>Junho</option>
                                        <option value="07" <?=$sel_mes['07']?>>Julho</option>
                                        <option value="08" <?=$sel_mes['08']?>>Agosto</option>
                                        <option value="09" <?=$sel_mes['09']?>>Setembro</option>
                                        <option value="10" <?=$sel_mes['10']?>>Outubro</option>
                                        <option value="11" <?=$sel_mes['11']?>>Novembro</option>
                                        <option value="12" <?=$sel_mes['12']?>>Dezembro</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Ano:</label>
                                    <?php 
                                        $sel_ano[$ano] = 'selected';
                                    ?>
                                    <select class="form-control input-sm" id="ano" name="ano" required>
                                        <option value="" <?=$sel_ano['']?>>Selecione</option>
                                        <option value="2019" <?=$sel_ano['2019']?>>2019</option>
                                        <option value="2020" <?=$sel_ano['2020']?>>2020</option>
                                        <option value="2021" <?=$sel_ano['2021']?>>2021</option>
                                        <option value="2022" <?=$sel_ano['2022']?>>2022</option>
                                        <option value="2023" <?=$sel_ano['2023']?>>2023</option>
                                        <option value="2024" <?=$sel_ano['2024']?>>2024</option>
                                        <option value="2025" <?=$sel_ano['2025']?>>2025</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Canal de atendimento:</label>
                                    <?php 
                                        $sel_tipo[$tipo] = 'selected';
                                    ?>
                                    <select class="form-control input-sm" id="tipo_monitoria" name="tipo_monitoria" required onChange="tipoMonitoria();">
                                        <option value="" <?=$sel_tipo['']?>>Selecione</option>
                                        <option value="1" <?=$sel_tipo['1']?>>Telefone</option>
                                        <option value="2" <?=$sel_tipo['2']?>>Texto</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Classificação atendente:</label>
                                    <?php 
                                        $sel_classificacao[$classificacao] = 'selected';
                                    ?>
                                    <select class="form-control input-sm" id="classificacao" name="classificacao" required>
                                        <option value="" <?=$sel_classificacao['']?>>Selecione</option>
                                        <option value="1" <?=$sel_classificacao['1']?>>Em treinamento</option>
                                        <option value="2" <?=$sel_classificacao['2']?>>Período de experiência</option>
                                        <option value="3" <?=$sel_classificacao['3']?>>Efetivado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--end row-->

                        <!-- row qtd audios-->
                        <div class="row" id="monitoria_telefone" style="margin-bottom: 10px; display: <?= $display_telefone ?> ">
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Quantidade de áudios para <strong>meio turno:</strong></div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>*Sem Nota</label>
                                                    <input class="form-control input-sm number_int" name="qtd_audios_monitoria_meio_turno_sn" id="qtd_audios_monitoria_meio_turno_sn" autocomplete="off" value="<?=$qtd_audios_monitoria_meio_turno_sn?>" >
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>*Nota 1</label>
                                                    <input class="form-control input-sm number_int" name="qtd_audios_monitoria_meio_turno_n1" id="qtd_audios_monitoria_meio_turno_n1"  autocomplete="off" value="<?=$qtd_audios_monitoria_meio_turno_n1?>" >
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>*Nota 2</label>
                                                    <input class="form-control input-sm number_int" name="qtd_audios_monitoria_meio_turno_n2" id="qtd_audios_monitoria_meio_turno_n2" autocomplete="off" value="<?=$qtd_audios_monitoria_meio_turno_n2?>" >
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>*Nota 3</label>
                                                    <input class="form-control input-sm number_int" name="qtd_audios_monitoria_meio_turno_n3" id="qtd_audios_monitoria_meio_turno_n3"  autocomplete="off" value="<?=$qtd_audios_monitoria_meio_turno_n3?>" >
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>*Nota 4</label>
                                                    <input class="form-control input-sm number_int" name="qtd_audios_monitoria_meio_turno_n4" id="qtd_audios_monitoria_meio_turno_n4" autocomplete="off" value="<?=$qtd_audios_monitoria_meio_turno_n4?>" >
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>*Nota 5</label>
                                                    <input class="form-control input-sm number_int" name="qtd_audios_monitoria_meio_turno_n5" id="qtd_audios_monitoria_meio_turno_n5" autocomplete="off" value="<?=$qtd_audios_monitoria_meio_turno_n5?>" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Quantidade de áudios para <strong>turno integral:</strong></div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>*Sem Nota</label>
                                                    <input class="form-control input-sm number_int" name="qtd_audios_monitoria_integral_sn" id="qtd_audios_monitoria_integral_sn" value="<?=$qtd_audios_monitoria_integral_sn?>" autocomplete="off" >
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>*Nota 1</label>
                                                    <input class="form-control input-sm number_int" name="qtd_audios_monitoria_integral_n1" id="qtd_audios_monitoria_integral_n1" value="<?=$qtd_audios_monitoria_integral_n1?>" autocomplete="off" >
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>*Nota 2</label>
                                                    <input class="form-control input-sm number_int" name="qtd_audios_monitoria_integral_n2" id="qtd_audios_monitoria_integral_n2" value="<?=$qtd_audios_monitoria_integral_n2?>" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>*Nota 3</label>
                                                    <input class="form-control input-sm number_int" name="qtd_audios_monitoria_integral_n3" id="qtd_audios_monitoria_integral_n3" value="<?=$qtd_audios_monitoria_integral_n3?>" autocomplete="off" >
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>*Nota 4</label>
                                                    <input class="form-control input-sm number_int" name="qtd_audios_monitoria_integral_n4" id="qtd_audios_monitoria_integral_n4" value="<?=$qtd_audios_monitoria_integral_n4?>" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>*Nota 5</label>
                                                    <input class="form-control input-sm number_int" name="qtd_audios_monitoria_integral_n5" id="qtd_audios_monitoria_integral_n5" value="<?=$qtd_audios_monitoria_integral_n5?>" autocomplete="off" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end row qtd audios-->

                        <!-- row qtd atendimentos via texto-->
                        <div class="row" id="monitoria_texto" style="margin-bottom: 10px; display: <?= $display_texto ?>">
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Quantidade de atendimentos para <strong>meio turno:</strong></div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>*Atendimentos via texto</label>
                                                    <input class="form-control input-sm number_int" name="qtd_texto_monitoria_meio_turno" id="qtd_texto_monitoria_meio_turno" autocomplete="off" value="<?=$qtd_texto_monitoria_meio_turno?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Quantidade de atendimentos para <strong>turno integral:</strong></div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>*Atendimentos via texto</label>
                                                    <input class="form-control input-sm number_int" name="qtd_texto_monitoria_integral" id="qtd_texto_monitoria_integral" value="<?=$qtd_texto_monitoria_integral?>" autocomplete="off" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end row qtd atendimentos via texto-->

                        <!-- row table-->
                        <div class="row">
                            <div class="col-md-12">
                            <div id="tooltip_container"></div>
                                <div class='table-responsive'>
                                    <table class='table table-bordered' style='font-size: 14px; background-color: #F2F2F2;' id="myTable">
                                        <thead>
                                            <tr>
                                                <th>Mover</th>
                                                <th class="col-md-1">Passo</th>
                                                <th class="col-md-6">Quesito</th>
                                                <th>Ordenação</th>
                                                <th>Pontos</th>
                                                <th>Pontos (Tirar)</th>
                                                <th>Percentual para plano de ação</th>
                                                <th>Ação</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                         <?php
                                            $total_pontos = 0;
                                            $total_pontos_tirar = 0;
                                            if($operacao == "alterar" || $operacao == "clonar"){
                                                $dados_quesitos = DBRead('', 'tb_monitoria_mes a', "INNER JOIN tb_monitoria_mes_quesito b ON a.id_monitoria_mes = b.id_monitoria_mes INNER JOIN tb_monitoria_quesito c ON b.id_monitoria_quesito = c.id_monitoria_quesito WHERE a.id_monitoria_mes = '$id' ");

                                                /* echo '<pre>';
                                                var_dump($dados_quesitos);
                                                echo '</pre>'; */

                                                $cont = 0;
                                                
                                                foreach($dados_quesitos as $conteudo){
                                                    $cont++;
                                                    $total_pontos += $conteudo['pontos_valor'];
                                                    $total_pontos_tirar += $conteudo['pontos_tirar'];
                                                ?>
                                                    <tr class='linha_quesito'>
                                                        <td class='text-center'>
                                                            <i class='fa fa-arrows-v' style='margin-top: 7px; font-size: 19px;'></i>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $sel_passo[$conteudo['passo_atendimento']] = 'selected';
                                                            ?>
                                                            <select class='form-control passo input-sm' required>
                                                                <option></option>
                                                                <option value="1" <?=$sel_passo['1']?> >1</option>
                                                                <option value="2" <?=$sel_passo['2']?> >2</option>
                                                                <option value="3" <?=$sel_passo['3']?> >3</option>
                                                                <option value="4" <?=$sel_passo['4']?> >4</option>
                                                                <option value="5" <?=$sel_passo['5']?> >5</option>
                                                                <option value="6" <?=$sel_passo['6']?> >6</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class='form-control quesito js-example-basic-multiple' name='quesitos[]' required>
                                                                <?php
                                                                    $quesito = DBRead('', 'tb_monitoria_quesito', "WHERE passo_atendimento = '".$conteudo['passo_atendimento']."' ");

                                                                    foreach($quesito as $conteudo_quesitos){
                                                                    $selected = $conteudo['id_monitoria_quesito'] == $conteudo_quesitos['id_monitoria_quesito'] ? "selected" : "";
                                                                ?>
                                                                    <option value="<?=$conteudo_quesitos['id_monitoria_quesito']?>" <?=$selected?>><?=$conteudo_quesitos['descricao']?></option>
                                
                                                                <?php

                                                                    }
                                                                ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input class='form-control posicao input-sm' name='posicao[]' id='index' value='<?=$cont?>' readOnly>
                                                        </td>
                                                        <td>
                                                            <input class='form-control pontos number_int input-sm' name='pontos[]' value='<?=$conteudo['pontos_valor']?>' autocomplete='off' onkeyup='somaPontos()' required>
                                                        </td>
                                                        <td>
                                                            <input class='form-control pontos-tirar number_int' name='pontos_tirar[]' value='<?=$conteudo['pontos_tirar']?>' onkeyup='somaPontosTirar()' autocomplete='off' required>
                                                        </td>
                                                        <td>
                                                            <input class="form-control number_float input-sm" name="porcentagem[]" autocomplete="off" required="" placeholder="0.00" maxlength="18" value="<?=$conteudo['porcentagem_plano_acao']?>">
                                                        </td>
                                                        <td>
                                                            <button class='center-block btn btn-danger btn-sm removeLinha' role='button'>
                                                                <i class='fa fa-trash-o' aria-hidden='true'></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                            }

                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="index">
                                                <td class="indexInput"></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <strong>Total:</strong>
                                                    <span id="t_pontos"><?=$total_pontos?></span>
                                                </td>
                                                <td>
                                                    <strong>Total:</strong>
                                                    <span id="t_pontos_tirar"><?=$total_pontos_tirar?></span>
                                                </td>
                                                <td></td>
                                                <td>
                                                    <button type="button" class='center-block btn btn-warning btn-sm' id='adiciona-quesito' role='button'>
                                                        <i class='fa fa-plus' aria-hidden='true'></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <input type="hidden" name="total_pontos" id="total_pontos" value="<?=$total_pontos?>">
                                    <input type="hidden" name="total_pontos_tirar" id="total_pontos_tirar" value="<?=$total_pontos_tirar?>">
                                </div>
                            </div>
                        </div>
                        <!--end row table-->
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?=$id?>" name="<?=$operacao;?>"/>
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit">
                                    <i class="fa fa-floppy-o"></i> Salvar
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet "type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

<script>

    $('.js-example-basic-multiple').select2();

    var fixHelperModified = function(e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();

        $helper.children().each(function(index) {
            $(this).width($originals.eq(index).width())
        });

		return $helper;
	},

    updateIndex = function(e, ui) {
        $('td.index', ui.item.parent()).each(function (i) {
            $(this).html(i+1);
        });
        $('.posicao', ui.item.parent()).each(function (i) {
            $(this).val(i + 1);
        });
    };

	$("#myTable tbody").sortable({
		helper: fixHelperModified,
		stop: updateIndex
	}).disableSelection();
	
    $("tbody").sortable({
        distance: 5,
        delay: 100,
        opacity: 0.6,
        cursor: 'move',
        update: function() {}
    });

    $("#adiciona-quesito").on('click', function(){

        var rowCount = parseInt($('#myTable tr').length) - parseInt(1);

        $("tbody").append("<tr class='linha_quesito'>" + 
            "<td class='text-center'><i class='fa fa-arrows-v' style='margin-top: 7px; font-size: 19px;'></i></td>" +
            "<td><select class='form-control passo input-sm' required><option></option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option></select></td>"+
            "<td><select class='form-control quesito js-example-basic-multiple' name='quesitos[]' required></select></td>" + 
            "<td><input class='form-control posicao input-sm' name='posicao[]' id='index' value='"+rowCount+"' readOnly></td>"+ 
            "<td><input class='form-control pontos number_int input-sm' name='pontos[]' onkeyup='somaPontos()' autocomplete='off' required></td>"+
            "<td><input class='form-control pontos-tirar number_int input-sm' name='pontos_tirar[]' onkeyup='somaPontosTirar()' autocomplete='off' required></td>"+
            "<td><input class='form-control number_float input-sm' name='porcentagem[]' autocomplete='off' required></td>"+
            "<td><button class='center-block btn btn-danger btn-sm removeLinha' role='button'><i class='fa fa-trash-o' aria-hidden='true'></i></button></td></tr>");
        $(".nome").focus();

        $('.js-example-basic-multiple').select2();

    });

    $(document).on('click', '.removeLinha', function(){
        if(confirm('Deseja excluir a configuração?')){
            $(this).parent().parent().remove();
            
            $('.posicao').each(function (i) {
                $(this).val(i + 1);
            });
        }
        return false;
    });

    $(document).on('change', '.passo', function(){

        var passo_atendimento = $(this).val();
        obj = $(this);

        $.ajax({
            type: "POST",
            url: "/api/ajax?class=SelectQuesito.php",
            dataType: "json",
            data: {
               'passo_atendimento': passo_atendimento,
               token: '<?= $request->token ?>'
            },
            success: function(data){              
                obj.parent().parent().find('.quesito').html(data['dados']);

                $(document).ready(function() {
                    $('[data-toggle="tooltip"]').tooltip();
                });
            },
        });
    });

    function somaPontos(){

        soma = 0;
        $(".pontos").each(function(i, e){
            soma = parseInt(soma) + parseInt($(this).val());
        });

        if(isNaN(soma)){
            soma = 0;
            $('#t_pontos').text(soma);
            $('#total_pontos').val(soma);
        }else{
            $('#t_pontos').text(soma);
            $('#total_pontos').val(soma);
        }
    }

    function somaPontosTirar(){

        soma = 0;
        $(".pontos-tirar").each(function(i, e){
            soma = parseInt(soma) + parseInt($(this).val());
        });

        if(isNaN(soma)){
            var soma = 0;
            $('#t_pontos_tirar').text(soma);
            $('#total_pontos_tirar').val(soma);
        }else{
            $('#t_pontos_tirar').text(soma);
            $('#total_pontos_tirar').val(soma);
        }
    }

    function tipoMonitoria(){
        var tipo = $('#tipo_monitoria').val();

        if (tipo == 1) {
            $('#monitoria_texto').hide();
            $('#monitoria_telefone').show();

        } else if (tipo == 2) {
            $('#monitoria_telefone').hide();
            $('#monitoria_texto').show();
        }
    }

    $(document).on('submit', '#monitoria_formulario', function (event) {

        var mes = $('#mes').val();
        var ano = $('#ano').val();
        var tipo_monitoria = $('#tipo_monitoria').val();
        var classificacao = $('#classificacao').val();

        if (mes == '') {
            alert('Selecione o mês!');
            return false;
        }

        if (ano == '') {
            alert('Selecione o ano!');
            return false;
        }

        if (tipo_monitoria == '') {
            alert('Selecione o tipo!');
            return false;
        }

        if (classificacao == '') {
            alert('Selecione a classificação!');
            return false;
        }

        var qtd_audios_monitoria_meio_turno_sn = $('#qtd_audios_monitoria_meio_turno_sn').val();
        var qtd_audios_monitoria_meio_turno_n1 = $('#qtd_audios_monitoria_meio_turno_n1').val();
        var qtd_audios_monitoria_meio_turno_n2 = $('#qtd_audios_monitoria_meio_turno_n2').val();
        var qtd_audios_monitoria_meio_turno_n3 = $('#qtd_audios_monitoria_meio_turno_n3').val();
        var qtd_audios_monitoria_meio_turno_n4 = $('#qtd_audios_monitoria_meio_turno_n4').val();
        var qtd_audios_monitoria_meio_turno_n5 = $('#qtd_audios_monitoria_meio_turno_n5').val();

        var qtd_audios_monitoria_integral_sn = $('#qtd_audios_monitoria_integral_sn').val();
        var qtd_audios_monitoria_integral_n1 = $('#qtd_audios_monitoria_integral_n1').val();
        var qtd_audios_monitoria_integral_n2 = $('#qtd_audios_monitoria_integral_n2').val();
        var qtd_audios_monitoria_integral_n3 = $('#qtd_audios_monitoria_integral_n3').val();
        var qtd_audios_monitoria_integral_n4 = $('#qtd_audios_monitoria_integral_n4').val();
        var qtd_audios_monitoria_integral_n5 = $('#qtd_audios_monitoria_integral_n5').val();

        var qtd_texto_monitoria_meio_turno = $('#qtd_texto_monitoria_meio_turno').val();
        var qtd_texto_monitoria_integral = $('#qtd_texto_monitoria_integral').val();

        verifica_qtd = 0
        if (tipo_monitoria == 1) {
            if (qtd_audios_monitoria_meio_turno_sn == '' || qtd_audios_monitoria_meio_turno_n1 == '' || qtd_audios_monitoria_meio_turno_n2 == '' || qtd_audios_monitoria_meio_turno_n3 == '' || qtd_audios_monitoria_meio_turno_n4 == '' || qtd_audios_monitoria_meio_turno_n5 == '' || qtd_audios_monitoria_meio_turno_n5 == '' || qtd_audios_monitoria_integral_sn == '' || qtd_audios_monitoria_integral_n1 == '' || qtd_audios_monitoria_integral_n2 == '' || qtd_audios_monitoria_integral_n3 == '' || qtd_audios_monitoria_integral_n4 == '' || qtd_audios_monitoria_integral_n5 == '') {
                verifica_qtd = 1;
            }
        } else if (tipo_monitoria == 2) {
            if (qtd_texto_monitoria_meio_turno == '' || qtd_texto_monitoria_integral == '') {
               verifica_qtd = 1;s
            }
        }

        if (verifica_qtd == 1) {
            alert('Todos os campos que representam as quantidades de áudios/atendimentos devem ser preenchidos!');
            return false;
        }

        var valor_passo = 0;
        var cont = 0;

        $(".passo").each(function(i, e){

            if(i == 0){
                valor_passo = $(this).val();
            }else{
            
                if($(this).val() < valor_passo){
                    cont++;
                }else{
                    valor_passo = $(this).val();
                }
            }
        });

        if(cont != 0){
            alert('Certifique-se que os quesitos estejam ordenados por passo');
            return false;
        }

        if(!$("tr.linha_quesito").length){
            alert("Deve haver pelo menos um quesito!");
            return false;
        }

        var naoSalva = 0;

        $("tr.linha_quesito").each(function(index_primeiro){
            passo_primeiro = $(this).find(".passo").val().toUpperCase();;
            quesito_primeiro = $(this).find(".quesito").val().toUpperCase();

            $("tr.linha_quesito").each(function(index_segundo){
                passo_segundo = $(this).find(".passo").val().toUpperCase();
                quesito_segundo = $(this).find(".quesito").val().toUpperCase();

                if(index_primeiro != index_segundo){
                    if(passo_primeiro == passo_segundo && quesito_primeiro == quesito_segundo){
                        ++naoSalva;
                    }
                }
            });
        });

        if(naoSalva >= 1){
            alert("Existem 2 quesitos iguais!");
            return false;
        }

        modalAguarde();

    });

</script>