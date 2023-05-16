<?php
require_once(__DIR__."/System.php");
require_once(__DIR__."/QuadroInformativoHistorico.php");

//abreSessao();

$id_usuario = $_SESSION['id_usuario'];
$operacao = (isset($_GET['operacao'])) ? $_GET['operacao'] : '';
$acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';
$id_contrato_plano_pessoa = (isset($_GET['id_contrato_plano_pessoa'])) ? $_GET['id_contrato_plano_pessoa'] : '';

$id_atendimento = (isset($_GET['id_atendimento'])) ? $_GET['id_atendimento'] : '';
$assunto = (isset($_GET['assunto'])) ? $_GET['assunto'] : '';
$setor = (isset($_GET['setor'])) ? $_GET['setor'] : '';
$filial = (isset($_GET['filial'])) ? $_GET['filial'] : '';
$tecnico = (isset($_GET['tecnico'])) ? $_GET['tecnico'] : '';
$departamento = (isset($_GET['departamento'])) ? $_GET['departamento'] : '';
$processo = (isset($_GET['processo'])) ? $_GET['processo'] : '';
$prioridade = (isset($_GET['prioridade'])) ? $_GET['prioridade'] : '';
$origem = (isset($_GET['origem'])) ? $_GET['origem'] : '';
$contrato = (isset($_GET['contrato'])) ? $_GET['contrato'] : '';
$login = (isset($_GET['login'])) ? $_GET['login'] : '';
$classificacao = (isset($_GET['classificacao'])) ? $_GET['classificacao'] : '';

//atendimento vinculado a OS ja existente
$classificacao_evento = (isset($_GET['classificacao_evento'])) ? $_GET['classificacao_evento'] : '';
$evento = (isset($_GET['evento'])) ? $_GET['evento'] : '';
$tecnico_responsavel = (isset($_GET['tecnico_responsavel'])) ? $_GET['tecnico_responsavel'] : '';
$id_os = (isset($_GET['id_os'])) ? $_GET['id_os'] : NULL;
$flag_pendencia = (isset($_GET['flag_pendencia'])) ? $_GET['flag_pendencia'] : NULL;

if (!$classificacao || $classificacao == '') {

	$classificacao = $classificacao_evento;
	$tecnico = $tecnico_responsavel;
}

$id_area_problema = (isset($_GET['id_area_problema'])) ? $_GET['id_area_problema'] : NULL;
$id_subarea_problema = (isset($_GET['id_subarea_problema'])) ? $_GET['id_subarea_problema'] : NULL;

$id_integracao_valores_default = (isset($_GET['id_integracao_valores_default'])) ? $_GET['id_integracao_valores_default'] : '';

