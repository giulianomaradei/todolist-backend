<?php
require_once(__DIR__."/../class/System.php");

$operacao = 'gerar_remessa';
$id = 1;

$dados_boleto = DBRead('', 'tb_boleto', "WHERE situacao = 'EMITIDO' OR situacao = 'REGISTRADO' OR situacao = 'BAIXA PENDENTE' OR situacao = 'ALTERACAO VENCIMENTO PENDENTE' OR situacao = 'ALTERACAO VALOR PENDENTE' OR situacao = 'PENDENTE_RETENTATIVA'");
?>

<?php
//teste de data crédido
/* if($id_usuario == 1){
    $dados_boleto_novo = DBRead('', 'tb_boleto', "WHERE cedente_convenio_numero = 56259 AND situacao != 'BAIXADO' AND situacao != 'FALHA'");

    if($dados_boleto_novo){
    	foreach ($dados_boleto_novo as $conteudo_boleto) {
            $link = DBConnect('');
            DBBegin($link);
	    	$id_boleto = $conteudo_boleto['id_boleto'];
	    	$id_integracao = $conteudo_boleto['id_integracao'];
	    	$resultado = troca_dados_curl(getDadosApiBoletos('link').'/api/v1/boletos?idIntegracao='.$id_integracao, '', array('Content-Type:application/json','cnpj-sh:'.getDadosApiBoletos('cnpj-sh'), 'token-sh:'.getDadosApiBoletos('token-sh'),'cnpj-cedente:'.getDadosApiBoletos('cnpj-cedente')), 'GET');
			if($resultado['_dados'][0]['situacao'] && $resultado['_dados'][0]['situacao'] != 'SALVO' && $resultado['_dados'][0]['situacao'] != ''){				               
                    $pagamento_data_credito = $resultado['_dados'][0]['PagamentoDataCredito'];
                    if($pagamento_data_credito){
                        $pagamento_data_credito = explode(' ',$pagamento_data_credito);
                    	$pagamento_data_credito = converteData($pagamento_data_credito[0]).' '.$pagamento_data_credito[1];
                    }
                    $dados_novos = array(
                        'pagamento_data_credito' => $pagamento_data_credito
                    );
                    DBUpdateTransaction($link, 'tb_boleto', $dados_novos, "id_boleto = $id_boleto");
            }
            DBCommit($link);
        }
    }
    echo 'Ok!';
} */
?>

<style>
    .body_boleto_sincronizar {
        display:block;
        height:430px;
        overflow:auto;
    }
    thead, tbody tr {
        display:table;
        width:100%;
        table-layout:fixed;
    }
</style>
<script src="inc/ckeditor/ckeditor.js"></script>
<div class="container-fluid">
    <div class="row">
    <form method="post" action="/api/ajax?class=Boleto.php" id="sincronizar" style="margin-bottom: 0;">
        <input type="hidden" name="token" value="<?php echo $request->token ?>">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Sincronização Bancária:</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                            <?php
                                if(!$dados_boleto){
                                    echo "<p class='alert alert-warning' style='text-align: center'>";
                                        echo "Não foram encontrados boletos para sincronizar!";
                                    echo "</p>";
                                }else{                                    
                                    echo "<div class='table-responsive'>";
                                        echo "<table class='table table-hover' style='font-size: 14px;'>";
                                            echo "<thead>";
                                                echo "<tr>";
                                                echo 
                                                "
                                                    <th class='col-md-1'>#</th>
                                                    <th class='col-md-4'>Cliente</th>
                                                    <th class='col-md-1'>Valor</th>
                                                    <th class='col-md-1'>Data de Emissão</th>
                                                    <th class='col-md-1'>Data de Vencimento</th>
                                                    <th class='col-md-2'>Situação</th>
                                                    <th class='col-md-2'>Remessa</th>
                                                ";

                                                echo "</tr>";
                                            echo "</thead>";
                                        echo "<tbody class='body_boleto_sincronizar'>";
                                        foreach($dados_boleto as $conteudo_boleto){

                                            $id_boleto = $conteudo_boleto['id_boleto'];
                                            $sacado_nome = $conteudo_boleto['sacado_nome'];
                                            $titulo_valor = converteMoeda($conteudo_boleto['titulo_valor']);
                                            $titulo_data_emissao = converteData($conteudo_boleto['titulo_data_emissao']);
                                            $titulo_data_vencimento = converteData($conteudo_boleto['titulo_data_vencimento']);
                                            $situacao = $conteudo_boleto['situacao'];
                                           
                                            if(!$conteudo_boleto['remessa_pendente']){
                                                $alerta_remessa = '<span class="text-success">Gerada</span>';
                                            }else{
                                                $alerta_remessa = '<span class="text-danger faa-flash animated">Criação de remessa pendente</span>';
                                            }    

                                            echo 
                                            "
                                                <tr>
                                                    <td class='col-md-1'>".$id_boleto."</td> 
                                                    <td class='col-md-4'>".$sacado_nome."</td> 
                                                    <td class='col-md-1'>R$ ".$titulo_valor."</td> 
                                                    <td class='col-md-1'>".$titulo_data_emissao."</td> 
                                                    <td class='col-md-1'>".$titulo_data_vencimento."</td> 
                                                    <td class='col-md-2'>".$situacao."</td> 
                                                    <td class='col-md-2'>".$alerta_remessa."</td> 
                                                </tr>
                                            "
                                            ;
                                        }
                                        echo "</tbody>";
                                    echo "</table>";
                                echo "</div>";
                                } 
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-12" style="text-align: center">
                            <input type="hidden" id="operacao" value="<?= $id; ?>" name="sincronizar"/>
                            <?php
                                if(!$dados_boleto){
                                    $disabled_salvar = 'disabled';
                                }else{
                                    $disabled_salvar = '';
                                }
                            ?>
                           <button class="btn btn-primary" name="salvar" id="ok" type="submit" <?= $disabled_salvar ?>><i class="fa fa-refresh"></i> Sincronizar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div>
    
</div>

<script>

    $(document).on('submit', '#sincronizar', function () {
        
        modalAguarde();
    });

</script>