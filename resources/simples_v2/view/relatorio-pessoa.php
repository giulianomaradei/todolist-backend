<?php
require_once(__DIR__."/../class/System.php");
?>
<style>
	td{
		padding: 0px !important;
	}
	table{
		margin: 0px !important;
	}
	.painel-vinculos{
		margin-bottom: 5px !important;
	}
	.painel-body-vinculos{
		padding: 0 !important;
	}
	.painel-body-externo{
		padding: 0;
	}

	.temConteudo{
		display: inline-block;
	}
	.naoTemConteudo{
		display: none;
	}
	.tdAniversario{
		padding: 10px !important;
	}
	.tableAniversario{
		margin: 10px !important;
	}
	
</style>
<?php
function relatorio_geral($dados_exibir, $atributo, $id_pessoa, $vinculo, $data_a_partir, $data_atualizacao, $escolhe_dados){

	$data_hora = converteDataHora(getDataHora());

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	if($atributo){
		$atributo_legenda = ucfirst($atributo);
	}
	if($id_pessoa){
		$dados_pessoa = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$id_pessoa."'", "nome");
		$pessoa_legenda = $dados_pessoa[0]['nome'];
	}else{
		$pessoa_legenda = 'Qualquer';
	}
	if($vinculo){
		if($vinculo != 'todos' && $vinculo != 'nenhum'){
			$dados_vinculo = DBRead('', 'tb_vinculo_tipo', "WHERE id_vinculo_tipo = '".$vinculo."'", "nome");
			$vinculo_legenda = $dados_vinculo[0]['nome'];
		}else{
			$vinculo_legenda = ucfirst($vinculo);
		}
	}
	if($dados_exibir){
		if($dados_exibir == 'dados_pessoais'){
			$dados_exibir_legenda = 'Dados Pessoais';
		}else if($dados_exibir == 'telefone'){
			$dados_exibir_legenda = 'Telefone e E-mail';
		}else if($dados_exibir == 'endereco'){
			$dados_exibir_legenda = 'Endereço';
		}else{
			$dados_exibir_legenda = ucfirst($dados_exibir);
		}
	}
	if($data_a_partir && $data_atualizacao){
		$data_legenda = ", <strong>Atualização entre: </strong>".$data_a_partir." e ".$data_atualizacao;
	}else if($data_a_partir){
		$data_legenda = ", <strong>Atualização a partir de: </strong>".$data_a_partir;
	}else if($data_atualizacao){
		$data_legenda = ", <strong>Atualização até: </strong>".$data_atualizacao;
	}else{
		$data_legenda = '';
	}

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Pessoas</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Atributo - </strong>".$atributo_legenda.", <strong>Pessoa - </strong>".$pessoa_legenda.", <strong> Vínculo - </strong>".$vinculo_legenda.", <strong>Dados a Exibir - </strong>".$dados_exibir_legenda."".$data_legenda;
	echo "</legend>";

	$filtro_pessoa = '';

	if($id_pessoa){
		$filtro_pessoa .= " AND a.id_pessoa = '$id_pessoa'";
	}
	if($data_a_partir){
		$filtro_pessoa .= " AND a.data_atualizacao >= '".converteData($data_a_partir)." 00:00:00'";
	}
	if($data_atualizacao){
		$filtro_pessoa .= " AND a.data_atualizacao <= '".converteData($data_atualizacao)." 23:59:59'";
	}

	if($atributo == 'nenhum'){
		$filtro_pessoa .= " AND a.candidato = 0 AND a.cliente = 0 AND a.fornecedor = 0 AND a.funcionario = 0 AND a.prospeccao = 0";
	}else if($atributo == 'candidato'){
		$filtro_pessoa .= " AND a.candidato = 1";
	}else if($atributo == 'cliente'){
		$filtro_pessoa .= " AND a.cliente = 1";
	}else if($atributo == 'fornecedor'){
		$filtro_pessoa .= " AND a.fornecedor = 1";
	}else if($atributo == 'funcionario'){
		$filtro_pessoa .= " AND a.funcionario = 1";
	}else if($atributo == 'prospeccao'){
		$filtro_pessoa .= " AND a.prospeccao = 1";
	}

	$inner_join_vinculo = '';
	$filtro_vinculo_pessoa = '';
	if($vinculo != 'nenhum' && $vinculo != 'todos'){
		$inner_join_vinculo = "INNER JOIN tb_vinculo_pessoa d ON a.id_pessoa = d.id_pessoa_pai INNER JOIN tb_vinculo_tipo_pessoa e ON d.id_vinculo_pessoa = e.id_vinculo_pessoa";
		$filtro_vinculo_pessoa = " AND e.id_vinculo_tipo = '$vinculo'";
		$group_vinculo = "GROUP BY a.id_pessoa";
	}
	if($escolhe_dados){
		$dados_apresentacao = " a.id_pessoa, a.nome, a.candidato, a.cliente, a.fornecedor, a.funcionario, a.prospeccao, a.tipo, a.sexo";
		foreach($escolhe_dados as $escolhido){

			if($escolhido == 'a'){
				$dados_apresentacao .= ", a.data_nascimento";
			}
			if($escolhido == 'b'){
				$dados_apresentacao .= ", b.nome AS 'nome_cidade', c.sigla, a.cep, a.logradouro, a.numero, a.bairro, a.complemento";
			}
			if($escolhido == 'c'){
				$dados_apresentacao .= ", a.razao_social";
			}
			if($escolhido == 'd'){
				$dados_apresentacao .= ", a.cpf_cnpj";
			}
			if($escolhido == 'e'){
				$dados_apresentacao .= ", a.inscricao_estadual";
			}
			if($escolhido == 'f'){
				$dados_apresentacao .= ", a.endereco_correspondencia";
			}
			if($escolhido == 'g'){
				$dados_apresentacao .= ", a.obs_interna";
			}
			if($escolhido == 'h'){
				$dados_apresentacao .= ", a.obs_externa";
			}
			if($escolhido == 'i'){
				$dados_apresentacao .= ", a.status";
			}
			if($escolhido == 'j'){
				$dados_apresentacao .= ", a.skype";
			}
			if($escolhido == 'l'){
				$dados_apresentacao .= ", a.facebook";
			}
			if($escolhido == 'm'){
				$dados_apresentacao .= ", a.site";
			}
			if($escolhido == 'n'){
				$dados_apresentacao .= ", a.fone1, a.fone2, a.fone3";
			}
			if($escolhido == 'o'){
				$dados_apresentacao .= ", a.email1, a.email2";
			}
			if($escolhido == 'p'){
				$dados_apresentacao .= ", a.data_atualizacao";
			}
		}

		$dados_pessoa = DBRead('', 'tb_pessoa a', "INNER JOIN tb_cidade b ON a.id_cidade = b.id_cidade INNER JOIN tb_estado c ON b.id_estado = c.id_estado $inner_join_vinculo WHERE 1 $filtro_pessoa $filtro_vinculo_pessoa AND a.status != 2 $group_vinculo ORDER BY a.nome ASC", "$dados_apresentacao");

	}else{

		$dados_pessoa = DBRead('', 'tb_pessoa a', "INNER JOIN tb_cidade b ON a.id_cidade = b.id_cidade INNER JOIN tb_estado c ON b.id_estado = c.id_estado $inner_join_vinculo WHERE 1 $filtro_pessoa $filtro_vinculo_pessoa AND a.status != 2 $group_vinculo ORDER BY a.nome ASC", "a.*, b.nome AS 'nome_cidade', c.sigla");
	}

	if(!$dados_pessoa){
		echo '<div class="alert alert-warning text-center">Nenhum resultado encontrado!</div>';
	}else{
		foreach($dados_pessoa as $conteudo_pessoa){

			if($conteudo_pessoa['data_nascimento'] == '0000-00-00'){
				$conteudo_pessoa['data_nascimento'] = '';
			}

			$atributo_pessoa = array();
			$atributo_pessoa_relatorio = '';
			if($conteudo_pessoa['candidato']){
				$atributo_pessoa[] = 'Candidato';
			}
			if($conteudo_pessoa['cliente']){
				$atributo_pessoa[] = 'Cliente';
			}
			if($conteudo_pessoa['fornecedor']){
				$atributo_pessoa[] = 'Fornecedor';
			}
			if($conteudo_pessoa['funcionario']){
				$atributo_pessoa[] = 'Funcionário';
			}
			if($conteudo_pessoa['prospeccao']){
				$atributo_pessoa[] = 'Prospecção';
			}
			foreach ($atributo_pessoa as $conteudo_atributo_pessoa) {
				$atributo_pessoa_relatorio .= $conteudo_atributo_pessoa.' | ';
			}
			$atributo_pessoa_relatorio = substr($atributo_pessoa_relatorio, 0, -3);

			echo '
				<div class="panel panel-primary">
		  			<div class="panel-heading clearfix">
			  			<div class="row">
			  			 	<h3 class="panel-title text-left col-xs-6"><strong>Nome:</strong> '.$conteudo_pessoa['nome'].'</h3>
			  				<h3 class="panel-title text-right col-xs-6"><strong>Atributo(s):</strong> '.$atributo_pessoa_relatorio.'</h3>
						</div>
		  			</div>
					<div class="panel-body painel-body-externo">
			';

				echo "<table class='table table-bordered'>";
					
					if($dados_exibir == 'todos' || $dados_exibir == 'dados_pessoais'){
						echo "<tr>";
							echo "<td>";
								echo "<strong>Razão Social:</strong>" .  $conteudo_pessoa['razao_social'];
							echo "</td>";
								if($conteudo_pessoa['tipo'] == "pf"){
									$nome_cpf_cnpj = 'CPF';
									echo "<td>";
									echo "<strong>Tipo: </strong>" .  "PF";
									echo "</td>";
								}else{
									$nome_cpf_cnpj = 'CNPJ';
									echo "<td>";
									echo "<strong>Tipo: </strong>" .  "PJ";
									echo "</td>";
								}
							echo "<td>";
								echo "<strong>".$nome_cpf_cnpj.": </strong>" .  formataCampo('cpf_cnpj',$conteudo_pessoa['cpf_cnpj']);
							echo "</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td>";
								echo "<strong>Inscrição Estadual: </strong>" .  $conteudo_pessoa['inscricao_estadual'];
							echo "</td>";
							echo "<td>";
								echo "<strong>Nascimento: </strong>" . converteData($conteudo_pessoa['data_nascimento']);
							echo "</td>";
							echo "<td>";
								if($conteudo_pessoa['sexo'] == 'm')
									echo "<strong>Sexo: </strong>" .  'M';
								else if($conteudo_pessoa['sexo'] == 'f')
									echo "<strong>Sexo: </strong>" .  'F';
								else{
									echo "<strong>Sexo: </strong>" .  'ND';
								}
							echo "</td>";
						echo "</tr>";
					}

					if($dados_exibir == 'todos' || $dados_exibir == 'endereco'){

						echo "<tr>";
							echo "<td>";
								echo "<strong>Logradouro: </strong>" .  $conteudo_pessoa['logradouro'];
							echo "</td>";
							echo "<td>";
								echo "<strong>Número: </strong>" . $conteudo_pessoa['numero'];
							echo "</td>";
							echo "<td>";
								echo "<strong>CEP: </strong>" .  formataCampo('cep', $conteudo_pessoa['cep']);
							echo "</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td>";
								echo "<strong>Bairro: </strong>" . $conteudo_pessoa['bairro'];
							echo "</td>";
							echo "<td>";
								echo "<strong>Complemento: </strong>" . $conteudo_pessoa['complemento'];
							echo "</td>";
							echo "<td>";
								echo "<strong>Cidade: </strong>" .  $conteudo_pessoa['nome_cidade']." - ". $conteudo_pessoa['sigla'];
							echo "</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td colspan='3'>";
								echo "<strong>Endereço de correspondência: </strong>" .  $conteudo_pessoa['endereco_correspondencia'];
							echo "</td>";
						echo "</tr>";
					}

					if($dados_exibir == 'todos' || $dados_exibir == 'contato'){
						echo "<tr>";
							echo "<td class = 'col-xs-4'>";
								echo "<strong>Fone(1): </strong>" . formataCampo('fone',$conteudo_pessoa['fone1']);
							echo "</td>";
							echo "<td class = 'col-xs-4'>";
								echo "<strong>Fone(2): </strong>" . formataCampo('fone',$conteudo_pessoa['fone2']);
							echo "</td>";
							echo "<td class = 'col-xs-4'>";
								echo "<strong>Fone(3): </strong>" .  formataCampo('fone',$conteudo_pessoa['fone3']);
							echo "</td>";
						echo "</tr>";		
						echo "<tr>";
							echo "<td>";
								echo "<strong>E-Mail(1): </strong>" .  $conteudo_pessoa['email1'];
							echo "</td>";
							echo "<td>";
								echo "<strong>E-Mail(2): </strong>" .  $conteudo_pessoa['email2'];
							echo "</td>";
							echo "<td>";
								echo "<strong>Site: </strong>" . $conteudo_pessoa['site'];
							echo "</td>";			
						echo "</tr>";
						echo "<tr>";
							echo "<td colspan='2'>";
								echo "<strong>Facebook: </strong>" . $conteudo_pessoa['facebook'];
							echo "</td>";
							echo "<td>";
								echo "<strong>Skype: </strong>" . $conteudo_pessoa['skype'];
							echo "</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td>";
								echo "<strong>Instagram: </strong>" . $conteudo_pessoa['instagram'];
							echo "</td>";
							echo "<td>";
								echo "<strong>Linkedin: </strong>" . $conteudo_pessoa['linkedin'];
							echo "</td>";
							echo "<td>";
								echo "<strong>Twitter: </strong>" . $conteudo_pessoa['twitter'];
							echo "</td>";
						echo "</tr>";
					}

					if($dados_exibir == 'telefone'){

						echo "<tr>";
							echo "<td>";
								echo "<strong>Fone(1): </strong>" .  formataCampo('fone',$conteudo_pessoa['fone1']);
							echo "</td>";
							echo "<td>";
								echo "<strong>Fone(2): </strong>" . formataCampo('fone', $conteudo_pessoa['fone2']);
							echo "</td>";
							echo "<td>";
								echo "<strong>Fone(3): </strong>" . formataCampo('fone', $conteudo_pessoa['fone3']);
							echo "</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td colspan='2'>";
								echo "<strong>E-mail(1): </strong>" . $conteudo_pessoa['email1'];
							echo "</td>";
							echo "<td>";
								echo "<strong>E-mail(2): </strong>" . $conteudo_pessoa['email2'];
							echo "</td>";
						echo "</tr>";
					}

				echo "</table>";

				//Tabela Filtro Personalizado
				if($dados_exibir == 'personalizado'){

					echo "<div class='row'>";

						//Dados Pessoais
							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-razao-social'>";
									echo "<strong>Razão Social:</strong>" . $conteudo_pessoa['razao_social'];
								echo "</div>";
							echo "</div>";

							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-cpf'>";
									echo "<strong>".$nome_cpf_cnpj.": </strong>" .  formataCampo('cpf_cnpj',$conteudo_pessoa['cpf_cnpj']);
								echo "</div>";
							echo "</div>";

							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-inscricao'>";
									echo "<strong>Inscrição Estadual: </strong>" .  $conteudo_pessoa['inscricao_estadual'];
								echo "</div>";
							echo "</div>";

							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-nascimento'>";
									echo "<strong>Nascimento: </strong>" . converteData($conteudo_pessoa['data_nascimento']);
								echo "</div>";
							echo "</div>";

							echo "<div class='container-relatorio-personalizado'>";
								if($conteudo_pessoa['sexo'] == 'm'){
									echo "<div class='col-xs-4 bloco-sexo'>";
										echo "<strong>Sexo: </strong>" .  'M';
									echo "</div>";
								}
								else if($conteudo_pessoa['sexo'] == 'f'){
									echo "<div class='col-xs-4 bloco-sexo'>";
										echo "<strong>Sexo: </strong>" .  'F';
									echo "</div>";
								}
								else{
									echo "<div class='col-xs-4 bloco-sexo'>";
										echo "<strong>Sexo: </strong>" .  'ND';
									echo "</div>";
								}
							echo "</div>";
						
						//Dados endereco
							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-logradouro'>";
									echo "<strong>Logradouro: </strong>" .  $conteudo_pessoa['logradouro'];
								echo "</div>";
							echo "</div>";

							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-numero'>";
									echo "<strong>Número: </strong>" . $conteudo_pessoa['numero'];
								echo "</div>";
							echo "</div>";

							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-cep'>";
									echo "<strong>CEP: </strong>" .  formataCampo('cep', $conteudo_pessoa['cep']);
								echo "</div>";
							echo "</div>";

							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-bairro'>";
									echo "<strong>Bairro: </strong>" . $conteudo_pessoa['bairro'];
								echo "</div>";
							echo "</div>";

							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-complemento'>";
									echo "<strong>Complemento: </strong>" . $conteudo_pessoa['complemento'];
								echo "</div>";
							echo "</div>";

							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-cidade'>";
									echo "<strong>Cidade: </strong>" .  $conteudo_pessoa['nome_cidade']." - ". $conteudo_pessoa['sigla'];
								echo "</div>";
							echo "</div>";

							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-endereco_correspondencia'>";
									echo "<strong>Endereço de correspondência: </strong>" . $conteudo_pessoa['endereco_correspondencia'];
								echo "</div>";
							echo "</div>";
						
						//Dados de contato
							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-telefone'>";
									echo "<strong>Fone(1): </strong>" . formataCampo('fone', $conteudo_pessoa['fone1']);
								echo "</div>";
							echo "</div>";

							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-telefone'>";
									echo "<strong>Fone(2): </strong>" . formataCampo('fone', $conteudo_pessoa['fone2']);
								echo "</div>";
							echo "</div>";

							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-telefone'>";
									echo "<strong>Fone(3): </strong>" . formataCampo('fone', $conteudo_pessoa['fone3']);
								echo "</div>";
							echo "</div>";

							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-email'>";
									echo "<strong>E-Mail(1): </strong>" . $conteudo_pessoa['email1'];
								echo "</div>";
							echo "</div>";

							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-email'>";
									echo "<strong>E-Mail(2): </strong>" . $conteudo_pessoa['email2'];
								echo "</div>";
							echo "</div>";

							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-site'>";
									echo "<strong>Site: </strong>" . $conteudo_pessoa['site'];
								echo "</div>";
							echo "</div>";

							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-facebook'>";
									echo "<strong>Facebook: </strong>" . $conteudo_pessoa['facebook'];
								echo "</div>";
							echo "</div>";

							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-skype'>";
									echo "<strong>Skype: </strong>" . $conteudo_pessoa['skype'];
								echo "</div>";
							echo "</div>";

							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-skype'>";
									echo "<strong>Instagram: </strong>" . $conteudo_pessoa['instagram'];
								echo "</div>";
							echo "</div>";

							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-skype'>";
									echo "<strong>Linkedin: </strong>" . $conteudo_pessoa['linkedin'];
								echo "</div>";
							echo "</div>";

							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-skype'>";
									echo "<strong>Twitter: </strong>" . $conteudo_pessoa['twitter'];
								echo "</div>";
							echo "</div>";

							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-obs_interna'>";
									echo "<strong>Observação interna: </strong>" . $conteudo_pessoa['obs_interna'];
								echo "</div>";
							echo "</div>";

							echo "<div class='container-relatorio-personalizado'>";
								echo "<div class='col-xs-4 bloco-obs_externa'>";
									echo "<strong>Observação externa: </strong>" . $conteudo_pessoa['obs_externa'];
								echo "</div>";
							echo "</div>";
	
					echo "</div>";
				}
				//Fim Tabela Filtro Personalizado

				// INICIO DOS DADOS DE PESSOAS VINCULADAS
				if($vinculo != 'nenhum'){

					if($vinculo == 'todos'){
						if($escolhe_dados){

							$dados_apresentacao = " a.id_vinculo_pessoa, b.nome, b.candidato, b.cliente, b.fornecedor, b.funcionario, b.prospeccao, b.tipo, b.sexo, c.nome AS 'nome_cidade', d.sigla";

							foreach($escolhe_dados as $escolhido){

								if($escolhido == 'a'){
									$dados_apresentacao .= ", b.data_nascimento";
								}
								if($escolhido == 'b'){
									$dados_apresentacao .= ", c.nome AS 'nome_cidade', d.sigla, b.cep, b.logradouro, b.numero, b.bairro, b.complemento";
								}
								if($escolhido == 'c'){
									$dados_apresentacao .= ", b.razao_social";
								}
								if($escolhido == 'd'){
									$dados_apresentacao .= ", b.cpf_cnpj";
								}
								if($escolhido == 'e'){
									$dados_apresentacao .= ", b.inscricao_estadual";
								}
								if($escolhido == 'f'){
									$dados_apresentacao .= ", b.endereco_correspondencia";
								}
								if($escolhido == 'g'){
									$dados_apresentacao .= ", b.obs_interna";
								}
								if($escolhido == 'h'){
									$dados_apresentacao .= ", b.obs_externa";
								}
								if($escolhido == 'i'){
									$dados_apresentacao .= ", b.status";
								}
								if($escolhido == 'j'){
									$dados_apresentacao .= ", b.skype";
								}
								if($escolhido == 'l'){
									$dados_apresentacao .= ", b.facebook";
								}
								if($escolhido == 'm'){
									$dados_apresentacao .= ", b.site";
								}
								if($escolhido == 'n'){
									$dados_apresentacao .= ", b.fone1, b.fone2, b.fone3";
								}
								if($escolhido == 'o'){
									$dados_apresentacao .= ", b.email1, b.email2";
								}
								if($escolhido == 'p'){
									$dados_apresentacao .= ", b.data_atualizacao";
								}
							}

							$dados_pessoa_vinculo = DBRead('', 'tb_vinculo_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa_filho = b.id_pessoa INNER JOIN tb_cidade c ON b.id_cidade = c.id_cidade INNER JOIN tb_estado d ON c.id_estado = d.id_estado WHERE a.id_pessoa_pai = '".$conteudo_pessoa['id_pessoa']."' AND b.status != 2 ORDER BY b.nome ASC", "$dados_apresentacao");

						}else{

							$dados_pessoa_vinculo = DBRead('', 'tb_vinculo_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa_filho = b.id_pessoa INNER JOIN tb_cidade c ON b.id_cidade = c.id_cidade INNER JOIN tb_estado d ON c.id_estado = d.id_estado WHERE a.id_pessoa_pai = '".$conteudo_pessoa['id_pessoa']."' AND b.status != 2  ORDER BY b.nome ASC","a.id_vinculo_pessoa, b.*, c.nome AS 'nome_cidade', d.sigla");

						}					

					}else{

						if($escolhe_dados){

							$dados_apresentacao = " b.id_vinculo_pessoa, c.nome, c.candidato, c.cliente, c.fornecedor, c.funcionario, c.prospeccao, c.tipo, c.sexo, d.nome AS 'nome_cidade', e.sigla";

							foreach($escolhe_dados as $escolhido){

								if($escolhido == 'a'){
									$dados_apresentacao .= ", c.data_nascimento";
								}
								if($escolhido == 'b'){
									$dados_apresentacao .= ", d.nome AS 'nome_cidade', e.sigla, c.cep, c.logradouro, c.numero, c.bairro, c.complemento";
								}
								if($escolhido == 'c'){
									$dados_apresentacao .= ", c.razao_social";
								}
								if($escolhido == 'd'){
									$dados_apresentacao .= ", c.cpf_cnpj";
								}
								if($escolhido == 'e'){
									$dados_apresentacao .= ", c.inscricao_estadual";
								}
								if($escolhido == 'f'){
									$dados_apresentacao .= ", c.endereco_correspondencia";
								}
								if($escolhido == 'g'){
									$dados_apresentacao .= ", c.obs_interna";
								}
								if($escolhido == 'h'){
									$dados_apresentacao .= ", c.obs_externa";
								}
								if($escolhido == 'i'){
									$dados_apresentacao .= ", c.status";
								}
								if($escolhido == 'j'){
									$dados_apresentacao .= ", c.skype";
								}
								if($escolhido == 'l'){
									$dados_apresentacao .= ", c.facebook";
								}
								if($escolhido == 'm'){
									$dados_apresentacao .= ", c.site";
								}
								if($escolhido == 'n'){
									$dados_apresentacao .= ", c.fone1, c.fone2, c.fone3";
								}
								if($escolhido == 'o'){
									$dados_apresentacao .= ", c.email1, c.email2";
								}
								if($escolhido == 'p'){
									$dados_apresentacao .= ", c.data_atualizacao";
								}
							}

							$dados_pessoa_vinculo = DBRead('', 'tb_vinculo_tipo_pessoa a',"INNER JOIN tb_vinculo_pessoa b ON a.id_vinculo_pessoa = b.id_vinculo_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa_filho = c.id_pessoa INNER JOIN tb_cidade d ON c.id_cidade = d.id_cidade INNER JOIN tb_estado e ON d.id_estado = e.id_estado WHERE b.id_pessoa_pai = '".$conteudo_pessoa['id_pessoa']."' AND a.id_vinculo_tipo = '".$vinculo."' AND c.status != 2 ORDER BY c.nome ASC","$dados_apresentacao");

						}else{

							$dados_pessoa_vinculo = DBRead('', 'tb_vinculo_tipo_pessoa a',"INNER JOIN tb_vinculo_pessoa b ON a.id_vinculo_pessoa = b.id_vinculo_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa_filho = c.id_pessoa INNER JOIN tb_cidade d ON c.id_cidade = d.id_cidade INNER JOIN tb_estado e ON d.id_estado = e.id_estado WHERE b.id_pessoa_pai = '".$conteudo_pessoa['id_pessoa']."' AND a.id_vinculo_tipo = '".$vinculo."' AND c.status != 2  ORDER BY c.nome ASC","b.id_vinculo_pessoa, c.*, d.nome AS 'nome_cidade', e.sigla");

						}
					}

					if($dados_pessoa_vinculo){
						foreach($dados_pessoa_vinculo as $conteudo_pessoa_vinculo){
							if($conteudo_pessoa_vinculo['data_nascimento'] == '0000-00-00'){
								$conteudo_pessoa_vinculo['data_nascimento'] = '';
							}
							$dados_tipo_vinculo = DBRead('','tb_vinculo_tipo_pessoa a',"INNER JOIN tb_vinculo_tipo b ON a.id_vinculo_tipo = b.id_vinculo_tipo WHERE a.id_vinculo_pessoa = '".$conteudo_pessoa_vinculo['id_vinculo_pessoa']."'");
					        $tipo_vinculo = '';
					        if($dados_tipo_vinculo){
					            foreach($dados_tipo_vinculo as $conteudo_tipo_vinculo){
					                $tipo_vinculo .= $conteudo_tipo_vinculo['nome']." | ";
					            }
					            $tipo_vinculo = substr($tipo_vinculo, 0, -3);
					        }

							echo '
								<div class="panel panel-default painel-vinculos">
						  			<div class="panel-heading clearfix">
						  			 <div class="row">
						  			 	<h3 class="panel-title text-left col-xs-6"><strong>Nome:</strong> '.$conteudo_pessoa_vinculo['nome'].'</h3>
						  				<h3 class="panel-title text-right col-xs-6"><strong>Vínculo(s):</strong> '.$tipo_vinculo.'</h3>
									</div>
						  			</div>
									<div class="panel-body painel-body-vinculos">
							';

								echo "<table class='table table-bordered'>";
									
									if($dados_exibir == 'todos' || $dados_exibir == 'dados_pessoais'){
										echo "<tr>";
											echo "<td>";
												echo "<strong>Razão Social:</strong>" .  $conteudo_pessoa_vinculo['razao_social'];
											echo "</td>";
												if($conteudo_pessoa_vinculo['tipo'] == "pf"){
													$nome_cpf_cnpj = 'CPF';
													echo "<td>";
													echo "<strong>Tipo: </strong>" .  "PF";
													echo "</td>";
												}else{
													$nome_cpf_cnpj = 'CNPJ';
													echo "<td>";
													echo "<strong>Tipo: </strong>" .  "PJ";
													echo "</td>";
												}
											echo "<td>";
												echo "<strong>".$nome_cpf_cnpj.": </strong>" .  formataCampo('cpf_cnpj', $conteudo_pessoa_vinculo['cpf_cnpj']);
											echo "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td>";
												echo "<strong>Inscrição Estadual: </strong>" .  $conteudo_pessoa_vinculo['inscricao_estadual'];
											echo "</td>";
											echo "<td>";
												echo "<strong>Nascimento: </strong>" . converteData($conteudo_pessoa_vinculo['data_nascimento']);
											echo "</td>";
											echo "<td>";
												if($conteudo_pessoa_vinculo['sexo'] == 'm')
													echo "<strong>Sexo: </strong>" .  'M';
												else if($conteudo_pessoa_vinculo['sexo'] == 'f')
													echo "<strong>Sexo: </strong>" .  'F';
												else{
													echo "<strong>Sexo: </strong>" .  'ND';
												}
											echo "</td>";
											
										echo "</tr>";
									}

									if($dados_exibir == 'todos' || $dados_exibir == 'endereco'){

										echo "<tr>";
											echo "<td>";
												echo "<strong>Logradouro: </strong>" .  $conteudo_pessoa_vinculo['logradouro'];
											echo "</td>";
											echo "<td>";
												echo "<strong>Número: </strong>" . $conteudo_pessoa_vinculo['numero'];
											echo "</td>";
											echo "<td>";
												echo "<strong>CEP: </strong>" .  formataCampo('cep',$conteudo_pessoa_vinculo['cep']);
											echo "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td>";
												echo "<strong>Bairro: </strong>" . $conteudo_pessoa_vinculo['bairro'];
											echo "</td>";
											echo "<td>";
												echo "<strong>Complemento: </strong>" . $conteudo_pessoa_vinculo['complemento'];
											echo "</td>";
											echo "<td>";
												echo "<strong>Cidade: </strong>" .  $conteudo_pessoa_vinculo['nome_cidade']." - ". $conteudo_pessoa_vinculo['sigla'];
											echo "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td colspan='3'>";
												echo "<strong>Endereço de correspondência: </strong>" .  $conteudo_pessoa_vinculo['endereco_correspondencia'];
											echo "</td>";
										echo "</tr>";
									}

									if($dados_exibir == 'todos' || $dados_exibir == 'contato'){
										echo "<tr>";
											echo "<td class = 'col-xs-4'>";
												echo "<strong>Fone(1): </strong>" . formataCampo('fone',$conteudo_pessoa_vinculo['fone1']);
											echo "</td>";
											echo "<td class = 'col-xs-4'>";
												echo "<strong>Fone(2): </strong>" . formataCampo('fone',$conteudo_pessoa_vinculo['fone2']);
											echo "</td>";
											echo "<td class = 'col-xs-4'>";
												echo "<strong>Fone(3): </strong>" .  formataCampo('fone',$conteudo_pessoa_vinculo['fone3']);
											echo "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td>";
												echo "<strong>E-Mail(1): </strong>" .  $conteudo_pessoa_vinculo['email1'];
											echo "</td>";
											echo "<td>";
												echo "<strong>E-Mail(2): </strong>" .  $conteudo_pessoa_vinculo['email2'];
											echo "</td>";
											echo "<td>";
												echo "<strong>Site: </strong>" . $conteudo_pessoa_vinculo['site'];
											echo "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td colspan='2'>";
												echo "<strong>Facebook: </strong>" . $conteudo_pessoa_vinculo['facebook'];
											echo "</td>";
											echo "<td>";
												echo "<strong>Skype: </strong>" . $conteudo_pessoa_vinculo['skype'];
											echo "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td>";
												echo "<strong>Instagram: </strong>" . $conteudo_pessoa_vinculo['instagram'];
											echo "</td>";
											echo "<td>";
												echo "<strong>Linkedin: </strong>" . $conteudo_pessoa_vinculo['linkedin'];
											echo "</td>";
											echo "<td>";
												echo "<strong>Twitter: </strong>" . $conteudo_pessoa_vinculo['twitter'];
											echo "</td>";
										echo "</tr>";
									}

									if($dados_exibir == 'telefone'){
										echo "<tr>";
											echo "<td>";
												echo "<strong>Fone(1): </strong>" .  formataCampo('fone', $conteudo_pessoa_vinculo['fone1']);
											echo "</td>";
											echo "<td>";
												echo "<strong>Fone(2): </strong>" . formataCampo('fone', $conteudo_pessoa_vinculo['fone2']);
											echo "</td>";
											echo "<td>";
												echo "<strong>Fone(3): </strong>" . formataCampo('fone', $conteudo_pessoa_vinculo['fone3']);
											echo "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td colspan='2'>";
												echo "<strong>E-mail(1): </strong>" . $conteudo_pessoa_vinculo['email1'];
											echo "</td>";
											echo "<td>";
												echo "<strong>E-mail(2): </strong>" . $conteudo_pessoa_vinculo['email2'];
											echo "</td>";
										echo "</tr>";
									}
								echo "</table>";


								//Tabela Filtro Personalizado
						if($dados_exibir == 'personalizado'){

							echo "<div class='row'>";

								//Dados Pessoais
									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-razao-social'>";
											echo "<strong>Razão Social:</strong><span class='conteudo'>" . $conteudo_pessoa_vinculo['razao_social'] . "</span>";
										echo "</div>";
									echo "</div>";

								?>

								<?php

									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-cpf'>";
											echo "<strong>".$nome_cpf_cnpj.": </strong>" . formataCampo('cpf_cnpj',$conteudo_pessoa_vinculo['cpf_cnpj']);
										echo "</div>";
									echo "</div>";

									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-inscricao'>";
											echo "<strong>Inscrição Estadual: </strong>" .  $conteudo_pessoa_vinculo['inscricao_estadual'];
										echo "</div>";
									echo "</div>";

									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-nascimento'>";
											echo "<strong>Nascimento: </strong>" . converteData($conteudo_pessoa_vinculo['data_nascimento']);
										echo "</div>";
									echo "</div>";

									echo "<div class='container-relatorio-personalizado'>";
										if($conteudo_pessoa_vinculo['sexo'] == 'm'){
											echo "<div class='col-xs-4 bloco-sexo'>";
												echo "<strong>Sexo: </strong>" .  'M';
											echo "</div>";
										}
										else if($conteudo_pessoa_vinculo['sexo'] == 'f'){
											echo "<div class='col-xs-4 bloco-sexo'>";
												echo "<strong>Sexo: </strong>" .  'F';
											echo "</div>";
										}
										else{
											echo "<div class='col-xs-4 bloco-sexo'>";
												echo "<strong>Sexo: </strong>" .  'ND';
											echo "</div>";
										}
									echo "</div>";
								//

								//Dados endereco
									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-logradouro'>";
											echo "<strong>Logradouro: </strong>" .  $conteudo_pessoa_vinculo['logradouro'];
										echo "</div>";
									echo "</div>";

									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-numero'>";
											echo "<strong>Número: </strong>" . $conteudo_pessoa_vinculo['numero'];
										echo "</div>";
									echo "</div>";

									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-cep'>";
											echo "<strong>CEP: </strong>" .  formataCampo('cep', $conteudo_pessoa_vinculo['cep']);
										echo "</div>";
									echo "</div>";

									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-bairro'>";
											echo "<strong>Bairro: </strong>" . $conteudo_pessoa_vinculo['bairro'];
										echo "</div>";
									echo "</div>";

									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-complemento'>";
											echo "<strong>Complemento: </strong>" . $conteudo_pessoa_vinculo['complemento'];
										echo "</div>";
									echo "</div>";

									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-cidade'>";
											echo "<strong>Cidade: </strong>" .  $conteudo_pessoa_vinculo['nome_cidade']." - ". $conteudo_pessoa_vinculo['sigla'];
										echo "</div>";
									echo "</div>";

									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-endereco_correspondencia'>";
											echo "<strong>Endereço de correspondência: </strong>" . $conteudo_pessoa_vinculo['endereco_correspondencia'];
										echo "</div>";
									echo "</div>";
							
									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-telefone'>";
											echo "<strong>Fone(1): </strong>" . formataCampo('fone', $conteudo_pessoa_vinculo['fone1']);
										echo "</div>";
									echo "</div>";

									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-telefone'>";
											echo "<strong>Fone(2): </strong>" . formataCampo('fone', $conteudo_pessoa_vinculo['fone2']);
										echo "</div>";
									echo "</div>";

									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-telefone'>";
											echo "<strong>Fone(3): </strong>" . formataCampo('fone', $conteudo_pessoa_vinculo['fone3']);
										echo "</div>";
									echo "</div>";

									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-email'>";
											echo "<strong>E-Mail(1): </strong>" . $conteudo_pessoa_vinculo['email1'];
										echo "</div>";
									echo "</div>";

									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-email'>";
											echo "<strong>E-Mail(2): </strong>" . $conteudo_pessoa_vinculo['email2'];
										echo "</div>";
									echo "</div>";

									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-site'>";
											echo "<strong>Site: </strong>" . $conteudo_pessoa_vinculo['site'];
										echo "</div>";
									echo "</div>";

									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-facebook'>";
											echo "<strong>Facebook: </strong>" . $conteudo_pessoa_vinculo['facebook'];
										echo "</div>";
									echo "</div>";

									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-skype'>";
											echo "<strong>Skype: </strong>" . $conteudo_pessoa_vinculo['skype'];
										echo "</div>";
									echo "</div>";

									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-skype'>";
											echo "<strong>Instagram: </strong>" . $conteudo_pessoa_vinculo['instagram'];
										echo "</div>";
									echo "</div>";

									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-skype'>";
											echo "<strong>Linkedin: </strong>" . $conteudo_pessoa_vinculo['linkedin'];
										echo "</div>";
									echo "</div>";

									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-skype'>";
											echo "<strong>Twitter: </strong>" . $conteudo_pessoa_vinculo['twitter'];
										echo "</div>";
									echo "</div>";

									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-obs_interna'>";
											echo "<strong>Observação interna: </strong>" . $conteudo_pessoa_vinculo['obs_interna'];
										echo "</div>";
									echo "</div>";

									echo "<div class='container-relatorio-personalizado'>";
										echo "<div class='col-xs-4 bloco-obs_externa'>";
											echo "<strong>Observação externa: </strong>" . $conteudo_pessoa_vinculo['obs_externa'];
										echo "</div>";
									echo "</div>";

					echo "</div>";
				}
				//Fim Tabela Filtro Personalizado

							echo '
								  </div>
								</div>
							';
						}
					}
				}
				// FIM DOS DADOS DE PESSOAS VINCULADAS
			echo '
				  </div>
				</div>
			';
		}
	}
}

function relatorio_aniversario($id_pessoa, $var_mes){

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

	$data_hora = converteDataHora(getDataHora());

	$filtro_pessoa = '';

	if($id_pessoa){
		$filtro_pessoa .= " AND a.id_pessoa = '$id_pessoa'";
		$filtro_funcionario = '';
		if($id_pessoa == 2){
			$filtro_funcionario = " AND f.funcionario = 1";
		}
	}

	$inner_join_vinculo = "INNER JOIN tb_vinculo_pessoa d ON a.id_pessoa = d.id_pessoa_pai INNER JOIN tb_vinculo_tipo_pessoa e ON d.id_vinculo_pessoa = e.id_vinculo_pessoa";
	$filtro_vinculo_pessoa = '';
	
	$dados_pessoa = DBRead('', 'tb_pessoa a', "INNER JOIN tb_cidade b ON a.id_cidade = b.id_cidade INNER JOIN tb_estado c ON b.id_estado = c.id_estado $inner_join_vinculo INNER JOIN tb_pessoa f ON d.id_pessoa_filho = f.id_pessoa WHERE 1 $filtro_funcionario AND a.id_pessoa = '".$id_pessoa."' AND a.status != 2 AND f.data_nascimento like '%-".$var_mes."-%' AND f.status != 2 ORDER BY a.nome ASC", "a.*, b.nome AS 'nome_cidade', c.sigla, f.nome AS nome_filho, f.razao_social AS razao_social_filho, f.data_nascimento AS data_nascimento_filho, f.fone1 AS fone1_filho, d.id_vinculo_pessoa");
	
	$data_hora = converteDataHora(getDataHora());

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	$dados_nome = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$id_pessoa."'", "nome");

	
	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Pessoas</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Pessoa - </strong>".$dados_nome[0]['nome'].", <strong>Referente ao mês de - </strong>".$meses[$var_mes]."</span></legend>";

	if(!$dados_pessoa){
       
        echo "<div class='col-md-12'>";
            echo "<table class='table table-bordered'>";
                echo "<tbody>";
                    echo "<tr>";
                        echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
                    echo "</tr>";
                echo "</tbody>";
            echo "</table>";
        echo "</div>";

   	}else{
			

		echo '<table class="table table-hover dataTable tableAniversario">
		      <thead>
		        <tr>
		            <th class="text-left col-md-4">Nome</th>
		            <th class="text-left col-md-2">Data de Nascimento</th>
		            <th class="text-left col-md-4">Vínculo (s)</th>
		            <th class="text-left col-md-2">Telefone</th>
		        </tr>
		      </thead>
		      <tbody>';   

			foreach($dados_pessoa as $conteudo_pessoa){

				$dados_tipo_vinculo = DBRead('','tb_vinculo_tipo_pessoa a',"INNER JOIN tb_vinculo_tipo b ON a.id_vinculo_tipo = b.id_vinculo_tipo WHERE a.id_vinculo_pessoa = '".$conteudo_pessoa['id_vinculo_pessoa']."'");	 

	    		echo "<tr>";
	    			if($conteudo_pessoa['id_pessoa'] == 2){
				    	echo "<td class='text-left tdAniversario'>".$conteudo_pessoa['razao_social_filho']."</td>";
	    			}else{
				    	echo "<td class='text-left tdAniversario'>".$conteudo_pessoa['nome_filho']."</td>";
	    			}
				    echo "<td class='text-left tdAniversario'>".converteData($conteudo_pessoa['data_nascimento_filho'])."</td>";
				    echo "<td class='text-left tdAniversario'>".$dados_tipo_vinculo[0]['nome']."</td>";
				    echo "<td class='text-left tdAniversario'><span class='phone'>".$conteudo_pessoa['fone1_filho']."</span></td>";
			    echo "</tr>";
	    	}

	    	echo "</tbody>";
	    
		echo '</table>';
		
		echo "<script>
				$(document).ready(function(){
				    $('.dataTable').DataTable({
					    \"language\": {
				            \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
				        },			        
				        \"searching\": false,
				        \"paging\":   false,
				        \"info\":     false
			    	});
				});
			</script>			
			";

	}

}