if($acao == 'busca_campos_default'){

    $id_integracao = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
	$tb_integracao_campos_default = DBRead('', 'tb_integracao_campos_default', "WHERE id_integracao = '".$id_integracao[0]['id_integracao']."'");

	$dados = array();
	foreach($tb_integracao_campos_default as $key => $conteudo){
		$valores_default = DBRead('', 'tb_integracao_valores_default a', "INNER JOIN tb_integracao_campos_default b ON a.id_integracao_campos_default = b.id_integracao_campos_default WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND a.id_integracao_campos_default = '" . $tb_integracao_campos_default[$key]['id_integracao_campos_default'] . "' AND a.classificacao = '$classificacao'");

		//var_dump($valores_default);

		if($valores_default){
			foreach ($valores_default as $k => $c) {
				if($dados[$key]['id_integracao_campos_default'] == 10){
					$dados[$key]['id_integracao_campos_default'] = $valores_default[$k]['id_integracao_campos_default'];
					$dados[$key]['descricao_campo'] = $valores_default[$k]['descricao_campo'];
					$dados[$key]['codigo_campo'] = $valores_default[$k]['codigo_campo'];
					$dados[$key]['value_default'] = $valores_default[$k]['value_default'];
				}
				if($dados[$key]['id_integracao_campos_default'] == 12){
					$dados[$key]['id_integracao_campos_default'] = $valores_default[$k]['id_integracao_campos_default'];
					$dados[$key]['descricao_campo'] = $valores_default[$k]['descricao_campo'];
					$dados[$key]['codigo_campo'] = $valores_default[$k]['codigo_campo'];
					$dados[$key]['value_default'] = $valores_default[$k]['value_default'];
				}
				if($dados[$key]['id_integracao_campos_default'] == 13){
					$dados[$key]['id_integracao_campos_default'] = $valores_default[$k]['id_integracao_campos_default'];
					$dados[$key]['descricao_campo'] = $valores_default[$k]['descricao_campo'];
					$dados[$key]['codigo_campo'] = $valores_default[$k]['codigo_campo'];
					$dados[$key]['value_default'] = $valores_default[$k]['value_default'];
				}
				if($dados[$key]['id_integracao_campos_default'] == 14){
					$dados[$key]['id_integracao_campos_default'] = $valores_default[$k]['id_integracao_campos_default'];
					$dados[$key]['descricao_campo'] = $valores_default[$k]['descricao_campo'];
					$dados[$key]['codigo_campo'] = $valores_default[$k]['codigo_campo'];
					$dados[$key]['value_default'] = $valores_default[$k]['value_default'];
				}
				if($dados[$key]['id_integracao_campos_default'] == 16){
					$dados[$key]['id_integracao_campos_default'] = $valores_default[$k]['id_integracao_campos_default'];
					$dados[$key]['descricao_campo'] = $valores_default[$k]['descricao_campo'];
					$dados[$key]['codigo_campo'] = $valores_default[$k]['codigo_campo'];
					$dados[$key]['value_default'] = $valores_default[$k]['value_default'];
				}
				if($dados[$key]['id_integracao_campos_default'] == 17){
					$dados[$key]['id_integracao_campos_default'] = $valores_default[$k]['id_integracao_campos_default'];
					$dados[$key]['descricao_campo'] = $valores_default[$k]['descricao_campo'];
					$dados[$key]['codigo_campo'] = $valores_default[$k]['codigo_campo'];
					$dados[$key]['value_default'] = $valores_default[$k]['value_default'];
				}
				if ($dados[$key]['id_integracao_campos_default'] == 18) {
					$dados[$key]['id_integracao_campos_default'] = $valores_default[$k]['id_integracao_campos_default'];
					$dados[$key]['descricao_campo'] = $valores_default[$k]['descricao_campo'];
					$dados[$key]['codigo_campo'] = $valores_default[$k]['codigo_campo'];
					$dados[$key]['value_default'] = $valores_default[$k]['value_default'];
				}
				if ($dados[$key]['id_integracao_campos_default'] == 19) {
					$dados[$key]['id_integracao_campos_default'] = $valores_default[$k]['id_integracao_campos_default'];
					$dados[$key]['descricao_campo'] = $valores_default[$k]['descricao_campo'];
					$dados[$key]['codigo_campo'] = $valores_default[$k]['codigo_campo'];
					$dados[$key]['value_default'] = $valores_default[$k]['value_default'];
				}
			}
		}
	}
	if(empty($dados)){
		echo json_encode($tb_integracao_campos_default);
	}else{
		echo json_encode($dados);
	}
}

if($acao == 'salva_campos') {

	$integracao_valores_default = DBRead('', 'tb_integracao_valores_default', "WHERE id_contrato_plano_pessoa = '$contrato' AND id_subarea_problema = '$id_subarea_problema'");

	if ($integracao_valores_default) {
		echo json_encode("2");
		die();
	}

	if($id_area_problema == '-1'){

		//salva todas as subareas de todas as areas problema
		$area_problema = DBRead('', 'tb_area_problema');

		foreach($area_problema as $key => $area){
			$subarea_problema = DBRead('', 'tb_subarea_problema', "WHERE id_area_problema = '".$area_problema[$key]['id_area_problema']."'");
			$id_area = $area_problema[$key]['id_area_problema'];

			foreach($subarea_problema as $key => $subarea){

				//excluiCampos($contrato, $subarea_problema[$key]['id_subarea_problema']);

				salvaCampos($id_area, $assunto, $setor, $filial, $tecnico, $departamento, $processo, $prioridade, $origem, $classificacao, $subarea_problema[$key]['id_subarea_problema'], $contrato, $id_usuario);
			}
		}
	}else{

		salvaCampos($id_area_problema, $assunto, $setor, $filial, $tecnico, $departamento, $processo, $prioridade, $origem, $classificacao, $id_subarea_problema, $contrato, $id_usuario);
	}

	echo json_encode("1");
}

