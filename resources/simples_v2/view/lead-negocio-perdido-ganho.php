<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Acompanhamento de negócios:</h3>
                    <div class="panel-title text-right pull-right">
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-negocios-busca" style="color: white;">
                            <i class="fa fa-usd"></i> Negócios
                        </a>
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-conversao-busca" style="color: white;">
                            <i class="fas fa-check-double"></i> Conversões RD
                        </a>
                        <a class="btn btn-xs btn-primary" href="https://www.google.com/calendar" style="color: white;" target="_blank">
                            <i class="fa fa-calendar"></i> Google Calendário
                        </a>
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-timeline" style="color: white;">
                            <i class="fa fa-bars"></i> Timeline
                        </a>
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=pessoa-form&pagina-origem=lead-negocios-busca" style="color: white;">
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
                                <label>Busca:</label>
                                <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe o palavra chave..." autocomplete="off" autofocus>
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label>Responsavel:</label>
                                <select class="form-control" name="responsavel" id="responsavel" onChange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <?php
                                        $usuarios = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE (id_perfil_sistema = 22 OR id_perfil_sistema = 11 OR id_perfil_sistema = 8 OR id_perfil_sistema = 7 OR id_perfil_sistema = 29) AND b.status = 1 ORDER BY a.nome ASC", 'b.id_usuario, a.nome, b.email');
                                        if($usuarios){
                                        foreach($usuarios as $conteudo){
                                            $id_usuario = $conteudo['id_usuario'];
                                            $nomeSelect = $conteudo['nome'];
                                            echo "<option value='$id_usuario'>$nomeSelect</option>";
                                        }
                                        }
                                    ?>    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label>Andamento:</label>
                                <select class="form-control" name="andamento" id="andamento" onChange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <option value="3">Em andamento</option>
                                    <option value="1">Ganhou</option>
                                    <option value="2">Perdeu</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label>Lembrete:</label>
                                <select class="form-control" name="lembrete" id="lembrete" onChange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <option value="1">Sim</option>
                                    <option value="2">Não</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label>Sinalizado no RD:</label>
                                <select class="form-control" name="sinalizacao" id="sinalizacao" onChange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <option value="1">Sim</option>
                                    <option value="2">Não</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label>Opções de data:</label>
                                <select class="form-control" name="tipo_data" id="tipo_data" onChange="call_busca_ajax();">
                                    <option value="0">Nenhuma</option>
                                    <option value="1">Data início do negócio</option>
                                    <option value="2">Data da conclusão</option>
                                    <option value="3">Data da perda</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label>Data de:</label>
                                <input class="form-control date calendar hasDatePicker" type="text" name="data_de" id="data_de" onChange="call_busca_ajax();" placeholder="Informe o nome..." autocomplete="off" autofocus>
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                              <div class="form-group has-feedback">
                                  <label>Data até:</label>
                                  <input class="form-control date calendar hasDatePicker" type="text" name="data_ate" id="data_ate" onChange="call_busca_ajax();" placeholder="Informe o nome..." autocomplete="off" autofocus>
                                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
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
        var responsavel = $('#responsavel').val();
        var andamento = $('#andamento').val();
        var lembrete = $('#lembrete').val();
        var sinalizacao = $('#sinalizacao').val();
        var tipo_data = $('#tipo_data').val();
        var data_de = $('input[name="data_de"]').val();
        var data_ate = $('input[name="data_ate"]').val();
        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'responsavel': responsavel,
            'andamento': andamento,
            'lembrete': lembrete,
            'sinalizacao': sinalizacao,
            'tipo_data': tipo_data,
            'data_de': data_de,
            'data_ate': data_ate,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>' , 'LeadBuscaNegocioPerdidoGanho', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function() {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>