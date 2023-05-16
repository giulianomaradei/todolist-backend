<?php
	require_once(__DIR__."/../class/System.php");
    $operacao = 'clonar';
    $id = 1;

	$hoje = explode("-", getDataHora());
	$ano_hoje = $hoje[0];
	$mes_hoje = $hoje[1];

	$ano_atual = (!empty($_POST['ano'])) ? $_POST['ano'] : $ano_hoje;
	$mes_atual = (!empty($_POST['mes'])) ? $_POST['mes'] : $mes_hoje;

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Clonar Escalas:</h3>
                </div>
                <form method="post" action="/api/ajax?class=EscalaHorarios.php" style="margin-bottom: 0;">
					<input type="hidden" name="token" value="<?php echo $request->token ?>">

                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*De (mês/ano):</label>
			                        <select class="form-control" id="data_existente" name="data_existente">
			                            <option></option>
			                            <?php
			                                $horarios = DBRead('', 'tb_horarios_escala', "GROUP BY data_inicial", "data_inicial");
			                                
			                                foreach($horarios as $conteudo){
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
			                                    echo "<option value=".$conteudo['data_inicial'].">".$mes."/".$data[0]."</option>";
			                                }
			                            ?>
			                        </select>
                                </div>
                            </div>                     

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Para (mês):</label>
			                        <select name="mes" id="mes" class="form-control">
											<?php
											$meses = array(
												"01" => "Janeiro",
												"02" => "Fevereiro",
												"03" => "Março",
												"04" => "Abril",
												"05" => "Maio",
												"06" => "Junho",
												"07" => "Julho",
												"08" => "Agosto",
												"09" => "Setembro",
												"10" => "Outubro",
												"11" => "Novembro",
												"12" => "Dezembro",
												
											);

											foreach ($meses as $num => $mes) {
												$selected = $mes_atual == $num ? "selected" : "";
												echo "<option value='".sprintf('%02d', $num)."' ".$selected.">".$mes."</option>";
											}
											?>													
										</select>       
                                </div>
                            </div>         
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>*Para (ano):</label>
			                        <select name="ano" id="ano" class="form-control">
										<?php
										$anos = array(
											"2019" => "19",
											"2020" => "20",
											"2021" => "21",
											"2022" => "22",
											"2023" => "23",
											"2024" => "24",
											"2025" => "25",
										);
										foreach ($anos as $num => $ano) {
											$selected = $ano_atual == $num ? "selected" : "";
											echo "<option value='".$num."' ".$selected.">".$num."</option>";
										}
										?>													
									</select>              
                                </div>
                            </div>                       
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="2" name="clonar"/>
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>     