if ($acao == 'busca_valores') {

	//$valores_default = DBRead('', 'tb_integracao_valores_default', "WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND classificacao = '$classificacao'");
	$valores_default = DBRead('', 'tb_integracao_valores_default a', "INNER JOIN tb_integracao_campos_default b ON a.id_integracao_campos_default = b.id_integracao_campos_default WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND a.id_subarea_problema = '$id_subarea_problema'");

	echo json_encode($valores_default);
}

if ($acao == 'exclui_campos') {
	excluiCampos($id_contrato_plano_pessoa, $id_subarea_problema, $id_usuario);
}

if ($acao == 'salva_campos_atendimento') {

	salvarCamposAtendimento($id_atendimento, $classificacao, $assunto, $prioridade, $setor, $filial, $tecnico, $origem, $contrato, $login, $processo, $evento, $id_os, $flag_pendencia);
}

function excluiCampos($id_contrato_plano_pessoa, $id_subarea_problema, $id_usuario){

	//Se true, os valores antigos são deletados do banco
	if($id_subarea_problema && $id_contrato_plano_pessoa && $id_usuario){

		registraLog('Exclusão para cadastrar um novo valor default.','e','tb_integracao_valores_default',$id_subarea_problema,"id_subarea_problema: $id_subarea_problema | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_usuario: $id_usuario");

		DBDelete('', 'tb_integracao_valores_default', "id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND id_subarea_problema = '$id_subarea_problema'");

		$alert = ('Item excluído com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=integracao-campos-default&contrato=$id_contrato_plano_pessoa");

	} else {
		$alert = ('Não foi possível excluir o item!','d');
        header("location: /api/iframe?token=$request->token&view=integracao-campos-default&contrato=$id_contrato_plano_pessoa");
	}
}


function salvaCampos($id_area_problema, $assunto, $setor, $filial, $tecnico, $departamento, $processo, $prioridade, $origem, $classificacao, $id_subarea_problema, $contrato, $id_usuario){

	//Verifica se já existe valores default para determinada id subarea problema
	/* $integracao_valores_default = DBRead('', 'tb_integracao_valores_default', "WHERE id_contrato_plano_pessoa = '$contrato' AND id_subarea_problema = '$id_subarea_problema'"); */

	//Se true, os valores antigos são deletados do banco
	/* if($integracao_valores_default){

		$id_integracao_valores_default_delete = $integracao_valores_default[0]['id_integracao_valores_default'];
		$value_default_delete = $integracao_valores_default[0]['value_default'];
		$id_area_problema_delete = $integracao_valores_default[0]['id_area_problema'];
		$id_subarea_problema_delete = $integracao_valores_default[0]['id_subarea_problema'];
		$id_contrato_plano_pessoa_delete = $integracao_valores_default[0]['id_contrato_plano_pessoa'];
		$id_integracao_campos_default_delete = $integracao_valores_default[0]['id_integracao_campos_default'];

		registraLog('Exclusão para cadastrar um novo valor default.','e','tb_integracao_valores_default',$id_integracao_valores_default_delete,"value_default: $value_default_delete | id_area_problema: $id_area_problema_delete | id_subarea_problema: $id_subarea_problema_delete | id_contrato_plano_pessoa: $id_contrato_plano_pessoa_delete | id_integracao_campos_default: $id_integracao_campos_default_delete | id_usuario: $id_usuario");

		DBDelete('', 'tb_integracao_valores_default', "id_contrato_plano_pessoa = '$contrato' AND id_subarea_problema = '$id_subarea_problema'");
	} */

	$texto_classificacao = (isset($_GET['texto_classificacao'])) ? $_GET['texto_classificacao'] : '';
	$texto_assunto = (isset($_GET['texto_assunto'])) ? $_GET['texto_assunto'] : '';
	$texto_setor = (isset($_GET['texto_setor'])) ? $_GET['texto_setor'] : '';
	$texto_filial = (isset($_GET['texto_filial'])) ? $_GET['texto_filial'] : '';
	$texto_tecnico = (isset($_GET['texto_tecnico'])) ? $_GET['texto_tecnico'] : '';
	$texto_departamento = (isset($_GET['texto_departamento'])) ? $_GET['texto_departamento'] : '';
	$texto_processo = (isset($_GET['texto_processo'])) ? $_GET['texto_processo'] : '';
	$texto_prioridade = (isset($_GET['texto_prioridade'])) ? $_GET['texto_prioridade'] : '';
	$texto_origem = (isset($_GET['texto_origem'])) ? $_GET['texto_origem'] : '';
	$texto_area_problema = (isset($_GET['texto_area_problema'])) ? $_GET['texto_area_problema'] : '';
	$texto_sub_area_problema = (isset($_GET['texto_sub_area_problema'])) ? $_GET['texto_sub_area_problema'] : '';

	if($assunto) {
		$dados = array(
			'value_default' => $assunto,
			'id_area_problema' => $id_area_problema,
			'id_subarea_problema' => $id_subarea_problema,
			'id_contrato_plano_pessoa' => $contrato,
			'id_integracao_campos_default' => '2',
		);

		$insertID = DBCreate('', 'tb_integracao_valores_default', $dados, true);
		registraLog('Inserção de valores default integração.','i','tb_integracao_valores_default',$insertID,"value_default: $assunto | id_area_problema: $id_area_problema | id_subarea_problema: $id_subarea_problema | id_contrato_plano_pessoa: $contrato | id_integracao_campos_default: 2 | id_usuario: $id_usuario");

		// QUADRO INFORMATIVO HISTORICO
		inserirHistorico($contrato, 1, "Inserção de valores default integração", "área do problema: $texto_area_problema | subárea do problema: $texto_sub_area_problema | assunto: $texto_assunto", 6);
	}

	if($setor) {
		$dados = array(
			'value_default' => $setor,
			'id_area_problema' => $id_area_problema,
			'id_subarea_problema' => $id_subarea_problema,
			'id_contrato_plano_pessoa' => $contrato,
			'id_integracao_campos_default' => '7',
		);

		$insertID = DBCreate('', 'tb_integracao_valores_default', $dados, true);
		registraLog('Inserção de valores default integração.','i','tb_integracao_valores_default',$insertID,"value_default: $setor | id_area_problema: $id_area_problema | id_subarea_problema: $id_subarea_problema | id_contrato_plano_pessoa: $contrato | id_integracao_campos_default: 7 | id_usuario: $id_usuario");

		// QUADRO INFORMATIVO HISTORICO
		inserirHistorico($contrato, 1, "Inserção de valores default integração", "área do problema: $texto_area_problema | subárea do problema: $texto_sub_area_problema | setor: $texto_setor", 6);
	}

	if($filial) {
		$dados = array(
			'value_default' => $filial,
			'id_area_problema' => $id_area_problema,
			'id_subarea_problema' => $id_subarea_problema,
			'id_contrato_plano_pessoa' => $contrato,
			'id_integracao_campos_default' => '3',
		);

		$insertID = DBCreate('', 'tb_integracao_valores_default', $dados, true);
		registraLog('Inserção de valores default integração.','i','tb_integracao_valores_default',$insertID,"value_default: $filial | id_area_problema: $id_area_problema | id_subarea_problema: $id_subarea_problema | id_contrato_plano_pessoa: $contrato | id_integracao_campos_default: 3 | id_usuario: $id_usuario");

		// QUADRO INFORMATIVO HISTORICO
		inserirHistorico($contrato, 1, "Inserção de valores default integração", "área do problema: $texto_area_problema | subárea do problema: $texto_sub_area_problema | filial: $texto_filial", 6);
	}

	if($tecnico) {
		$dados = array(
			'value_default' => $tecnico,
			'id_area_problema' => $id_area_problema,
			'id_subarea_problema' => $id_subarea_problema,
			'id_contrato_plano_pessoa' => $contrato,
			'id_integracao_campos_default' => '4',
		);

		$insertID = DBCreate('', 'tb_integracao_valores_default', $dados, true);
		registraLog('Inserção de valores default integração.','i','tb_integracao_valores_default',$insertID,"value_default: $tecnico | id_area_problema: $id_area_problema | id_subarea_problema: $id_subarea_problema | id_contrato_plano_pessoa: $contrato | id_integracao_campos_default: 4 | id_usuario: $id_usuario");

		// QUADRO INFORMATIVO HISTORICO
		inserirHistorico($contrato, 1, "Inserção de valores default integração", "área do problema: $texto_area_problema | subárea do problema: $texto_sub_area_problema | técnico: $texto_tecnico", 6);
	}

	if($departamento) {
		$dados = array(
			'value_default' => $departamento,
			'id_area_problema' => $id_area_problema,
			'id_subarea_problema' => $id_subarea_problema,
			'id_contrato_plano_pessoa' => $contrato,
			'id_integracao_campos_default' => '8',
		);

		$insertID = DBCreate('', 'tb_integracao_valores_default', $dados, true);
		registraLog('Inserção de valores default integração.','i','tb_integracao_valores_default',$insertID,"value_default: $departamento | id_area_problema: $id_area_problema | id_subarea_problema: $id_subarea_problema | id_contrato_plano_pessoa: $contrato | id_integracao_campos_default: 8 | id_usuario: $id_usuario");

		// QUADRO INFORMATIVO HISTORICO
		inserirHistorico($contrato, 1, "Inserção de valores default integração", "área do problema: $texto_area_problema | subárea do problema: $texto_sub_area_problema | departamento: $texto_departamento", 6);
	}

	if($processo) {
		$dados = array(
			'value_default' => $processo,
			'id_area_problema' => $id_area_problema,
			'id_subarea_problema' => $id_subarea_problema,
			'id_contrato_plano_pessoa' => $contrato,
			'id_integracao_campos_default' => '9',
		);

		$insertID = DBCreate('', 'tb_integracao_valores_default', $dados, true);
		registraLog('Inserção de valores default integração.','i','tb_integracao_valores_default',$insertID,"value_default: $processo | id_area_problema: $id_area_problema | id_subarea_problema: $id_subarea_problema | id_contrato_plano_pessoa: $contrato | id_integracao_campos_default: 9 | id_usuario: $id_usuario");

		// QUADRO INFORMATIVO HISTORICO
		inserirHistorico($contrato, 1, "Inserção de valores default integração", "área do problema: $texto_area_problema | subárea do problema: $texto_sub_area_problema | processo: $texto_processo", 6);
	}

	if ($prioridade) {

		if($prioridade == 'N' && $classificacao == '2'){
			$prioridade = 'M';
		}

		$dados = array(
			'value_default' => $prioridade,
			'id_area_problema' => $id_area_problema,
			'id_subarea_problema' => $id_subarea_problema,
			'id_contrato_plano_pessoa' => $contrato,
			'id_integracao_campos_default' => '5',
		);

		$insertID = DBCreate('', 'tb_integracao_valores_default', $dados, true);
		registraLog('Inserção de valores default integração.','i','tb_integracao_valores_default',$insertID,"value_default: $prioridade | id_area_problema: $id_area_problema | id_subarea_problema: $id_subarea_problema | id_contrato_plano_pessoa: $contrato | id_integracao_campos_default: 5 | id_usuario: $id_usuario");

		// QUADRO INFORMATIVO HISTORICO
		inserirHistorico($contrato, 1, "Inserção de valores default integração", "área do problema: $texto_area_problema | subárea do problema: $texto_sub_area_problema | prioridade: $texto_prioridade", 6);
	}

	if ($origem) {
		$dados = array(
			'value_default' => $origem,
			'id_area_problema' => $id_area_problema,
			'id_subarea_problema' => $id_subarea_problema,
			'id_contrato_plano_pessoa' => $contrato,
			'id_integracao_campos_default' => '6',
		);

		$insertID = DBCreate('', 'tb_integracao_valores_default', $dados, true);
		registraLog('Inserção de valores default integração.','i','tb_integracao_valores_default',$insertID,"value_default: $origem | id_area_problema: $id_area_problema | id_subarea_problema: $id_subarea_problema | id_contrato_plano_pessoa: $contrato | id_integracao_campos_default: 6 | id_usuario: $id_usuario");

		// QUADRO INFORMATIVO HISTORICO
		inserirHistorico($contrato, 1, "Inserção de valores default integração", "área do problema: $texto_area_problema | subárea do problema: $texto_sub_area_problema | origem: $texto_origem", 6);
	}

	if ($classificacao) {
		$dados = array(
			'value_default' => $classificacao,
			'id_area_problema' => $id_area_problema,
			'id_subarea_problema' => $id_subarea_problema,
			'id_contrato_plano_pessoa' => $contrato,
			'id_integracao_campos_default' => '1',
		);

		$insertID = DBCreate('', 'tb_integracao_valores_default', $dados, true);
		registraLog('Inserção de valores default integração.','i','tb_integracao_valores_default',$insertID,"value_default: $classificacao | id_area_problema: $id_area_problema | id_subarea_problema: $id_subarea_problema | id_contrato_plano_pessoa: $contrato | id_integracao_campos_default: 1 | id_usuario: $id_usuario");

		// QUADRO INFORMATIVO HISTORICO
		inserirHistorico($contrato, 1, "Inserção de valores default integração", "área do problema: $texto_area_problema | subárea do problema: $texto_sub_area_problema | classificação: $texto_classificacao", 6);
	}
}

function salvarCamposAtendimento ($id_atendimento, $classificacao, $assunto, $prioridade, $setor, $filial, $tecnico, $origem, $contrato, $login, $processo, $evento, $id_os, $flag_pendencia) {

	if (($classificacao != '' || $assunto !='' || $prioridade !='' || $setor !='' || $filial !='' || $tecnico !='' || $origem !='' || $contrato !='' || $login !='' || $processo !='' || $evento !='' || $id_os !='') && $id_atendimento !='') {

		$data = getDataHora();

		if ($flag_pendencia == 1) {
			$id_usuario = $_SESSION['id_usuario'];

		} else {
			$id_usuario = NULL;
		} 

		$dados = array(
			'id_atendimento' => $id_atendimento,
			'classificacao' => $classificacao,
			'assunto' => $assunto,
			'prioridade' => $prioridade,
			'setor' => $setor,
			'filial' => $filial,
			'tecnico' => $tecnico,
			'origem' => $origem,
			'contrato' => $contrato,
			'login' => $login,
			'processo' => $processo,
			'evento' => $evento,
			'id_sistema_de_gestao' => $id_os,
			'flag_pendencia' => $flag_pendencia,
			'id_usuario_pendencia' => $id_usuario,
			'data' => $data
		);

		$insertID = DBCreate('', 'tb_atendimento_valores_integracao', $dados, true);
		registraLog('Inserção de atendimento valores.','i','tb_atendimento_valores_integracao',$insertID,"id_atendimento: $id_atendimento | classificacao: $classificacao | assunto: $assunto | prioridade: $prioridade | setor: $setor | filial: $filial | tecnico: $tecnico | origem: $origem | contrato: $contrato | login: $login | processo: $processo | evento: $evento | id_sistema_de_gestao: $id_os | flag_pendencia: $flag_pendencia | id_usuario_pendencia: $id_usuario | data: $data");
	}
}

?>