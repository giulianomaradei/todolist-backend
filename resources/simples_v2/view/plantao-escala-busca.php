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

$ano_atual = substr(getDataHora('data'), 0,4);
$mes_atual = substr(getDataHora('data'), 5,2);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Escalas:</h3>
                    <div class="panel-title text-right pull-right">
                        <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modal_referencia"><i class="fa fa-plus"></i> Nova</button>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label>Referência:</label>
                                <select name="data_referencia" id="data_referencia" class="form-control input-sm" onchange="call_busca_ajax();">
                                    <option value="">Todas</option>
                                    <?php
                                        $dados_data_referencia = DBRead('', 'tb_plantonista_redes_mes', "ORDER BY data_referencia ASC");

                                        if ($dados_data_referencia) {                                           
                                            foreach ($dados_data_referencia as $conteudo_data_referencia) {
                                                $data = explode('-', $conteudo_data_referencia['data_referencia']);
                                                $mes = $data[1];
                                                $ano = $data[0];
                                                echo "<option value='".$conteudo_data_referencia['data_referencia']."'>".$meses[$mes]." de ".$ano."</option>";
                                            }
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

<form action="/api/iframe?token=<?php echo $request->token ?>&view=plantao-escala-form" method="POST">

    <div class="modal fade" id="modal_referencia" role="dialog">
        <div class="modal-dialog">
        
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Adicionar nova escala referente a:</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                
                                <strong><label>*Mês:</label></strong>
                                    <select name="mes" id="mes" class="form-control">
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

                                        foreach ($meses as $num => $mes) {
                                            $selected = $mes_atual == $num ? "selected" : "";
                                            echo "<option value='".sprintf('%02d', $num)."' ".$selected.">".$mes."</option>";
                                        }
                                        ?>													
                                    </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <strong><label>*Ano:</label></strong>
                                <select name="ano" id="ano" class="form-control">
                                    <?php
                                    $anos = array(
                                        "2018" => "18",
                                        "2019" => "19",
                                        "2020" => "20",
                                        "2021" => "21",
                                        "2022" => "22",
                                        "2023" => "23",
                                        "2024" => "24",
                                        "2025" => "25",
                                    );

                                    foreach ($anos as $num => $ano) {
                                        $selected = $ano_atual == $num ? "selected" : "";
                                        echo "<option value='".$num."' ".$selected.">".$num."</option>";
                                    }
                                    ?>													
                                </select>
                            </div>
                        </div>
                    </div>
        
                </div>
                <div class="modal-footer text-center">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Adicionar</button>
                </div>
            </div>
            
        </div>
    </div>

</form>



<script>
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
    });

    function call_busca_ajax(pagina){
        var data_referencia = $('#data_referencia').val();

       
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'data_referencia': data_referencia,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>' , 'PlantaoEscalaBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>