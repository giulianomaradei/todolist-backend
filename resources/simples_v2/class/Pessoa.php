<?php
require_once "System.php";


$pagina_origem = (!empty($_POST['pagina_origem'])) ? $_POST['pagina_origem'] : '';
$id_negocio = (!empty($_POST['id_negocio'])) ? $_POST['id_negocio'] : 0;
$candidato = (!empty($_POST['candidato'])) ? $_POST['candidato'] : 0;
$cliente = (!empty($_POST['cliente'])) ? $cliente = $_POST['cliente'] : 0;
$fornecedor = (!empty($_POST['fornecedor'])) ? $_POST['fornecedor'] : 0;
$funcionario = (!empty($_POST['funcionario'])) ? $_POST['funcionario'] : 0;
$prospeccao = (!empty($_POST['prospeccao'])) ? $_POST['prospeccao'] : 0;
$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
$razao_social = (!empty($_POST['razao_social'])) ? $_POST['razao_social'] : '';
$tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] : '';
$cpf_cnpj = (!empty($_POST['cpf_cnpj'])) ? preg_replace("/[^0-9]/", "", $_POST['cpf_cnpj']) : '';
$inscricao_estadual = (!empty($_POST['inscricao_estadual'])) ? $_POST['inscricao_estadual'] : '';
$data_nascimento = (!empty($_POST['data_nascimento'])) ? $_POST['data_nascimento'] : '';
$data_nascimento = ($data_nascimento != '') ? converteData($data_nascimento) : '0000-00-00';
$sexo = (!empty($_POST['sexo'])) ? $_POST['sexo'] : '';
$status = (!empty($_POST['status'])) ? $_POST['status'] : 0;
$fone1 = (!empty($_POST['fone1'])) ? preg_replace("/[^0-9]/", "", $_POST['fone1']) : '';
$fone2 = (!empty($_POST['fone2'])) ? preg_replace("/[^0-9]/", "", $_POST['fone2']) : '';
$fone3 = (!empty($_POST['fone3'])) ? preg_replace("/[^0-9]/", "", $_POST['fone3']) : '';
$email1 = (!empty($_POST['email1'])) ? $_POST['email1'] : '';
$email2 = (!empty($_POST['email2'])) ? $_POST['email2'] : '';
$skype = (!empty($_POST['skype'])) ? $_POST['skype'] : '';
$facebook = (!empty($_POST['facebook'])) ? $_POST['facebook'] : '';
$site = (!empty($_POST['site'])) ? $_POST['site'] : '';
$obs_interna = (!empty($_POST['obs_interna'])) ? $_POST['obs_interna'] : '';
$obs_externa = (!empty($_POST['obs_externa'])) ? $_POST['obs_externa'] : '';
$logradouro = (!empty($_POST['logradouro'])) ? $_POST['logradouro'] : '';
$numero = (!empty($_POST['numero'])) ? $_POST['numero'] : '';
$bairro = (!empty($_POST['bairro'])) ? $_POST['bairro'] : '';
$complemento = (!empty($_POST['complemento'])) ? $_POST['complemento'] : '';
$id_cidade = (!empty($_POST['cidade'])) ? $_POST['cidade'] : '';
$cep = (!empty($_POST['cep'])) ? preg_replace("/[^0-9]/", "", $_POST['cep']) : '';
$endereco_correspondencia = (!empty($_POST['endereco_correspondencia'])) ? $_POST['endereco_correspondencia'] : '';
$linkedin = (!empty($_POST['linkedin'])) ? $_POST['linkedin'] : '';
$instagram = (!empty($_POST['instagram'])) ? $_POST['instagram'] : '';
$twitter = (!empty($_POST['twitter'])) ? $_POST['twitter'] : '';
$id_usuario = (!empty($_POST['id_usuario'])) ? $_POST['id_usuario'] : '';
$id_pessoa_alterou = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario'");
$pessoa_alterou = $id_pessoa_alterou[0]['id_pessoa']; // se refere a id_pessoa alterou passado como argumento de notificação
$flag = (!empty($_POST['flag'])) ? $_POST['flag'] : '';
$id_rd_conversao = (!empty($_POST['id_rd_conversao'])) ? $_POST['id_rd_conversao'] : '';

$modal_salvar = (!empty($_POST['modal_salvar'])) ? $_POST['modal_salvar'] : '';

