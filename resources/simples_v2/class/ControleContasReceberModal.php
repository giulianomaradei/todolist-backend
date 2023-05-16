<?php
require_once(__DIR__."/System.php");
?>

<style>
	button.editar_obs:active {
		outline: none;
		border: none;
	}

	button.editar_obs:focus {
		outline:0;
	}

	button.salvar_obs:active {
		outline: none;
		border: none;
	}

	button.salvar_obs:focus {
		outline:0;
	}
</style>

<?php
$id_conta_receber = (isset($_POST['parametros'])) ? $_POST['parametros'] : '';

$dados = DBRead('', 'tb_conta_receber a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_natureza_financeira c ON a.id_natureza_financeira = c.id_natureza_financeira INNER JOIN tb_natureza_financeira_agrupador d ON c.id_natureza_financeira_agrupador = d.id_natureza_financeira_agrupador WHERE a.situacao = 'aberta' AND a.id_conta_receber = $id_conta_receber", "a.*, b.nome, c.nome AS nome_natureza, d.nome AS nome_natureza_agrupador");

$nome = $dados[0]['nome'];

$natureza = $dados[0]['nome_natureza_agrupador']." (".$dados[0]['nome_natureza'].")";
$nome_natureza = $dados[0]['nome_natureza'];

$informacoes = '';

$data_emissao = converteData($dados[0]['data_emissao']);

$data_vencimento = $dados[0]['data_vencimento'];
$data_hoje = getDataHora('data');

$dados_caixa = DBRead('', 'tb_caixa', "WHERE id_caixa = '".$dados[0]['id_caixa']."' ");
$dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$dados[0]['id_usuario']."' ");
$dados_pessoa = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$dados[0]['id_pessoa']."' ");

if($dados_pessoa[0]['cpf_cnpj']){
	$cpf_cnpj = formataCampo('cpf_cnpj', $dados_pessoa[0]['cpf_cnpj']);
}else{
	$cpf_cnpj = '';
}

$titulo_conta = 'Conta a Receber';                                

if($dados[0]['id_conta_pai']){
	$conta_pai = 'Possui';
}else{
	$conta_pai = '';
}

if($dados[0]['id_boleto']){
	$id_boleto = $dados[0]['id_boleto'];
	$btn_visualizar_boleto = ' <a href="/api/iframe?token=<?php echo $request->token ?>&view=boleto-visualizar&visualizar='.$id_boleto.'" target="_blank"><i class="fa fa-eye"></i></a>';
	$dados_boleto = DBRead('','tb_boleto', "WHERE id_boleto = '$id_boleto'");
	if($dados_boleto[0]['situacao'] != 'EMITIDO' && $dados_boleto[0]['situacao'] != 'REJEITADO'){
		$informacoes .= '<span><i class="fa fa-barcode" aria-hidden="true"></i> Boleto registrado</span>';
	}else if($dados_boleto[0]['situacao'] == 'REJEITADO'){
		$informacoes .= '<span class="text-danger faa-flash animated"><i class="fa fa-barcode" aria-hidden="true"></i> Boleto rejeitado</span>';
	}else{
		$informacoes .= '<span class="text-warning faa-flash animated"><i class="fa fa-barcode" aria-hidden="true"></i> Boleto não registrado</span>';
	}
}else{
	$id_boleto = '';
	$btn_visualizar_boleto = '';
}

if($dados[0]['id_nfs']){
	$id_nfs = $dados[0]['id_nfs'];
	$dados_nfs = DBRead('','tb_nfs',"WHERE id_nfs = '$id_nfs'");
	$btn_visualizar_nfs = ' <a href="/api/iframe?token=<?php echo $request->token ?>&view=nfs-visualizar&visualizar='.$id_nfs.'" target="_blank"><i class="fa fa-eye"></i></a>';
	if($dados_nfs[0]['status'] == 'autorizada'){                                       
		$informacoes .= '<br><span><i class="fas fa-file-invoice-dollar"></i> NFS-e emitida</span>';
	}else if($dados_nfs[0]['status'] == 'negada'){
		$informacoes .= '<br><span class="text-danger faa-flash animated"><i class="fas fa-file-invoice-dollar"></i> NFS-e negada</span>';
	}else{
		$informacoes .= '<br><span class="text-warning faa-flash animated"><i class="fas fa-file-invoice-dollar"></i> NFS-e pendente</span>';
	}
	
}else{
	$id_nfs = '';
	$btn_visualizar_nfs = '';
}

if($dados[0]['id_faturamento']){
	$id_faturamento = $dados[0]['id_faturamento'];
}else{
	$id_faturamento = '';
}

if($dados[0]['data_pagamento']){
	$data_pagamento = converteData($dados[0]['data_pagamento']);
}else{
	$data_pagamento = '';
}

if($dados_pessoa[0]['email']){
	$email = $dados_pessoa[0]['email'];
}else{
	$email = '';
}

if($dados_pessoa[0]['fone1']){
	$telefone = formataCampo('fone', $dados_pessoa[0]['fone1']);
}else{
	$telefone = '';
}

$soma_total_conta_receber += $dados[0]['valor'];
$valor = converteMoeda($dados[0]['valor']);

$situacao = ucfirst($dados[0]['situacao']);

if($dados[0]['tipo'] == 'entrada'){
	$tipo = '<span class="label label-success" style="display: inline-block; min-width: 50px;">Entrada</span>';
	$tipo_modal = '<span class="label label-success" style="display: inline-block; min-width: 100px;"> Entrada </span>';
}else{
	$tipo = '<span class="label label-danger" style="display: inline-block; min-width: 50px;"> Saída </span>';
	$tipo_modal = '<span class="label label-danger" style="display: inline-block; min-width: 100px;"> Saída </span>';
}

