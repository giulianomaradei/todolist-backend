<?php
require_once(__DIR__."/../class/System.php");

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

$operacao = 'gerar_remessa';
$id = 1;

$dados_data = DBRead('', 'tb_boleto', " GROUP BY titulo_data_vencimento ORDER BY titulo_data_vencimento ASC", "titulo_data_vencimento");

$dados_mes_ano_registro = array();
$contador_registro = 0;

$dados_mes_ano_baixa = array();
$contador_baixa = 0;

$dados_mes_ano_vencimento = array();
$contador_vencimento = 0;

if($dados_data){
    foreach ($dados_data as $conteudo_data) {        
        $data_quebrada = explode("-", $conteudo_data['titulo_data_vencimento']);
        $data_mes_ano = $data_quebrada[0].'-'.$data_quebrada[1];
//echo $data_mes_ano." - - - - ".$data_mes_ano_passado."<hr>";
  
        if($data_mes_ano != $data_mes_ano_passado){

            $dados_data_registro = DBRead('', 'tb_boleto', "WHERE titulo_data_vencimento LIKE '%".$data_mes_ano."%' AND situacao = 'EMITIDO' ");
            if($dados_data_registro){
                foreach ($dados_data_registro as $conteudo_data_registro) {
                
                    $dados_boleto_remessa_registro = DBRead('', 'tb_remessa_bancaria_boleto', 'WHERE id_boleto = "'.$conteudo_data_registro['id_boleto'].'" ');
                    if(!$dados_boleto_remessa_registro){
                        $dados_mes_ano_registro[$contador_registro]['data'] = $data_mes_ano; 
                        $dados_mes_ano_registro[$contador_registro]['qtd'] ++;
                    }
                }

                $data_mes_ano_passado = $data_mes_ano;
                $contador_registro++;
            }

            $dados_data_baixa = DBRead('', 'tb_boleto', "WHERE titulo_data_vencimento LIKE '%".$data_mes_ano."%' AND situacao = 'BAIXA PENDENTE' ");
            if($dados_data_baixa){
                foreach ($dados_data_baixa as $conteudo_data_baixa) {
                
                    $dados_boleto_remessa_baixa = DBRead('', 'tb_remessa_bancaria_boleto a', 'INNER JOIN tb_remessa_bancaria b ON a.id_remessa_bancaria = b.id_remessa_bancaria WHERE a.id_boleto = "'.$conteudo_data_baixa['id_boleto'].'" AND b.tipo = "baixa" ');
                    if(!$dados_boleto_remessa_baixa){
                        $dados_mes_ano_baixa[$contador_baixa]['data'] = $data_mes_ano; 
                        $dados_mes_ano_baixa[$contador_baixa]['qtd'] ++;
                    }
                }

                $data_mes_ano_passado = $data_mes_ano;
                $contador_baixa++;
            }

            $dados_data_vencimento = DBRead('', 'tb_boleto', "WHERE titulo_data_vencimento LIKE '%".$data_mes_ano."%' AND situacao = 'ALTERACAO VENCIMENTO PENDENTE' AND remessa_pendente = '1' ");
            if($dados_data_vencimento){
                foreach ($dados_data_vencimento as $conteudo_data_vencimento) {
                
                    $dados_boleto_remessa_vencimento = DBRead('', 'tb_remessa_bancaria_boleto a', "INNER JOIN tb_remessa_bancaria b ON a.id_remessa_bancaria = b.id_remessa_bancaria WHERE a.id_boleto = '".$conteudo_data_vencimento['id_boleto']."' AND b.tipo = 'alteracao_vencimento' AND a.titulo_data_vencimento = '".$conteudo_data_vencimento['titulo_data_vencimento']."'");
                    if(!$dados_boleto_remessa_vencimento){
                        $dados_mes_ano_vencimento[$contador_vencimento]['data'] = $data_mes_ano; 
                        $dados_mes_ano_vencimento[$contador_vencimento]['qtd'] ++;
                    }
                }

                $data_mes_ano_passado = $data_mes_ano;
                $contador_vencimento++;
            }
           
        }
    }
}

