<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Feriados:</h3>
                    <div class="panel-title text-right pull-right"><a href="/api/iframe?token=<?php echo $request->token ?>&view=feriado-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Novo</button></a></div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nome:</label>
                                <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe o nome, UF ou cidade..." autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>É fixo:</label>
                                <select class="form-control" name="fixo" id="fixo" onchange="call_busca_ajax();">
                                    <option value=''></option>
                                    <option value='1'>Sim</option>
                                    <option value='0'>Não</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo:</label>
                                <select class="form-control" name="tipo" id="tipo" onchange="call_busca_ajax();">
                                    <option value=''></option>
                                    <option value='Nacional'>Nacional</option>
                                    <option value='Estadual'>Estadual</option>
                                    <option value='Municipal'>Municipal</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Mês:</label>
                                <select class="form-control" name="mes" id="mes" onchange="call_busca_ajax();">
                                    <option value=''></option>
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
                                            echo "<option value='$cont_zero'>".$meses[$cont_zero]."</option>";
                                            $cont++;
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
        var nome = $('#nome').val();
        var fixo = $('#fixo').val();
        var tipo = $('#tipo').val();
        var mes = $('#mes').val();
        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'fixo': fixo,
            'tipo': tipo,
            'mes': mes,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token; ?>', 'FeriadoBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>