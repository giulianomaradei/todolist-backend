<?php
require_once "System.php";


$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);


// Informações da query
// $filtros_query = "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = 1 AND (a.id_perfil_sistema = 4 OR a.id_perfil_sistema = 14 OR a.id_perfil_sistema = 13 OR a.id_perfil_sistema = 23 OR a.id_perfil_sistema = 3 OR a.id_perfil_sistema = 15) AND b.nome LIKE '%".$letra."%' " ;
$filtros_query = "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = 1 AND (a.id_perfil_sistema = 4 OR a.id_perfil_sistema = 14 OR a.id_perfil_sistema = 13 OR a.id_perfil_sistema = 23 OR a.id_perfil_sistema = 3) AND b.nome LIKE '%".$letra."%' " ;

###################################################################################
// INICIO DO CONTEÚDOs 
//
$dados = DBRead('', 'tb_usuario a', $filtros_query . " ORDER BY b.nome ASC", "a.*, b.*");
if (!$dados) {
	echo "<p class='alert alert-warning' style='text-align: center'>";
	if (!$letra) {
		echo "Não foram encontrados registros!";
	} else {
		echo "Nenhum resultado encontrado na busca por \"<strong>$letra</strong>\"";
	}
	echo "</p>";
} else {
	echo '<input type="hidden" id="auxiliar" value="">';
	echo '<input type="hidden" id="auxiliar_contrato" value="">';

	echo "<div class='table-responsive'>";
		echo "<table class='table table-hover' style='font-size: 14px;'>";
			echo "<thead>";
				echo "<tr>";
					//echo "<th>#</th>";
					echo "<th>Nome</th>";
					echo "<th class='text-center' colspan='1'>Opções</th>";
				echo "</tr>";
				echo "<tr>";
					echo "<th></th>";
					echo "<th class='text-center'>Atendimentos de Voz</th>";
					// echo "<th class='text-center'>Atendimentos de Texto</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";

			foreach ($dados as $conteudo) {
  
				$id_usuario = $conteudo['id_usuario']; 
				$nome = $conteudo['nome'];

                $dados_responsavel_atendimento_voz = DBRead('', 'tb_responsavel_atendimento',"WHERE id_usuario = '".$id_usuario."' AND status = 1 AND tipo = 0 ORDER BY id_responsavel_atendimento DESC");

                if(!$dados_responsavel_atendimento_voz){
                    $botao_voz = "<button class='btn btn-sm btn-info botao_voz' style= 'min-width: 100%; max-width: 100%;' cor='btn-info' id='id_".$id_usuario."' >Tornar Responsável</button> ";
                }else{
                    $botao_voz = "<button class='btn btn-sm btn-warning botao_voz' style= 'min-width: 100%; max-width: 100%;' cor='btn-warning' id='id_".$id_usuario."' >Deixar de ser Responsável</button> ";
				}
				
				// $dados_responsavel_atendimento_texto = DBRead('', 'tb_responsavel_atendimento a',"INNER JOIN tb_grupo_atendimento_chat b ON a.id_grupo_atendimento_chat = b.id_grupo_atendimento_chat WHERE a.id_usuario = '".$id_usuario."' AND a.status = 1 AND a.tipo = 1 GROUP BY a.id_grupo_atendimento_chat ORDER BY b.nome ASC ", "a.id_grupo_atendimento_chat");

              

				echo "<tr>";
					//echo "<td>".$id_usuario."</td>";	
					echo "<td class='col-md-4' style='vertical-align: middle;'><span class = 'nome_atendente' id='nome_atendente_".$id_usuario."'>".$nome."</span></td>";	
					echo "<td class=\"text-center col-md-4\" style='vertical-align: middle;'>".$botao_voz."</td>";
				echo "</tr>";

				
	    
			  
			  echo "</tr>";

			}
			echo "</tbody>";
		echo "</table>";
	echo "</div>";


}
?>

<script>
	$(function(){
		$('[data-toggle="popover"]').popover({ trigger: "hover", container: "body" });
	});
</script>
