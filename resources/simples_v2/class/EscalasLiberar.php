<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['titulo']);
$id_usuario = $_SESSION['id_usuario'];

// Informações da query
$filtros_query  = "GROUP BY data_inicial ORDER BY data_inicial DESC";

// Maximo de registros por pagina
$maximo = 10000;

// Limite de links(antes e depois da pagina atual) da paginação
$lim_links = 5;

// Declaração da pagina inicial
$pagina = $parametros['pagina'];
if($pagina == ''){
    $pagina = 1;
}   

// Conta os resultados no total da query  
$dados = DBRead('','tb_horarios_escala',$filtros_query,"COUNT(*) AS 'num_registros', data_inicial");
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
	$dados = DBRead('', 'tb_horarios_escala',$filtros_query." LIMIT $inicio, $maximo","data_inicial");
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
	    echo "<th class=\"col-md-8\">Mês/Ano</th>";
	  
	    echo "<th class=\"col-md-4 text-center\">Liberar/Bloquear</th>";
	    echo "</tr>";
	    echo "</thead>";
	    echo "<tbody>";
	    $contador = 0;
		    foreach ($dados as $escalas) {
		      
		        //############# Contatos realizados e diferença entre realizado e contratado

		        echo "<tr>";    

		        $hoje = explode("-", $escalas['data_inicial']);
				$ano_hoje = $hoje[0];
		        
				if($hoje[1] == "01"){
					$mes = "Janeiro";
					}else if($hoje[1] == "02"){
					$mes = "Fevereiro";
					}else if($hoje[1] == "03"){
					$mes = "Março";
					}else if($hoje[1] == "04"){
					$mes = "Abril";
					}else if($hoje[1] == "05"){
					$mes = "Maio";
					}else if($hoje[1] == "06"){
					$mes = "Junho";
					}else if($hoje[1] == "07"){
					$mes = "Julho";
					}else if($hoje[1] == "08"){
					$mes = "Agosto";
					}else if($hoje[1] == "09"){
					$mes = "Setembro";
					}else if($hoje[1] == "10"){
					$mes = "Outubro";
					}else if($hoje[1] == "11"){
					$mes = "Novembro";
					}else if($hoje[1] == "12"){
					$mes = "Dezembro";
				}

		        echo "<td>".$mes."/".$ano_hoje."</td>";
		      	
		        $dados = DBRead('', 'tb_horarios_escala',"WHERE data_inicial = '".$escalas['data_inicial']."'");

		        $data_inicial = $escalas['data_inicial'];

		        if($dados[0]['liberado'] == 0){
					$status = "<a href=\"class/EscalaHorarios.php?ativar=$data_inicial\" title='Liberar' onclick=\"if (!confirm('Liberar " . addslashes($mes."/".$ano_hoje) . "?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-upload' style='color:#228B22;'></i></a>";

				}else if($dados[0]['liberado'] == 1){
					$status = "<a href=\"class/EscalaHorarios.php?desativar=$data_inicial\" title='Bloquear' onclick=\"if (!confirm('Bloquear " . addslashes($mes."/".$ano_hoje) . "?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-download' style='color:#FFA500;'></i></a>";

				}

				echo "<td class=\"text-center\">".$status."</td>";
				
		        $cont++;
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