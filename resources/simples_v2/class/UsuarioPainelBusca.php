<?php
require_once "System.php";

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = $parametros['nome'];
$letra = addslashes($letra);

// Informações da query
$filtros_query = "INNER JOIN tb_pessoa b ON a.id_pessoa_usuario = b.id_pessoa INNER JOIN tb_pessoa c ON a.id_pessoa_cliente = c.id_pessoa WHERE (a.email LIKE '%$letra%' OR b.nome LIKE '%$letra%' OR c.nome LIKE '%$letra%') AND a.status != '2' AND b.id_pessoa != '2033' ORDER BY b.nome ASC";

// Maximo de registros por pagina
$maximo = 10;

// Limite de links(antes e depois da pagina atual) da paginação
$lim_links = 5;

// Declaração da pagina inicial
$pagina = $parametros['pagina'];
if ($pagina == '') {
	$pagina = 1;
}

// Conta os resultados no total da query
$dados = DBRead('', 'tb_usuario_painel a', $filtros_query, "COUNT(*) AS 'num_registros'");
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
$dados = DBRead('', 'tb_usuario_painel a', $filtros_query . " LIMIT $inicio,$maximo","a.*, b.nome, c.nome AS nome_empresa");
if (!$dados) {
	echo "<p class='alert alert-warning' style='text-align: center'>";
	if (!$letra) {
		echo "Não foram encontrados registros!";
	}else{
		echo "Nenhum resultado encontrado na busca por \"<strong>$letra</strong>\"";
	}
	echo "</p>";
}else {

	echo "<div class='table-responsive'>";
	echo "<table class='table table-hover' style='font-size: 14px;'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th class=\"col-md-4\">Nome</th>";
	echo "<th class=\"col-md-4\">E-Mail</th>";
	echo "<th class=\"col-md-3\">Pessoa (Cliente)</th>";
	echo "<th class=\"col-md-1 text-center\">Opções</th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	foreach ($dados as $conteudo) {

		$id = $conteudo['id_usuario_painel'];
		$email = $conteudo['email'];
		$status = $conteudo['status'];
		$nome = $conteudo['nome'];

		$nome_empresa = $conteudo['nome_empresa'];
		if ($status != 1) {
			echo '<tr class="warning">';
		} else {
			echo "<tr>";
		}
		echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=usuario-painel-form&alterar=$id'\" style='cursor: pointer'>$nome</td>";
		echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=usuario-painel-form&alterar=$id'\" style='cursor: pointer'>$email</td>";
		echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=usuario-painel-form&alterar=$id'\" style='cursor: pointer'>$nome_empresa</td>";
		echo "<td class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=usuario-painel-form&alterar=$id' title='Alterar'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"class/UsuarioPainel.php?excluir=$id\" title='Excluir' onclick=\"if (!confirm('Excluir " . addslashes($email) . "?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-trash' style='color:#b92c28;'></i></a></td>";
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