<?php 

	$dados_arvore_instrucao = DBRead('', 'tb_arvore a', "INNER JOIN tb_pergunta b ON a.id_pergunta = b.id_pergunta WHERE a.id_arvore = '$id_arvore'", "b.nome");
	
	if($dados_arvore){
		echo "<legend class='text-center'><strong>".$dados_arvore_instrucao[0]['nome']."</strong></legend>";
		foreach ($dados_arvore as $conteudo_arvore) {
			echo "<button type='submit' name='atualizar' value='".$conteudo_arvore['id_arvore']."' class='btn-submit-fluxo btn-opcao-fluxo btn btn-primary btn-opcao form-control ' disabled='disabled'><small class='pull-left'>(".$conteudo_arvore['id_arvore'].")</small> ".$conteudo_arvore['nome']."</button>";
		}
	}else{
		$id_usuario = $_SESSION['id_usuario'];
		$dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario'",'b.nome');
		$nome_atendente = $dados_usuario[0]['nome'];
		
		//verifica se existe filhos do passo que a empresa não tem, caso sim exibe 'Fim', caso não, exibe a instrução.
		$dados_arvore_existe_filhos = DBRead('', 'tb_arvore', "WHERE id_pai = '$id_arvore'", "id_arvore");
		if($dados_arvore_existe_filhos){
			echo "<legend class='text-center'><strong>Fim</strong></legend>";
		}else{
			echo "<legend class='text-center'><strong>".$dados_arvore_instrucao[0]['nome']."</strong></legend>";
		}
		
		echo "<span id= 'clip_contato'><strong>Contato:</strong> <span class='span-contato'>".$contato."</span></span><br />";

		//input temporário para a integrção
		$contato_para_int = "";
		if($contato != ""){
			$contato_para_int = "Contato: ".$contato;
		}
		echo "<input name='clip-contato-int' type='hidden' value='".$contato_para_int."' />";
		////////////////////

		//input temporário para a integrção
		$fone1_para_int = "";
		//if($fone1 != ""){
			$fone1_para_int = "Fone1: ".$fone1;
		//}
		echo "<input name='clip-fone1-int' type='hidden' value='".$fone1_para_int."' />";
		////////////////////
		echo "<span id= 'clip_fone1'><strong>Fone 1:</strong> <span class='span-fone1'>".$fone1."</span></span><br />";

		//input temporário para a integrção
		$fone2_para_int = "";
		if($fone2 != ""){
			$fone2_para_int = "Fone2: ".$fone2;
		}
		echo "<input name='clip-fone2-int' type='hidden' value='".$fone2_para_int."' />";
		////////////////////
		//essa div por fora serve para inclusão e exclusão do fone 2 pelo "editar dados"
		echo "<div id='div-fone2'>";
		if($fone2){
			echo "<span id='clip_fone2'><strong>Fone 2:</strong> <span class='span-fone2'>".$fone2."</span></span><br />";
		}
		echo "</div>";

		//input temporário para a integrção
		$assinante_para_int = "";
		if($assinante != ""){
			$assinante_para_int = "Assinante: ".$assinante;
		}
		echo "<input name='clip-assinante-int' type='hidden' value='".$assinante_para_int."' />";
		////////////////////
		echo "<span id= 'clip_assinante'><strong>Assinante:</strong> <span class='span-assinante'>".$assinante."</span></span><br />";
		
		if($cpf_cnpj){
			//input temporário para a integrção
			$cpf_para_int = "";
			if($cpf_cnpj != ""){
				$cpf_para_int = "CPF/CNPJ: ".$cpf_cnpj;
			}
			echo "<input name='clip-cpf-int' type='hidden' value='".$cpf_para_int."' />";
			////////////////////
			echo "<span id= 'clip_cpf_cnpj'><strong>CPF/CNPJ:</strong> <span class='span-cpf_cnpj'>".$cpf_cnpj."</span></span><br />";
		}
		if($dado_adicional){
			//input temporário para a integrção
			$adicional_para_int = "";
			if($dado_adicional != ""){
				$adicional_para_int = $descricao_dado_adicional.": ".$dado_adicional;
			}
			echo "<input name='clip-adicional-int' type='hidden' value='".$adicional_para_int."' />";
			////////////////////
			echo "<span id= 'clip_dado_adicional'><strong>".$descricao_dado_adicional.":</strong> <span class='span-dado_adicional'>".$dado_adicional."</span></span><br />";
		}

		if($exibir_protocolo){
			//input temporário para a integrção
			$protocolo_para_int = "";
			if($protocolo != ""){
				$protocolo_para_int = "Protocolo: ".$protocolo;
			}
			echo "<input name='clip-protocolo-int' type='hidden' value='".$protocolo_para_int."' />";
			////////////////////
			echo "<div class='row'>";
				echo "<div class='col-lg-12'>";
					echo "<span id= 'clip_protocolo'><strong>Protocolo:</strong> ".$protocolo."</span>";
				echo "</div>";
			echo "</div>";
		}

		//input temporário para a integrção
		$atendente_para_int = "";
		if($nome_atendente != ""){
			$atendente_para_int = "Atendente: ".$nome_atendente;
		}
		echo "<input name='clip-atendente-int' type='hidden' value='".$atendente_para_int."' />";
		////////////////////
		echo "<span id= 'clip_atendente'><strong>Atendente:</strong> <span class='span-atendente'>".$nome_atendente."</span></span><br />";

		$dados_atendimento_arvore = DBRead('', 'tb_atendimento_arvore', "WHERE id_atendimento = '$id_atendimento'", "exibe_texto_os, anotacao, texto_os");
		echo "<br><textarea name='os' class='form-control clipboard' id='textarea-os' rows='10' required>";
		$texto_os_textarea = '';
		if($dados_atendimento_arvore){
			foreach ($dados_atendimento_arvore as $conteudo){
				if($conteudo['exibe_texto_os']){
					if($conteudo['anotacao']){
						$texto_os_textarea.= "- ".$conteudo['texto_os']." (".$conteudo['anotacao'].")"."\n";
					}else{
						$texto_os_textarea.= "- ".$conteudo['texto_os']."\n";
					}
				}elseif($conteudo['anotacao']){
					$texto_os_textarea.= "- (".$conteudo['anotacao'].")"."\n";
				}
			}
		}
		if($dados_falha){
			$texto_os_textarea.= "- ".$dados_falha[0]['texto_os']."\n";
		}
		echo substr($texto_os_textarea, 0, -1);
			
		echo "</textarea>";

		echo "<div id='texto-os' style='display: none'></div>";

		$dados_situacao = DBRead('', 'tb_situacao', "ORDER BY nome ASC");

        echo "<select name='situacao' class='form-control clipboard_select' id='select-situacao' required>";
            echo "<option value=''>Selecione uma situação para o atendimento!</option>";
            foreach ($dados_situacao as $conteudo){
            	echo "<option value='".$conteudo['id_situacao']."'>".$conteudo['nome']."</option>";
            }
        echo "</select>";
		?>

		<div class="row" > 
			<div class="col-md-12" style="display: inline;">
				<button type='button' class='btn btn-xs btn-default' id='fixar-os' style='margin-top: 5px;'><i class='fa fa-check'></i> Fixar OS</button>
				<button type='button' class='btn btn-xs btn-default' id='editar-os' style='margin-top: 5px;display: none;'><i class='fa fa-pencil'></i> Editar OS</button>
				<button type='button' class='btn btn-xs btn-warning' id='clipboard' style='margin-top: 5px;display: none;'><i class='fa fa-clone'></i> Copiar</button>
				<button type='button' class='btn btn-xs btn-primary' id='frases-uteis' style='margin-top: 5px;' data-toggle='collapse' data-target='#collapseFrasesUteis' aria-expanded='false' aria-controls='collapseOpcoes'><i class='fa fa-commenting-o'></i> Frases úteis</button>
			</div>
		</div>
		
		<div class="collapse" id="collapseFrasesUteis" style="margin-top: 20px;">	
			<div class="panel panel-default"  style="margin-bottom: 0px;">
				<div class="panel-heading clearfix">
					<h3 class="panel-title text-left pull-left">Frases úteis:</h3>
				</div>
				<div class="panel-body">	
				<?php
				$dados_frases_uteis = DBRead('','tb_frase_util',"ORDER BY texto ASC");
				if($dados_frases_uteis){
					foreach ($dados_frases_uteis as $frase_util) {
						echo "<button class='btn btn-primary form-control btn-frase-util' type='button' style='margin-bottom: 5px; margin-top:5px;'>".$frase_util['texto']."</button>";
					}
				}else{
					echo '<div class="text-center">Nenhuma frase útil cadastrada!</div>';
				}
				?>
				</div>
			</div>
		</div>


		<?php
		//Importação de arquivos externos para atendimentos integrados a sistemas de gestão
		$temIntegracao = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
		if($temIntegracao):
		?>
		<br />
		<div class="alert opcoes-sistema-gestao" role="alert" style="display: none; margin-top: 20px; background-color: #f5f5f5; border: 1px solid #ccc; color: #000; padding-bottom: 20px;">

			<fieldset>
				<legend style="color: #000"><h4>Opções do sistema de gestão</h4></legend>

				<div class="quadro-sistema-gestao">
					<?php
					$finalizacao_sistema_gestao = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa", "classificacao_atendimento_sistema_gestao, selecao_finalizacao_sistema_gestao");

					echo "<ul class='list-group'>";
						echo "<li class='list-group-item' style='background-color: #e2e2e2'><strong>Classificação de atendimento no sistema de gestão:</strong></li>";
						echo "<li class='list-group-item' style='background-color: #f5f5f5'>".nl2br($finalizacao_sistema_gestao[0]['classificacao_atendimento_sistema_gestao'])."</li>";
					echo "</ul>";

					echo "<ul class='list-group'>";
						echo "<li class='list-group-item' style='background-color: #e2e2e2'><strong>Seleção de finalização no sistema de gestão:</strong></li>";
						echo "<li class='list-group-item' style='background-color: #f5f5f5'>".nl2br($finalizacao_sistema_gestao[0]['selecao_finalizacao_sistema_gestao'])."</li>";
					echo "</ul>";

					?>
				</div>
			</fieldset>

		<?php
			if($temIntegracao[0]['id_integracao']):
				include "integracoes/atendimento-form-arvore-ixc.php";
			endif;
		endif;
		?>


	<?php
    }
    
	if(!$id_falha){
		echo "<div class='text-center'><hr><strong>ID do passo: </strong>".$id_arvore."</div>";
    }

    $dados_atendimento_resolvido = DBRead('', 'tb_atendimento', "WHERE id_atendimento = '$id_atendimento' LIMIT 1", "resolvido");
    if ($dados_atendimento_resolvido[0]['resolvido'] == 1) {
        echo "<div class='text-center'><hr><strong>Situação: </strong>Resolvido</div>";
    } else if ($dados_atendimento_resolvido[0]['resolvido'] == 2) {
        echo "<div class='text-center'><hr><strong>Situação: </strong>Não resolvido</div>";
    } else if ($dados_atendimento_resolvido[0]['resolvido'] == 3) {
        echo "<div class='text-center'><hr><strong>Situação: </strong>Diagnosticado</div>";
    }
	?>


