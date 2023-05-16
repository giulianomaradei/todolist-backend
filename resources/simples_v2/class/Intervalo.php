<?php
require_once(__DIR__."/System.php");


	$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
	$acao = (!empty($_POST['acao'])) ? $_POST['acao'] : '';

	if($acao == 'verificar'){
		$data_inicial = addslashes($parametros['data_inicial']);

		$id_usuario = addslashes($parametros['id_usuario']);

		$data_de_hoje = explode(' ', $data_inicial);
		$dia_de_hoje = explode('-', $data_de_hoje[0]);

		$data_de_hoje = $dia_de_hoje[0]."-".sprintf('%02d', $dia_de_hoje[1])."-".sprintf('%02d', $dia_de_hoje[2]);

		$dados = DBRead('', 'tb_intervalo',"WHERE id_usuario = '".$id_usuario."' AND data_inicial LIKE '".$data_de_hoje."%' AND data_inicial <= '".getDataHora()."'");

		if($dados){
			$resultado = 1;

			$dados2 = DBRead('', 'tb_intervalo',"WHERE id_usuario = '".$id_usuario."' AND data_final IS NULL AND data_inicial <= '".getDataHora()."'");
			if($dados2){
				$resultado = 2;
			}
		}else{
			$resultado = 0;
		}
		echo json_encode($resultado);


	}else if($acao == 'inserir_inicial'){
		$data_inicial = addslashes($parametros['data_inicial']);
		$id_usuario = addslashes($parametros['id_usuario']);
        $id_usuario_liberou = $_SESSION['id_usuario'];
		$dados = array(
		    'data_inicial' => $data_inicial,
		    'id_usuario' => $id_usuario,
		    'id_usuario_liberou' => $id_usuario_liberou
		);

		$insertID = DBCreate('', 'tb_intervalo', $dados, true);
	    registraLog('Inserção de intervalo.','a','tb_intervalo',$id_usuario,"data_inicial: $data_inicial | id_usuario: $id_usuario | id_usuario_liberou: $id_usuario_liberou");

	}else if($acao == 'verifica_liberacao'){

		$id_usuario = $request->user()->id_usuario;

		$dados2 = DBRead('', 'tb_intervalo',"WHERE id_usuario = '".$id_usuario."' AND visto = 0 AND data_inicial <= '".getDataHora()."'");

		if($dados2){
			$dados = array(
			    'visto' => '1',
			);
			DBUpdate('', 'tb_intervalo', $dados, "id_usuario = '".$id_usuario."' AND visto = 0");
	        registraLog('Alteração da visualização do intervalo.','a','tb_intervalo',$id_usuario,"visto: 1");
			$resultado = 1;

		}else{

			$resultado = 0;
		}

		echo json_encode($resultado);

	}else if($acao == 'verifica_intervalo_aberto'){

		$dados_intervalo = DBRead('', 'tb_intervalo',"WHERE data_inicial IS NOT NULL AND data_inicial <= '".getDataHora()."' AND data_final IS NULL");
	    if($dados_intervalo){

	    	foreach ($dados_intervalo as $conteudo_intervalo) {

		        $data_inicial    = new DateTime($conteudo_intervalo['data_inicial']);
		        $data_agora      = new DateTime(getDataHora());

		        $diff = $data_inicial->diff($data_agora);

				echo $diff ->format("%Y-%M-%D %H:%I:%s");

				if($diff ->format("%Y-%M-%D %H:%I:%s") >= '00-00-00 01:00:00'){
					$data_final = date('Y-m-d H:i:s', strtotime("+20 minutes",strtotime($conteudo_intervalo['data_inicial'])));
					$dados = array(
					    'data_final' => $data_final
					);

					DBUpdate('', 'tb_intervalo', $dados, "id_intervalo = '".$conteudo_intervalo['id_intervalo']."'");
			        registraLog('Alteração/Inserção da data final no intervalo.','a','tb_intervalo',$conteudo_intervalo['id_intervalo'],"data_final: $data_final");
				}
	    	}
	    }
	//--------------------------------------
	}else if($acao == 'verifica_agendamento'){

		$id_usuario = addslashes($parametros['id_usuario']);

		$dados = DBRead('', 'tb_intervalo',"WHERE id_usuario = '".$id_usuario."' AND visto = 0 AND data_inicial > '".getDataHora()."'");

		if($dados){

			$resultado = 1;

		}else{

			$resultado = 0;
		}

		echo json_encode($resultado);

	}else if($acao == 'cadastrar_agendamento'){

		$id_usuario = addslashes($parametros['id_usuario']);
		$data_agendamento = addslashes($parametros['data_agendamento']);
		$hora_agendamento = addslashes($parametros['hora_agendamento']);
		$data_inicial = $data_agendamento." ".$hora_agendamento."00";
		$data_inicial = converteDataHora($data_inicial);

		$dados = DBRead('', 'tb_intervalo',"WHERE id_usuario = '".$id_usuario."' AND visto = 0 AND data_inicial > '".getDataHora()."'");

		if($dados){
			$dados2 = array(
			    'data_inicial' => $data_inicial
			);

			DBUpdate('', 'tb_intervalo', $dados2, "id_intervalo = '".$dados[0]['id_intervalo']."'");
	        registraLog('Alteração da agendamento de intervalo.','a','tb_intervalo',$dados[0]['id_intervalo'],"data_final: $data_final");
		}else{

			$id_usuario_liberou = $_SESSION['id_usuario'];
			$dados2 = array(
			    'data_inicial' => $data_inicial,
			    'id_usuario' => $id_usuario,
			    'id_usuario_liberou' => $id_usuario_liberou
			);

			$insertID = DBCreate('', 'tb_intervalo', $dados2, true);
	    	registraLog('Inserção de intervalo.','a','tb_intervalo',$id_usuario,"data_inicial: $data_inicial | id_usuario: $id_usuario | id_usuario_liberou: $id_usuario_liberou");
		}
		echo json_encode(converteDataHora($data_inicial));
	//--------------------------------------
	}else{

		$data_final = addslashes($parametros['data_final']);
		$id_usuario = addslashes($parametros['id_usuario']);

		$verificacao = DBRead('', 'tb_intervalo',"WHERE id_usuario = '".$id_usuario."' AND data_final IS NULL");
		if($verificacao){
			$dados = array(
			    'data_final' => $data_final,
			);
			DBUpdate('', 'tb_intervalo', $dados, "id_usuario = '".$id_usuario."' AND data_final IS NULL");
	        registraLog('Alteração/Inserção da data final no intervalo.','a','tb_intervalo',$id_usuario,"data_final: $data_final");
		}

   	}



?>