$dados_exibir = (!empty($_POST['dados_exibir'])) ? $_POST['dados_exibir'] : '';
$atributo = (!empty($_POST['atributo'])) ? $_POST['atributo'] : '';
$id_pessoa = (!empty($_POST['id_pessoa'])) ? $_POST['id_pessoa'] : '';
$vinculo = (!empty($_POST['vinculo'])) ? $_POST['vinculo'] : '';
$data_atualizacao = (!empty($_POST['data_atualizacao'])) ? $_POST['data_atualizacao'] : '';
$data_a_partir = (!empty($_POST['data_a_partir'])) ? $_POST['data_a_partir'] : '';
$escolhe_dados = (!empty($_POST['escolhe_dados'])) ? $_POST['escolhe_dados'] : '';
$gerar = (!empty($_POST['gerar'])) ? 1 : 0;
$tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] : 'geral';

$hoje = explode("-", getDataHora());
$hoje = $hoje[1];

$var_mes = (!empty($_POST['var_mes'])) ? $_POST['var_mes'] : $hoje;

$id_usuario_sessao = $_SESSION['id_usuario'];
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario_sessao'");
$perfil_sistema = $dados[0]['id_perfil_sistema'];

if($gerar){
	$collapse = '';
	$collapse_icon = 'plus';
	$dados = DBRead('','tb_pessoa',"WHERE id_pessoa = '$id_pessoa'");
	if($dados){
		$nome_pessoa = $dados[0]['nome'];
		$pessoa_input = $id_pessoa . ' - ' . $nome_pessoa;
	}else{
		$nome_pessoa = '';
		$pessoa_input ='';
	}
}else{
	$collapse = 'in';
	$collapse_icon = 'minus';
	$nome_pessoa = '';
	$pessoa_input = '';
}

