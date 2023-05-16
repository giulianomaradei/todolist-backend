<?php
require_once(__DIR__."/../class/System.php");

$hoje = explode("-", getDataHora('data'));
$ano_hoje = $hoje[0];
$mes_hoje = $hoje[1];

$ano_atual = (!empty($_POST['ano'])) ? $_POST['ano'] : $ano_hoje;
$mes_atual = (!empty($_POST['mes'])) ? $_POST['mes'] : $mes_hoje;

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Replicar Horários Especiais:</h3>
                </div>
                <form method="post" action="/api/ajax?class=EscalaHorarios.php" style="margin-bottom: 0;">
					<input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Escala de Referência(mês/ano):</label>
			                        <select class="form-control" id="data_existente" name="data_existente">
			                            <?php
			                                $horarios = DBRead('', 'tb_horarios_escala', "GROUP BY data_inicial", "data_inicial");
			                                $i = 0;
											$total = count($horarios);
			                                foreach($horarios as $conteudo){
												$i++;
			                                	$data = explode("-", $conteudo['data_inicial']);

												if($data[1] == "01"){
													$mes = "Janeiro";
													}else if($data[1] == "02"){
													$mes = "Fevereiro";
													}else if($data[1] == "03"){
													$mes = "Março";
													}else if($data[1] == "04"){
													$mes = "Abril";
													}else if($data[1] == "05"){
													$mes = "Maio";
													}else if($data[1] == "06"){
													$mes = "Junho";
													}else if($data[1] == "07"){
													$mes = "Julho";
													}else if($data[1] == "08"){
													$mes = "Agosto";
													}else if($data[1] == "09"){
													$mes = "Setembro";
													}else if($data[1] == "10"){
													$mes = "Outubro";
													}else if($data[1] == "11"){
													$mes = "Novembro";
													}else if($data[1] == "12"){
													$mes = "Dezembro";
												}
												if($i == $total){
			                                    	echo "<option value=".$conteudo['data_inicial']." selected>".$mes."/".$data[0]."</option>";
			                                    	$data_proximo_mes = new DateTime($data[0].'-'.$data[1].'-01');
													$data_proximo_mes->modify('first day of this month');
													$data_proximo_mes = $data_proximo_mes->format('d/m/Y');
												}else{
			                                    	echo "<option value=".$conteudo['data_inicial'].">".$mes."/".$data[0]."</option>";
												}
			                                }
			                            ?>
			                        </select>
                                </div>
                            </div>                     

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Escalas de:</label>
			                        <select name="dia_escala_referencia" id="mes" class="form-control">
										<option value="dom">Domingo</option>
	                                    <option value="sab">Sábado</option>
	                                    <option value="seg">Segunda a Sexta</option>					
									</select>       
                                </div>
                            </div>         
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">*Para:</label>
                                	<input class="form-control date calendar hasDatePicker hasDatepicker" type="text" name="data_replica" placeholder="dd/mm/aaaa" autocomplete="off" value="<?= $data_proximo_mes?>"> 
                                </div>
                            </div>                       
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="2" name="replicar"/>
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>     
