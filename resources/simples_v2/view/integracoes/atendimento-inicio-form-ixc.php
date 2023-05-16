<?php
//Faz o acesso a vários recursos de consulta da API para atualizar a base de dados obrigatórios para a finalização de um atendimento com integração ao IXC
require_once "class/integracoes/ixc/Assunto.php";
require_once "class/integracoes/ixc/DepartamentoAtendimento.php";
require_once "class/integracoes/ixc/Filial.php";
require_once "class/integracoes/ixc/Setor.php";
require_once "class/integracoes/ixc/Funcionarios.php";
require_once "class/integracoes/ixc/Cliente.php";
//require_once "class/integracoes/ixc/Login.php";

//id da integração do ixc na base de dados do Simples
$id_integracao = 1;

//Consulta que trás os dados obrigatórios para a finalização de uma atendimento, o.s. ou ação no sistema de gestão.
$dados_obrigatorios = DBRead('', 'tb_dados_obrigatorios_integracao', "WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");

$count = 0;
//Faz a contagem de quantos atributos de assunto existem no banco do Simples
if ($dados_obrigatorios) {
	foreach ($dados_obrigatorios as $conteudo) {
		if ($conteudo["chave"] == "assunto") {
			$count++;
		}
	}
}
$assunto = new Integracao\Ixc\Assunto();
$retorno_assunto = $assunto->get('su_oss_assunto.id', '', true, $id_contrato_plano_pessoa);
/*Faz a comparação do total de atributos de assunto no banco do Simples com o total retornado da API de integração do IXC para verificar se não foi adicionado
novos dados na base do IXC. Se sim, é atualizado a base do Simples*/
if ($retorno_assunto) {
	if ($count != $retorno_assunto["total"]) {
		DBDelete('', 'tb_dados_obrigatorios_integracao', "chave = 'assunto' AND id_integracao = 1 AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
		if ($retorno_assunto['registros']) {
			foreach ($retorno_assunto['registros'] as $retorno) {
				$dados = array(
					"chave" => "assunto",
					"valor_id" => $retorno['id'],
					"valor_descricao" => $retorno['assunto'],
					"id_integracao" => $id_integracao,
					'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
				);
				DBCreate('', 'tb_dados_obrigatorios_integracao', $dados, false);
			}
		}
	}
}

$count = 0;
//Faz a contagem de quantos atributos de assunto existem no banco do Simples
if ($dados_obrigatorios) {
	foreach ($dados_obrigatorios as $conteudo) {
		if ($conteudo["chave"] == "departamento") {
			$count++;
		}
	}
}
$departamento = new Integracao\Ixc\DepartamentoAtendimento();
$retorno_departamento = $departamento->get('su_ticket_setor.id', '', true, $id_contrato_plano_pessoa);
/*Faz a comparação do total de atributos de assunto no banco do Simples com o total retornado da API de integração do IXC para verificar se não foi adicionado
novos dados na base do IXC. Se sim, e atualizado a base do Simples*/
if ($retorno_departamento) {
	if ($count != $retorno_departamento["total"]) {
		DBDelete('', 'tb_dados_obrigatorios_integracao', "chave = 'departamento' AND id_integracao = 1 AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
		if ($retorno_departamento['registros']) {
			foreach ($retorno_departamento['registros'] as $retorno) {
				$dados = array(
					"chave" => "departamento",
					"valor_id" => $retorno['id'],
					"valor_descricao" => $retorno['setor'],
					"id_integracao" => $id_integracao,
					'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
				);
				DBCreate('', 'tb_dados_obrigatorios_integracao', $dados, false);
			}
		}
	}
}

$count = 0;
//Faz a contagem de quantos atributos de assunto existem no banco do Simples
if ($dados_obrigatorios) {
	foreach ($dados_obrigatorios as $conteudo) {
		if ($conteudo["chave"] == "filial") {
			$count++;
		}
	}
}
$filial = new Integracao\Ixc\Filial();
$retorno_filial = $filial->get('filial.id', '', '=', true, $id_contrato_plano_pessoa);
/*Faz a comparação do total de atributos de assunto no banco do Simples com o total retornado da API de integração do IXC para verificar se não foi adicionado
novos dados na base do IXC. Se sim, e atualizado a base do Simples*/
if ($retorno_filial) {
	if ($retorno_filial["total"]) {
		if ($count != $retorno_filial["total"]) {
			DBDelete('', 'tb_dados_obrigatorios_integracao', "chave = 'filial' AND id_integracao = 1 AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
			foreach ($retorno_filial['registros'] as $retorno) {
				$dados = array(
					"chave" => "filial",
					"valor_id" => $retorno['id'],
					"valor_descricao" => $retorno['razao'],
					"id_integracao" => $id_integracao,
					'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
				);
				DBCreate('', 'tb_dados_obrigatorios_integracao', $dados, false);
			}
		}
	}
}

$count = 0;
//Faz a contagem de quantos atributos de assunto existem no banco do Simples
if ($dados_obrigatorios) {
	foreach ($dados_obrigatorios as $conteudo) {
		if ($conteudo["chave"] == "setor") {
			$count++;
		}
	}
}
$setor = new Integracao\Ixc\Setor();
$retorno_setor = $setor->get('empresa_setor.id', '', '=', true, $id_contrato_plano_pessoa);
/*Faz a comparação do total de atributos de assunto no banco do Simples com o total retornado da API de integração do IXC para verificar se não foi adicionado
novos dados na base do IXC. Se sim, e atualizado a base do Simples*/
if ($retorno_setor) {
	if ($count != $retorno_setor["total"]) {
		DBDelete('', 'tb_dados_obrigatorios_integracao', "chave = 'setor' AND id_integracao = 1 AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
		foreach ($retorno_setor['registros'] as $retorno) {
			$dados = array(
				"chave" => "setor",
				"valor_id" => $retorno['id'],
				"valor_descricao" => $retorno['setor'],
				"id_integracao" => $id_integracao,
				'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
			);
			DBCreate('', 'tb_dados_obrigatorios_integracao', $dados, false);
		}
	}
}

$count = 0;
//Faz a contagem de quantos atributos de assunto existem no banco do Simples
if ($dados_obrigatorios) {
	foreach ($dados_obrigatorios as $conteudo) {
		if ($conteudo["chave"] == "funcionario") {
			$count++;
		}
	}
}
$tecnico = new Integracao\Ixc\Funcionarios();
$retorno_tecnico = $tecnico->get('funcionarios.id', '', true, $id_contrato_plano_pessoa);
/*Faz a comparação do total de atributos de assunto no banco do Simples com o total retornado da API de integração do IXC para verificar se não foi adicionado
novos dados na base do IXC. Se sim, e atualizado a base do Simples*/
if ($retorno_tecnico) {
	if ($count != $retorno_tecnico["total"]) {
		DBDelete('', 'tb_dados_obrigatorios_integracao', "chave = 'funcionario' AND id_integracao = 1 AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
		if ($retorno_tecnico['registros']) {

			$retorno_tecnico = unique_multidim_array($retorno_tecnico['registros'], 'usuario');

			foreach ($retorno_tecnico as $retorno) {
				$dados = array(
					"chave" => "funcionario",
					"valor_id" => $retorno['usuario'],
					"valor_descricao" => $retorno['funcionario'],
					"id_integracao" => $id_integracao,
					'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
				);
				DBCreate('', 'tb_dados_obrigatorios_integracao', $dados, false);
			}
		}
	}
}
?>

<script>
	//Validação para não permitir a continuidade do atendimento em caso de assinante não existente na base do IXC do provedor.
	$('#inserir').on('click', function() {
		if (!sessionStorage.getItem('id_assinante')) {
			alert('Selecione um assinante válido!');
			modalAguarde(false);
			return false;
		}
	});

	$('#collapseFalha').on('shown.bs.collapse', function() {
		$('#busca_assinante').attr('required', false);
	});

	$('#collapseFalha').on('hide.bs.collapse', function() {
		$('#busca_assinante').attr('required', true);
	});

	//remove todos os dados de sessionStorage que possam estar salvos no navegador do atendente antes de um novo atendimento.
	sessionStorage.clear();

	//Status referente ao assinante(cliente)
	status_assinante = {
		'S': {
			'nome': 'Ativo',
			'cor': 'text-success'
		},
		'N': {
			'nome': 'Inativo',
			'cor': 'text-danger'
		}
	};

	//Autocomplete para o input assinante
	$("#busca_assinante").autocomplete({
			minLength: 2,
			source: function(request, response) {
				$.ajax({
					url: "class/integracoes/AssinanteIxcAutocomplete.php",
					dataType: "json",
					data: {
						acao: 'autocomplete',
						parametros: {
							'nome': $('#busca_assinante').val(),
							'id_contrato_plano_pessoa': <?= $id_contrato_plano_pessoa ?>
						}
					},
					success: function(data) {
						console.log(data);
						$('#loading_assinante').html('');
						if (data) {
							if (data.registros) {
								response(data.registros);
								$("#erro-busca-assinante").html("");
							} else if (!data.registros) {
								if (!data.registros && data.total == 0) {
									$("#erro-busca-assinante").html("<p class='text-danger'>Assinante não encontrado!</p>");
								} else {
									$("#erro-busca-assinante").html("<p class='text-danger'>Assinante não encontrado!</p>");
									console.error("Problemas ao carregar os dados!");
								}
							}
						}
					},
					beforeSend: function() {
						$('#loading_assinante').html(' <i class="fa fa-spinner faa-spin animated"></i> carregando...');
					},
					error: function() {
						$("#aviso-integracao").html("<span class='text-danger' style='font-size: 20px; font-weight: bolder;'>Sistema de gestão indisponível</span>");
					}
				});
			},
			select: function(event, ui) {

				sessionStorage.setItem("nome", status_assinante[ui.item.ativo]['nome']);
				sessionStorage.setItem("cor", status_assinante[ui.item.ativo]['cor']);
				$("#busca_assinante").val(ui.item.razao);
				carregarDadosAssinante(ui.item.id);
				return false;
			}
		}).focus(function() {
			$(this).autocomplete("search");
		})
		.autocomplete("instance")._renderItem = function(ul, item) {

			if (!item.razao) {
				item.razao = '';
			}
			if (!item.cnpj_cpf) {
				item.cnpj_cpf = '';
			}
			sessionStorage.setItem("nome", status_assinante[item.ativo]['nome']);
			sessionStorage.setItem("cor", status_assinante[item.ativo]['cor']);
			return $("<li style='padding-top: 15px;padding-left: 8px'>").append("<a><span class='" + status_assinante[item.ativo]['cor'] + "'><i class='fas fa-circle'></i> (" + status_assinante[item.ativo]['nome'] + ")</span><br><strong>ASSINANTE: </strong>" + item.razao + " <br><strong>CPF/CNPJ: </strong>" + item.cnpj_cpf + "<br><strong>ENDEREÇO: </strong>" + item.endereco + ", BAIRRO: " + item.bairro + ", " + item.numero + "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
		};


	//Metodo que pega o id do assinante pesquisado e trás os seus respectivos dados.
	function carregarDadosAssinante(id) {

		var busca = $('#busca_assinante').val();

		if (busca != "" && busca.length >= 2) {
			$.ajax({
				url: "class/integracoes/AssinanteIxcAutocomplete.php",
				dataType: "json",
				data: {
					acao: 'consulta',
					parametros: {
						'id': id,
						'id_contrato_plano_pessoa': <?= $id_contrato_plano_pessoa ?>
					}
				},
				success: function(data) {
					console.log(data);
					if (data) {

						if (data.registros) {
							id_cidade = data.registros[0].cidade;
							/* Armazenar temporariamente, em sessionStorage, o id do assinante no sistema ixc para utilização posterior
							no fluxo de atendimento do sistema Simples */
							sessionStorage.setItem("id_assinante", data.registros[0].id);
							sessionStorage.setItem("razao_social", data.registros[0].razao);
							sessionStorage.setItem("cpf_cnpj", data.registros[0].cnpj_cpf);
							sessionStorage.setItem("endereco", data.registros[0].endereco);
							sessionStorage.setItem("complemento", data.registros[0].complemento);
							sessionStorage.setItem("bairro", data.registros[0].bairro);
							sessionStorage.setItem("numero_endereco", data.registros[0].numero);
							sessionStorage.setItem("cep", data.registros[0].cep);
							sessionStorage.setItem("observacao", data.registros[0].obs);
							sessionStorage.setItem('data_inicial', '<?php echo getDataHora(); ?>');
							sessionStorage.setItem("email", data.registros[0].email);
							sessionStorage.setItem("telefone_celular", data.registros[0].telefone_celular);

							//Configura variaveis com o valor obtido pela requisição ajax para nos campos do elemento container-info-assinante montado logo a baixo
							razao = data.registros[0].razao ? data.registros[0].razao : "";
							cpf_cnpj = data.registros[0].cnpj_cpf ? data.registros[0].cnpj_cpf : "";
							endereco = data.registros[0].endereco ? data.registros[0].endereco : "";
							complemento = data.registros[0].complemento ? data.registros[0].complemento : "";
							numero = data.registros[0].numero ? data.registros[0].numero : "";
							bairro = data.registros[0].bairro ? data.registros[0].bairro : "";
							observacao = data.registros[0].obs ? data.registros[0].obs : "";
							$("#container-info-assinante").html(
								`
							<div class="row" style="margin: 10px 0px 0px 0px !important; padding: 10px 15px 10px 15px !important; background-color: #f2f2f2; border: 1px solid #d5d5d5;>
								<div class="col-md-12">
									<p><span class='` + sessionStorage.getItem("cor") + `'><i class='fas fa-circle'></i> (` + sessionStorage.getItem("nome") + `)</span></p>
									<p><strong>Nome (Razão social):</strong> ` + razao + `</p>
									<p><strong>CPF/CNPJ:</strong> ` + cpf_cnpj + `</p>
									<p><strong>Endereço:</strong> ` + endereco + `, ` + numero + `, Bairro: ` + bairro + ` - Cidade: <span class='cidade'></span></p>
									<p><strong>Complemento:</strong> ` + complemento + `</p>
									<p><strong>Observação:</strong> ` + observacao + `</p>
								</div>
							</div>
							`
							);
							$("#assinante").val(razao);
							$('#modal-lista-assinantes').modal('hide');
						}
					}
				},
				complete: function() {
					//Busca a cidade do assinante
					$.ajax({
						url: "class/IntegracaoTipoSistemaAjax.php",
						method: "GET",
						dataType: "json",
						data: {
							acao: "busca_cidade",
							id_cidade: id_cidade,
							id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>
						},
						success: function(data) {
							sessionStorage.setItem("cidade", data.registros[0].nome);
							$(".cidade").text(data.registros[0].nome);
						}
					});

				}
			});
		}
	}
</script>