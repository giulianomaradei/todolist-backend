<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Leads:</h3>
                    <div class="panel-title text-right pull-right">
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-negocios-busca" style="color: white;">
                            <i class="fa fa-usd"></i> Negócios
                        </a>
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-negociacoes-pausadas" style="color: white;">
                            <i class="fa fa-pause-circle"></i> Negociações Pausadas
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
                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                                <label class="control-label">Busca:</label>
                                <label class="control-label sr-only">Hidden label</label>
                                <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="" autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                                <label class="control-label">Status:</label>
                                <select class='form-control' name="status" id="status" onchange="call_busca_ajax();">
                                <option></option>
                                    <?php
                                        $dados_status = DBRead('', 'tb_lead_status', "ORDER BY descricao ASC");
                                        foreach($dados_status as $conteudo){
                                    ?>
                                        <option value="<?=$conteudo['id_lead_status']?>"><?=$conteudo['descricao']?></option>
                                    <?php
                                        }
                                    ?>
                                </select> 
                            </div>
                        </div>
                        <div class="col-md-4">
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
        var status = $('#status').val();
        var data = $("input[name='data']").val();
        
        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }

        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'id_lead_status': status,
            'data': data,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>' , 'LeadBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function(){
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>