if (!empty($_POST['inserir'])) {

	$dados = DBRead('', 'tb_pessoa', "WHERE BINARY cpf_cnpj = '" . addslashes($cpf_cnpj) . "' AND cpf_cnpj != '' AND status != '2'");
	if (!$dados) {

		inserir($candidato, $cliente, $fornecedor, $funcionario, $prospeccao, $nome, $razao_social, $tipo, $cpf_cnpj, $inscricao_estadual, $data_nascimento, $sexo, $status, $fone1, $fone2, $fone3, $email1, $email2,$skype, $facebook, $site, $obs_interna, $obs_externa, $logradouro, $numero, $bairro, $complemento, $id_cidade, $cep, $endereco_correspondencia, $pagina_origem, $instagram, $linkedin, $twitter, $modal_salvar, $flag, $id_rd_conversao);

	} else {
		$alert = ('Item já existe na base de dados!', 'w');
		header("location: /api/iframe?token=$request->token&view=pessoa-form");
		exit;
	}

} else if (!empty($_POST['alterar'])) {
	$id = (int) $_POST['alterar'];
	
	$dados = DBRead('', 'tb_pessoa', "WHERE BINARY cpf_cnpj = '" . addslashes($cpf_cnpj) . "' AND cpf_cnpj != '' AND id_pessoa != '$id' AND status != '2'");

	if (!$dados) {

		$id_vinculo = DBRead('', 'tb_vinculo_pessoa', "WHERE id_pessoa_filho = $id");
		$id_pessoa_pai = $id_vinculo[0]['id_pessoa_pai'];
		$plano = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE c.id_pessoa = '".$id_pessoa_pai."' AND c.cliente = 1");
		$id_plano = $plano[0]['id_plano'];

		alterar($id, $candidato, $cliente, $fornecedor, $funcionario, $prospeccao, $nome, $razao_social, $tipo, $cpf_cnpj, $inscricao_estadual, $data_nascimento, $sexo, $status, $fone1, $fone2, $fone3, $email1, $email2, $skype, $facebook, $site, $obs_interna, $obs_externa, $logradouro, $numero, $bairro, $complemento, $id_cidade, $cep, $endereco_correspondencia, $id_usuario, $pessoa_alterou,$pagina_origem, $id_negocio, $instagram, $linkedin, $twitter, $modal_salvar);

	} else {
		$alert = ('Item já existe na base de dados!', 'w');
		header("location: /api/iframe?token=$request->token&view=pessoa-form&alterar=$id");
		exit;
	}

} else if (isset($_GET['excluir'])) {

	$id = (int) $_GET['excluir'];

	//verifica se a pessoa não corresponde a Belluno - id_pessoa = 2
	if ($id != 2) {

		$operacao = "Excluir";
		$id_vinculo = DBRead('', 'tb_vinculo_pessoa', "WHERE id_pessoa_filho = $id");
		$id_pessoa_pai = $id_vinculo[0]['id_pessoa_pai'];
		$plano = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE c.id_pessoa = '".$id_pessoa_pai."' AND c.cliente = 1");
		$id_plano = $plano[0]['id_plano'];
		$id_usuario = $_SESSION['id_usuario'];

		excluir($id);
	} else {
		$alert = ('Não é possivel excluir a pessoa Belluno!', 'w');
		header("location: /api/iframe?token=$request->token&view=pessoa-busca");
		exit;
	}

} else {
	header("location: ../adm.php");
	exit;
}