if($tipo == 'geral'){
	$display_atributo = '';
	$display_vinculo = '';
	$display_dados = '';
	$display_personalizado = '';
	$display_de = '';
	$display_ate = '';
	$display_mes = 'style="display:none;"';
}else if($tipo == 'aniversario'){
	$display_atributo = 'style="display:none;"';
	$display_vinculo = 'style="display:none;"';
	$display_dados = 'style="display:none;"';
	$display_personalizado = 'style="display:none;"';
	$display_de = 'style="display:none;"';
	$display_ate = 'style="display:none;"';
	$display_mes = '';
}
if($perfil_sistema == 3){
	$display_dados = 'style="display:none;"';
	$display_tipo = 'style="display:none;"';
	$display_atributo = 'style="display:none;"';
	$display_dados = 'style="display:none;"';
	$display_personalizado = 'style="display:none;"';
	$display_de = 'style="display:none;"';
	$display_ate = 'style="display:none;"';
	$display_mes = 'style="display:none;"';
	$display_pessoa = 'style="display:none;"';
	$nome_belluno = 'Belluno';
}
?>
<style>
    @media print {
        .noprint { display:none; }
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            padding-top: 0;
        }
    }
</style>
<script src="https://code.highcharts.com/7.2.1/highcharts.js"></script>
<script src="https://code.highcharts.com/7.2.1/modules/exporting.js"></script>
<script src="https://code.highcharts.com/7.2.1/modules/export-data.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.css"/> 
 <script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.js"></script>
 <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/time.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/chinese-string.js"></script>
