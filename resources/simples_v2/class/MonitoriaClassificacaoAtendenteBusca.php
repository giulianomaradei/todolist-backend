<?php
require_once "System.php";

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);
$classificacao = $parametros['classificacao'];
$canal_atendimento = $parametros['canal_atendimento'];

if ($classificacao) {
    $filtro_classificacao = "AND c.tipo_classificacao = $classificacao";
}

if ($canal_atendimento == 1) {
    $filtro_canal_atendimento = "AND c.voz = 1";

} else if ($canal_atendimento == 2) {
    $filtro_canal_atendimento = "AND c.texto = 1";
}

// Informações da query
$filtros_query = "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa LEFT JOIN tb_monitoria_classificacao_usuario c ON b.id_usuario = c.id_usuario WHERE (a.nome LIKE '%$letra%' OR a.razao_social LIKE '%$letra%') $filtro_classificacao $filtro_canal_atendimento AND a.status != 2 AND b.status = 1 AND (b.id_perfil_sistema = '3' OR b.id_perfil_sistema = '13' OR b.id_perfil_sistema = '15') ORDER BY a.nome ASC";

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
$dados = DBRead('', 'tb_pessoa a', $filtros_query, "COUNT(*) AS 'num_registros'");
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
$dados = DBRead('', 'tb_pessoa a', $filtros_query . " LIMIT $inicio,$maximo", "a.nome, b.id_usuario, b.id_usuario as usuario, c.*");
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
	echo "<th class=\"col-md-4\">Nome</th>";
    echo "<th class=\"col-md-3\">Classificação</th>";
    echo "<th class=\"col-md-4\">Canal de atendimento</th>";
	echo "<th class=\"col-md-1 text-center\">Opções</th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	foreach ($dados as $conteudo) {

		$id = $conteudo['usuario'];
		$nome = $conteudo['nome'];
        
        //$dados_classificacao = DBRead('', 'tb_monitoria_classificacao_usuario', "WHERE id_usuario = $id");

        if ($conteudo['id_monitoria_classificacao_usuario']) {
            $classificacao = $conteudo['tipo_classificacao'];

            if ($classificacao == 1) {
                $classificacao = 'Em treinamento';

            } else if ($classificacao == 2) {
                $classificacao = 'Período de experiência';

            } else {
                $classificacao = 'Efetivado';
            }

            $texto = $conteudo['texto'];
            $voz = $conteudo['voz'];

            if ($texto == '1' && $voz == '1') {
                $legenda_avaliar = '<strong>Telefone</strong> e <strong>Texto</strong>';

            } else if ($texto == '1' && $voz == '2') {
                $legenda_avaliar = '<strong>Texto</strong>';

            } else if ($texto == '2' && $voz == '1') {
                $legenda_avaliar = '<strong>Telefone</strong>';

            } else {
                $legenda_avaliar = 'Nenhum';
            }

        } else {
            $classificacao = 'ND';
            $legenda_avaliar = 'ND';
        }
        
		echo "<tr onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=monitoria-classificacao-atendente-form&alterar=$id'\" style='cursor: pointer'>";
		echo "<td>$nome</td>";
		echo "<td>$classificacao</td>";
		echo "<td>$legenda_avaliar</td>";
		echo "<td class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=monitoria-classificacao-atendente-form&alterar=$id' title='Alterar'><i class='fa fa-pencil'></i></a></td>";
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