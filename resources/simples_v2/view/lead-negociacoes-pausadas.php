<?php
require_once(__DIR__."/../class/System.php");

?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.css"/> 
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/time.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/chinese-string.js"></script>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Leads (<strong>Negociações pausadas</strong>):</h3>
                    <div class="panel-title text-right pull-right" id="panel_buttons">
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-busca" style="color: white;">
                            <i class="fa fa-search"></i> Buscar Negócio
                        </a>
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-negocios-busca" style="color: white;">
                            <i class="fa fa-usd"></i> Negócios
                        </a>
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-calendar" style="color: white;">
                            <i class="fa fa-calendar"></i> Calendário
                        </a>
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-timeline" style="color: white;">
                            <i class="fa fa-bars"></i> Timeline
                        </a>
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=pessoa-form" style="color: white;">
                            <i class="fa fa-plus"></i> Nova Empresa/Pessoa
                        </a>
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-form" style="color: white;">
                            <i class="fa fa-plus"></i> Novo Negócio
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label class="control-label">Nome do lead:</label>
                                <label class="control-label sr-only">Hidden label</label>
                                <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe o nome do contrato..." autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label class="control-label">Segmento:</label>
                                <input class="form-control" type="text" name="segmento" id="segmento" onKeyUp="call_busca_ajax();" placeholder="Informe o nome do contrato..." autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label class="control-label">Origem:</label>
                                <select class="form-control" name="origem" id="origem" onchange="call_busca_ajax();">
                                    <option></option>
                                    <?php
                                        $dados_origem = DBRead('', 'tb_lead_origem', "ORDER BY descricao ASC");
                                        foreach($dados_origem as $conteudo){
                                    ?>
                                        <option value="<?=$conteudo['id_lead_origem']?>"><?=$conteudo['descricao']?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label class="control-label">Motivo</label>
                                <select class="form-control" name="motivo" id="motivo" onchange="call_busca_ajax();">
                                    <option></option>
                                    <?php
                                        $dados_motivo_pausa = DBRead('', 'tb_lead_motivo_pausa', "ORDER BY descricao ASC");
                                        foreach($dados_motivo_pausa as $conteudo){
                                    ?>
                                        <option value="<?=$conteudo['id_lead_motivo_pausa']?>"><?=$conteudo['descricao']?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label class="control-label">Data:</label>
                                <input class="form-control date calendar hasDatePicker" name="data" id="data" onChange="call_busca_ajax();">
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
        var origem = $('#origem').val();
        var motivo = $('#motivo').val();
        var data = $("input[name='data']").val();

        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }

        if(pagina === undefined){
            pagina = 1;
        }

        var parametros = {
            'nome': nome,
            'pagina': pagina,
            'id_lead_origem': origem,
            'id_lead_motivo_pausa': motivo,
            'data': data
        };

        busca_ajax('<?= $request->token ?>' , 'LeadBuscaNegociacoesPausadas', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function(){
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>