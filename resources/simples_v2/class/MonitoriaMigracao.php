<?php
require_once(__DIR__."/System.php");


// Migracao de dados monitoria
$dados_monitoria_mes_migracao = DBRead('', 'tb_monitoria_mes');

foreach ($dados_monitoria_mes_migracao as $conteudo_migracao) {

	$id_monitoria_mes_migracao = $conteudo_migracao['id_monitoria_mes'];
	echo 'id_monitoria_mes: '.$id_monitoria_mes_migracao.'<br>';

	$data_referencia_migracao = $conteudo_migracao['data_referencia'];
	echo 'data_referencia: '.$data_referencia_migracao.'<br><br>';
	
	$dados_audios_migracao = DBRead('', 'tb_monitoria_avaliacao_audio', "WHERE data_referencia = '$data_referencia_migracao'", 'id_monitoria_avaliacao_audio, data_referencia');

	foreach ($dados_audios_migracao as $audios_migracao) {

		$id_monitoria_avaliacao_audio_migracao = $audios_migracao['id_monitoria_avaliacao_audio'];
		echo 'id_monitoria_avaliacao_audio: '.$id_monitoria_avaliacao_audio_migracao.' - data_referencia: '.$audios_migracao['data_referencia'].'<br>';

		$dados_migracao = array(
			'id_monitoria_mes' => $id_monitoria_mes_migracao
        );
        
        if ($id_monitoria_avaliacao_audio_migracao) {
            DBUpdate('', 'tb_monitoria_avaliacao_audio', $dados_migracao, "id_monitoria_avaliacao_audio = $id_monitoria_avaliacao_audio_migracao");
        }
    }
    
    $dados_resultado_migracao = DBRead('', 'tb_monitoria_resultado', "WHERE data_referencia = '$data_referencia_migracao'", 'id_monitoria_resultado, id_usuario, data_referencia');

	foreach ($dados_resultado_migracao as $resultado_migracao) {

		$id_monitoria_resultado_migracao = $resultado_migracao['id_monitoria_resultado'];
		$id_usuario_resultado_migracao = $resultado_migracao['id_usuario'];
		echo 'id_monitoria_resultado: '.$id_monitoria_resultado_migracao.' - data_referencia: '.$resultado_migracao['data_referencia'].' - id_usuario: '.$id_usuario_resultado_migracao.'<br>';

		$dados_migracao = array(
			'id_monitoria_mes' => $id_monitoria_mes_migracao
		);

        if ($id_monitoria_resultado_migracao) {
            DBUpdate('', 'tb_monitoria_resultado', $dados_migracao, "id_monitoria_resultado = $id_monitoria_resultado_migracao");
        }
    }
    
    $dados_plano_acao_migracao = DBRead('', 'tb_monitoria_mes_plano_acao_chamado', "WHERE data_referencia = '$data_referencia_migracao'", 'id_monitoria_mes_plano_acao_chamado, data_referencia');

	foreach ($dados_plano_acao_migracao as $plano_acao_migracao) {

		$id_monitoria_plano_acao_migracao = $plano_acao_migracao['id_monitoria_mes_plano_acao_chamado'];
		echo 'id_monitoria_mes_plano_acao_chamado: '.$id_monitoria_plano_acao_migracao.' - data_referencia: '.$plano_acao_migracao['data_referencia'].'<br>';

		$dados_migracao = array(
			'id_monitoria_mes' => $id_monitoria_mes_migracao
		);

		if ($id_monitoria_plano_acao_migracao) {
			DBUpdate('', 'tb_monitoria_mes_plano_acao_chamado', $dados_migracao, "id_monitoria_mes_plano_acao_chamado = $id_monitoria_plano_acao_migracao");
		}
	}

	echo '<br>###################################################################################<br><br>';
}

echo "Finalizado!";
// end migrao de dados monitoria