<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);
$controle_automatico_fila = addslashes($parametros['controle_automatico_fila']);
$tipo_fila_controle_automatico = addslashes($parametros['tipo_fila_controle_automatico']);
$pesquisa = addslashes($parametros['pesquisa']);
$aceita_ligacao = addslashes($parametros['aceita_ligacao']);

if($controle_automatico_fila !== ''){
	$filtro .= " AND controle_automatico_fila = '".$controle_automatico_fila."'";
}

if($tipo_fila_controle_automatico !== ''){
	$filtro .= " AND tipo_fila_controle_automatico = '".$tipo_fila_controle_automatico."'";
}

if($pesquisa !== ''){
	$filtro .= " AND pesquisa = '".$pesquisa."'";
}

if($aceita_ligacao !== ''){
	$filtro .= " AND aceita_ligacao = '".$aceita_ligacao."'";
}

// Informações da query
$filtros_query  = "WHERE status != 2 AND (nome LIKE '%$letra%' OR id LIKE '$letra%') $filtro ORDER BY nome ASC";

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
$dados = DBRead('snep','empresas',$filtros_query,"COUNT(*) AS 'num_registros'");
$total = $dados[0]['num_registros'];

// Calculando o registro inicial
$inicio = $maximo * ($pagina - 1);
if($inicio >= $total){
    $inicio = 0;
    $pagina = 1;
}

###################################################################################
// INICIO DO CONTEÚDO
// 
$dados = DBRead('snep','empresas',$filtros_query." LIMIT $inicio,$maximo");
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
    echo "<th class=\"col-md-1\">Prefixo</th>";
    echo "<th class=\"col-md-2\">Nome</th>";
    echo "<th class=\"col-md-2\">Fila 1</th>";
    echo "<th class=\"col-md-2\">Fila 2</th>";
    echo "<th class=\"col-md-1\">Aceita Ligações</th>";    
    echo "<th class=\"col-md-1\">Pesquisa</th>";    
    echo "<th class=\"col-md-1\">Controle Automático de Filas</th>";
    echo "<th class=\"col-md-1\">Tipo Fila Controle Automático</th>";
    echo "<th class=\"col-md-1 text-center\">Opções</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($dados as $conteudo) {
        $id = $conteudo['id'];
        $nome = $conteudo['nome'];
        $fila1 = $conteudo['fila1'];
        $fila2 = $conteudo['fila2'];
        $controle_automatico_fila = $conteudo['controle_automatico_fila'] ? 'Sim' : 'Não';
        $pesquisa = $conteudo['pesquisa'] ? 'Sim' : 'Não';
        $aceita_ligacao = $conteudo['aceita_ligacao'] ? 'Sim' : 'Não';
        $status = $conteudo['status'];

        $tipo_fila_controle_automatico = $conteudo['tipo_fila_controle_automatico'] == 'interna' ? ucfirst($conteudo['tipo_fila_controle_automatico']) : ucfirst($conteudo['tipo_fila_controle_automatico'])." (EXT)";

        if($conteudo['tipo_fila_controle_automatico'] == 'interna'){
            $tipo_fila_controle_automatico = "Interna";
        }else if($conteudo['tipo_fila_controle_automatico'] == 'experiencia'){
            $tipo_fila_controle_automatico = "Experiência (EXP)";
        }else{
            $tipo_fila_controle_automatico = "Externa (EXT)";
        }
        
        if ($status != 1) {
			echo '<tr class="warning">';
		} else {
			echo "<tr>";
		}

		if($conteudo['status'] == 0){
			$status = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"class/PrefixoCentral.php?ativar=$id\" title='Ativar' onclick=\"if (!confirm('Ativar " . addslashes($nome) . "?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-upload' style='color:#228B22;'></i></a>";

		}else if($conteudo['status'] == 1){
			$status = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"class/PrefixoCentral.php?desativar=$id\" title='Desativar' onclick=\"if (!confirm('Desativar " . addslashes($nome) . "?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-download' style='color:#FFA500;'></i></a>";
        }
        
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=prefixo-central-form&alterar=$id'\" style='cursor: pointer'>$id-</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=prefixo-central-form&alterar=$id'\" style='cursor: pointer'>$nome</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=prefixo-central-form&alterar=$id'\" style='cursor: pointer'>$fila1</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=prefixo-central-form&alterar=$id'\" style='cursor: pointer'>$fila2</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=prefixo-central-form&alterar=$id'\" style='cursor: pointer'>$aceita_ligacao</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=prefixo-central-form&alterar=$id'\" style='cursor: pointer'>$pesquisa</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=prefixo-central-form&alterar=$id'\" style='cursor: pointer'>$controle_automatico_fila</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=prefixo-central-form&alterar=$id'\" style='cursor: pointer'>$tipo_fila_controle_automatico</td>";
        echo "<td class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=prefixo-central-form&alterar=$id' title='Alterar'><i class='fa fa-pencil'></i></a>".$status."</td>";
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
$fim_links = ((($pagina+$lim_links) < $pgs) ? $pagina+$lim_links : $pgs);

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