<div class="container-fluid">
	<form method="post" action="">
	    <div class="row">
	        <div class="col-md-4 col-md-offset-4">
	            <div class="panel panel-default noprint">
	                <div class="panel-heading clearfix">
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório de pessoas: <?php echo $nome_belluno ?></h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">
	                		<div class="form-group" <?=$display_tipo?>>
								<label>Tipo de Relatório:</label>
								<select class="form-control input-sm" id="tipo" name="tipo" >
									<?php
	                                echo "<option value='geral'";
	                                	if ($tipo == 'geral') {
	                                		echo 'selected';
	                                	}
	                                		echo "> Geral</option>";
	                                echo "<option value='aniversario'";
	                                	if ($tipo == 'aniversario') {
	                                		echo 'selected';
	                                	}
	                                		echo "> Aniversário</option>";
	                                
	                                ?>
								</select>
							</div>
							<div class="form-group" id="atributos_group" <?=$display_atributo?>>
								<label>Atributo:</label>
								<select class="form-control input-sm" id="atributo" name="atributo">
									<?php
	                                echo "<option value='qualquer'".$atributo == "qualquer" ? "selected" : "".">Qualquer</option>";
	                                echo "<option value='nenhum'".$atributo == "nenhum" ? "selected" : "".">Nenhum</option>";
	                                echo "<option value='' disabled>------------</option>";
	                                echo "<option value='candidato'".$atributo == "candidato" ? "selected" : "".">Candidato</option>";
	                                echo "<option value='cliente'".$atributo == "cliente" ? "selected" : "".">Cliente</option>";
	                                echo "<option value='fornecedor'".$atributo == "fornecedor" ? "selected" : "".">Fornecedor</option>";
	                                echo "<option value='funcionario'".$atributo == "funcionario" ? "selected" : "".">Funcionário</option>";
	                                echo "<option value='prospeccao'".$atributo == "prospeccao" ? "selected" : "".">Prospecção</option>";
	                                ?>
								</select>
							</div>
							<div class="form-group" id="pessoa_group" name="pessoa_group" <?=$display_pessoa?>>
	                            <label>Pessoa:</label>
	                            <div class="input-group">
	                                <input class="form-control input-sm" id="busca_pessoa" type="text" name="busca_pessoa"  value="<?=$pessoa_input;?>" placeholder="Informe o nome ou CPF/CNPJ..." autocomplete="off" readonly>
	                                <div class="input-group-btn">
	                                    <button class="btn btn-info btn-sm" id="habilita_busca_pessoa" name="habilita_busca_pessoa" type="button" title="Clique para selecionar a pessoa" style="height: 30px;"><i class="fa fa-search"></i></button>
	                                </div>
	                            </div>
	                            <input type="hidden" name="id_pessoa" id="id_pessoa" value="<?=$id_pessoa?>">
	                        </div>

							<div class="form-group" id="vinculo_group" <?=$display_vinculo?>>
								<label>Vínculo:</label>
								<select class="form-control input-sm" name="vinculo">
									<?php
									if($id_pessoa){
										$dados = DBRead('','tb_vinculo_tipo_pessoa a', "INNER JOIN tb_vinculo_pessoa b ON a.id_vinculo_pessoa = b.id_vinculo_pessoa INNER JOIN tb_vinculo_tipo c ON a.id_vinculo_tipo = c.id_vinculo_tipo WHERE b.id_pessoa_pai = '$id_pessoa' GROUP BY c.id_vinculo_tipo ORDER BY c.nome ASC", "c.*");
									}else{
										$dados = DBRead('', 'tb_vinculo_tipo', "ORDER BY nome ASC");
									}
									if($dados){
										echo '
											<option value="todos">Todos</option>
											<option value="nenhum">Nenhum</option>
											<option value="" disabled>------------</option>
										';
										foreach($dados as $conteudo){
											$idSelect = $conteudo['id_vinculo_tipo'];
											$vinculoSelect = $conteudo['nome'];
											$selected = $vinculo == $idSelect ? "selected" : "";
									        echo "<option value='$idSelect' ".$selected.">$vinculoSelect</option>";
										}
									}else{
										echo  '<option value="nenhum">Nenhuma pessoa vinculada</option>';
									}
									?>
								</select>
							</div>
							<div class="form-group" id="dados_group" <?=$display_dados?>>
								<label>Dados:</label>
								<select class="form-control input-sm" id="dados_exibir" name="dados_exibir">
									<?php
	                                echo "<option value='todos'".$dados_exibir == "todos" ? "selected" : "".">Todos</option>";
	                                echo "<option value='contato'".$dados_exibir == "contato" ? "selected" : "".">Contato</option>";
	                                echo "<option value='dados_pessoais'".$dados_exibir == "dados_pessoais" ? "selected" : "".">Dados Pessoais</option>";
	                                echo "<option value='endereco'".$dados_exibir == "endereco" ? "selected" : "".">Endereço</option>";
	                                echo "<option value='telefone'".$dados_exibir == "telefone" ? "selected" : "".">Fone e E-mail</option>";
	                                echo "<option id='personalizado' value='personalizado' ".$dados_exibir == "personalizado" ? "selected" : "".">Personalizado</option>";
	                                ?>
								</select>
							</div>

							<div class="form-group" id="bloco-filtro-personalizado" <?=$display_personalizado?>>
								<div class='table-responsive' style="max-height: 200px; overflow-y: auto;">
                                    <table class='table table-hover table_paginas' style='font-size: 14px;'>
                                        <thead>
                                            <tr>
                                                <th class="col-md-2">Habilitar</th>
                                                <th class="col-md-10">Dados</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        	<?php
                                        		$destino = 'cidade';
                                        		foreach($escolhe_dados as $key => $checado){
                                        			if($escolhe_dados[$key] == substr($checado, -1, 1)){
                                        				$checked = "checked_" . substr($checado, -1, 1);
                                        				$$checked = "checked";
                                        			}else{
                                        				$checked = "";
                                        			}
                                        		}
                                        	?>
			                            	<tr>
			                            		<td><input type="checkbox" id="escolhe_data_nascimento" value="a" class="escolhe_dados" <?= $checked_a ?> name="escolhe_dados[]" /></td>
			                            		<td>Data de nascimento</td>
			                            	</tr>
			                            	<tr>
			                            		<td><input type="checkbox" id="escolhe_cidade" <?= $checked_b ?> value="b" class="escolhe_dados" name="escolhe_dados[]" /></td>
			                            		<td>Endereço</td>
			                            	</tr>
			                            	<tr>
			                            		<td><input type="checkbox" id="escolhe_razao_social" <?= $checked_c ?> value="c" class="escolhe_dados" name="escolhe_dados[]" /></td>
			                            		<td>Razão social</td>
			                            	</tr>
			                            	<tr>
			                            		<td><input type="checkbox" id="escolhe_cpf" value="d" <?= $checked_d ?> class="escolhe_dados" name="escolhe_dados[]" /></td>
			                            		<td>CPF/CNPJ</td>
			                            	</tr>
			                            	<tr>
			                            		<td><input type="checkbox" id="escolhe_inscricao_estadual" <?= $checked_e ?> value="e" class="escolhe_dados" name="escolhe_dados[]" /></td>
			                            		<td>Inscrição estadual</td>
			                            	</tr>
			                            	<tr>
			                            		<td><input type="checkbox" id="escolhe_endereco_correspondencia" <?= $checked_f ?> value="f" class="escolhe_dados" name="escolhe_dados[]" /></td>
			                            		<td>Endereço para correspondência</td>
			                            	</tr>
			                            	<tr>
			                            		<td><input type="checkbox" id="escolhe_obs_interna" <?= $checked_g ?> value="g" class="escolhe_dados" name="escolhe_dados[]" /></td>
			                            		<td>Observação interna</td>
			                            	</tr>
			                            	<tr>
			                            		<td><input type="checkbox" id="escolhe_obs_externa" <?= $checked_h ?> value="h" class="escolhe_dados" name="escolhe_dados[]" /></td>
			                            		<td>Observação externa</td>
			                            	</tr>
			                            	<tr>
			                            		<td><input type="checkbox" id="escolhe_status" <?= $checked_i ?> value="i" class="escolhe_dados" name="escolhe_dados[]" /></td>
			                            		<td>Status</td>
			                            	</tr>
			                            	<tr>
			                            		<td><input type="checkbox" id="escolhe_skype" <?= $checked_j ?> value="j" class="escolhe_dados" name="escolhe_dados[]" /></td>
			                            		<td>Skype</td>
			                            	</tr>
			                            	<tr>
			                            		<td><input type="checkbox" id="escolhe_facebook" <?= $checked_l ?> value="l" class="escolhe_dados" name="escolhe_dados[]" /></td>
			                            		<td>Facebook</td>
			                            	</tr>
			                            	<tr>
			                            		<td><input type="checkbox" id="escolhe_site" value="m" <?= $checked_m ?> class="escolhe_dados" name="escolhe_dados[]" /></td>
			                            		<td>Site</td>
			                            	</tr>
			                            	<tr>
			                            		<td><input type="checkbox" id="escolhe_telefone" value="n" <?= $checked_n ?> class="escolhe_dados" name="escolhe_dados[]" /></td>
			                            		<td>Fone</td>
			                            	</tr>

			                            	<tr>
			                            		<td><input type="checkbox" id="escolhe_email" value="o" <?= $checked_o ?> class="escolhe_dados" name="escolhe_dados[]" /></td>
			                            		<td>E-mail</td>
			                            	</tr>
                                        </tbody>
                                    </table>
                                </div>
							</div>

							<div class="form-group" id="de_group" <?=$display_de?>>
								<label>Atualizado a partir de:</label>
								<input type="text" name="data_a_partir" class="form-control date calendar input-sm" value="<?=$data_a_partir?>">
							</div>
							<div class="form-group" id="ate_group" <?=$display_ate?>>
								<label>Atualizado até:</label>
								<input type="text" name="data_atualizacao" class="form-control date calendar input-sm" value="<?=$data_atualizacao?>">
							</div>
							<div class="form-group" id="mes_group" <?=$display_mes?>>
								<label>Mês:</label> 
								<select name="var_mes" id="var_mes" class="form-control">
										<?php

										$sel_dados_mes[$var_mes] = 'selected';  
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

										foreach ($meses as $nume => $mes) {
											$selected = $var_mes == $nume ? "selected" : "";
											echo "<option value='".sprintf('%02d', $nume)."' ".$selected.">".$mes."</option>";
										}
										?>													
								</select>
							</div>
		                </div>

	            	</div>
	                <div class="panel-footer">
                        <div class="row">
                            <div id="panel_buttons" class="col-md-12" style="text-align: center">
                                <button class="btn btn-primary" name="gerar" id="gerar" value="1" type="submit"><i class="fa fa-refresh"></i> Gerar</button>
                                <button class="btn btn-warning" name="imprimir" type="button" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
                            </div>
                        </div>
                    </div>
	            </div>
	        </div>
	    </div>
	</form>
	<div class="row">
		<div class="col-md-8 col-md-offset-2" style="padding: 0">
			<?php 
			if($gerar){

				if($perfil_sistema == 3){
					$tipo = 'geral';
					$dados_exibir = 'contato';
					relatorio_geral('contato', 'qualquer', '2', $vinculo, '', '', '');
				}else{

					if ($tipo == 'geral') {
				        relatorio_geral($dados_exibir, $atributo, $id_pessoa, $vinculo, $data_a_partir, $data_atualizacao, $escolhe_dados);
				    }else if ($tipo == 'aniversario') {
				        relatorio_aniversario($id_pessoa, $var_mes);
				    }
			    }
			} 
			?>
		</div>
	</div>
