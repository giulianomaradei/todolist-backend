<?php
require_once(__DIR__."/../class/System.php");

$primeiro_dia = new DateTime(getDataHora('data'));
$primeiro_dia->modify('first day of last month');
$primeiro_dia = $primeiro_dia->format('d/m/Y');

$periodo = explode('/', $primeiro_dia);
$data_periodo_mes = (!empty($_POST['data_periodo_mes'])) ? $_POST['data_periodo_mes'] : $periodo[1];
$data_periodo_ano = (!empty($_POST['data_periodo_ano'])) ? $_POST['data_periodo_ano'] : $periodo[2];
    
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Rateio Mensal:</h3>
                    <div class="panel-title text-right pull-right"><a href="/api/iframe?token=<?php echo $request->token ?>&view=rateio-mensal-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Novo</button></a></div>
                </div>
                
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                                <label>Centro De Custos Principal:</label>
                                <select id="centro_custos_principal" name="centro_custos_principal" class="form-control" onchange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                        <?php
                                        $dados_centro_custos = DBRead('', 'tb_centro_custos', "WHERE status = '1' ORDER BY nome ASC");

                                        if ($dados_centro_custos) {
                                            foreach ($dados_centro_custos as $conteudo_centro_custos) {
                                                echo "<option value='".$conteudo_centro_custos['id_centro_custos']."'>".$conteudo_centro_custos['nome']."</option>";
                                            }
                                        }
                                        ?>
                                </select>
                            </div>
                        </div>
                      
                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                                <label>Mês:</label>
                                <select class="form-control" id="data_periodo_mes" name="data_periodo_mes" onchange="call_busca_ajax();">
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
                                        echo "<option value='".$key."' ".$selected.">".$mes."</option>";
                                    }
                                        
                                    ?>
                                </select>  
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                                <label>Ano:</label>
                                <select class="form-control" id="data_periodo_ano" name="data_periodo_ano" onchange="call_busca_ajax();">
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
                                        echo "<option value='".$key."' ".$selected.">".$ano."</option>";
                                    }
                                        
                                    ?>
                                </select>  
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="resultado_busca"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function call_busca_ajax(pagina){
        var inicia_busca = 1;

        var centro_custos_principal = $("#centro_custos_principal").val();
        var data_periodo_mes = $("#data_periodo_mes").val();
        var data_periodo_ano = $("#data_periodo_ano").val();
        
        if(pagina === undefined){
            pagina = 1;
        }

        var parametros = {
            'centro_custos_principal': centro_custos_principal,
            'data_periodo_mes': data_periodo_mes,
            'data_periodo_ano': data_periodo_ano,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>' , 'RateioMensalBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>