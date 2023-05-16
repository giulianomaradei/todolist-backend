<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);
$situacao = addslashes($parametros['situacao']);
$plano = addslashes($parametros['plano']);
$filtro_plano = '';
$filtro_situacao = '';
$filtro_data = '';

if($plano){
    $filtro_plano = " AND b.id_plano = '".$plano."' ";
}

if($situacao){
	if($situacao == 1){
		$filtro_situacao = " AND a.status = '1' OR a.status = '4'";
	}else{
		$filtro_situacao = " AND a.status = '".$situacao."' ";
	}
}

if($parametros['data_de'] && $parametros['data_ate']){
    $data_de = converteData($parametros['data_de']);
    $data_ate = converteData($parametros['data_ate']);

    $data_de = $data_de.' 00:00:00';
    $data_ate = $data_ate.' 23:59:59';

    $filtro_data = "AND (a.data_cadastro BETWEEN '$data_de' AND '$data_ate')";

}else if($parametros['data_de'] && !$parametros['data_ate']){
    $data_de = converteData($parametros['data_de']);

    $data_de = $data_de.' 00:00:00';

    $filtro_data = "AND a.data_cadastro >= '$data_de'";

}else if(!$parametros['data_de'] && $parametros['data_ate']){

    $data_ate = converteData($parametros['data_ate']);

    $data_ate = $data_ate.' 23:59:59';

    $filtro_data = "AND a.data_cadastro <= '$data_ate'";
}

// Informações da query
$filtros_query = "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_plano d ON b.id_plano = d.id_plano INNER JOIN tb_usuario_painel e ON a.id_usuario_painel = e.id_usuario_painel INNER JOIN tb_pessoa f ON e.id_pessoa_usuario = f.id_pessoa WHERE (c.nome LIKE '%$letra%' OR a.descricao LIKE '%$letra%' OR f.nome LIKE '%$letra%') ".$filtro_plano." ".$filtro_situacao." ".$filtro_data." ";

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
$dados = DBRead('', 'tb_alerta_painel a', $filtros_query, "COUNT(*) AS 'num_registros'");
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
$dados = DBRead('', 'tb_alerta_painel a', $filtros_query . "ORDER BY a.data_cadastro DESC LIMIT $inicio,$maximo","a.*, b.nome_contrato, c.nome AS nome_cliente, d.cod_servico, d.nome AS nome_plano, f.nome AS nome_usuario_painel");
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
					echo "<th class=\"col-md-2\">Cliente</th>";
					echo "<th class=\"col-md-2\">Serviço</th>";
					echo "<th class=\"col-md-2\">Descrição</th>";
					echo "<th class=\"col-md-1\">Usuário Painel</th>";					
					echo "<th class=\"col-md-1\">Data do Cadastro</th>";
					echo "<th class=\"col-md-1\">Data da Resposta</th>";
					echo "<th class=\"col-md-1\">Status</th>";
					echo "<th class=\"col-md-1 text-center\">Opções</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";
			foreach ($dados as $conteudo) {

				$id_alerta_painel = $conteudo['id_alerta_painel'];
				
				$nome_cliente = $conteudo['nome_cliente'];
				if($conteudo['nome_contrato']){
	                $nome_contrato = " (".$conteudo['nome_contrato'].") ";
	            }else{
	                $nome_contrato = '';
	            }
	            $nome = $nome_cliente."".$nome_contrato;

	            $servico = getNomeServico($conteudo['cod_servico'])." - ".$conteudo['nome_plano'];
                $descricao = $conteudo['descricao'];
                $nome_usuario_painel = $conteudo['nome_usuario_painel'];                
                $data_cadastro = converteDataHora($conteudo['data_cadastro']);

				if($conteudo['status'] == 1){
					$dados_alerta_painel = DBRead('', 'tb_alerta', "WHERE id_alerta_painel = '".$id_alerta_painel."'");
					if($dados_alerta_painel){
						$status = "Alteração";
						$data_resposta = converteDataHora($conteudo['data_resposta']);

					}else{
						$status = "Pendente";
						$data_resposta = '';
					}
                	$opcoes = "<a href='/api/iframe?token=$request->token&view=alerta-form&avaliar=$id_alerta_painel' title='Avaliar'><i class='fas fa-thumbs-up' style='color:#FFBF00;'></i></a>";
				}else if($conteudo['status'] == 2){
                	$status = "Aprovado";
                	$opcoes = "<a href='/api/iframe?token=$request->token&view=alerta-form&visualizar=$id_alerta_painel' title='Visualizar'><i class='fa fa-eye'></i></a>";
                	$data_resposta = converteDataHora($conteudo['data_resposta']);
				}else if($conteudo['status'] == 3){
                	$status = "Vencido";
                	$opcoes = "<a href='/api/ifra$request->token&view=alerta-form&visualizar=$id_alerta_painel' title='Visualizar'><i class='fa fa-eye'></i></a>";
                	$data_resposta = converteDataHora($conteudo['data_resposta']);
				}else if($conteudo['status'] == 4){
                	$status = "Cancelamento Pendente";
                	$opcoes = "<a href='/api/ifra$request->token&view=alerta-form&cancelar=$id_alerta_painel' title='Cancelar'><i class='fas fa-times' style='color:#B40404;'></i></a>";
                	$data_resposta = converteDataHora($conteudo['data_resposta']);
				}else if($conteudo['status'] == 5){
                	$status = "Descartado";
                	$opcoes = "<a href='/api/ifra$request->token&view=alerta-form&visualizar=$id_alerta_painel' title='Visualizar'><i class='fa fa-eye'></i></a>";
                	$data_resposta = converteDataHora($conteudo['data_resposta']);
				}

                echo "<tr>";	
				 	echo "<td style='vertical-align: middle;'>$id_alerta_painel</td>";
					echo "<td style='vertical-align: middle;'>$nome</td>";
					echo "<td style='vertical-align: middle;'>$servico</td>";
					echo "<td style='vertical-align: middle;'><span data-toggle=\"tooltip\" title=\"".limitarTexto($descricao, 300)."\">".limitarTexto($descricao, 30)."</span></td>";
					echo "<td style='vertical-align: middle;'>$nome_usuario_painel</td>";
					echo "<td style='vertical-align: middle;'>$data_cadastro</td>";
					echo "<td style='vertical-align: middle;'>$data_resposta</td>";
					echo "<td style='vertical-align: middle;'>$status</td>";
					
					echo "<td class='text-center' style='vertical-align: middle;'>".$opcoes."</td>";
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
<script>
    $(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>