</div>
<script>

	function dadosRelatorioPersonalizado(radioButton, container){

		if($(radioButton + ":checked")){
			$(".conteudo").addClass("temConteudo");
		}else if($(container + ":empty")){
			$(".conteudo").addClass("naoTemConteudo");
		}
		if(!$(radioButton).attr('checked')){
			$(container).each(function(){
				$(this).hide();
			});
		}
	}
	dadosRelatorioPersonalizado("#escolhe_razao_social", ".bloco-razao-social");
	dadosRelatorioPersonalizado("#escolhe_cpf", ".bloco-cpf");
	dadosRelatorioPersonalizado("#escolhe_inscricao_estadual", ".bloco-inscricao");
	dadosRelatorioPersonalizado("#escolhe_data_nascimento", ".bloco-nascimento");
	dadosRelatorioPersonalizado("#escolhe_sexo", ".bloco-sexo");
	dadosRelatorioPersonalizado("#escolhe_cidade", ".bloco-logradouro");
	dadosRelatorioPersonalizado("#escolhe_cidade", ".bloco-numero");
	dadosRelatorioPersonalizado("#escolhe_cidade", ".bloco-cep");
	dadosRelatorioPersonalizado("#escolhe_cidade", ".bloco-bairro");
	dadosRelatorioPersonalizado("#escolhe_cidade", ".bloco-cidade");
	dadosRelatorioPersonalizado("#escolhe_cidade", ".bloco-complemento");
	dadosRelatorioPersonalizado("#escolhe_endereco_correspondencia", ".bloco-endereco_correspondencia");
	dadosRelatorioPersonalizado("#escolhe_telefone", ".bloco-telefone");
	dadosRelatorioPersonalizado("#escolhe_email", ".bloco-email");
	dadosRelatorioPersonalizado("#escolhe_site", ".bloco-site");
	dadosRelatorioPersonalizado("#escolhe_facebook", ".bloco-facebook");
	dadosRelatorioPersonalizado("#escolhe_skype", ".bloco-skype");
	dadosRelatorioPersonalizado("#escolhe_obs_interna", ".bloco-obs_interna");
	dadosRelatorioPersonalizado("#escolhe_obs_externa", ".bloco-obs_externa");

	$("#bloco-filtro-personalizado").hide();

	$("#dados_exibir").on('change', function(){
		if($(this).val() == "personalizado"){
			$("#bloco-filtro-personalizado").fadeIn();
		}else{
			$("#bloco-filtro-personalizado").fadeOut();
			$(".escolhe_dados").attr("checked", false);
		}
	});

	if($("#dados_exibir").val() == "personalizado"){
		$("#bloco-filtro-personalizado").fadeIn();
	}else{
		$("#bloco-filtro-personalizado").fadeOut();
		$(".escolhe_dados").attr("checked", false);
	}

	$("#tipo").on('change', function(){
		if($("#tipo").val() == "geral"){
			$("#atributos_group").fadeIn();
			$("#dados_group").fadeIn();
			$("#vinculo_group").fadeIn();
			$("#de_group").fadeIn();
			$("#ate_group").fadeIn();
			$("#mes_group").fadeOut();
		}else{
			$("#atributos_group").fadeOut();
			$("#dados_group").fadeOut();
			$("#vinculo_group").fadeOut();
			$("#de_group").fadeOut();
			$("#ate_group").fadeOut();
			$("#mes_group").fadeIn();
		}
	});

	

	// Atribui evento e função para limpeza dos campos
    $('#busca_pessoa').on('input', limpaCamposPessoa);

    // Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_pessoa").autocomplete({
            minLength: 2,
            source: function(request, response){
                $.ajax({
                    url: "/api/ajax?class=PessoaAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'autocomplete',
                        parametros: {
                            'nome' : $('#busca_pessoa').val(),
                            'atributo' : $('#atributo').val(),
                        },
						token: '<?= $request->token ?>'
                    },
                    success: function(data){
                        response(data);
                    }
                });
            },
            focus: function(event, ui){
                $("#busca_pessoa").val(ui.item.nome + " "+ ui.item.nome_contrato);
                carregarDadosPessoa(ui.item.id_pessoa);
                return false;
            },
            select: function(event, ui){
                $("#busca_pessoa").val(ui.item.nome + " "+ ui.item.nome_contrato);
                $('#busca_pessoa').attr("readonly", true);
                return false;
            }
        })
        .autocomplete("instance")._renderItem = function(ul, item){
            if(!item.razao_social){
                item.razao_social = '';
            }
            if(!item.cpf_cnpj){
                item.cpf_cnpj = '';
            }
			if(!item.nome_contrato){
                item.nome_contrato = '';
            }else{
                item.nome_contrato = ' ('+item.nome_contrato+') '; 
            }

        return $("<li>").append("<a><strong>"+item.id_pessoa+" - "+ item.nome + item.nome_contrato +" </strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
    };

    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosPessoa(id){
        var busca = $('#busca_pessoa').val();

        if(busca != "" && busca.length >= 2){
            $.ajax({
                url: "/api/ajax?class=PessoaAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta',
                    parametros: {
                        'id' : id,
                    },
					token: '<?= $request->token ?>'
                },
                success: function(data){
                    $('#id_pessoa').val(data[0].id_pessoa);
                    carrregaSelectVinculo(data[0].id_pessoa);
                }
            });
        }
    }

    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposPessoa(){
        var busca = $('#busca_pessoa').val();
        if (busca == "") {
            $('#id_pessoa').val('');
            carrregaSelectVinculo(null);
        }
    }

    $(document).on('click', '#habilita_busca_pessoa', function () {
        $('#id_pessoa').val('');
        $('#busca_pessoa').val('');
        $('#busca_pessoa').attr("readonly", false);
        $('#busca_pessoa').focus();
        carrregaSelectVinculo(null);
    });

    function carrregaSelectVinculo(id_pessoa){
    	$("select[name=vinculo]").html('<option value="">Carregando...</option>');
        $.post("/api/ajax?class=SelectVinculoTipoPessoa.php",
            {id_pessoa_pai:id_pessoa,
			token: '<?= $request->token ?>'},
            function(valor){
                $("select[name=vinculo]").html(valor);
            }
        )
    }

    $('#accordionRelatorio').on('shown.bs.collapse', function () {
       $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });

    $('#accordionRelatorio').on('hidden.bs.collapse', function () {
       $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });


    $(document).on('submit', 'form', function () {       
        modalAguarde();
    });

	$(document).on('click', '#gerar', function () {
		var busca = $('#busca_pessoa').val();
		var tipo = $('#tipo').val();
        if(!busca && tipo == 'aniversario'){
            alert("Deve-se selecionar uma pessoa!");
            return false;
        }
        modalAguarde();
    });

</script>