function inserir($candidato, $cliente, $fornecedor, $funcionario, $prospeccao, $nome, $razao_social, $tipo, $cpf_cnpj, $inscricao_estadual, $data_nascimento, $sexo, $status, $fone1, $fone2, $fone3, $email1, $email2, $skype, $facebook, $site, $obs_interna, $obs_externa, $logradouro, $numero, $bairro, $complemento, $id_cidade, $cep, $endereco_correspondencia, $pagina_origem, $instagram, $linkedin, $twitter, $modal_salvar, $flag, $id_rd_conversao) {

	$link = DBConnect('');
	DBBegin($link);

	$data_atualizacao = getDataHora();

	$dados = array(
		'candidato' => $candidato,
		'cliente' => $cliente,
		'fornecedor' => $fornecedor,
		'funcionario' => $funcionario,
		'prospeccao' => $prospeccao,
		'nome' => $nome,
		'razao_social' => $razao_social,
		'tipo' => $tipo,
		'cpf_cnpj' => $cpf_cnpj,
		'inscricao_estadual' => $inscricao_estadual,
		'data_nascimento' => $data_nascimento,
		'sexo' => $sexo,
		'status' => $status,
		'fone1' => $fone1,
		'fone2' => $fone2,
		'fone3' => $fone3,
		'email1' => $email1,
		'email2' => $email2,
		'skype' => $skype,
		'facebook' => $facebook,
		'site' => $site,
		'obs_interna' => $obs_interna,
		'obs_externa' => $obs_externa,
		'logradouro' => $logradouro,
		'numero' => $numero,
		'bairro' => $bairro,
		'complemento' => $complemento,
		'id_cidade' => $id_cidade,
		'cep' => $cep,
		'endereco_correspondencia' => $endereco_correspondencia,
		'data_atualizacao' => $data_atualizacao,
		'instagram' => $instagram,
		'linkedin' => $linkedin,
		'twitter' => $twitter
	);

	$insertID = DBCreateTransaction($link, 'tb_pessoa', $dados, true);
	registraLogTransaction($link, 'Inserção de pessoa.', 'i', 'tb_pessoa', $insertID, "candidato: $candidato |cliente: $cliente | fornecedor: $fornecedor | funcionario: $funcionario | prospeccao: $prospeccao | nome: $nome | razao_social: $razao_social | tipo: $tipo | cpf_cnpj: $cpf_cnpj | inscricao_estadual: $inscricao_estadual | data_nascimento: $data_nascimento | sexo: $sexo | status: $status | fone1: $fone1 | fone2: $fone2 | fone3: $fone3 | email1: $email1 | email2: $email2 | skype: $skype | facebook: $facebook | site: $site | obs_interna: $obs_interna | obs_externa: $obs_externa | logradouro: $logradouro | numero: $numero | bairro: $bairro | complemento: $complemento | id_cidade: $id_cidade | cep: $cep | endereco_correspondencia: $endereco_correspondencia | data_atualizacao: $data_atualizacao | instagram: $instagram | linkedin: $linkedin | twitter: $twitter");

	if ($id_rd_conversao) {

		$dados_conversao = array(
			'id_pessoa' => $insertID
		);

		DBUpdateTransaction($link, 'tb_rd_conversao', $dados_conversao, "id_rd_conversao = $id_rd_conversao");
		registraLogTransaction($link, 'Alteração de RD lead conversao.', 'a', 'tb_rd_conversao', $id_rd_conversao, " id_pessoa: $insertID");
	}

	$id_lead_origem = (!empty($_POST['origem'])) ? $_POST['origem'] : '';
	$segmento = (!empty($_POST['segmento'])) ? $_POST['segmento'] : 0;
	$quantidade_clientes = (!empty($_POST['quantidade_clientes'])) ? $_POST['quantidade_clientes'] : 0;
	$id_pessoa_indicacao = (!empty($_POST['id_pessoa_indicacao'])) ? $_POST['id_pessoa_indicacao'] : NULL;
	$data_pessoa_indicacao = (!empty($_POST['data_pessoa_indicacao'])) ? $_POST['data_pessoa_indicacao'] : NULL;
	$estrutura_tres_niveis = (!empty($_POST['estrutura_tres_niveis'])) ? $_POST['estrutura_tres_niveis'] : '';
	$qtde_funcionarios_nivel_1 = (!empty($_POST['quantidade_funcionarios_nivel_1'])) ? $_POST['quantidade_funcionarios_nivel_1'] : 0;
	$qtde_funcionarios_nivel_2 = (!empty($_POST['quantidade_funcionarios_nivel_2'])) ? $_POST['quantidade_funcionarios_nivel_2'] : 0;
	$qtde_funcionarios_nivel_3 = (!empty($_POST['quantidade_funcionarios_nivel_3'])) ? $_POST['quantidade_funcionarios_nivel_3'] : 0;
	$central_telefonica = (!empty($_POST['central_telefonica'])) ? $_POST['central_telefonica'] : 0;
	$sistema_de_gestao = (!empty($_POST['sistema_de_gestao'])) ? $_POST['sistema_de_gestao'] : 0;
	$acesso_internet = (!empty($_POST['acesso_internet'])) ? $_POST['acesso_internet'] : '';
	$horario_mais_ligacoes = (!empty($_POST['horario_mais_ligacoes'])) ? $_POST['horario_mais_ligacoes'] : '';
	$atendimento_fideliza_cliente = (!empty($_POST['atendimento_fideliza_cliente'])) ? $_POST['atendimento_fideliza_cliente'] : '';
	$terceirizacao_atendimento = (!empty($_POST['terceirizacao_atendimento'])) ? $_POST['terceirizacao_atendimento'] : '';
	$qualificacao_cliente = (!empty($_POST['qualificacao_cliente'])) ? $_POST['qualificacao_cliente'] : '';
	$concorrencia = (!empty($_POST['concorrencia'])) ? $_POST['concorrencia'] : '';
	$reclamacoes_redes_sociais = (!empty($_POST['reclamacoes_redes_sociais'])) ? $_POST['reclamacoes_redes_sociais'] : '';
	$equipamento_redes = (!empty($_POST['equipamento_redes'])) ? $_POST['equipamento_redes'] : '';
	$outro_equipamento = (!empty($_POST['outro_equipamento'])) ? $_POST['outro_equipamento'] : ''; 
	$contato = (!empty($_POST['contato'])) ? $_POST['contato'] : ''; 
	$exp_assessoria_redes = (!empty($_POST['exp_assessoria_redes'])) ? $_POST['exp_assessoria_redes'] : 0;
	$qual_assessoria = (!empty($_POST['qual_assessoria'])) ? $_POST['qual_assessoria'] : '';
	$pq_nao_tem_mais = (!empty($_POST['pq_nao_tem_mais'])) ? $_POST['pq_nao_tem_mais'] : '';
	$obs_lead = (!empty($_POST['obs_lead'])) ? $_POST['obs_lead'] : '';
	$atendimento_chat = (!empty($_POST['atendimento_chat'])) ? $_POST['atendimento_chat'] : 0;
	$qtd_atendimentos_simultaneos = (!empty($_POST['qtd_atendimentos_simultaneos'])) ? $_POST['qtd_atendimentos_simultaneos'] : 0;
	$bina = (!empty($_POST['possui_bina'])) ? $_POST['possui_bina'] : 0;
	$obs_bina = (!empty($_POST['obs_bina'])) ? $_POST['obs_bina'] : '';

	if($id_lead_origem != '' || $segmento != 0 || $quantidade_clientes != 0 || $estrutura_tres_niveis != '' || $central_telefonica != 0 || $sistema_de_gestao != 0 || $acesso_internet != '' || $horario_mais_ligacoes != '' || $atendimento_fideliza_cliente != '' ||$terceirizacao_atendimento != '' || $qualificacao_cliente != '' || $concorrencia != '' || $reclamacoes_redes_sociais != '' || $qtde_funcionarios_nivel_1 != 0 || $qtde_funcionarios_nivel_2 != 0 || $qtde_funcionarios_nivel_3 != 0 || $equipamento_redes != '' || $outro_equipamento != '' || $contato != '' || $exp_assessoria_redes != '' || $qual_assessoria != '' || $pq_nao_tem_mais !='' || $bina != 0 ){

		if ($quantidade_clientes && $quantidade_clientes != '') {
			$data_atualizacao_clentes = $data_atualizacao;

		} else {
			$data_atualizacao_clentes = null;
		}

		if ($id_pessoa_indicacao != NULL) {
			$data_pessoa_indicacao = converteData($data_pessoa_indicacao);
		}

		$dados = array(
			'id_pessoa' => $insertID,
			'segmento' => $segmento,
			'quantidade_clientes' => $quantidade_clientes,
			'estrutura_tres_niveis' => $estrutura_tres_niveis,
			'central_telefonica' => $central_telefonica,
			'sistema_de_gestao' => $sistema_de_gestao,
			'horario_mais_ligacoes' => $horario_mais_ligacoes,
			'atendimento_fideliza_cliente' => $atendimento_fideliza_cliente,
			'terceirizacao_atendimento' => $terceirizacao_atendimento,
			'qualificacao_cliente' => $qualificacao_cliente,
			'concorrencia' => $concorrencia,
			'reclamacoes_redes_sociais' => $reclamacoes_redes_sociais,
			'id_lead_origem' => $id_lead_origem,
			'qtde_funcionarios_nivel_1' => $qtde_funcionarios_nivel_1,
			'qtde_funcionarios_nivel_2' => $qtde_funcionarios_nivel_2,
			'qtde_funcionarios_nivel_3' => $qtde_funcionarios_nivel_3,
			'pessoa_contato' => $contato,
			'exp_outra_assessoria_redes' => $exp_assessoria_redes,
			'exp_outra_qual' => $qual_assessoria,
			'pq_nao_tem_mais' => $pq_nao_tem_mais,
			'obs_lead' => $obs_lead,
			'atendimento_chat' => $atendimento_chat,
			'qtd_atendimentos_simultaneos' => $qtd_atendimentos_simultaneos,
			'data_atualizacao_clientes' => $data_atualizacao_clentes,
			'id_pessoa_indicacao' => $id_pessoa_indicacao,
			'data_pessoa_indicacao' => $data_pessoa_indicacao,
			'bina' => $bina,
			'obs_bina' => $obs_bina
		);

		$insertID_prospeccao = DBCreateTransaction($link, 'tb_pessoa_prospeccao', $dados, true);
		registraLogTransaction($link, 'Inserção de pessoa_prospeccao.', 'i', 'tb_pessoa_prospeccao', $insertID_prospeccao, "id_pessoa: $insertID | segmento: $segmento | quantidade_clientes: $quantidade_clientes | estrutura_tres_niveis: $estrutura_tres_niveis | central_telefonica: $central_telefonica | sistema_de_gestao: $sistema_de_gestao | horario_mais_ligacoes: $horario_mais_ligacoes | atendimento_fideliza_cliente: $atendimento_fideliza_cliente | terceirizacao_atendimento: $terceirizacao_atendimento | qualificacao_cliente: $qualificacao_cliente | concorrencia: $concorrencia | reclamacoes_redes_sociais: $reclamacoes_redes_sociais | id_lead_origem: $id_lead_origem | qtde_funcionarios_nivel_1: $qtde_funcionarios_nivel_1 | qtde_funcionarios_nivel_2: $qtde_funcionarios_nivel_2 | qtde_funcionarios_nivel_3: $qtde_funcionarios_nivel_3 | pessoa_contato: $contato | exp_outra_assessoria_redes: $exp_assessoria_redes | exp_qual_outra: $qual_assessoria | pq_nao_tem_mais: $pq_nao_tem_mais | obs_lead: $obs_lead | atendimento_chat: $atendimento_chat | qtd_atendimentos_simultaneos: $qtd_atendimentos_simultaneos | data_atualizacao_clientes: $data_atualizacao_clentes | id_pessoa_indicacao: $id_pessoa_indicacao | data_pessoa_indicacao: $data_pessoa_indicacao | bina: $bina | obs_bina: $obs_bina");

		if($acesso_internet != ''){
			for($i = 0; $i < sizeof($acesso_internet); $i++){

				$id_tipo_equipamento = $acesso_internet[$i];

				$dados = array(
					'id_pessoa_prospeccao' => $insertID_prospeccao,
					'id_tipo_equipamento' => $id_tipo_equipamento
				);

				$insertID_prospeccao_equipamento = DBCreateTransaction($link, 'tb_pessoa_prospeccao_tipo_equipamento', $dados, true);
				registraLogTransaction($link, 'Inserção de tipo equipamento pessoa_prospeccao.', 'i', 'tb_pessoa_prospeccao_tipo_equipamento', $insertID_prospeccao_equipamento, "id_pessoa_prospeccao: $insertID_prospeccao | id_tipo_equipamento:  $id_tipo_equipamento");
			}
		}

		if($equipamento_redes != ''){
			for($i = 0; $i < sizeof($equipamento_redes); $i++){

				$id_tipo_equipamento_redes = $equipamento_redes[$i];

				if($id_tipo_equipamento_redes == 5){
					$dados = array(
						'id_pessoa_prospeccao' => $insertID_prospeccao,
						'id_tipo_equipamento_redes' => $id_tipo_equipamento_redes,
						'outro' => $outro_equipamento
					);
				}else{
					$dados = array(
						'id_pessoa_prospeccao' => $insertID_prospeccao,
						'id_tipo_equipamento_redes' => $id_tipo_equipamento_redes,
					);
				}
				
				$insertID_prospeccao_equipamento = DBCreateTransaction($link, 'tb_pessoa_prospeccao_tipo_equipamento_redes', $dados, true);
				registraLogTransaction($link, 'Inserção de tipo equipamento redes pessoa_prospeccao.', 'i', 'tb_pessoa_prospeccao_tipo_equipamento_redes', $insertID_prospeccao_equipamento, "id_pessoa_prospeccao: $insertID_prospeccao | id_tipo_equipamento_redes: $id_tipo_equipamento_redes | outro: $outro_equipamento");
			}
		}
	}

	DBCommit($link);

	if ($pagina_origem == 'negocio-form') {
		$alert = ('Item inserido com sucesso!', 's');
		if($modal_salvar != -1){
			header("location: /api/iframe?token=$request->token&view=pessoa-form&alterar=$insertID");
		}else{
			header("location: /api/iframe?token=$request->token&view=lead-negocio-form");
		}
		exit;

	} else if ($pagina_origem == 'lead-timeline') {
		$alert = ('Item inserido com sucesso!', 's');
		if($modal_salvar != -1){
			header("location: /api/iframe?token=$request->token&view=pessoa-form&alterar=$insertID");
		}else{
			header("location: /api/iframe?token=$request->token&view=lead-timeline");
		}
		exit;
		
	} else if($pagina_origem == 'lead-negocios-busca') {
		$alert = ('Item inserido com sucesso!', 's');
		if($modal_salvar != -1){
			header("location: /api/iframe?token=$request->token&view=pessoa-form&alterar=$insertID");
		}else{
			header("location: /api/iframe?token=$request->token&view=lead-negocios-busca");
		}
		exit;

	} else{

		$alert = ('Item inserido com sucesso!', 's');
		if ($modal_salvar != -1) {
			header("location: /api/iframe?token=$request->token&view=pessoa-form&alterar=$insertID");

		} else if ($flag == 1) {
			header("location: /api/iframe?token=$request->token&view=vinculo-pessoa-form&vincular=$insertID&id_rd_conversao=$id_rd_conversao");

		} else if ($flag == 2) {
			header("location: /api/iframe?token=$request->token&view=lead-negocio-form&pessoa=$insertID&id_rd_conversao=$id_rd_conversao");

		} else {
			header("location: /api/iframe?token=$request->token&view=pessoa-busca");
		}
		exit;
	}
}

