<?php
require_once(__DIR__."/System.php");

	$id = (isset($_POST['parametros'])) ? $_POST['parametros'] : '';

	$dados = DBRead('', 'tb_chamado_acao a', "
	INNER JOIN tb_usuario b ON b.id_usuario = a.id_usuario_responsavel
		INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa 
			INNER JOIN tb_chamado g ON a.id_chamado = g.id_chamado
				INNER JOIN tb_chamado_status j ON a.id_chamado_status = j.id_chamado_status
					INNER JOIN tb_chamado_origem k ON g.id_chamado_origem = k.id_chamado_origem
						WHERE id_chamado_acao = '$id'", "a.*, DATE_FORMAT(a.data, '%d/%m/%Y %H:%i:%s') AS data_formatada, c.nome AS responsavel, k.descricao as origem, g.id_chamado, a.descricao AS conteudo, j.descricao AS descricao_status, g.id_chamado_origem, a.arquivo");


	// INNER JOIN tb_usuario e ON e.id_usuario = a.id_usuario_acao
	// 	INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa

	// INNER JOIN tb_usuario h ON g.id_usuario_remetente = h.id_usuario
	// 	INNER JOIN tb_pessoa i ON i.id_pessoa = h.id_pessoa
	//f.nome AS feito_por
	//i.nome AS remetente,

	if($dados[0]['acao_painel'] == 1){
		$dados_acao_painel = DBRead('', 'tb_usuario_painel a', " INNER JOIN tb_pessoa b ON a.id_pessoa_usuario = b.id_pessoa WHERE a.id_usuario_painel = '".$dados[0]['id_usuario_acao']."' ", " b.nome as nome_usuario_acao");

		$nome_usuario_acao = $dados_acao_painel[0]['nome_usuario_acao']." (Painel do Cliente)";
	}else{
		$dados_acao_painel = DBRead('', 'tb_usuario a', " INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$dados[0]['id_usuario_acao']."' ", " b.nome as nome_usuario_acao");

		$nome_usuario_acao = $dados_acao_painel[0]['nome_usuario_acao'];
	}

	$id_chamado = $dados[0]['id_chamado'];

	$contrato_plano_pessoa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE b.status = '1' AND a.status = '1' AND a.id_contrato_plano_pessoa = '".$dados[0]['id_contrato_plano_pessoa']."'", "b.id_pessoa, a.id_contrato_plano_pessoa, a.nome_contrato, b.nome, b.cpf_cnpj, b.razao_social, c.cod_servico AS 'servico', c.nome AS 'plano'");

    if($contrato_plano_pessoa[0]['nome_contrato']){
        $nome_contrato = " (".$contrato_plano_pessoa[0]['nome_contrato'].") ";
    }else{
        $nome_contrato = "";
    }

    if($contrato_plano_pessoa[0]['nome']){
        $contrato = $contrato_plano_pessoa[0]['nome'] . " ". $nome_contrato ." - " . getNomeServico($contrato_plano_pessoa[0]['servico']) . " - " . $contrato_plano_pessoa[0]['plano'] . " (" . $contrato_plano_pessoa[0]['id_contrato_plano_pessoa'] . ")";
    }else{
        $contrato = "";
    }

	if($dados[0]['acao_painel'] == 1){
		$descricao = nl2br($dados[0]['descricao']);
	}else{
		$descricao = $dados[0]['descricao'];
	}


if($dados[0]['acao'] == "criacao"){

	echo '<table class="table table-striped" style="margin-bottom: -15px;">';
    echo '<tbody>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Feito por:</strong></td>';
	echo '<td>'.$nome_usuario_acao.'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Tempo:</strong></td>';
	echo '<td>'.$dados[0]['tempo'].' minutos</td>';
	echo '</tr>';      
	echo '<tr>';
	echo '<td class="td-table"><strong>Data:</strong></td>';
	echo '<td>'.$dados[0]['data_formatada'].'</td>';
	echo '</tr>';                    
	echo '<tr>';
	echo '<td class="td-table" id="tr-origem"><strong>Origem:</strong></td>';
	echo '<td>'.$dados[0]['origem'].'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Status:</strong></td>';
	echo '<td>'.$dados[0]['descricao_status'].'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Data do prazo:</strong></td>';
	echo '<td>'.converteDataHora($dados[0]['prazo_encerramento']).'</td>';
	echo '</tr>';
	echo '<tr>';

	$visibilidade = array(
		"1" => "Público",
		"2" => "Privado"
	  );

	echo '<td class="td-table"><strong>Visibilidade:</strong></td>';
	echo '<td>'.$visibilidade[$dados[0]['visibilidade']].'</td>';
	echo '</tr>';
	echo '<tr>';                   
	echo '<td class="td-table"><strong>Responsável:</strong></td>';
	echo '<td>'.$dados[0]['responsavel'].'</td>';
	echo '</tr>';

	$categorias = DBRead('', 'tb_chamado_categoria a', "INNER JOIN tb_categoria b ON a.id_categoria = b.id_categoria WHERE a.id_chamado = '$id_chamado' ", 'b.nome');

	if($categorias){
		foreach($categorias as $c){
			$nome_categoria .= $c['nome']."<br>";
		}
	}else{
		$nome_categoria = '';
	}

	$nome_categoria = substr($nome_categoria, 0, strlen($nome_categoria) - 2);

	echo '<tr>';
	echo '<td class="td-table"><strong>Categoria:</strong></td>';
	echo '<td>'.$nome_categoria.'</td>';
	echo '</tr>';
	echo '<tr>';

	if($contrato != ""){

		echo '<tr>';
		echo '<td class="td-table"><strong>Contrato (Cliente):</strong></td>';
		echo '<td>'.$contrato.'</td>';
		echo '</tr>';
		echo '<tr>';
	}

	$id_chamado = $dados[0]['id_chamado'];
	if($dados[0]['visibilidade'] == 1){

		$envolvidos = DBRead('', 'tb_chamado_perfil a', "INNER JOIN tb_perfil_sistema b ON a.id_perfil_sistema = b.id_perfil_sistema WHERE a.id_chamado = '$id_chamado'");

		$aux_envolvidos = '';
		foreach($envolvidos as $conteudo){

			$aux_envolvidos .= $conteudo['nome']."; <br>";
		}
		$aux_envolvidos = substr_replace($aux_envolvidos, '', -6);

	}else if($dados[0]['visibilidade'] == 2){

		$envolvidos = DBRead('', 'tb_chamado_usuario a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_chamado = '$id_chamado'");

		$aux_envolvidos = '';
		foreach($envolvidos as $conteudo){

			$aux_envolvidos .= $conteudo['nome']."; <br>";
		}
		$aux_envolvidos = substr_replace($aux_envolvidos, '', -6);
	}

	echo ' <td class="td-table"><strong>Setor(es)/Envolvido(s):</strong></td>';
	echo '<td>'.$aux_envolvidos.'</td>';
	echo '</tr>';        
	
	if($dados[0]['arquivo']){
		echo '<tr>';
		echo '<td class="td-table"><strong>Anexo:</strong></td>';
		echo "<td class='td-table'><a href='class/Chamado.php?arq=".$dados[0]['id_chamado_acao']."' title='Download'><i class='fa fa-download'></i></a></td>";
		echo '</tr>'; 
	}

	echo '</tbody>';
	echo '</table>';
	
	echo '<hr>';
	echo '<div class="row">';
    echo '<div class="col-md-12" style="margin-left: 10px;">';
    echo '<label>Descrição:</label>';
	echo '<span class="conteudo-editor" id="teste">'.$descricao.'</span>';
	echo '</div>';                      
	echo '</div>';

	?>

	<script>
		$('#teste').css('border-left', '4px solid');
        $('#teste').css('border-left-color', '#265a88');
	</script>

	<?php

}else if($dados[0]['acao'] == "nota_geral"){
	echo '<table class="table table-striped" style="margin-bottom: -15px;">';
    echo '<tbody>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Feito por:</strong></td>';
	echo '<td>'.$nome_usuario_acao.'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Tempo:</strong></td>';
	echo '<td>'.$dados[0]['tempo'].' minutos</td>';
	echo '</tr>';      
	echo '<tr>';
	echo '<td class="td-table"><strong>Data:</strong></td>';
	echo '<td>'.$dados[0]['data_formatada'].'</td>';
	echo '</tr>';               
	
	echo '<tr>';
	echo '<td class="td-table"><strong>Tipo de Visibilidade da Nota:</strong></td>';
	echo "<td class='td-table'>Belluno e Cliente</td>";
	echo '</tr>';

	if($dados[0]['arquivo']){
		echo '<tr>';
		echo '<td class="td-table"><strong>Anexo:</strong></td>';
		echo "<td class='td-table'><a href='class/Chamado.php?arq=".$dados[0]['id_chamado_acao']."' title='Download'><i class='fa fa-download'></i></a></td>";
		echo '</tr>'; 
	}
             
	echo '</tbody>';
	echo '</table>';
	
	echo '<hr>';
	echo '<div class="row">';
    echo '<div class="col-md-12" style="margin-left: 10px;">';
    echo '<label>Descrição:</label>';
	echo '<span class="conteudo-editor" id="teste">'.$descricao.'</span>';
	echo '</div>';                      
	echo '</div>';

	?>

	<script>
		$('#teste').css('border-left', '4px solid');
        $('#teste').css('border-left-color', '#5bc0de');
	</script>

	<?php

}else if($dados[0]['acao'] == "nota_interna"){
	echo '<table class="table table-striped" style="margin-bottom: -15px;">';
    echo '<tbody>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Feito por:</strong></td>';
	echo '<td>'.$nome_usuario_acao.'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Tempo:</strong></td>';
	echo '<td>'.$dados[0]['tempo'].' minutos</td>';
	echo '</tr>';      
	echo '<tr>';
	echo '<td class="td-table"><strong>Data:</strong></td>';
	echo '<td>'.$dados[0]['data_formatada'].'</td>';
	echo '</tr>';        
	
	echo '<tr>';
		echo '<td class="td-table"><strong>Tipo de Visibilidade da Nota:</strong></td>';
		echo "<td class='td-table'>Somente Belluno</td>";
	echo '</tr>'; 

	if($dados[0]['arquivo']){
		echo '<tr>';
		echo '<td class="td-table"><strong>Anexo:</strong></td>';
		echo "<td class='td-table'><a href='class/Chamado.php?arq=".$dados[0]['id_chamado_acao']."' title='Download'><i class='fa fa-download'></i></a></td>";
		echo '</tr>'; 
	}

	echo '</tbody>';
	echo '</table>';
	
	echo '<hr>';
	echo '<div class="row">';
    echo '<div class="col-md-12" style="margin-left: 10px;">';
    echo '<label>Descrição:</label>';
	echo '<span class="conteudo-editor" id="teste">'.$descricao.'</span>';
	echo '</div>';                      
	echo '</div>';

	?>

	<script>
		$('#teste').css('border-left', '4px solid');
        $('#teste').css('border-left-color', '#363636');
	</script>

	<?php

}else if($dados[0]['acao'] == "encerrar"){
	echo '<table class="table table-striped" style="margin-bottom: -15px;">';
    echo '<tbody>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Feito por:</strong></td>';
	echo '<td>'.$nome_usuario_acao.'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Tempo:</strong></td>';
	echo '<td>'.$dados[0]['tempo'].' minutos</td>';
	echo '</tr>';      
	echo '<tr>';
	echo '<td class="td-table"><strong>Data:</strong></td>';
	echo '<td>'.$dados[0]['data_formatada'].'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Status:</strong></td>';
	echo '<td>'.$dados[0]['descricao_status'].'</td>';
	echo '</tr>';        
	
	if($dados[0]['arquivo']){
		echo '<tr>';
		echo '<td class="td-table"><strong>Anexo:</strong></td>';
		echo "<td class='td-table'><a href='class/Chamado.php?arq=".$dados[0]['id_chamado_acao']."' title='Download'><i class='fa fa-download'></i></a></td>";
		echo '</tr>'; 
	}

	echo '</tbody>';
	echo '</table>';
	
	echo '<hr>';
	echo '<div class="row">';
    echo '<div class="col-md-12" style="margin-left: 10px;">';
    echo '<label>Descrição:</label>';
	echo '<span class="conteudo-editor" id="teste">'.$descricao.'</span>';
	echo '</div>';                      
	echo '</div>';

	if($dados[0]['id_chamado_status'] == 3){
	?>
		<script>
			$('#teste').css('border-left', '4px solid');
			$('#teste').css('border-left-color', '#59ba1f');
		</script>
	<?php
	}
	if($dados[0]['id_chamado_status'] == 4){
	?>
		<script>
			$('#teste').css('border-left', '4px solid');
			$('#teste').css('border-left-color', '#ba1f1f');
		</script>
	<?php
	}
	

}else if($dados[0]['acao'] == "encaminhar"){
	echo '<table class="table table-striped" style="margin-bottom: -15px;">';
    echo '<tbody>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Feito por:</strong></td>';
	echo '<td>'.$nome_usuario_acao.'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Tempo:</strong></td>';
	echo '<td>'.$dados[0]['tempo'].' minutos</td>';
	echo '</tr>';      
	echo '<tr>';
	echo '<td class="td-table"><strong>Data:</strong></td>';
	echo '<td>'.$dados[0]['data_formatada'].'</td>';
	echo '<tr>';

	$visibilidade = array(
		"1" => "Público",
		"2" => "Privado"
	  );

	echo '<td class="td-table"><strong>Visibilidade:</strong></td>';
	echo '<td>'.$visibilidade[$dados[0]['visibilidade']].'</td>';
	echo '</tr>';
	echo '<tr>';                   
	echo '<td class="td-table"><strong>Responsável:</strong></td>';
	echo '<td>'.$dados[0]['responsavel'].'</td>';
	echo '</tr>';
	
	$id_chamado = $dados[0]['id_chamado'];
	if($dados[0]['visibilidade'] == 1){

		$envolvidos = DBRead('', 'tb_chamado_perfil a', "INNER JOIN tb_perfil_sistema b ON a.id_perfil_sistema = b.id_perfil_sistema WHERE a.id_chamado = '$id_chamado'");

		$aux_envolvidos = '';
		foreach($envolvidos as $conteudo){

			$aux_envolvidos .= $conteudo['nome']."; <br>";
		}
		$aux_envolvidos = substr_replace($aux_envolvidos, '', -6);

	}else if($dados[0]['visibilidade'] == 2){

		$envolvidos = DBRead('', 'tb_chamado_usuario a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_chamado = '$id_chamado'");

		$aux_envolvidos = '';
		foreach($envolvidos as $conteudo){

			$aux_envolvidos .= $conteudo['nome']."; <br>";
		}
		$aux_envolvidos = substr_replace($aux_envolvidos, '', -6);

	}

	echo ' <td class="td-table"><strong>Setor(es)/Envolvido(s):</strong></td>';
	echo '<td>'.$aux_envolvidos.'</td>';
	echo '</tr>';
	
	if($dados[0]['arquivo']){
		echo '<tr>';
		echo '<td class="td-table"><strong>Anexo:</strong></td>';
		echo "<td class='td-table'><a href='class/Chamado.php?arq=".$dados[0]['id_chamado_acao']."' title='Download'><i class='fa fa-download'></i></a></td>";
		echo '</tr>'; 
	}
	
	echo '</tbody>';
	echo '</table>';
	
	echo '<hr>';
	echo '<div class="row">';
    echo '<div class="col-md-12" style="margin-left: 10px;">';
    echo '<label>Justificativa:</label>';
	echo '<span class="conteudo-editor" id="teste">'.$descricao.'</span>';
	echo '</div>';                      
	echo '</div>';

	?>

	<script>
		$('#teste').css('border-left', '4px solid');
        $('#teste').css('border-left-color', '#FFC125');
	</script>

	<?php

}else if($dados[0]['acao'] == "assumir"){
	echo '<table class="table table-striped" style="margin-bottom: -15px;">';
    echo '<tbody>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Feito por:</strong></td>';
	echo '<td>'.$nome_usuario_acao.'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Tempo:</strong></td>';
	echo '<td>'.$dados[0]['tempo'].' minutos</td>';
	echo '</tr>';      
	echo '<tr>';
	echo '<td class="td-table"><strong>Data:</strong></td>';
	echo '<td>'.$dados[0]['data_formatada'].'</td>';
	echo '<tr>';

	$visibilidade = array(
		"1" => "Público",
		"2" => "Privado"
	  );

	echo '<td class="td-table"><strong>Visibilidade:</strong></td>';
	echo '<td>'.$visibilidade[$dados[0]['visibilidade']].'</td>';
	echo '</tr>';
	echo '<tr>';                   
	echo '<td class="td-table"><strong>Responsável:</strong></td>';
	echo '<td>'.$dados[0]['responsavel'].'</td>';
	echo '</tr>';
	
	$id_chamado = $dados[0]['id_chamado'];
	if($dados[0]['visibilidade'] == 1){

		$envolvidos = DBRead('', 'tb_chamado_perfil a', "INNER JOIN tb_perfil_sistema b ON a.id_perfil_sistema = b.id_perfil_sistema WHERE a.id_chamado = '$id_chamado'");

		$aux_envolvidos = '';
		foreach($envolvidos as $conteudo){

			$aux_envolvidos .= $conteudo['nome']."; <br>";
		}
		$aux_envolvidos = substr_replace($aux_envolvidos, '', -6);

	}else if($dados[0]['visibilidade'] == 2){

		$envolvidos = DBRead('', 'tb_chamado_usuario a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_chamado = '$id_chamado'");

		$aux_envolvidos = '';
		foreach($envolvidos as $conteudo){

			$aux_envolvidos .= $conteudo['nome']."; <br>";
		}
		$aux_envolvidos = substr_replace($aux_envolvidos, '', -6);

	}

	echo ' <td class="td-table"><strong>Setor(es)/Envolvido(s):</strong></td>';
	echo '<td>'.$aux_envolvidos.'</td>';
	echo '</tr>';
	
	echo '</tbody>';
	echo '</table>';
	
	echo '<hr>';
	echo '<div class="row">';
    echo '<div class="col-md-12" style="margin-left: 10px;">';
    echo '<label>Justificativa:</label>';
	echo '<span class="conteudo-editor" id="teste"><p>'.$descricao.'<p></span>';
	echo '</div>';                      
	echo '</div>';

	?>

	<script>
		$('#teste').css('border-left', '4px solid');
        $('#teste').css('border-left-color', '#FFC125');
	</script>

	<?php

}else if($dados[0]['acao'] == "desbloquear"){
	echo '<table class="table table-striped" style="margin-bottom: -15px;">';
    echo '<tbody>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Feito por:</strong></td>';
	echo '<td>'.$nome_usuario_acao.'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Tempo:</strong></td>';
	echo '<td>'.$dados[0]['tempo'].' minutos</td>';
	echo '</tr>';      
	echo '<tr>';
	echo '<td class="td-table"><strong>Data:</strong></td>';
	echo '<td>'.$dados[0]['data_formatada'].'</td>';
	echo '</tr>';
	echo '</tbody>';
	echo '</table>';
	
	echo '<hr>';
	echo '<div class="row">';
    echo '<div class="col-md-12" style="margin-left: 10px;">';
    echo '<label>Justificativa:</label>';
	echo '<span class="conteudo-editor" id="teste">'.$descricao.'</span>';
	echo '</div>';                      
	echo '</div>';

	?>

	<script>
		$('#teste').css('border-left', '4px solid');
        $('#teste').css('border-left-color', '#DF7401');
	</script>

	<?php
}else if($dados[0]['acao'] == "bloquear"){
	echo '<table class="table table-striped" style="margin-bottom: -15px;">';
    echo '<tbody>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Feito por:</strong></td>';
	echo '<td>'.$nome_usuario_acao.'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Tempo:</strong></td>';
	echo '<td>'.$dados[0]['tempo'].' minutos</td>';
	echo '</tr>';      
	echo '<tr>';
	echo '<td class="td-table"><strong>Data:</strong></td>';
	echo '<td>'.$dados[0]['data_formatada'].'</td>';
	echo '</tr>';
	echo '</tbody>';
	echo '</table>';
	
	echo '<hr>';
	echo '<div class="row">';
    echo '<div class="col-md-12" style="margin-left: 10px;">';
    echo '<label>Justificativa:</label>';
	echo '<span class="conteudo-editor" id="teste">'.$descricao.'</span>';
	echo '</div>';                      
	echo '</div>';

	?>

	<script>
		$('#teste').css('border-left', '4px solid');
        $('#teste').css('border-left-color', '#DF7401');
	</script>

	<?php
	
}else if($dados[0]['acao'] == "reabrir"){
	echo '<table class="table table-striped" style="margin-bottom: -15px;">';
    echo '<tbody>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Feito por:</strong></td>';
	echo '<td>'.$nome_usuario_acao.'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Tempo:</strong></td>';
	echo '<td>'.$dados[0]['tempo'].' minutos</td>';
	echo '</tr>';      
	echo '<tr>';
	echo '<td class="td-table"><strong>Data:</strong></td>';
	echo '<td>'.$dados[0]['data_formatada'].'</td>';
	echo '</tr>';
	echo '</tbody>';
	echo '</table>';
	
	echo '<hr>';
	echo '<div class="row">';
    echo '<div class="col-md-12" style="margin-left: 10px;">';
    echo '<label>Justificativa:</label>';
	echo '<span class="conteudo-editor" id="teste">'.$descricao.'</span>';
	echo '</div>';                      
	echo '</div>';

	?>

	<script>
		$('#teste').css('border-left', '4px solid');
        $('#teste').css('border-left-color', '#265a88');
	</script>

	<?php
}else if($dados[0]['acao'] == "gerenciar"){
	echo '<table class="table table-striped" style="margin-bottom: -15px;">';
    echo '<tbody>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Feito por:</strong></td>';
	echo '<td>'.$nome_usuario_acao.'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Tempo:</strong></td>';
	echo '<td>'.$dados[0]['tempo'].' minutos</td>';
	echo '</tr>';      
	echo '<tr>';
	echo '<td class="td-table"><strong>Data:</strong></td>';
	echo '<td>'.$dados[0]['data_formatada'].'</td>';
	echo '<tr>';

	$visibilidade = array(
		"1" => "Público",
		"2" => "Privado"
	  );

	echo '<td class="td-table"><strong>Visibilidade:</strong></td>';
	echo '<td>'.$visibilidade[$dados[0]['visibilidade']].'</td>';
	echo '</tr>';
	echo '<tr>';                   
	echo '<td class="td-table"><strong>Responsável:</strong></td>';
	echo '<td>'.$dados[0]['responsavel'].'</td>';
	echo '</tr>';
	
	$id_chamado = $dados[0]['id_chamado'];
	if($dados[0]['visibilidade'] == 1){

		$envolvidos = DBRead('', 'tb_chamado_perfil a', "INNER JOIN tb_perfil_sistema b ON a.id_perfil_sistema = b.id_perfil_sistema WHERE a.id_chamado = '$id_chamado'");

		$aux_envolvidos = '';
		foreach($envolvidos as $conteudo){

			$aux_envolvidos .= $conteudo['nome']."; <br>";
		}
		$aux_envolvidos = substr_replace($aux_envolvidos, '', -6);

	}else if($dados[0]['visibilidade'] == 2){

		$envolvidos = DBRead('', 'tb_chamado_usuario a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_chamado = '$id_chamado'");

		$aux_envolvidos = '';
		foreach($envolvidos as $conteudo){

			$aux_envolvidos .= $conteudo['nome']."; <br>";
		}
		$aux_envolvidos = substr_replace($aux_envolvidos, '', -6);

	}

	echo ' <td class="td-table"><strong>Setor(es)/Envolvido(s):</strong></td>';
	echo '<td>'.$aux_envolvidos.'</td>';
	echo '</tr>';
	
	echo '</tbody>';
	echo '</table>';
	
	echo '<hr>';
	echo '<div class="row">';
    echo '<div class="col-md-12" style="margin-left: 10px;">';
    echo '<label>Justificativa:</label>';
	echo '<span class="conteudo-editor" id="teste">'.$descricao.'</span>';
	echo '</div>';                      
	echo '</div>';

	?>

	<script>
		$('#teste').css('border-left', '4px solid');
        $('#teste').css('border-left-color', '#20B2AA');
	</script>

	<?php
}else if($dados[0]['acao'] == "alterar"){
	echo '<table class="table table-striped" style="margin-bottom: -15px;">';
    echo '<tbody>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Feito por:</strong></td>';
	echo '<td>'.$nome_usuario_acao.'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Tempo:</strong></td>';
	echo '<td>'.$dados[0]['tempo'].' minutos</td>';
	echo '</tr>';      
	echo '<tr>';
	echo '<td class="td-table"><strong>Data:</strong></td>';
	echo '<td>'.$dados[0]['data_formatada'].'</td>';
	echo '</tr>';

	$categorias = DBRead('', 'tb_chamado_categoria a', "INNER JOIN tb_categoria b ON a.id_categoria = b.id_categoria WHERE a.id_chamado = '$id_chamado' ", 'b.nome');

	if($categorias){
		foreach($categorias as $c){
			$nome_categoria .= $c['nome']."<br>";
		}
	}else{
		$nome_categoria = '';
	}

	$nome_categoria = substr($nome_categoria, 0, strlen($nome_categoria) - 2);

	echo '<td class="td-table"><strong>Categoria:</strong></td>';
	echo '<td>'.$nome_categoria.'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Origem:</strong></td>';
	echo '<td>'.$dados[0]['origem'].'</td>';
	echo '</tr>';

	if($contrato != ""){

		echo '<tr>';
		echo '<td class="td-table"><strong>Contrato (Cliente):</strong></td>';
		echo '<td>'.$contrato.'</td>';
		echo '</tr>';
		echo '<tr>';
	}

	echo '</tbody>';
	echo '</table>';
	
	echo '<hr>';
	echo '<div class="row">';
    echo '<div class="col-md-12" style="margin-left: 10px;">';
    echo '<label>Justificativa:</label>';
	echo '<span class="conteudo-editor" id="teste">'.$descricao.'</span>';
	echo '</div>';                      
	echo '</div>';

	?>

	<script>
		$('#teste').css('border-left', '4px solid');
        $('#teste').css('border-left-color', '#9370DB');
	</script>

	<?php
}else if($dados[0]['acao'] == "pendencia"){

	$data_pendencia =  DBRead('', 'tb_chamado_pendencia', "WHERE id_chamado_acao = '$id'");

	echo '<table class="table table-striped" style="margin-bottom: -15px;">';
    echo '<tbody>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Para o dia:</strong></td>';
	echo '<td>'.converteDataHora($data_pendencia[0]['data']).'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Feito por:</strong></td>';
	echo '<td>'.$nome_usuario_acao.'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Tempo:</strong></td>';
	echo '<td>'.$dados[0]['tempo'].' minutos</td>';
	echo '</tr>';      
	echo '<tr>';
	echo '<td class="td-table"><strong>Criada em:</strong></td>';
	echo '<td>'.$dados[0]['data_formatada'].'</td>';
	echo '</tr>';

	echo '</tbody>';
	echo '</table>';
	
	echo '<hr>';
	echo '<div class="row">';
    echo '<div class="col-md-12" style="margin-left: 10px;">';
    echo '<label>Descrição:</label>';
	echo '<span class="conteudo-editor" id="teste">'.$descricao.'</span>';
	echo '</div>';                      
	echo '</div>';

	?>

	<script>
		$('#teste').css('border-left', '4px solid');
        $('#teste').css('border-left-color', '#EE8262');
	</script>

	<?php
}else if($dados[0]['acao'] == "alteracao_prazo_encerramento"){
	echo '<table class="table table-striped" style="margin-bottom: -15px;">';
    echo '<tbody>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Feito por:</strong></td>';
	echo '<td>'.$nome_usuario_acao.'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Tempo:</strong></td>';
	echo '<td>'.$dados[0]['tempo'].' minutos</td>';
	echo '</tr>';      
	echo '<tr>';
	echo '<td class="td-table"><strong>Data:</strong></td>';
	echo '<td>'.$dados[0]['data_formatada'].'</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="td-table"><strong>Data do prazo alterado:</strong></td>';
	echo '<td>'.converteDataHora($dados[0]['prazo_encerramento']).':00</td>';
	echo '</tr>';
	echo '</tbody>';
	echo '</table>';
	
	echo '<hr>';
	echo '<div class="row">';
    echo '<div class="col-md-12" style="margin-left: 10px;">';
    echo '<label>Justificativa:</label>';
	echo '<span class="conteudo-editor">'.$descricao.'</span>';
	echo '</div>';                      
	echo '</div>';

	?>

	<script>
		$('#teste').css('border-left', '4px solid');
        $('#teste').css('border-left-color', '#ff3399');
	</script>

	<?php
}

if($dados[0]['acao_painel'] == 1){ ?>
	<script>
		$('#teste').css('border-left', '4px solid');
        $('#teste').css('border-left-color', '#FF4000');
	</script>
<?php }

?>