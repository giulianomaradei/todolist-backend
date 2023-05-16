<?php
	$dados = DBRead('', 'tb_solicitacao_ajuda a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_usuario d ON a.atendente = d.id_usuario INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa WHERE data_encerramento IS NULL", "a.id_solicitacao_ajuda, a.data_inicio, b.nome_contrato, c.nome AS 'empresa', e.nome AS 'atendente'");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">

            	<div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Solicitações de ajuda:</h3>
                </div>
                <div class="panel-body">
                	<?php if($dados){ ?>
                	<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th class="text-center">Atendente</th>
								<th class="text-center">Empresa</th>
								<th class="text-center">Hora</th>
								<th class="text-center">Motivo da solicitação</th>
								<th class="text-center">Ação</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if($dados){
								foreach($dados as $conteudo){

									echo "<form method='post' action='/api/ajax?class=SolicitaAjuda.php'>";
									echo '<input type="hidden" name="token" value="'.$request->token.'">';
									echo "<tr>";
										echo "<td class='text-center'>".$conteudo['atendente']."</td>";
										echo "<td class='text-center'>".$conteudo['empresa'];
										
										if($conteudo['nome_contrato']){
											echo " (".$conteudo['nome_contrato'].")";
										}
	
										echo "</td>";
										echo "<td class='text-center'>".converteDataHora($conteudo['data_inicio'])."</td>";
	
										echo "<td>";
											echo "<select class='form-control input-sm select-motivo' name='id_motivo_solicitacao_ajuda' >";
	
												$dados_motivo = DBRead('', 'tb_motivo_solicitacao_ajuda',"WHERE status = '1' ORDER BY descricao ASC");
												echo "<option></option>";
												if($dados_motivo){
													foreach($dados_motivo as $motivo){
														echo "<option value='".$motivo['id_motivo_solicitacao_ajuda']."'>".$motivo['descricao']."</option>";
													}
												}
	
											echo "</select>";
										echo "</td>";
	
										echo "<td class='text-center'><button class='btn btn-danger btn-sm ok-motivo' name='alterar' type='submit'><i class='fa fa-floppy-o'></i> Encerrar solicitação</button></td>";
										echo "<input type='hidden' value='".$conteudo['id_solicitacao_ajuda']."' name='encerrar' />";
									echo "</tr>";
	
									echo "</form>";
								}
							}
							
							
							?>
						</tbody>
					</table>
                    <?php 
                	}else{

						echo "<tr>";
						    echo "<td colspan='5'><div class='alert alert-info text-center' role='alert'>Não há solicitações pendentes!</div></td>";
						echo "</tr>";
					} 
					?>          
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	 $(document).on('click', '.ok-motivo', function(){
		motivo = $(this).parent().parent().find('[name="id_motivo_solicitacao_ajuda"]').val();		
		if(!motivo){
			alert("É necessário selecionar um motivo!");
			return false;
		}
		
	});
</script>