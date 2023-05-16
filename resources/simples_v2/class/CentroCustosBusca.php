<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);
$situacao = addslashes($parametros['situacao']);
$id_usuario_responsavel = addslashes($parametros['id_usuario_responsavel']);

if($situacao !== ''){
	$filtro .= " AND a.status = '".$situacao."' ";
}

if($id_usuario_responsavel){
	$filtro .= " AND a.id_usuario_responsavel = '".$id_usuario_responsavel."' ";
}

// Informações da query
$filtros_query = "INNER JOIN tb_usuario b ON a.id_usuario_responsavel = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE (a.nome LIKE '%$letra%' OR c.nome LIKE '%$letra%') ".$filtro." ";

// Maximo de registros por pagina
$maximo = 10;

// Limite de links (antes e depois da pagina atual) da paginação
$lim_links = 5;

// Declaração da pagina inicial
$pagina = $parametros['pagina'];
if ($pagina == '') {
	$pagina = 1;
}
// Conta os resultados no total da query
$dados = DBRead('', 'tb_centro_custos a', $filtros_query, "COUNT(*) AS 'num_registros'");
$total = $dados[0]['num_registros'];

// Calculando o registro inicial
$inicio = $maximo * ($pagina - 1);

if ($inicio >= $total) {
	$inicio = 0;
	$pagina = 1;
}

###################################################################################
// INICIO DO CONTEÚDO
//
$dados = DBRead('', 'tb_centro_custos a', $filtros_query . " ORDER BY nome ASC LIMIT $inicio,$maximo", "a.*, c.nome AS 'nome_usuario_responsavel'");
if (!$dados) {
	echo "<p class='alert alert-warning' style='text-align: center'>";
	if (!$letra) {
		echo "Não foram encontrados registros!";
	} else {
		echo "Nenhum resultado encontrado na busca por \"<strong>$letra</strong>\"";
	}
	echo "</p>";
} else {
	echo "<div class='table-responsive'>";
		echo "<table class='table table-hover' style='font-size: 14px;'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th class=\"col-md-1\">#</th>";
					echo "<th class=\"col-md-4\">Nome</th>";
					echo "<th class=\"col-md-4\">Responsável</th>";
					echo "<th class=\"col-md-2 text-center\">Status</th>";
					echo "<th class=\"col-md-1 text-center\">Opções</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";
			foreach ($dados as $conteudo) {

				$id = $conteudo['id_centro_custos'];
				$nome = $conteudo['nome'];
				$nome_usuario_responsavel = $conteudo['nome_usuario_responsavel'];
					
				$status = $conteudo['status'];
				if($status == 1){
					$status_nome = "Ativo";
				}else{
					$status_nome = "Inativo";
				}
				
                if ($status != 1) {
					echo '<tr class="warning">';
				} else {
					echo "<tr>";
				}
				 	echo "<td>$id</td>";
					echo "<td>$nome</td>";
					echo "<td>$nome_usuario_responsavel</td>";
					echo "<td class=\"text-center\">$status_nome</td>";
					echo "<td class=\"text-center\">

						<a href='/api/iframe?token=<?php echo $request->token ?>&view=centro-custos-form&alterar=$id' title='Alterar'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;&nbsp;";
						
						if($conteudo['status'] == 0){
							echo "<a href=\"class/CentroCustos.php?ativar=$id\" title='Ativar' onclick=\"if (!confirm('Ativar " . addslashes($nome) . "?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-upload' style='color:#228B22;'></i></a>";

						}else if($conteudo['status'] == 1){
							echo "<a href=\"class/CentroCustos.php?desativar=$id\" title='Desativar' onclick=\"if (!confirm('Desativar " . addslashes($nome) . "?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-download' style='color:#FFA500;'></i></a>";
						}    
						//<a href='/api/iframe?token=<?php echo $request->token ?>&view=boleto-visualizar&visualizar=$id' title='Visualizar'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;&nbsp;";
						/*if($situacao == 'REGISTRADO'){

							echo "<a href=\"class/Boleto.php?baixa=$id\" title='Dar baixa' onclick=\"if (!confirm('Dar baixa no boleto de ".addslashes($texto_confirmacao)."?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-window-close' style='color:#b92c28;'></i></a></td>";
						}else{

							echo "<i title='Não é possível dar baixa no boleto!' class='fa fa-window-close' style='color:#F5A9A9;'></i></td>";
						}*/			             

		                //fim

				echo "</tr>";
			}
			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

// FIM DO CONTEUDO
###################################################################################

$menos = $pagina - 1;
$mais = $pagina + 1;
$pgs = ceil($total / $maximo);

// Inicio e fim dos links
$ini_links = ((($pagina - $lim_links) > 1) ? $pagina - $lim_links : 1);
$fim_links = ((($pagina + $lim_links) < $pgs) ? $pagina + $lim_links : $pgs);

if($pgs > 1 ) {

    echo "<nav style=\"text-align: center;\">";
	    echo "<ul class=\"pagination\">";

	    // Mostragem de pagina
	    if($menos > 0) {                                    
	        echo "<li><a href=\"#\" class=\"troca_pag\" atr-pagina=\"$menos\" aria-label=\"Previous\"><span aria-hidden=\"true\">&laquo; Anterior</span></a></li>";
	        echo "<li><a href=\"#\" class=\"troca_pag\" atr-pagina=\"1\">Pri.</a></li>";
	    }else{
	        echo "<li class=\"disabled\"><a href=\"#\" aria-label=\"Previous\"><span aria-hidden=\"true\">&laquo; Anterior</span></a></li>";
	        echo "<li class=\"disabled\"><a href=\"#\">Pri.</a></li>";
	    }

	    // Listando as paginas
	    for($i = $ini_links; $i <= $fim_links; $i++) {
	        if($i != $pagina) {                                        
	            echo "<li><a href=\"#\" class=\"troca_pag\" atr-pagina=\"$i\">$i</a></li>";
	        } else {
	            echo "<li class=\"active\"><a href=\"#\">$i <span class=\"sr-only\">(current)</span></a></li>";
	        }
	    }

	    if($mais <= $pgs) {
	        echo "<li><a href=\"#\" class=\"troca_pag\" atr-pagina=\"$pgs\">Últ.</a></li>";
	        echo "<li><a href=\"#\" class=\"troca_pag\" atr-pagina=\"$mais\" aria-label=\"Next\"><span aria-hidden=\"true\">Próximo &raquo;</span></a></li>";
	    }else{
	        echo "<li class=\"disabled\"><a href=\"#\">Últ.</a></li>";
	        echo "<li class=\"disabled\"><a href=\"#\" aria-label=\"Next\"><span aria-hidden=\"true\">Próximo &raquo;</span></a></a></li>";
	    }

	    echo "</ul>";
    echo "</nav>";
}
?>