<?php
require_once "System.php";

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = $parametros['nome'];
$tipo = $parametros['tipo'];
$cargo = $parametros['cargo'];
$letra = addslashes($letra);

if ($tipo) {
	$filtro_tipo = 'AND a.tipo = '.$tipo;
}

if ($parametros['data_de'] && $parametros['data_ate']) {

    $data_de = converteData($parametros['data_de']);
    $data_ate = converteData($parametros['data_ate']);

    $filtro_data = "AND (a.data_inicio BETWEEN '$data_de' AND '$data_ate')";

} else if ($parametros['data_de'] && !$parametros['data_ate']) {

    $data_de = converteData($parametros['data_de']);

    $filtro_data = "AND a.data_inicio >= '$data_de'";

} else if (!$parametros['data_de'] && $parametros['data_ate']) {

    $data_ate = converteData($parametros['data_ate']);

    $filtro_data = "AND a.data_inicio <= '$data_ate'";
}

if ($cargo) {
	$filtro_cargo = "AND a.id_cargo = $cargo";
}

// Informações da query
$filtros_query = "INNER JOIN tb_cargo b ON a.id_cargo = b.id_cargo INNER JOIN tb_setor c ON b.id_setor = c.id_setor WHERE a.status != '2' AND (a.descricao LIKE '%$letra%') $filtro_tipo $filtro_data $filtro_cargo ORDER BY a.id_vaga DESC";

// Maximo de registros por pagina
$maximo = 10;

// Limite de links(antes e depois da pagina atual) da paginação
$lim_links = 5;

// Declaração da pagina inicial
$pagina = $parametros['pagina'];
if($pagina == ''){
	$pagina = 1;
}

