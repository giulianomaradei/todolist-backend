<?php
require_once(__DIR__."/System.php");

$id = (isset($_POST['parametros'])) ? $_POST['parametros'] : '';

$chamado_acao = DBRead('', 'tb_chamado_acao', "WHERE id_chamado = $id");

echo '<table class="table table-striped" style="margin-bottom: -15px;">';
echo '<tbody>';

echo '<tr>';

	echo '<th><strong>Usuário</strong></th>';
	echo '<th><strong>Tempo</strong></th>';
	echo '<th><strong>Ação</strong></th>';
	

echo '</tr>';
$aux_tempo = '';
foreach ($chamado_acao as $acao) {

	if($acao['acao_painel'] == 1){
		$dados_usuario = DBRead('', 'tb_usuario_painel a',"INNER JOIN tb_pessoa b ON a.id_pessoa_usuario = b.id_pessoa WHERE a.id_usuario_painel = '".$acao['id_usuario_acao']."'");
		$nome_usuario = "<span style='color: #FF4000;'>".$dados_usuario[0]['nome']." (Painel do Cliente)</span>";
	}else{
		$dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$acao['id_usuario_acao']."'");
		$nome_usuario = $dados_usuario[0]['nome'];
	}

	echo '<tr>';

	echo '<td>'.$nome_usuario.'</td>';
	
	if($acao['tempo'] == '1' ){
		$tempo = $acao['tempo']." minuto";
	}else{
		$tempo = $acao['tempo']." minutos";
	}

	echo '<td>'.$tempo.'</td>';                                                                               
                                                    
            if($acao['acao'] == 'nota_interna'){
                $acao_descricao = "Nota interna adicionada";
            }else if($acao['acao'] == 'encerrar'){
                $acao_descricao = "Chamado encerrado";
            }else if($acao['acao'] == 'encaminhar'){
                $acao_descricao = "Troca de responsável";
            }else if($acao['acao'] == 'desbloquear'){
                $acao_descricao = "Chamado desbloqueado";
            }else if($acao['acao'] == 'bloquear'){
                $acao_descricao = "Chamado bloqueado";
            }else if($acao['acao'] == 'reabrir'){
                $acao_descricao = "Chamado reaberto";
            }else if($acao['acao'] == 'gerenciar'){
                $acao_descricao = "Gerenciamento dos envolvidos";
            }else if($acao['acao'] == 'nota_geral'){
                $acao_descricao = "Nota adicionada";
            }else if($acao['acao'] == 'alterar'){
                $acao_descricao = "Alteração do Chamado";
            }else{
                $acao_descricao = "Criação do chamado";
            }            

	echo '<td>'.$acao_descricao.'</td>';

	echo '</tr>';
	
	$aux_tempo = $aux_tempo + (int)$acao['tempo'];

}
	
	echo '</tbody>';

	if($aux_tempo == '1' ){
		$aux_tempo = $aux_tempo." minuto";
	}else{
		$aux_tempo = $aux_tempo." minutos";
	}
	
	echo "<tfoot>";
					
		echo '<tr>';
			echo '<th>Total</th>';
			echo '<th>'.$aux_tempo.'</th>';
			echo '<th> </th>';			
		echo '</tr>';

	echo "</tfoot> ";

	echo '</table>';
	echo "<br>";
?>