if($dados_mes_ano_baixa){
    $select_baixa = 'selected';
    $select_registro = '';
    $select_vencimento = '';

    $display_row_baixa = '';
    $display_row_registro = 'style="display:none;"';
    $display_row_vencimento = 'style="display:none;"';

}else if($dados_mes_ano_registro){
    $select_baixa = '';
    $select_registro = 'selected';
    $select_vencimento = '';

    $display_row_baixa = 'style="display:none;"';
    $display_row_registro = '';
    $display_row_vencimento = 'style="display:none;"';

}else if($dados_mes_ano_vencimento){
    $select_baixa = '';
    $select_registro = '';
    $select_vencimento = 'selected';

    $display_row_baixa = 'style="display:none;"';
    $display_row_registro = 'style="display:none;"';
    $display_row_vencimento = '';

}else{
    $select_baixa = 'selected';
    $select_registro = '';
    $select_vencimento = '';

    $display_row_baixa = '';
    $display_row_registro = 'style="display:none;"';
    $display_row_vencimento = 'style="display:none;"';

}

?>

<script src="inc/ckeditor/ckeditor.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Gerar Remessa:</h3>
                </div>
                <form method="post" action="/api/ajax?class=Boleto.php" id="boleto_form" style="margin-bottom: 0;">
                    <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Tipo de Remessa:</label> 
                                    <select name="tipo_remessa" id="tipo_remessa" class="form-control input-sm">
                                        <option value="2" <?= $select_baixa ?>>Baixa</option>
                                        <option value="1" <?= $select_registro ?>>Registro</option>
                                        <option value="3" <?= $select_vencimento ?>>Alteração de Vencimento</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="row_registro" <?=$display_row_registro ?>>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Referente ao Mês:</label>
                                    <select name="mes_ano_registro" id="mes_ano_registro" class="form-control input-sm">
                                    <?php
                                        if($dados_mes_ano_registro){
                                            foreach ($dados_mes_ano_registro as $conteudo_mes_ano_registro) {
                                                if($conteudo_mes_ano_registro['qtd'] == 1){
                                                    $qtd_descricao = "(".$conteudo_mes_ano_registro['qtd']." pendente)";
                                                }else{
                                                    $qtd_descricao = "(".$conteudo_mes_ano_registro['qtd']." pendentes)";
                                                }
                                                $mes_data = explode("-", $conteudo_mes_ano_registro['data']);
                                                $ano_data = $mes_data[0];
                                                $mes_data = $mes_data[1];
                                                echo "<option value='".$conteudo_mes_ano_registro['data']."' >".$meses[$mes_data]." de ".$ano_data." ".$qtd_descricao."</option>";
                                            }    
                                        }else{
                                                echo "<option value='-1'>Não foram encontrados boletos disponíveis para remessa de registro!</option>";
                                        }
    									
                                    ?>                                                  
                                    </select>
                                </div>
                            </div> 
                        </div>

                        <div class="row" id="row_baixa" <?=$display_row_baixa ?>>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Referente ao Mês:</label>
                                    <select name="mes_ano_baixa" id="mes_ano_baixa" class="form-control input-sm">
                                    <?php
                                        if($dados_mes_ano_baixa){
                                            foreach ($dados_mes_ano_baixa as $conteudo_mes_ano_baixa) {
                                                if($conteudo_mes_ano_baixa['qtd'] == 1){
                                                    $qtd_descricao = "(".$conteudo_mes_ano_baixa['qtd']." pendente)";
                                                }else{
                                                    $qtd_descricao = "(".$conteudo_mes_ano_baixa['qtd']." pendentes)";
                                                }
                                                $mes_data = explode("-", $conteudo_mes_ano_baixa['data']);
                                                $ano_data = $mes_data[0];
                                                $mes_data = $mes_data[1];
                                                echo "<option value='".$conteudo_mes_ano_baixa['data']."' >".$meses[$mes_data]." de ".$ano_data." ".$qtd_descricao."</option>";
                                            }    
                                        }else{
                                                echo "<option value='-1'>Não foram encontrados boletos disponíveis para remessa de baixa!</option>";
                                        }
                                        
                                    ?>                                                  
                                    </select>
                                </div>
                            </div> 
                        </div>

                        <div class="row" id="row_vencimento" <?=$display_row_vencimento ?>>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Referente ao Mês:</label>
                                    <select name="mes_ano_vencimento" id="mes_ano_vencimento" class="form-control input-sm">
                                    <?php
                                        if($dados_mes_ano_vencimento){
                                            foreach ($dados_mes_ano_vencimento as $conteudo_mes_ano_vencimento) {
                                                if($conteudo_mes_ano_vencimento['qtd'] == 1){
                                                    $qtd_descricao = "(".$conteudo_mes_ano_vencimento['qtd']." pendente)";
                                                }else{
                                                    $qtd_descricao = "(".$conteudo_mes_ano_vencimento['qtd']." pendentes)";
                                                }
                                                $mes_data = explode("-", $conteudo_mes_ano_vencimento['data']);
                                                $ano_data = $mes_data[0];
                                                $mes_data = $mes_data[1];
                                                echo "<option value='".$conteudo_mes_ano_vencimento['data']."' >".$meses[$mes_data]." de ".$ano_data." ".$qtd_descricao."</option>";
                                            }    
                                        }else{
                                                echo "<option value='-1'>Não foram encontrados boletos disponíveis para remessa de alterção de vencimento!</option>";
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
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-check"></i> Gerar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="resultado_busca"></div>
        </div>
    </div>
</div>

<script>
	
	$( document ).ready(function() {
		call_busca_ajax();
	});

    $('#mes_ano_registro').on('change', function() {
        call_busca_ajax();
    })

    $('#mes_ano_baixa').on('change', function() {
        call_busca_ajax();
    })

    $('#mes_ano_vencimento').on('change', function() {
        call_busca_ajax();
    })
	
	$('#tipo_remessa').on('change', function() {
		call_busca_ajax();
	})

    function call_busca_ajax(){

        if($("#tipo_remessa option:selected").val() == 1){
            var ano_mes = $('#mes_ano_registro').val();
            $('#row_baixa').hide();
            $('#row_registro').show();
            $('#row_vencimento').hide();
        }else if($("#tipo_remessa option:selected").val() == 2){
            var ano_mes = $('#mes_ano_baixa').val();
            $('#row_baixa').show();
            $('#row_registro').hide();
            $('#row_vencimento').hide();
        }else if($("#tipo_remessa option:selected").val() == 3){
            var ano_mes = $('#mes_ano_vencimento').val();
            $('#row_baixa').hide();
            $('#row_registro').hide();
            $('#row_vencimento').show();
        }

        if(ano_mes == '-1'){
            $("#ok").attr("disabled", true);
        }else{
            $("#ok").attr("disabled", false);
        }      

        var tipo_remessa = $('#tipo_remessa').val();
        //alert(mes_ano+'-01');
        
        var parametros = {
            'ano_mes': ano_mes,
            'tipo_remessa': tipo_remessa
        };
        busca_ajax('<?= $request->token ?>' , 'BuscaRemessa', 'resultado_busca', parametros);
    }

    call_busca_ajax();

    $(document).on('submit', '#boleto_form', function () {
        var ano_mes_registro = $('#mes_ano_registro').val();
        var ano_mes_baixa = $('#mes_ano_baixa').val();
        var ano_mes_vencimento = $('#mes_ano_vencimento').val();

        if(ano_mes_registro == -1 && ano_mes_baixa == -1 && ano_mes_vencimento == -1 ){
            alert('Não existem remessas para gerar!');
            return false;
        }
        modalAguarde();
    });

</script>