// Conta os resultados no total da query
$dados = DBRead('', 'tb_vaga a', $filtros_query, "COUNT(*) AS 'num_registros'");
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
$dados = DBRead('', 'tb_vaga a', $filtros_query . " LIMIT $inicio,$maximo", 'a.*, b.descricao as descricao_cargo, c.descricao as descricao_setor');
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
	echo "<th>#ID</th>";
	echo "<th class='col-md-2'>Cargo</th>";
	echo "<th class='col-md-2'>Setor</th>";
	echo "<th >Tipo</th>";
	echo "<th class='col-md-3'>Descrição</th>";
	echo "<th>Data início</th>";
	echo "<th>Data Fim</th>";
	echo "<th class='text-center'>Opções</th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";

	foreach ($dados as $conteudo) {

		$id_vaga = $conteudo['id_vaga'];
		$cargo = $conteudo['descricao_cargo'];
		$setor = $conteudo['descricao_setor'];
		$descricao = $conteudo['descricao'];
        $tipo = $conteudo['tipo'];
        $data_inicio = converteData($conteudo['data_inicio']);
        $data_fim = converteData($conteudo['data_fim']);
		$status = $conteudo['status'];
		$divulgado = $conteudo['divulgado'];

		if ($tipo == 1) {
			$tipo = 'Efetivo (CLT)';

		} else if ($tipo == 2) {
			$tipo = 'Estágio';

		} else if ($tipo == 3) {
			$tipo = 'Jovem aprendiz';

		} else if ($tipo == 4) {
			$tipo = 'PCD';

		} else if ($tipo == 5) {
			$tipo = 'Terceirizado';

		} else if ($tipo == 6) {
			$tipo = 'Terceirizado';
		}

		$check_email = '';
		$legenda_email = '';
		if ($divulgado == 1) {
			$check_email = 'color: #04B45F';
			$legenda_email = 'Enviado';
		}

		$data_atual = getDataHora();
		$data_atual = explode(' ', $data_atual);

		if ($data_atual[0] < $conteudo['data_fim']) {
			$divulgar_vaga = "<a href='/api/iframe?token=<?php echo $request->token ?>&view=vaga-email-form&id_vaga=$id_vaga' title='$legenda_email'><i class='fas fa-envelope-open-text' style='$check_email'></i>
			</a>
			&nbsp";

		} else {
			$divulgar_vaga = "<a title='Data da vaga já expirou!'><i class='fas fa-exclamation-triangle' style='opacity: 0.2; color: red;'></i></a>
			&nbsp";
		}

		echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=vaga-form&alterar=$id_vaga'\" style='cursor: pointer'>$id_vaga</td>";
		echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=vaga-form&alterar=$id_vaga'\" style='cursor: pointer'>$cargo</td>";
		echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=vaga-form&alterar=$id_vaga'\" style='cursor: pointer'>$setor</td>";
		echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=vaga-form&alterar=$id_vaga'\" style='cursor: pointer'>$tipo</td>";
		echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=vaga-form&alterar=$id_vaga'\" style='cursor: pointer'>".limitarTexto($descricao, 70)."</td>";
		echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=vaga-form&alterar=$id_vaga'\" style='cursor: pointer'>$data_inicio</td>";
		echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=vaga-form&alterar=$id_vaga'\" style='cursor: pointer'>$data_fim</td>";
		
		echo "<td class=\"text-center\">
				$divulgar_vaga
				<a href='/api/iframe?token=<?php echo $request->token ?>&view=vaga-form&alterar=$id_vaga' title='Alterar'><i class='fa fa-pencil'></i>
				</a>
				&nbsp
				<a class='a_modalAguarde' href='/api/iframe?token=<?php echo $request->token ?>&view=vaga-form&clonar=$id_vaga' title='Clonar'>
                    <i class='fa fa-clone' style='color:#FF8C00;'></i>
                </a>
				&nbsp;
                <a href=\"class/Vaga.php?excluir=$id_vaga\" title='Excluir' onclick=\"if (!confirm('Excluir esta vaga?')) { return false; } else { modalAguarde(); }\">
                    <i class='fa fa-trash' style='color:#b92c28;'></i>
                </a>
			   </td>";
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

if ($pgs > 1 ) {

    echo "<nav style=\"text-align: center;\">";
    echo "<ul class=\"pagination\">";

    // Mostragem de pagina
    if ($menos > 0) {                                    
        echo "<li><a href=\"#\" class=\"troca_pag\" atr-pagina=\"$menos\" aria-label=\"Previous\"><span aria-hidden=\"true\">&laquo; Anterior</span></a></li>";
        echo "<li><a href=\"#\" class=\"troca_pag\" atr-pagina=\"1\">Pri.</a></li>";
    } else {

        echo "<li class=\"disabled\"><a href=\"#\" aria-label=\"Previous\"><span aria-hidden=\"true\">&laquo; Anterior</span></a></li>";
        echo "<li class=\"disabled\"><a href=\"#\">Pri.</a></li>";
    }

    // Listando as paginas
    for ($i = $ini_links; $i <= $fim_links; $i++) {
        if ($i != $pagina) {                                        
            echo "<li><a href=\"#\" class=\"troca_pag\" atr-pagina=\"$i\">$i</a></li>";
        } else {
            echo "<li class=\"active\"><a href=\"#\">$i <span class=\"sr-only\">(current)</span></a></li>";
        }
    }

    if ($mais <= $pgs) {
        echo "<li><a href=\"#\" class=\"troca_pag\" atr-pagina=\"$pgs\">Últ.</a></li>";
        echo "<li><a href=\"#\" class=\"troca_pag\" atr-pagina=\"$mais\" aria-label=\"Next\"><span aria-hidden=\"true\">Próximo &raquo;</span></a></li>";
    } else {
        echo "<li class=\"disabled\"><a href=\"#\">Últ.</a></li>";
        echo "<li class=\"disabled\"><a href=\"#\" aria-label=\"Next\"><span aria-hidden=\"true\">Próximo &raquo;</span></a></a></li>";
    }

    echo "</ul>";
    echo "</nav>";
}
?>