$origem = 'Conta Receber';

echo '
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<h4><strong>Movimentação</strong></h4>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Valor:</label>
							<input type="text" class="form-control input-sm" value="R$ '.converteMoeda($dados[0]['valor']).'" readonly/>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Caixa:</label>
							<input type="text" class="form-control input-sm" value="'.$dados_caixa[0]['nome'].'" readonly/>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Data e Hora do Cadastro:</label>
							<input type="text" class="form-control input-sm" value="'.converteDataHora($dados[0]['data_cadastro']).'" readonly/>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Usuário que Cadastrou:</label>
							<input type="text" class="form-control input-sm" value="'.$dados_usuario[0]['nome'].'" readonly/>
						</div>
					</div>
				</div>

				<hr>
				<h4><strong>Natureza Financeira</strong></h4>
				<hr>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Natureza:</label>
							<input type="text" class="form-control input-sm" value="'.$dados[0]['nome_natureza'].'" readonly/>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Agrupador:</label>
							<input type="text" class="form-control input-sm" value="'.$dados[0]['nome_natureza_agrupador'].'" readonly/>
						</div>
					</div>
				</div>
				<hr>
				<h4><strong>Pessoa</strong></h4>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Nome:</label>
							<input type="text" class="form-control input-sm" value="'.$dados_pessoa[0]['nome'].'" readonly/>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>CPF/CNPJ:</label>
							<input type="text" class="form-control input-sm" value="'.$cpf_cnpj.'" readonly/>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Razão Social:</label>
							<input type="text" class="form-control input-sm" value="'.$dados_pessoa[0]['razao_social'].'" readonly/>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>E-mail:</label>
							<input type="text" class="form-control input-sm" value="'.$email.'" readonly/>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Telefone:</label>
							<input type="text" class="form-control input-sm" value="'.$telefone.'" readonly/>
						</div>
					</div>
				</div>
				
				<hr>
				<h4><strong>'.$titulo_conta.'</strong></h4>
				<hr>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>Data de Emissão:</label>
							<input type="text" class="form-control input-sm" value="'.converteData($dados[0]['data_emissao']).'" readonly/>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Data de Vencimento:</label>
							<input type="text" class="form-control input-sm" value="'.converteData($dados[0]['data_vencimento']).'" readonly/>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Data do Pagamento:</label>
							<input type="text" class="form-control input-sm" value="'.$data_pagamento.'" readonly/>
						</div>
					</div>
				</div>				                      
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>Situação:</label>
							<input type="text" class="form-control input-sm" value="'.ucfirst($dados[0]['situacao']).'" readonly/>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Número Parcela:</label>
							<input type="text" class="form-control input-sm" value="'.$dados[0]['numero_parcela'].'" readonly/>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Conta Pai:</label>
							<input type="text" class="form-control input-sm" value="'.$conta_pai.'" readonly/>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Descrição:</label>
							<textarea type="text" class="form-control input-sm" style="height: 100px;" readonly>'.$dados[0]['descricao'].'</textarea>
						</div>
					</div>
				</div>
				';

				echo 
				'					                      
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>Boleto:'.$btn_visualizar_boleto.'</label>
							<input type="text" class="form-control input-sm" value="'.$id_boleto.'" readonly/>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>NFS-e:'.$btn_visualizar_nfs.'</label>
							<input type="text" class="form-control input-sm" value="'.$id_nfs.'" readonly/>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Faturamento:</label>
							<input type="text" class="form-control input-sm" value="'.$id_faturamento.'" readonly/>
						</div>
					</div>
				</div>';

				echo '
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label id="label_editar">Observação: <button class="button_obs" id="editar_obs" href="" title="Editar Observação" style="color: #337ab7; text-decoration: none; background-color: transparent; cursor: pointer; border: none" value="0"><i class="fa fa-pen"></i> Editar</button></label>

							<label id="label_salvar" style="display: none">Observação: <button class="button_obs" id="salvar_obs" href="" title="Salvar Observação" style="color: #5cb85c ; text-decoration: none; background-color: transparent; cursor: pointer; border: none" value="1"><i class="fa fa-check"></i> Salvar</button></label>


							<textarea id="obs_textarea" type="text" class="form-control input-sm" style="height: 100px;" readonly>'.$dados[0]['observacao'].'</textarea>
						</div>
					</div>
				</div>
				';

											
				echo 
				'
			</div>
		</div>
	</div>';

?>
<script>
	$('.button_obs').click(function(){
		if($(this).val() == 0){
			$("#obs_textarea").attr("readonly", false); 
			$("#label_editar").hide();
			$("#label_salvar").show();
			
		}else{
			if (!confirm('Você tem certeza da alteração na observação?')) { 
				return false; 
			} else {
				var id_conta_receber = '<?=$id_conta_receber?>';
				var observacao = $("#obs_textarea").val();

				$.ajax({
                    url: "class/ControleContasObservacao.php",
                    dataType: "html",
                    method: 'POST',
                    data: {
                        parametros: { 
                            'id_conta_receber': id_conta_receber,
                            'observacao': observacao
                        }
                    },
                    success: function (data) {
                        $("#obs_textarea").attr("readonly", true); 
						$("#label_editar").show();
						$("#label_salvar").hide();
                    }
                });
				
			}

			
		}
	});

</script>