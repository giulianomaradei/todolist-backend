<?php
require_once(__DIR__."/../class/System.php");

$dados_data_registro = DBRead('', 'tb_boleto', "WHERE situacao = 'EMITIDO' AND id_boleto NOT IN (SELECT id_boleto FROM tb_remessa_bancaria_boleto) GROUP BY titulo_data_vencimento ORDER BY titulo_data_vencimento ASC", "titulo_data_vencimento");

$dados_data_baixa = DBRead('', 'tb_boleto', "WHERE situacao = 'BAIXA PENDENTE' AND id_boleto NOT IN (SELECT a.id_boleto FROM tb_remessa_bancaria_boleto a INNER JOIN tb_remessa_bancaria b ON a.id_remessa_bancaria = b.id_remessa_bancaria WHERE b.tipo = 'baixa') GROUP BY titulo_data_vencimento ORDER BY titulo_data_vencimento ASC", "titulo_data_vencimento");

$dados_data_alteracao_vencimento = DBRead('', 'tb_boleto a', "WHERE a.situacao = 'ALTERACAO VENCIMENTO PENDENTE' AND a.id_boleto NOT IN (SELECT b.id_boleto FROM tb_remessa_bancaria_boleto b INNER JOIN tb_remessa_bancaria c ON b.id_remessa_bancaria = c.id_remessa_bancaria WHERE c.tipo = 'alteracao_vencimento' AND b.titulo_data_vencimento = a.titulo_data_vencimento) GROUP BY a.titulo_data_vencimento ORDER BY a.titulo_data_vencimento ASC", "a.titulo_data_vencimento");

$dados_data_alteracao_valor = DBRead('', 'tb_boleto a', "WHERE a.situacao = 'ALTERACAO VALOR PENDENTE' AND a.id_boleto NOT IN (SELECT b.id_boleto FROM tb_remessa_bancaria_boleto b INNER JOIN tb_remessa_bancaria c ON b.id_remessa_bancaria = c.id_remessa_bancaria WHERE c.tipo = 'alteracao_valor' AND b.titulo_valor = a.titulo_valor) GROUP BY a.titulo_data_vencimento ORDER BY a.titulo_data_vencimento ASC", "a.titulo_data_vencimento");

//$disabled_botao = 'disabled data-toggle="tooltip" title="Data de emissão e de vencimento!" ';
if(!$dados_data_registro && !$dados_data_baixa && !$dados_data_alteracao_vencimento && !$dados_data_alteracao_valor){
    $disabled_botao = 'disabled';
}

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Remessas:</h3>
                    <div class="panel-title text-right pull-right"><a id ='href' href="/api/iframe?token=<?php echo $request->token ?>&view=boleto-remessa-form"><button class="btn btn-xs btn-primary botao" <?= $disabled_botao ?>><i class="fa fa-plus"></i> Nova</button></a></div>
                </div>
                <div class="panel-body" style="padding-bottom: 0;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label>Busca:</label>
                                <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="" autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label class="control-label">Data de:</label>
                                <input class="form-control date calendar hasDatePicker hasDatepicker" type="text" name="data_de" onchange="call_busca_ajax();" placeholder="dd/mm/aaaa" autocomplete="off" maxlength="10">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label class="control-label">Data até:</label>
                                <input class="form-control date calendar hasDatePicker hasDatepicker" type="text" name="data_ate" onchange="call_busca_ajax();" placeholder="dd/mm/aaaa" autocomplete="off" maxlength="10">
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

    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
      var dados_data_registro = '<?=$dados_data_registro?>';
      var dados_data_baixa = '<?=$dados_data_baixa?>';
      var dados_data_alteracao_vencimento = '<?=$dados_data_alteracao_vencimento?>';
      var dados_data_alteracao_valor = '<?=$dados_data_alteracao_valor?>';
      if(!dados_data_registro && !dados_data_baixa && !dados_data_alteracao_vencimento && !dados_data_alteracao_valor){
        $('#href').attr('href', '');
      }

    });

    function call_busca_ajax(pagina){
        var inicia_busca = 1;
        var nome = $('#nome').val();
        var data_de = $('input[name="data_de"]').val();
        var data_ate = $('input[name="data_ate"]').val();

        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'pagina': pagina,
            'nome': nome,
            'data_de': data_de,
            'data_ate': data_ate,
        };
        busca_ajax('<?= $request->token ?>' , 'BoletoRemessaBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>