<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar']) ) {
    
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int) $_GET['alterar'];
    
    $dados_centro_custos_alterar = DBRead('', 'tb_centro_custos_rateio a', "INNER JOIN tb_centro_custos b ON a.id_centro_custos_principal = b.id_centro_custos WHERE a.id_centro_custos_rateio = '".$id."'");
    $centro_custos = $dados_centro_custos_alterar[0]['id_centro_custos_principal'];
   
    $data_referencia = $dados_centro_custos_alterar[0]['data_referencia'];
    $periodo = explode('-', $data_referencia);
    $data_periodo_mes = $periodo[1];
    $data_periodo_ano = $periodo[0];
    
	$disabled = 'disabled';
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 'Inserir';

}

if(!$data_periodo_mes){
    $primeiro_dia = new DateTime(getDataHora('data'));
    $primeiro_dia->modify('first day of this month');
    $primeiro_dia = $primeiro_dia->format('d/m/Y');

    $periodo = explode('/', $primeiro_dia);
    $data_periodo_mes = $periodo[1];
    $data_periodo_ano = $periodo[2];
}

$dados_centro_custos = DBRead('', 'tb_centro_custos', "WHERE status = '1' ORDER BY nome ASC");

?>



<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title pull-left text-left"><?=$tituloPainel?> rateio mensal:</h3>    
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=RateioMensal.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>             
                </div>
                <form method="post" action="/api/ajax?class=RateioMensal.php" id="rateio_mensal_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="tab-content">
                            <div class="row"> 
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>*Centro De Custos Principal:</label>
                                        <select id="centro_custos" name="centro_custos" class="form-control">
                                        <?php if (!isset($_GET['alterar'])){ ?>
                                            <option id="select_selecione" value="" <?=$disabled?>>Selecione um centro de custos principal...</option>
                                        <?php } ?>
                                            <?php
                                            $sel_centro_custos[$centro_custos] = 'selected';
                                            if ($dados_centro_custos) {
                                                foreach ($dados_centro_custos as $conteudo_centro_custos) {
                                                    $selected = $centro_custos == $conteudo_centro_custos['id_centro_custos'] ? "selected" : "";
                                                    if (isset($_GET['alterar'])){
                                                        if($conteudo_centro_custos['id_centro_custos'] == $centro_custos){
                                                            echo "<option value='" . $conteudo_centro_custos['id_centro_custos'] . "' ".$selected.">" . $conteudo_centro_custos['nome'] . "</option>";
                                                        }
                                                    }else{
                                                        echo "<option value='" . $conteudo_centro_custos['id_centro_custos'] . "'>" . $conteudo_centro_custos['nome'] . "</option>";
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                           
                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label>*Mês:</label>
                                        <select class="form-control" id="data_periodo_mes" name="data_periodo_mes"onchange="valida_todos();">
                                            <?php
                                            $meses = array(
                                                "01" => "Janeiro",
                                                "02" => "Fevereiro",
                                                "03" => "Março",
                                                "04" => "Abril",
                                                "05" => "Maio",
                                                "06" => "Junho",
                                                "07" => "Julho",
                                                "08" => "Agosto",
                                                "09" => "Setembro",
                                                "10" => "Outubro",
                                                "11" => "Novembro",
                                                "12" => "Dezembro",
                                            );     

                                            foreach ($meses as $key => $mes) {
                                                $selected = $data_periodo_mes == $key ? "selected" : "";
                                                if (isset($_GET['alterar'])){
                                                    if($key == $data_periodo_mes){
                                                        echo "<option value='".$key."' ".$selected.">".$mes."</option>";
                                                    }
                                                }else{
                                                    echo "<option value='".$key."' ".$selected.">".$mes."</option>";
                                                }
                                            }
                                                
                                            ?>
                                        </select>  
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label>*Ano:</label>
                                        <select class="form-control" id="data_periodo_ano" name="data_periodo_ano"onchange="valida_todos();">
                                            <?php
                                            $anos = array(
                                                "2019" => "2019",
                                                "2020" => "2020",
                                                "2021" => "2021",
                                                "2022" => "2022",
                                                "2023" => "2023",
                                                "2024" => "2024",
                                                "2025" => "2025",
                                                "2026" => "2026"
                                                
                                            );     

                                            foreach ($anos as $key => $ano) {
                                                $selected = $data_periodo_ano == $key ? "selected" : "";
                                                if (isset($_GET['alterar'])){
                                                    if($key == $data_periodo_ano){
                                                        echo "<option value='".$key."' ".$selected.">".$ano."</option>";
                                                    }
                                                }else{
                                                    echo "<option value='".$key."' ".$selected.">".$ano."</option>";
                                                }
                                            }
                                                
                                            ?>
                                        </select>  
                                    </div>
                                </div>
                            </div><!-- /.row -->
                            <?php

                                if ($dados_centro_custos) {
                                    foreach ($dados_centro_custos as $conteudo_centro_custos) {
                                        echo '<input type="hidden" value="'.$conteudo_centro_custos['id_centro_custos'].'" name="id_centro_custos_input[]">';

                                        echo '<input type="hidden" value="'.$conteudo_centro_custos['nome'].'" id="'.$conteudo_centro_custos['id_centro_custos'].'" >';
                                    }
                                }

                            ?>

                            <div id="row_lista" >

                            <?php
                                if ($dados_centro_custos_alterar) {
                                    
                                    echo '<div class="row"><div class="col-md-12"><legend>Centros de custos utilizados por '.$dados_centro_custos_alterar[0]['nome'].':</legend></div></div>';

                                    foreach ($dados_centro_custos as $conteudo_centro_custos) {

                                        $dados_centro_custos_rateio_centro_custos_alterar = DBRead('', 'tb_centro_custos_rateio_centro_custos', "WHERE id_centro_custos_rateio = '".$id."' AND id_centro_custos = '".$conteudo_centro_custos['id_centro_custos']."' ");
                                        if($dados_centro_custos_rateio_centro_custos_alterar){
                                            $porcentagem = $dados_centro_custos_rateio_centro_custos_alterar[0]['porcentagem'];
                                        }else{
                                            $porcentagem = '';
                                        }
                                        if($conteudo_centro_custos['id_centro_custos'] != $centro_custos){
                                            echo '
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="hidden" value="'.$conteudo_centro_custos['id_centro_custos'].'" name="id_centro_custos[]"/>
                                                        <input class="form-control input-sm" name="nome_centro_custos[]" disabled type="text" value="'.$conteudo_centro_custos['nome'].'" >
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input class="form-control input-sm number_float" onkeyup="valida_um(this);" name="porcentagem_rateio_centro_custos[]" type="text" value="'.$porcentagem.'">
                                                    </div>
                                                </div>
                                            </div>';
                                        }
                                    }
                                }
                            ?>
                            </div><!-- /.row -->

                        </div>     
                    </div> 
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?=$id;?>" name="<?=$operacao;?>"/>
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

    function valida_um(input_key){
        var nome_centro_custos_utilizado = $(input_key).parent().parent().parent().find("input[name='nome_centro_custos[]']").val();
        var id_centro_custos_utilizado = $(input_key).parent().parent().parent().find("input[name='id_centro_custos[]']").val();
        var id_centro_custos_principal = $('#centro_custos').val();
        var porcentagem_rateio_centro_custos = $(input_key).val();
        var data_periodo_ano = $('#data_periodo_ano').val();
        var data_periodo_mes = $('#data_periodo_mes').val();        
        if(porcentagem_rateio_centro_custos <= 100){
            $.ajax({
                cache: false,
                type: "POST",
                data: { 
                    id_centro_custos_utilizado:id_centro_custos_utilizado, 
                    id_centro_custos_principal:id_centro_custos_principal,
                    porcentagem_rateio_centro_custos:porcentagem_rateio_centro_custos,
                    data_periodo_ano:data_periodo_ano,
                    data_periodo_mes:data_periodo_mes,
                    token: '<?= $request->token ?>'
                    },
                url:'/api/ajax?class=RateioMensalValida.php',
                success: function(data){
                    if (data != 'n'){                    
                        alert(nome_centro_custos_utilizado+' já está em utilização neste mês/ano e essa porcentagem ultrapassa o máximo permitido ('+data+'%)!');
                        $(input_key).val('');
                    }
                }
            });
        }else{
            alert('A porcetagem não pode ser maior que 100%!');
            $(input_key).val('');
        }      
    }

    function valida_todos(){
        $("[name ='porcentagem_rateio_centro_custos[]']").each(function(){
            valida_um(this);
        });
    }
    
    $('#centro_custos').on('change',function(){
        $('#select_selecione').prop('disabled', 'disabled');

        $("#row_lista").html('');

        var id_select = $(this).val();
        $("#row_lista").append('<div class="row"><div class="col-md-12"><legend>Centros de custos utilizados por '+$('#'+$(this).val()).val()+':</legend></div></div>');

        $("[name ='id_centro_custos_input[]']").each(function(){
            var nome = $('#'+$(this).val()).val();

            if(id_select != $(this).val()){
                $("#row_lista").append('<div class="row"><div class="col-md-6"><div class="form-group"><input type="hidden" value="'+$(this).val()+'" name="id_centro_custos[]"/><input class="form-control input-sm" name="nome_centro_custos[]" disabled type="text" value="'+nome+'"></div></div><div class="col-md-6"><div class="form-group"><input class="form-control input-sm number_float" name="porcentagem_rateio_centro_custos[]"  onkeyup="valida_um(this);" type="text"></div></div></div>');
            }
        });
        
    });   

    
    $(document).on('submit', '#rateio_mensal_form', function () {
        var centro_custos = $("#centro_custos").val();
        if(!centro_custos || centro_custos == ''){
            alert('Você dve selecionar um centro de custos principal!');
            return false;
        }

        var flag_100 = 0;
        var total_porcentagem = 0;
        $("[name ='porcentagem_rateio_centro_custos[]']").each(function(){
            if($(this).val() && $(this).val() != '0.00'){
                total_porcentagem += parseFloat($(this).val());
            } 

            if($(this).val() && $(this).val() > 100){
                flag_100 = 1;
            }          
        });

        if(flag_100 == 1){
            alert('A porcetagem não pode ser maior que 100%!');
            return false;
        }

        if(total_porcentagem == 0){
            alert('Adicione a porcentagem ao menos em um centro de custos!');
            return false;
        }

        var data_de = $('input[name="data_de"]').val();
        var data_ate = $('input[name="data_ate"]').val();

        var data_de_valor = data_de.split("/")[2]+''+data_de.split("/")[1]+''+data_de.split("/")[0];
        var data_ate_valor = data_ate.split("/")[2]+''+data_ate.split("/")[1]+''+data_ate.split("/")[0];

        if(data_de_valor >= data_ate_valor){
            alert('A data inicial deva ser menor que a data final!');
            return false;
        }
        modalAguarde();
    });

</script>