function alterar($id, $candidato, $cliente, $fornecedor, $funcionario, $prospeccao, $nome, $razao_social, $tipo, $cpf_cnpj, $inscricao_estadual, $data_nascimento, $sexo, $status, $fone1, $fone2, $fone3, $email1, $email2, $skype, $facebook, $site, $obs_interna, $obs_externa, $logradouro, $numero, $bairro, $complemento, $id_cidade, $cep, $endereco_correspondencia, $id_usuario, $pessoa_alterou, $pagina_origem, $id_negocio, $instagram, $linkedin, $twitter, $modal_salvar) {

	$link = DBConnect('');
	DBBegin($link);

	$dados = DBReadTransaction($link, 'tb_pessoa', "WHERE id_pessoa = '$id'");
	$mudanca = '';
	if ($candidato == $dados[0]['candidato'] && $cliente == $dados[0]['cliente'] && $fornecedor == $dados[0]['fornecedor'] && $funcionario == $dados[0]['funcionario'] && $prospeccao == $dados[0]['prospeccao'] && $nome == $dados[0]['nome'] && $razao_social == $dados[0]['razao_social'] && $tipo == $dados[0]['tipo'] && $cpf_cnpj == $dados[0]['cpf_cnpj'] && $inscricao_estadual == $dados[0]['inscricao_estadual'] && $data_nascimento == $dados[0]['data_nascimento'] && $sexo == $dados[0]['sexo'] && $status == $dados[0]['status'] && $fone1 == $dados[0]['fone1'] && $fone2 == $dados[0]['fone2'] && $fone3 == $dados[0]['fone3'] && $email1 == $dados[0]['email1'] && $email2 == $dados[0]['email2'] && $skype == $dados[0]['skype'] && $facebook == $dados[0]['facebook'] && $site == $dados[0]['site'] && $obs_interna == $dados[0]['obs_interna'] && $obs_externa == $dados[0]['obs_externa'] && $logradouro == $dados[0]['logradouro'] && $numero == $dados[0]['numero'] && $bairro == $dados[0]['bairro'] && $complemento == $dados[0]['complemento'] && $id_cidade == $dados[0]['id_cidade'] && $cep == $dados[0]['cep'] && $endereco_correspondencia == $dados[0]['endereco_correspondencia'] && $linkedin == $dados[0]['linkedin'] && $instagram == $dados[0]['instagram'] && $twitter == $dados[0]['twitter'] ) {

	$data_atualizacao = $dados[0]['data_atualizacao'];
		
	} else {

		if ($razao_social != $dados[0]['razao_social'] || $tipo != $dados[0]['tipo'] || $cpf_cnpj != $dados[0]['cpf_cnpj'] || $inscricao_estadual != $dados[0]['inscricao_estadual'] || $data_nascimento != $dados[0]['data_nascimento'] || $sexo != $dados[0]['sexo'] || $status != $dados[0]['status']) {
			$mudanca .= 'Dados Pessoais, ';
		}

		if ($nome != $dados[0]['nome']) {
			$mudanca .= 'Nome, ';
		}

		if ($candidato != $dados[0]['candidato'] || $cliente != $dados[0]['cliente'] || $fornecedor != $dados[0]['fornecedor'] || $funcionario != $dados[0]['funcionario'] || $prospeccao != $dados[0]['prospeccao']) {
			$mudanca .= 'Atributo, ';
		}

		if ($fone1 != $dados[0]['fone1']) {
			$mudanca .= 'Telefone 1, ';
		}

		if ($fone2 != $dados[0]['fone2']) {
			$mudanca .= 'Telefone 2, ';
		}

		if ($fone3 != $dados[0]['fone3']) {
			$mudanca .= 'Telefone 3, ';
		}
		if ($email1 != $dados[0]['email1']) {
			$mudanca .= 'Email 1, ';
		}

		if ($email2 != $dados[0]['email2']) {
			$mudanca .= 'Email 2, ';
		}

		if ($skype != $dados[0]['skype']) {
			$mudanca .= 'Skype, ';
		}

		if ($facebook != $dados[0]['facebook']) {
			$mudanca .= 'Facebook, ';
		}

		if ($site != $dados[0]['site']) {
			$mudanca .= 'Site, ';
		}

		if ($instagram != $dados[0]['instagram']) {
			$mudanca .= 'Instagram, ';
		}

		if ($linkedin != $dados[0]['linkedin']) {
			$mudanca .= 'Linkedin, ';
		}

		if ($twitter != $dados[0]['twitter']) {
			$mudanca .= 'Twitter, ';
		}
		
		if ($site != $dados[0]['site']) {
			$mudanca .= 'Site, ';
		}

		if ($obs_interna != $dados[0]['obs_interna']) {
			$mudanca .= 'Observação Interna, ';
		}

		if ($obs_externa != $dados[0]['obs_externa']) {
			$mudanca .= 'Observação Externa, ';					
		}

		if ($logradouro != $dados[0]['logradouro'] || $numero != $dados[0]['numero'] || $bairro != $dados[0]['bairro'] || $complemento != $dados[0]['complemento'] || $id_cidade != $dados[0]['id_cidade'] || $cep != $dados[0]['cep'] || $endereco_correspondencia != $dados[0]['endereco_correspondencia']) {
			$mudanca .= 'Endereco, ';
		}

		$data_atualizacao = getDataHora();
	}

	$dados = array(
		'candidato' => $candidato,
		'cliente' => $cliente,
		'fornecedor' => $fornecedor,
		'funcionario' => $funcionario,
		'prospeccao' => $prospeccao,
		'nome' => $nome,
		'razao_social' => $razao_social,
		'tipo' => $tipo,
		'cpf_cnpj' => $cpf_cnpj,
		'inscricao_estadual' => $inscricao_estadual,
		'data_nascimento' => $data_nascimento,
		'sexo' => $sexo,
		'status' => $status,
		'fone1' => $fone1,
		'fone2' => $fone2,
		'fone3' => $fone3,
		'email1' => $email1,
		'email2' => $email2,
		'skype' => $skype,
		'facebook' => $facebook,
		'site' => $site,
		'obs_interna' => $obs_interna,
		'obs_externa' => $obs_externa,
		'logradouro' => $logradouro,
		'numero' => $numero,
		'bairro' => $bairro,
		'complemento' => $complemento,
		'id_cidade' => $id_cidade,
		'cep' => $cep,
		'endereco_correspondencia' => $endereco_correspondencia,
		'data_atualizacao' => $data_atualizacao,
		'instagram' => $instagram,
		'linkedin' => $linkedin,
		'twitter' => $twitter
	);

	$alterado_sem_ultimo = substr($mudanca, 0, -2);
	DBUpdateTransaction($link, 'tb_pessoa', $dados, "id_pessoa = $id");
	registraLogTransaction($link, 'Alteração de pessoa.', 'a', 'tb_pessoa', $id, "candidato: $candidato | cliente: $cliente | fornecedor: $fornecedor | funcionario: $funcionario | prospeccao: $prospeccao | nome: $nome | razao_social: $razao_social | tipo: $tipo | cpf_cnpj: $cpf_cnpj | inscricao_estadual: $inscricao_estadual | data_nascimento: $data_nascimento | sexo: $sexo | status: $status | fone1: $fone1 | fone2: $fone2 | fone3: $fone3 | email1: $email1 | email2: $email2 | skype: $skype | facebook: $facebook | site: $site | obs_interna: $obs_interna | obs_externa: $obs_externa | logradouro: $logradouro | numero: $numero | bairro: $bairro | complemento: $complemento | id_cidade: $id_cidade | cep: $cep | endereco_correspondencia: $endereco_correspondencia | data_atualizacao: $data_atualizacao | dado_alterado: $alterado_sem_ultimo | instagram: $instagram | linkedin: $linkedin | twitter: $twitter");

	$id_lead_origem = (!empty($_POST['origem'])) ? $_POST['origem'] : NULL;
	$segmento = (!empty($_POST['segmento'])) ? $_POST['segmento'] : 0;
	$quantidade_clientes = (!empty($_POST['quantidade_clientes'])) ? $_POST['quantidade_clientes'] : 0;
	$id_pessoa_indicacao = (!empty($_POST['id_pessoa_indicacao'])) ? $_POST['id_pessoa_indicacao'] : NULL;
	$data_pessoa_indicacao = (!empty($_POST['data_pessoa_indicacao'])) ? $_POST['data_pessoa_indicacao'] : NULL;
	$estrutura_tres_niveis = (!empty($_POST['estrutura_tres_niveis'])) ? $_POST['estrutura_tres_niveis'] : '';
	$qtde_funcionarios_nivel_1 = (!empty($_POST['quantidade_funcionarios_nivel_1'])) ? $_POST['quantidade_funcionarios_nivel_1'] : 0;
	$qtde_funcionarios_nivel_2 = (!empty($_POST['quantidade_funcionarios_nivel_2'])) ? $_POST['quantidade_funcionarios_nivel_2'] : 0;
	$qtde_funcionarios_nivel_3 = (!empty($_POST['quantidade_funcionarios_nivel_3'])) ? $_POST['quantidade_funcionarios_nivel_3'] : 0;
	$central_telefonica = (!empty($_POST['central_telefonica'])) ? $_POST['central_telefonica'] : 0;
	$sistema_de_gestao = (!empty($_POST['sistema_de_gestao'])) ? $_POST['sistema_de_gestao'] : 0;
	$acesso_internet = (!empty($_POST['acesso_internet'])) ? $_POST['acesso_internet'] : '';
	$horario_mais_ligacoes = (!empty($_POST['horario_mais_ligacoes'])) ? $_POST['horario_mais_ligacoes'] : '';
	$atendimento_fideliza_cliente = (!empty($_POST['atendimento_fideliza_cliente'])) ? $_POST['atendimento_fideliza_cliente'] : '';
	$terceirizacao_atendimento = (!empty($_POST['terceirizacao_atendimento'])) ? $_POST['terceirizacao_atendimento'] : '';
	$qualificacao_cliente = (!empty($_POST['qualificacao_cliente'])) ? $_POST['qualificacao_cliente'] : '';
	$concorrencia = (!empty($_POST['concorrencia'])) ? $_POST['concorrencia'] : '';
	$reclamacoes_redes_sociais = (!empty($_POST['reclamacoes_redes_sociais'])) ? $_POST['reclamacoes_redes_sociais'] : '';
	$equipamento_redes = (!empty($_POST['equipamento_redes'])) ? $_POST['equipamento_redes'] : '';
	$outro_equipamento = (!empty($_POST['outro_equipamento'])) ? $_POST['outro_equipamento'] : ''; 
	$contato = (!empty($_POST['contato'])) ? $_POST['contato'] : ''; 
	$exp_assessoria_redes = (!empty($_POST['exp_assessoria_redes'])) ? $_POST['exp_assessoria_redes'] : 0;
	$qual_assessoria = (!empty($_POST['qual_assessoria'])) ? $_POST['qual_assessoria'] : '';
	$pq_nao_tem_mais = (!empty($_POST['pq_nao_tem_mais'])) ? $_POST['pq_nao_tem_mais'] : '';
	$obs_lead = (!empty($_POST['obs_lead'])) ? $_POST['obs_lead'] : '';
	$atendimento_chat = (!empty($_POST['atendimento_chat'])) ? $_POST['atendimento_chat'] : 0;
	$qtd_atendimentos_simultaneos = (!empty($_POST['qtd_atendimentos_simultaneos'])) ? $_POST['qtd_atendimentos_simultaneos'] : 0;
	$bina = (!empty($_POST['possui_bina'])) ? $_POST['possui_bina'] : 0;
	$obs_bina = (!empty($_POST['obs_bina'])) ? $_POST['obs_bina'] : '';

	if ($id_lead_origem != '' || $segmento != 0 || $quantidade_clientes != 0 || $estrutura_tres_niveis != '' || $central_telefonica != 0 || $sistema_de_gestao != 0 || $acesso_internet != '' || $horario_mais_ligacoes != '' || $atendimento_fideliza_cliente != '' ||$terceirizacao_atendimento != '' || $qualificacao_cliente != '' || $concorrencia != '' || $reclamacoes_redes_sociais != '' || $qtde_funcionarios_nivel_1 != 0 || $qtde_funcionarios_nivel_2 != 0 || $qtde_funcionarios_nivel_3 != 0 || $equipamento_redes != '' || $outro_equipamento != '' || $contato != '' || $exp_assessoria_redes != '' || $qual_assessoria != '' || $pq_nao_tem_mais !='' || $id_pessoa_indicacao !='' || $bina != 0) {

		if ($alterado_sem_ultimo != '') {
			$alterado_sem_ultimo .= ', dados de prospecção';
		} else {
			$alterado_sem_ultimo = 'Dados de prospecção';
		}
		
		$existe_dados_prospeccao = DBReadTransaction($link, 'tb_pessoa_prospeccao', "WHERE id_pessoa = $id");

		if ($existe_dados_prospeccao[0]['quantidade_clientes'] != $quantidade_clientes) {
			$data_atualizacao_clentes = getDataHora();

		} else {
			$data_atualizacao_clentes = $existe_dados_prospeccao[0]['data_atualizacao_clientes'];
		}

		if ($id_pessoa_indicacao == NULL) {
			$data_pessoa_indicacao = NULL;

		} else {
			$data_pessoa_indicacao = converteData($data_pessoa_indicacao);
		}

		$dados = array(
			'id_pessoa' => $id,
			'segmento' => $segmento,
			'quantidade_clientes' => $quantidade_clientes,
			'estrutura_tres_niveis' => $estrutura_tres_niveis,
			'central_telefonica' => $central_telefonica,
			'sistema_de_gestao' => $sistema_de_gestao,
			'horario_mais_ligacoes' => $horario_mais_ligacoes,
			'atendimento_fideliza_cliente' => $atendimento_fideliza_cliente,
			'terceirizacao_atendimento' => $terceirizacao_atendimento,
			'qualificacao_cliente' => $qualificacao_cliente,
			'concorrencia' => $concorrencia,
			'reclamacoes_redes_sociais' => $reclamacoes_redes_sociais,
			'id_lead_origem' => $id_lead_origem,
			'qtde_funcionarios_nivel_1' => $qtde_funcionarios_nivel_1,
			'qtde_funcionarios_nivel_2' => $qtde_funcionarios_nivel_2,
			'qtde_funcionarios_nivel_3' => $qtde_funcionarios_nivel_3,
			'pessoa_contato' => $contato,
			'exp_outra_assessoria_redes' => $exp_assessoria_redes,
			'exp_outra_qual' => $qual_assessoria,
			'pq_nao_tem_mais' => $pq_nao_tem_mais,
			'obs_lead' => $obs_lead,
			'atendimento_chat' => $atendimento_chat,
			'qtd_atendimentos_simultaneos' => $qtd_atendimentos_simultaneos,
			'data_atualizacao_clientes' => $data_atualizacao_clentes,
			'id_pessoa_indicacao' => $id_pessoa_indicacao,
			'data_pessoa_indicacao' => $data_pessoa_indicacao,
			'bina' => $bina,
			'obs_bina' => $obs_bina
		);

		if ($existe_dados_prospeccao) {

			DBUpdateTransaction($link, 'tb_pessoa_prospeccao', $dados, "id_pessoa = $id");
			registraLogTransaction($link,'Alteracao de pessoa_prospeccao.', 'a', 'tb_pessoa_prospeccao', $id, "id_pessoa: $id | segmento: $segmento | quantidade_clientes: $quantidade_clientes | estrutura_tres_niveis: $estrutura_tres_niveis | central_telefonica: $central_telefonica | sistema_de_gestao: $sistema_de_gestao | horario_mais_ligacoes: $horario_mais_ligacoes | atendimento_fideliza_cliente: $atendimento_fideliza_cliente | terceirizacao_atendimento: $terceirizacao_atendimento | qualificacao_cliente: $qualificacao_cliente | concorrencia: $concorrencia | reclamacoes_redes_sociais: $reclamacoes_redes_sociais | id_lead_origem: $id_lead_origem | qtde_funcionarios_nivel_1: $qtde_funcionarios_nivel_1 | qtde_funcionarios_nivel_2: $qtde_funcionarios_nivel_2 | qtde_funcionarios_nivel_3: $qtde_funcionarios_nivel_3 | pessoa_contato: $contato | exp_outra_assessoria_redes: $exp_assessoria_redes | exp_qual_outra: $qual_assessoria | pq_nao_tem_mais: $pq_nao_tem_mais | obs_lead: $obs_lead | atendimento_chat: $atendimento_chat | qtd_atendimentos_simultaneos: $qtd_atendimentos_simultaneos | data_atualizacao_clientes: $data_atualizacao_clentes | id_pessoa_indicacao: $id_pessoa_indicacao | data_pessoa_indicacao: $data_pessoa_indicacao");

			$id_pessoa_prospeccao = $existe_dados_prospeccao[0]['id_pessoa_prospeccao'];

		} else {
			$id_pessoa_prospeccao = DBCreateTransaction($link, 'tb_pessoa_prospeccao', $dados, true);
			registraLogTransaction($link,'Inserção de pessoa_prospeccao.', 'i', 'tb_pessoa_prospeccao', $id, "id_pessoa: $id_pessoa_prospeccao | segmento: $segmento | quantidade_clientes: $quantidade_clientes | estrutura_tres_niveis: $estrutura_tres_niveis | central_telefonica: $central_telefonica | sistema_de_gestao: $sistema_de_gestao | horario_mais_ligacoes: $horario_mais_ligacoes | atendimento_fideliza_cliente: $atendimento_fideliza_cliente | terceirizacao_atendimento: $terceirizacao_atendimento | qualificacao_cliente: $qualificacao_cliente | concorrencia: $concorrencia | reclamacoes_redes_sociais: $reclamacoes_redes_sociais | id_lead_origem: $id_lead_origem | qtde_funcionarios_nivel_1: $qtde_funcionarios_nivel_1 | qtde_funcionarios_nivel_2: $qtde_funcionarios_nivel_2 | qtde_funcionarios_nivel_3: $qtde_funcionarios_nivel_3 | pessoa_contato: $contato | exp_outra_assessoria_redes: $exp_assessoria_redes | exp_qual_outra: $qual_assessoria | pq_nao_tem_mais: $pq_nao_tem_mais | obs_lead: $obs_lead | atendimento_chat: $atendimento_chat | qtd_atendimentos_simultaneos: $qtd_atendimentos_simultaneos | data_atualizacao_clientes: $data_atualizacao_clentes | id_pessoa_indicacao: $id_pessoa_indicacao | data_pessoa_indicacao: $data_pessoa_indicacao | bina: $bina | obs_bina: $obs_bina");
		}

		DBDeleteTransaction($link, 'tb_pessoa_prospeccao_tipo_equipamento', "id_pessoa_prospeccao = '$id_pessoa_prospeccao'");
		
		if ($acesso_internet != '') {
			for ($i = 0; $i < sizeof($acesso_internet); $i++) {

				$id_tipo_equipamento = $acesso_internet[$i];

				$dados = array(
					'id_pessoa_prospeccao' => $id_pessoa_prospeccao,
					'id_tipo_equipamento' => $id_tipo_equipamento
				);

				$insertID_prospecacao_equipamento = DBCreateTransaction($link, 'tb_pessoa_prospeccao_tipo_equipamento', $dados, true);
				registraLogTransaction($link, 'Inserção de tipo equipamento pessoa_prospeccao.', 'i', 'tb_pessoa_prospeccao', $insertID_prospecacao_equipamento, "id_pessoa_prospeccao: $id_pessoa_prospeccao | id_tipo_equipamento:  $id_tipo_equipamento");
			}
		}

		DBDeleteTransaction($link, 'tb_pessoa_prospeccao_tipo_equipamento_redes', "id_pessoa_prospeccao = '$id_pessoa_prospeccao'");

		if ($equipamento_redes != '') {

			for ($i = 0; $i < sizeof($equipamento_redes); $i++) {

				$id_tipo_equipamento_redes = $equipamento_redes[$i];

				if ($id_tipo_equipamento_redes == 5) {
					$dados = array(
						'id_pessoa_prospeccao' => $id_pessoa_prospeccao,
						'id_tipo_equipamento_redes' => $id_tipo_equipamento_redes,
						'outro' => $outro_equipamento
					);
				} else {
					$dados = array(
						'id_pessoa_prospeccao' => $id_pessoa_prospeccao,
						'id_tipo_equipamento_redes' => $id_tipo_equipamento_redes,
					);
				}
				
				$insertID_prospeccao_equipamento = DBCreateTransaction($link, 'tb_pessoa_prospeccao_tipo_equipamento_redes', $dados, true);
				registraLogTransaction($link, 'Inserção de tipo equipamento redes pessoa_prospeccao.', 'i', 'tb_pessoa_prospeccao_tipo_equipamento_redes', $insertID_prospeccao_equipamento, "id_pessoa_prospeccao: $id_pessoa_prospeccao | id_tipo_equipamento_redes: $id_tipo_equipamento_redes | outro: $outro_equipamento");
			}
		}

	}//end if

	DBCommit($link);
	
	$operacao = "Alterar";
	$alteracao = "Pessoa";
	notificacao($alteracao, $id, $operacao, $pessoa_alterou, $id_usuario, $alterado_sem_ultimo);

	if ($pagina_origem == 'negocio-informacoes') {
		$alert = ('Item alterado com sucesso!', 's');
		if($modal_salvar != -1){
			header("location: /api/iframe?token=$request->token&view=pessoa-form&alterar=$id");
		}else{
			header("location: /api/iframe?token=$request->token&view=lead-negocio-informacoes&lead=$id_negocio");
		}
		exit;

	} else {
		$alert = ('Item alterado com sucesso!', 's');
		if ($modal_salvar != -1) {
			header("location: /api/iframe?token=$request->token&view=pessoa-form&alterar=$id");
		} else {
			header("location: /api/iframe?token=$request->token&view=pessoa-busca");
		}
		exit;
	}	
}

function excluir($id) {
	
	$dados = array(
		'status' => '2',
	);

	DBUpdate('', 'tb_pessoa', $dados, "id_pessoa = '$id'");
	registraLog('Exclusão de pessoa.', 'e', 'tb_pessoa', $id, '');
	$alert = ('Item excluído com sucesso!', 's');
	header("location: /api/iframe?token=$request->token&view=pessoa-busca");
	exit;
}

?>