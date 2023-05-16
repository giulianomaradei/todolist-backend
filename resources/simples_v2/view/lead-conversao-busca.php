<?php
require_once(__DIR__."/../class/System.php");

$data = new DateTime(getDataHora('data'));  
$data_agora = $data->format('Y-m-d');
$negocios_perdidos = DBRead('', 'tb_lead_negocio_perdido', "WHERE data_lembrete <= '$data_agora' ");

$cont_visualizacao = 0;

if ($negocios_perdidos) {
    foreach ($negocios_perdidos as $conteudo_negocios) {

    $verifica_visualizacao = DBRead('', 'tb_lead_negocio_perdido_visualizado', "WHERE  id_lead_negocio_perdido = '".$conteudo_negocios['id_lead_negocio_perdido']."' AND id_usuario = $id_usuario");

    if (!$verifica_visualizacao) {
        $cont_visualizacao++;
    }
    }
}
    
if ($cont_visualizacao != 0) {
    $notifica = '<i class="fa fa-exclamation-circle faa-flash animated" style="color: #eea236;"></i> ';

} else {
    $notifica = '';
}

$id_rd_conversao = (!empty($_GET['id_rd_conversao'])) ? $_GET['id_rd_conversao'] : '';

if ($id_rd_conversao) {
    $dados = DBRead('', 'tb_rd_conversao', "WHERE id_rd_conversao = $id_rd_conversao");
    $id_rd_lead = $dados[0]['id_rd_lead'];
}

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Conversões RD:</h3>
                    <div class="panel-title text-right pull-right">
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-negocios-busca" style="color: white;">
                            <i class="fa fa-usd"></i> Negócios
                        </a>
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-perdido-ganho" style="color: white;">
                            <i class="fas fa-book-open"></i> Acompanhamento <?= $notifica ?> 
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
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label>ID RD lead:</label>
                                <input class="form-control" type="text" name="id_rd_lead" id="id_rd_lead" onKeyUp="call_busca_ajax();" placeholder="Informe o palavra chave..." autocomplete="off" autofocus value="<?= $id_rd_lead ?>">
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label>Busca:</label>
                                <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe o palavra chave..." autocomplete="off" autofocus>
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label>Negócio:</label>
                                <select class="form-control" type="text" name="negocio" id="negocio" onchange="call_busca_ajax();" autofocus>
                                    <option value="">Qualquer</option>
                                    <option value="1">Sim</option>
                                    <option value="2">Não</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label>Vínculo:</label>
                                <select class="form-control" type="text" name="vinculo" id="vinculo" onchange="call_busca_ajax();" autofocus>
                                    <option value="">Qualquer</option>
                                    <option value="1">Sim</option>
                                    <option value="2">Não</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label>Data de:</label>
                                <input class="form-control date calendar hasDatePicker" type="text" name="data_de" id="data_de" onChange="call_busca_ajax();" placeholder="Informe o nome..." autocomplete="off" autofocus>
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-2">
                              <div class="form-group has-feedback">
                                  <label>Data até:</label>
                                  <input class="form-control date calendar hasDatePicker" type="text" name="data_ate" id="data_ate" onChange="call_busca_ajax();" placeholder="Informe o nome..." autocomplete="off" autofocus>
                                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                              </div>
                        </div>
                    </div>
                    <hr>
                    <form method="post" action="class/LeadConversao.php" id="lead_conversao_form" style="margin-bottom: 0;">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="resultado_busca"></div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-12" style="text-align: center">
                            <input type="hidden" id="operacao" value="1" name="excluir">
                            <button type="submit" class="btn btn-danger" onclick="if (!confirm('Tem certeza que deseja excluir?')) {  return false; } else { modalAguarde(); }">
                                <i class="fa fa-trash"></i> Excluir
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function call_busca_ajax(pagina){
        var inicia_busca = 1;
        var nome = $('#nome').val();
        var negocio = $('#negocio').val();
        var vinculo = $('#vinculo').val();
        var id_rd_lead = $('#id_rd_lead').val();
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
            'negocio': negocio,
            'vinculo': vinculo,
            'id_rd_lead': id_rd_lead,
            'data_de': data_de,
            'data_ate': data_ate,
            'pagina': pagina
        };

        busca_ajax('<?= $request->token ?>' , 'LeadBuscaConversoes', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function() {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>