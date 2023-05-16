<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="row">
                        <div class="panel-title text-left pull-left col-md-4">Prefixos:</div>
                        <div class="col-md-4 text-center"><a href="/api/iframe?token=<?php echo $request->token ?>&view=controle-filas"><button class="btn btn-xs btn-warning"><i class="fa fa-cog"></i> Parâmetros - Controle Automático de Filas</button></a></div>
                        <div class="panel-title text-right pull-right col-md-4"><a href="/api/iframe?token=<?php echo $request->token ?>&view=prefixo-central-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Novo</button></a></div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label class="control-label">Busca:</label>
                                <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe o nome ou prefixo..." autocomplete="off" autofocus>
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>    
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Aceita Ligações:</label>
                                <select class="form-control" name="aceita_ligacao" id="aceita_ligacao" onchange="call_busca_ajax();">
                                    <option value=''>Todos</option>
                                    <option value='1'>Sim</option>
                                    <option value='0'>Não</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Pesquisa:</label>
                                <select class="form-control" name="pesquisa" id="pesquisa" onchange="call_busca_ajax();">
                                    <option value=''>Todos</option>
                                    <option value='1'>Sim</option>
                                    <option value='0'>Não</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Controle Automático:</label>
                                <select class="form-control" name="controle_automatico_fila" id="controle_automatico_fila" onchange="call_busca_ajax();">
                                    <option value=''>Todos</option>
                                    <option value='1'>Sim</option>
                                    <option value='0'>Não</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo Fila Controle Automático:</label>
                                <select class="form-control" name="tipo_fila_controle_automatico" id="tipo_fila_controle_automatico" onchange="call_busca_ajax();">
                                    <option value=''>Todos</option>
                                    <option value='interna'>Interna</option>
                                    <option value='experiencia'>Experiência (EXP)</option>
                                    <option value='externa'>Externa (EXT)</option>
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
        var controle_automatico_fila = $('#controle_automatico_fila').val();
        var tipo_fila_controle_automatico = $('#tipo_fila_controle_automatico').val();
        var pesquisa = $('#pesquisa').val();
        var aceita_ligacao = $('#aceita_ligacao').val();
        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'controle_automatico_fila': controle_automatico_fila,
            'tipo_fila_controle_automatico': tipo_fila_controle_automatico,
            'pesquisa': pesquisa,
            'aceita_ligacao': aceita_ligacao,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>' , 'PrefixoCentralBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>