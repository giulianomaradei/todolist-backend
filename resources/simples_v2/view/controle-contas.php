<?php
require_once(__DIR__."/../class/System.php"); 

$primeiro_dia = new DateTime(getDataHora('data'));
$primeiro_dia->modify('first day of this month');
$primeiro_dia = $primeiro_dia->format('d/m/Y');

$ultimo_dia = new DateTime(getDataHora('data'));
$ultimo_dia->modify('last day of this month');
$ultimo_dia = $ultimo_dia->format('d/m/Y');

if(isset($_GET['ancora'])){
    $ancora = $_GET['ancora'];
if($ancora == 'conta_pagar'){
    $ancora_conta_pagar = 'active';
}else if($ancora == 'conta_receber'){
    $ancora_conta_receber = 'active';
}else if($ancora == 'caixas'){
    $ancora_caixas = 'active';
}else{
    $ancora_dashboard = 'active';
}
}else{
    $ancora_dashboard = 'active';
}
?>
<style>
    .select2{
        width: 100% !important;
    }
</style>
<script src="https://code.highcharts.com/7.2.1/highcharts.js"></script>
<script src="https://code.highcharts.com/7.2.1/highcharts-3d.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="row">
                    <h3 class="panel-title text-left col-md-12">Controle de Contas:</h3>
                    
                    </div>
                </div>
                    <input type="hidden" name="id_usuario" value="<?=$_SESSION['id_usuario']?>">
                    <input type="hidden" name="pagina_origem" value="<?=$pagina_origem?>">
                    <input type="hidden" name="id_negocio" value="<?=$id_negocio?>">
                    <div class="panel-body" style="padding-bottom: 0;">

                        <!-- nav tabs -->
                        <ul class="nav nav-tabs">
                            <li class="<?=$ancora_dashboard?>">
                                <a data-toggle="tab" href="#tab_dashboard"><i class="fas fa-chart-pie"></i> Dashboard</a>
                            </li>
                            <li class="<?=$ancora_conta_receber?>">
                                <a data-toggle="tab" href="#tab_conta_receber"><i class="fas fa-arrow-circle-down" aria-hidden="true"></i> Contas a Receber</a>
                            </li>
                            <li class="<?=$ancora_conta_pagar?>">
                                <a data-toggle="tab" href="#tab_conta_pagar"><i class="fas fa-arrow-circle-up" aria-hidden="true"></i> Contas a Pagar</a>
                            </li>
                            <li class="<?=$ancora_caixas?>">
                                <a data-toggle="tab" href="#tab_caixas"><i class="fas fa-piggy-bank"></i> Caixas</a>
                            </li>
                        </ul>
                        <!-- end nav tabs -->

                        <div class="tab-content">

                            <!-- tab_dashboard  -->
                            <div id="tab_dashboard" class="tab-pane fade in <?=$ancora_dashboard?>">
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="resultado_dashboard"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- end tab_dashboard -->

                            <!-- tab_conta_receber  -->
                            <div id="tab_conta_receber" class="tab-pane fade in <?=$ancora_conta_receber?>">
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group has-feedback">
                                                    <label>#:</label>
                                                    <input class="form-control input-sm number_int" type="text" name="id_busca_conta_receber" id="id_busca_conta_receber" onKeyUp="call_busca_ajax_conta_receber();" placeholder="Digite a #" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group has-feedback">
                                                    <label>Busca:</label>
                                                    <input class="form-control input-sm" type="text" name="nome_conta_receber" id="nome_conta_receber" onKeyUp="call_busca_ajax_conta_receber();" placeholder="Digite o Nome da Pessoa" autocomplete="off">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-group has-feedback">
                                                    <label class="control-label">Data de Vencimento: </label>
                                                    <input class="form-control date calendar hasDatePicker input-sm date_receber" type="text" name="data_de_conta_receber" autocomplete="off" maxlength="10" value='<?=$primeiro_dia?>' onchange="call_busca_ajax_conta_receber();">                                               
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group has-feedback">
                                                    <label class="control-label">Data até Vencimento: </label>
                                                    <input class="form-control date calendar hasDatePicker input-sm date_receber" type="text" name="data_ate_conta_receber" placeholder="dd/mm/aaaa" autocomplete="off" maxlength="10" value='<?=$ultimo_dia?>' onchange="call_busca_ajax_conta_receber();">
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group has-feedback">
                                                    <label>Natureza Financeira:</label>
                                                    <select class="form-control input-sm" name="id_natureza_financeira_agrupador_conta_receber" id="id_natureza_financeira_agrupador_conta_receber" onchange="call_busca_ajax_conta_receber();">
                                                        <option value="">Todas</option>
                                                        <?php
                                                            $dados_natureza_financeira_agrupador = DBRead('', 'tb_natureza_financeira a', "INNER JOIN tb_natureza_financeira_agrupador b ON a.id_natureza_financeira_agrupador = b.id_natureza_financeira_agrupador WHERE a.status = 1 AND a.tipo = 'conta_receber' ORDER BY b.nome ASC", 'a.nome AS nome_natureza, a.*, b.*');

                                                            if ($dados_natureza_financeira_agrupador) {
                                                                foreach ($dados_natureza_financeira_agrupador as $conteudo_natureza_financeira_agrupador) {
                                                                    echo "<option value='".$conteudo_natureza_financeira_agrupador['id_natureza_financeira']."'>".$conteudo_natureza_financeira_agrupador['nome'].' - '.$conteudo_natureza_financeira_agrupador['nome_natureza']."</option>";
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group has-feedback">
                                                    <label>Observação:</label>
                                                    <select class="form-control input-sm" name="obs_conta_receber" id="obs_conta_receber" onchange="call_busca_ajax_conta_receber();">
                                                        <option value="0">Qualquer</option>
                                                        <option value="1">Sim</option>
                                                        <option value="2">Não</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group has-feedback">
                                                    <label>E-mail Enviado:</label>
                                                    <select class="form-control input-sm" name="envio_email" id="envio_email" onchange="call_busca_ajax_conta_receber();">
                                                        <option value="4">Qualquer</option>
                                                        <option value="1">Sim</option>
                                                        <option value="0">Não</option>
                                                        <option value="2">Ignorado</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group has-feedback">
                                                    <label>Gerado pelo Faturamento:</label>
                                                    <select class="form-control input-sm" name="faturamento_conta_receber" id="faturamento_conta_receber" onchange="call_busca_ajax_conta_receber();">
                                                        <option value="0">Qualquer</option>
                                                        <option value="1">Sim</option>
                                                        <option value="2">Não</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="resultado_busca_conta_receber"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end tab_conta_receber -->

                            <!-- tab_conta_pagar  -->
                            <div id="tab_conta_pagar" class="tab-pane fade in <?=$ancora_conta_pagar?>">
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <div class="form-group has-feedback">
                                                    <label>#:</label>
                                                    <input class="form-control input-sm number_int" type="text" name="id_busca_conta_pagar" id="id_busca_conta_pagar" onKeyUp="call_busca_ajax_conta_pagar();" placeholder="Digite a #" autocomplete="off">
                                                </div>
                                            </div>
					                        <div class="col-md-3">
					                            <div class="form-group has-feedback">
					                                <label>Busca:</label>
					                                <input class="form-control input-sm" type="text" name="nome_conta_pagar" id="nome_conta_pagar" onKeyUp="call_busca_ajax_conta_pagar();" placeholder="Digite o Nome da Pessoa" autocomplete="off">
					                            </div>
					                        </div>
					                        <div class="col-md-2">
					                            <div class="form-group has-feedback">
                                                    <label>Natureza Financeira:</label>
                                                    <select class="form-control input-sm" name="id_natureza_financeira_agrupador_conta_pagar" id="id_natureza_financeira_agrupador_conta_pagar" onchange="call_busca_ajax_conta_pagar();">
                                                        <option value="">Todas</option>
                                                        <?php
                                                            $dados_natureza_financeira_agrupador = DBRead('', 'tb_natureza_financeira a', "INNER JOIN tb_natureza_financeira_agrupador b ON a.id_natureza_financeira_agrupador = b.id_natureza_financeira_agrupador WHERE a.status = 1 AND a.tipo = 'conta_pagar' ORDER BY b.nome ASC", 'a.nome AS nome_natureza, a.*, b.*');

                                                            if ($dados_natureza_financeira_agrupador) {
                                                                foreach ($dados_natureza_financeira_agrupador as $conteudo_natureza_financeira_agrupador) {
                                                                    echo "<option value='".$conteudo_natureza_financeira_agrupador['id_natureza_financeira']."'>".$conteudo_natureza_financeira_agrupador['nome'].' - '.$conteudo_natureza_financeira_agrupador['nome_natureza']."</option>";
                                                                }
                                                            }
                                                        ?>
                                                    </select>
					                            </div>
					                        </div>                                            
					                        <div class="col-md-3">
					                            <div class="form-group has-feedback">
					                                <label class="control-label">Data de Vencimento: </label>
					                                <input class="form-control date calendar hasDatepicker input-sm" type="text" name="data_de_conta_pagar" onchange="call_busca_ajax_conta_pagar();" autocomplete="off" maxlength="10" value='<?=$primeiro_dia?>'>
					                            </div>
					                        </div>
					                        <div class="col-md-3">
					                            <div class="form-group has-feedback">
					                                <label class="control-label">Data até Vencimento: </label>
					                                <input class="form-control date calendar hasDatepicker input-sm" type="text" name="data_ate_conta_pagar" onchange="call_busca_ajax_conta_pagar();" autocomplete="off" maxlength="10" value='<?=$ultimo_dia?>'>
					                            </div>
					                        </div>
					                    </div>
					                    <br>
					                    <div class="row">
					                        <div class="col-md-12">
					                            <div id="resultado_busca_conta_pagar"></div>
					                        </div>
					                    </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end tab_conta_pagar -->

                            <!-- tab_caixas  -->
                            <div id="tab_caixas" class="tab-pane fade in <?=$ancora_caixas?>">
                                <br>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="panel-group" id="accordionVinculos" role='tablist'>
                                            <div class="panel panel-default">
                                                <div class="panel-heading clearfix">
                                                    <div class="panel-title text-left pull-left">
                                                        <h3 class="panel-title text-left pull-left">Caixas:</h3>
                                                    </div>
                                                    <div class="panel-title text-right pull-right">
                                                        <h3 class="panel-title text-right pull-right">Referência: <strong><?=converteData(getDataHora('data'))?></strong></h3>
                                                    </div>
                                                </div>
                                                <div class="panel-body" style='overflow: auto; height: 565px !important;'>

                                                    <?php
														$dados_caixa = DBRead('', 'tb_caixa', "WHERE status = 1 AND aceita_movimentacao = 1 ORDER BY nome ASC");
                                                        $saldo_total = 0;
                                                    	if($dados_caixa){
                                                            if(isset($_GET['caixa'])){
                                                                $caixa_selecionado = $_GET['caixa'];
                                                            }else{
                                                                $caixa_selecionado = $dados_caixa[0]['id_caixa'];
                                                            }
                                                            echo '<input type="hidden" id="id_caixa" value="'.$caixa_selecionado.'" >';
                                                    		foreach ($dados_caixa as $conteudo_caixa){
                                                    			$valor_total_caixa_receber = DBRead('','tb_caixa_movimentacao', "WHERE id_caixa = '".$conteudo_caixa['id_caixa']."' AND tipo = 'entrada' AND data_movimentacao = '".getDataHora('data')."' ", "SUM(valor) AS valor_total_caixa");

                                                                $valor_total_caixa_receber = converteMoeda($valor_total_caixa_receber[0]['valor_total_caixa']);

                                                                $valor_total_caixa_pagar = DBRead('','tb_caixa_movimentacao', "WHERE id_caixa = '".$conteudo_caixa['id_caixa']."' AND tipo = 'saida' AND data_movimentacao = '".getDataHora('data')."' ", "SUM(valor) AS valor_total_caixa");
                                                                $valor_total_caixa_pagar = converteMoeda($valor_total_caixa_pagar[0]['valor_total_caixa']);

                                                    			if($caixa_selecionado == $conteudo_caixa['id_caixa']){
                                                    				$cor_botao = 'primary';
                                                    			}else{
                                                    				$cor_botao = 'default';
                                                    			}
                                                                $saldo_total += $conteudo_caixa['saldo'];

                                                    			echo '
                                                        			<div class="row">
			                                                            <div class="col-md-12">
			                                                                <div class="form-group has-feedback">
																				<button class="btn btn-'.$cor_botao.'" id="botao_id_caixa" name="botao_id_caixa[]" style="width: 100%; height: 122px;" value="'.$conteudo_caixa['id_caixa'].'">
																					<div class="col-md-4 text-left pull-left"><i class="fas fa-piggy-bank" aria-hidden="true"></i> Caixa:</div>
																					<div class="col-md-8 text-left pull-left" id="nome_caixa_transferencia"><strong id="nome_id_caixa_'.$conteudo_caixa['id_caixa'].'">'.$conteudo_caixa['nome'].'</strong></div>
																					<div class="col-md-4 text-left pull-left"> <i class="fas fa-money-check-alt" aria-hidden="true"></i> Saldo:</div>
																					<div class="col-md-8 text-left pull-left"><strong>R$ '.converteMoeda($conteudo_caixa['saldo'],'moeda').'</strong></div>
                                                                                    <div class="col-md-4 text-left pull-left"><i class="fas fa-arrow-circle-down" aria-hidden="true"></i> Entradas:</div>
                                                                                    <div class="col-md-8 text-left pull-left"><strong>R$ '.$valor_total_caixa_receber.'</strong></div>
                                                                                    <div class="col-md-4 text-left pull-left"><i class="fas fa-arrow-circle-up" aria-hidden="true"></i> Saídas:</div>
                                                                                    <div class="col-md-8 text-left pull-left"><strong>R$ '.$valor_total_caixa_pagar.'</strong></div>
																				</button>
																			</div>
																		</div>
																	</div>
																';
                                                    		}
                                                    	}
                                                    ?>
                                                </div>
                                                <div class="panel-footer">
                                                    <div class="row">
                                                        <div class="col-md-12 text-center">
                                                            <span class="text-center">Saldo Total: <strong>R$ <?= converteMoeda($saldo_total) ?></strong></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="panel-group" id="accordionVinculos" role='tablist'>
                                            <div class="panel panel-default">
                                                <div class="panel-heading clearfix">
                                                    <h3 class="panel-title text-left pull-left">Movimentações: <strong id="titulo"></strong></h3>
                                                    <div class="panel-title text-right pull-right">
                                                        <button class="btn btn-xs btn-primary" data-toggle='modal' data-target='#modal_tranferencia'><i class="fas fa-exchange-alt"></i> Tranferência</button></a>
                                                    </div>
                                                </div>
                                                <div class="panel-body" style="height:560px">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="form-group has-feedback">
                                                                    <label>Busca:</label>
                                                                    <input class="form-control input-sm" type="text" id="nome_caixa" onKeyUp="call_busca_ajax_caixas();" placeholder="Digite o Nome da Pessoa" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group has-feedback">
                                                                    <label>Natureza Financeira:</label>
                                                                    <select class="form-control input-sm" id="id_natureza_financeira_agrupador_caixa" onchange="call_busca_ajax_caixas();">
                                                                        <option value="">Todas</option>
                                                                        <?php
                                                                            $dados_natureza_financeira_agrupador = DBRead('', 'tb_natureza_financeira a', "INNER JOIN tb_natureza_financeira_agrupador b ON a.id_natureza_financeira_agrupador = b.id_natureza_financeira_agrupador WHERE a.status = 1 ORDER BY b.nome ASC", 'a.nome AS nome_natureza, a.*, b.*');

                                                                            if ($dados_natureza_financeira_agrupador) {
                                                                                foreach ($dados_natureza_financeira_agrupador as $conteudo_natureza_financeira_agrupador) {
                                                                                    echo "<option value='".$conteudo_natureza_financeira_agrupador['id_natureza_financeira']."'>".$conteudo_natureza_financeira_agrupador['nome'].' - '.$conteudo_natureza_financeira_agrupador['nome_natureza']."</option>";
                                                                                }
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group has-feedback">
                                                                    <label class="control-label">Data de:</label>
                                                                    <input class="form-control date calendar hasDatePicker hasDatepicker input-sm" type="text" name="data_de_caixa" onchange="call_busca_ajax_caixas();" placeholder="dd/mm/aaaa" autocomplete="off" maxlength="10" value='<?=converteData(getDataHora('data'))?>'>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group has-feedback">
                                                                    <label class="control-label">Data até:</label>
                                                                    <input class="form-control date calendar hasDatePicker hasDatepicker input-sm" type="text" name="data_ate_caixa" onchange="call_busca_ajax_caixas();" placeholder="dd/mm/aaaa" autocomplete="off" maxlength="10" value='<?=converteData(getDataHora('data'))?>'>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-2">
                                                                <div class="form-group has-feedback">
                                                                    <label>Origem:</label>
                                                                    <select class="form-control input-sm" id="origem" onchange="call_busca_ajax_caixas();">
                                                                        <option value="">Todas</option>
                                                                        <option value="conta_receber">Conta a Receber</option>
                                                                        <option value="conta_pagar">Conta a Pagar</option>
                                                                        <option value="transferencia">Transferência</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div id="resultado_busca_caixas"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel-footer">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <!-- style='font-size: 11px; -->
                                                            <span class="text-right pull-left" id="total_registros"></span>
                                                            <span class="text-right pull-right" id="soma_total_caixa"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end tab_caixas -->
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_tranferencia" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="panel-title text-center pull-center" style="margin-top: 2px; font-size: 150%;">Transferência de <span id='texto_tranferencia'></span></h3>
            </div>
            <form method="post" action="/api/ajax?class=ControleContas.php" id="controle_contas_transferencia_form" style="margin-bottom: 0;">
		        <input type="hidden" name="token" value="<?php echo $request->token ?>">
                <input type="hidden" id="caixa_saida_transferencia" name="caixa_saida_transferencia" value="1"/>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>*Valor:</label>
                                                <input class="form-control input-sm money" name="valor_transferencia" id="valor_transferencia" type="text" autocomplete="off" value="0,00">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>*Caixa Destino:</label> 
                                            <select name="select_caixa_a_transferir" id="select_caixa_a_transferir" class="form-control input-sm">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>*Data da Transferência:</label>
                                                <input class="form-control date calendar hasDatePicker hasDatepicker input-sm" name="data_movimentacao_transferencia" id="data_movimentacao_transferencia" type="text" placeholder="dd/mm/aaaa" value="<?=converteData(getDataHora('data'))?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>*Natureza Financeira:</label> 
                                            <select name="select_caixa_a_transferir_natureza_financeira" id="select_caixa_a_transferir_natureza_financeira" class="form-control input-sm">
                                                <?php
                                                    $dados_natureza_financeira_transferencia = DBRead('', 'tb_natureza_financeira', "WHERE tipo = 'transferencia' AND status = 1 ORDER BY nome ASC");

                                                    if ($dados_natureza_financeira_transferencia) {
                                                        foreach ($dados_natureza_financeira_transferencia as $conteudo_natureza_financeira_transferencia) {
                                                            echo "<option value='".$conteudo_natureza_financeira_transferencia['id_natureza_financeira']."'>".$conteudo_natureza_financeira_transferencia['nome']."</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-12" style="text-align: center">
                            <input type="hidden" id="operacao_transferencia" name="operacao_transferencia" value="1"/>
                            <button class="btn btn-primary" id="submit_transferencia" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal contas pagar -->
<div class="modal fade" id="modal_contas_pagar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="panel-title text-center pull-center" style="margin-top: 2px; font-size: 150%;">Conta a Pagar</h3>
      </div>
        <div class="modal-body">      
            <div id="conteudo_modal_contas_pagar">
            </div>
        </div>
    </div>
  </div>
</div>

<!-- Modal contas receber -->
<div class="modal fade" id="modal_contas_receber" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="panel-title text-center pull-center" style="margin-top: 2px; font-size: 150%;">Conta a Receber</h3>
      </div>
        <div class="modal-body">      
            <div id="conteudo_modal_contas_receber">
            </div>
        </div>
    </div>
  </div>
</div>

<!-- Modal caixas -->
<div class="modal fade" id="modal_caixas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div id="conteudo_modal_caixas">
      </div>                                                           
    </div>
  </div>
</div>

<script>

    $(document).on('click', '.modal_contas_pagar', function(e){

        id = $(this).attr('attr-id');

        function call_busca_ajax(id){
           busca_ajax('<?= $request->token ?>' , 'ControleContasPagarModal', 'conteudo_modal_contas_pagar', id);
        }

        call_busca_ajax(id);

        $('#modal_contas_pagar').modal('show');
        
    });

    $(document).on('click', '.modal_contas_receber', function(e){

        id = $(this).attr('attr-id');

        function call_busca_ajax(id){
           busca_ajax('<?= $request->token ?>' , 'ControleContasReceberModal', 'conteudo_modal_contas_receber', id);
        }

        call_busca_ajax(id);

        $('#modal_contas_receber').modal('show');
        
    });

    $(document).on('click', '.modal_caixas', function(e){

        id = $(this).attr('attr-id');

        function call_busca_ajax(id){
           busca_ajax('<?= $request->token ?>' , 'ControleContasCaixaModal', 'conteudo_modal_caixas', id);
        }

        call_busca_ajax(id);

        $('#modal_caixas').modal('show');
        
    })

    $(document).on('submit', '#controle_contas_transferencia_form', function () {
        
        var select_caixa_a_transferir_natureza_financeira = $("#select_caixa_a_transferir_natureza_financeira").val();
        var select_caixa_a_transferir = $("#select_caixa_a_transferir").val();
        var valor = $("#valor_transferencia").val();
        var data_movimentacao_transferencia = $('input[name="data_movimentacao_transferencia"]').val();

        if(!valor || valor == "" || valor == "0,00"){
            alert("Deve-se inserir um valor!");
            return false;
        }else if(!data_movimentacao_transferencia || data_movimentacao_transferencia == ""){
            alert("Deve-se inserir a data de transferência!");
            return false;
        }else if(!select_caixa_a_transferir_natureza_financeira){
            alert("Deve-se selecionar uma natureza financeira!");
            return false;
        }else if(!select_caixa_a_transferir){
            alert("Deve-se selecionar uma caixa de destino!");
            return false;
        }

        modalAguarde();

    });

    $(document).ready(function(){
        var select_caixa = '';
        var contador_caixa = '';

        $("[name ='botao_id_caixa[]']").each(function(){

            var nome = $(this).find('#nome_caixa_transferencia').text();
            if(contador_caixa == ''){
                $('#texto_tranferencia').text(nome);
                $('#caixa_saida_transferencia').val($(this).val());
                contador_caixa = '1';
            }else{
                select_caixa +='<option value='+$(this).val()+'>'+nome+'</option>';
            }
        });

        $('#select_caixa_a_transferir').html(select_caixa);

    });
    
    $(document).on('click', '[name ="botao_id_caixa[]"]', function(){
        $('#caixa_saida_transferencia').val($(this).val());
        var select_caixa = '';
        var este_caixa = $(this).val();

        var nome = $(this).find('#nome_caixa_transferencia').text();
        $('#texto_tranferencia').text(nome);

        $("[name ='botao_id_caixa[]']").each(function(){
            var nome = $(this).find('#nome_caixa_transferencia').text();
            if($(this).val() != este_caixa){
                select_caixa +='<option value='+$(this).val()+'>'+nome+'</option>';
            }
        });

        $('#select_caixa_a_transferir').html(select_caixa);

    });
	
	//Início aba caixas
	    function call_busca_ajax_caixas(pagina){
	        var inicia_busca = 3;
	        var id_caixa = $('#id_caixa').val();
			var nome_id_caixa = $('#nome_id_caixa_'+id_caixa).text();
	        $('#titulo').text(nome_id_caixa);

            var nome = $('#nome_caixa').val();

            var data_de = $('input[name="data_de_caixa"]').val();
            var data_ate = $('input[name="data_ate_caixa"]').val();

            var agrupador = $('#id_natureza_financeira_agrupador_caixa').val();

            var origem = $('#origem').val();

	        if (nome.length < inicia_busca && nome.length >=1){
	            return false;
	        }
	        if(pagina === undefined){
	            pagina = 1;
	        }
	        var parametros = {
	            'id_caixa': id_caixa,
                'nome': nome,
                'data_de': data_de,
                'data_ate': data_ate,
                'agrupador': agrupador,
                'origem': origem,
	            'pagina': pagina
	        };
	        busca_ajax('<?= $request->token ?>' , 'ControleContasCaixaBusca', 'resultado_busca_caixas', parametros);
	    }

	    $(document).on('click', '.troca_pag', function () {
	        call_busca_ajax_caixas($(this).attr('atr-pagina'));
	    });

	    $(document).on('click', '#botao_id_caixa', function(){
	    	var id_caixa = $(this).val();

			var antigo = $('#id_caixa').val();

			$("[name ='botao_id_caixa[]']").each(function() {

				if($(this).val() == id_caixa){
					$(this).removeClass('btn-default');
					$(this).addClass('btn-primary');
				}else if($(this).val() == antigo){
					$(this).removeClass('btn-primary');
					$(this).addClass('btn-default');
				}
				
			});

			$('#id_caixa').val(id_caixa);
	    	call_busca_ajax_caixas();
	    });

	    call_busca_ajax_caixas();
    //Fim aba caixas

    //Início aba contas a pagar
 	    function call_busca_ajax_conta_pagar(pagina){
	        
            var inicia_busca = 3;

            var nome = $('#nome_conta_pagar').val();

            var data_de = $('input[name="data_de_conta_pagar"]').val();
            var data_ate = $('input[name="data_ate_conta_pagar"]').val();

            var agrupador = $('#id_natureza_financeira_agrupador_conta_pagar').val();

            var id_busca = $('#id_busca_conta_pagar').val();

	        if (nome.length < inicia_busca && nome.length >=1){
	            return false;
	        }
	        if(pagina === undefined){
	            pagina = 1;
	        }
	        var parametros = {
                'nome': nome,
                'data_de': data_de,
                'data_ate': data_ate,
                'agrupador': agrupador,
                'id_busca': id_busca,
	            'pagina': pagina
	        };
	        busca_ajax('<?= $request->token ?>' , 'ControleContasPagarBusca', 'resultado_busca_conta_pagar', parametros);
	    }

	    call_busca_ajax_conta_pagar();
    //Fim aba contas a pagar

    //Início aba contas a receber

        function call_busca_ajax_conta_receber(pagina){
            var inicia_busca = 3;

            var nome = $('#nome_conta_receber').val();

            var data_de = $('input[name="data_de_conta_receber"]').val();
            var data_ate = $('input[name="data_ate_conta_receber"]').val();

            var agrupador = $('#id_natureza_financeira_agrupador_conta_receber').val();
            
            var envio_email = $('#envio_email').val();
            var id_busca = $('#id_busca_conta_receber').val();
            
            var obs_conta_receber = $('#obs_conta_receber').val();

            var faturamento_conta_receber = $('#faturamento_conta_receber').val();

            if (nome.length < inicia_busca && nome.length >=1){
                return false;
            }
            if(pagina === undefined){
                pagina = 1;
            }

            var parametros = {
                'nome': nome,
                'data_de': data_de,
                'data_ate': data_ate,
                'agrupador': agrupador,
                'envio_email': envio_email,
                'id_busca': id_busca,
                'obs_conta_receber': obs_conta_receber,
                'faturamento_conta_receber': faturamento_conta_receber,
                'pagina': pagina
            };
            busca_ajax('<?= $request->token ?>' , 'ControleContasReceberBusca', 'resultado_busca_conta_receber', parametros);
        }

        call_busca_ajax_conta_receber();
    //Fim aba contas a receber

    //Início aba dashboard
        function call_busca_ajax_dashboard(){
            busca_ajax('<?= $request->token ?>' , 'class/ControleContasDashboardBusca', 'resultado_dashboard');
        }

        call_busca_ajax_dashboard();
    //Fim aba dashboard
</script>