<script>

	$('#clipboard').popover({
		content: "Copiado para a área de transferência!"
	}).click(function(){
		setTimeout(function(){
			$('#clipboard').popover('hide');
		}, 1800);
	});
    	
	$("#fixar-os").on('click', function(){
		if($("#select-situacao").val()){
			if($("#textarea-os").val()){
				$("#textarea-os").hide();
				$("#texto-os").html($("#textarea-os").val().replace(/\n/g,'<br>') + "<br><br>" + $("#select-situacao option:selected").text());
				$("#texto-os").show();
				$("#editar-os").show();
				$("#fixar-os").hide();
				$("#select-situacao").hide();
				$("#frases-uteis").hide();
				$("#clipboard").show();
				$("#collapseFrasesUteis").collapse('hide');
			}else{
				alert("OS não pode estar vazia!");
				$("#textarea-os").focus();
			}
		}else{
			alert("Selecione uma situação!");
			$("#select-situacao").focus();
		}
	});
	$(".btn-frase-util").on('click', function(){
		$("#textarea-os").val($("#textarea-os").val()+'\n'+'- '+$(this).html());
	});

	$("#clipboard").on('click', function(){

		if($("#select-situacao").val()){
			if($("#textarea-os").val()){	
				$("#textarea-os").hide();
				$("#texto-os").html($("#textarea-os").val().replace(/\n/g,'<br>') + "<br><br>" + $("#select-situacao option:selected").text());
				$("#texto-os").show();
				$("#editar-os").show();		
				$("#fixar-os").hide();
				$("#select-situacao").hide();

				var protocolo = <?=$exibir_protocolo?>;
				var os = $("#textarea-os").val();
				var situacao =  $("#select-situacao option:selected").text();

				var tudo = $("#clip_contato").text()+"\n"+$("#clip_fone1").text()+"\n";

				if($("#clip_fone2").text()){
					var tudo = tudo+$("#clip_fone2").text()+"\n";
				}	
				
				var tudo = tudo+$("#clip_assinante").text()+"\n";
				
				if($("#clip_cpf_cnpj").text()){
					var tudo = tudo+$("#clip_cpf_cnpj").text()+"\n";
				}

				if($("#clip_dado_adicional").text()){
					var tudo = tudo+$("#clip_dado_adicional").text()+"\n";
				}

				if(protocolo){
					var tudo = tudo+$("#clip_protocolo").text()+"\n";
				}

				var tudo = tudo+$("#clip_atendente").text()+"\n";
				
				var tudo =tudo+"\n"+os+"\n\n"+situacao;

				var $temp = $("<textarea>");  
				$("body").append($temp);  
				$temp.val(tudo).select();
			
				document.execCommand("copy");  
				$temp.remove();
			}else{
				alert("OS não pode estar vazia!");
				$("#textarea-os").focus();
			}
		}else{
			alert("Selecione uma situação!");
			$("#select-situacao").focus();
		}
	});

	$("#editar-os").on('click', function(){				
		$("#textarea-os").show();
	    $("#texto-os").hide();
	    $("#editar-os").hide();		
	    $("#fixar-os").show();
	    $("#select-situacao").show();
		$("#frases-uteis").show();
	    $("#clipboard").hide();
	});
</script>