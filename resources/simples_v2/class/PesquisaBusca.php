<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['titulo']);

if($parametros['status']){

    if($parametros['status'] == -1){
        $filtro_status = "AND a.status = '0' ";
    }else{
        $filtro_status = "AND a.status = '".$parametros['status']."'";
    }
}

if($parametros['data_de'] && $parametros['data_ate']){
    $data_de = converteData($parametros['data_de']);
    $data_ate = converteData($parametros['data_ate']);

    $data_de = $data_de.' 00:00:00';
    $data_ate = $data_ate.' 23:59:59';

    $filtro_data = "AND a.data_criacao BETWEEN '$data_de' AND '$data_ate'";

}else if($parametros['data_de'] && !$parametros['data_ate']){
    $data_de = converteData($parametros['data_de']);

    $data_de = $data_de.' 00:00:00';

    $filtro_data = "AND a.data_criacao >= '$data_de'";

}else if(!$parametros['data_de'] && $parametros['data_ate']){

    $data_ate = converteData($parametros['data_ate']);

    $data_ate = $data_ate.' 23:59:59';

    $filtro_data = "AND a.data_criacao <= '$data_ate'";
}

// Informações da query
$filtros_query  = "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.status != 2 AND (nome LIKE '%$letra%' OR titulo LIKE '%$letra%') $filtro_status $filtro_data ORDER BY a.data_criacao DESC";

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
$dados = DBRead('','tb_pesquisa a',$filtros_query,"COUNT(*) AS 'num_registros'");
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
$dados = DBRead('', 'tb_pesquisa a',$filtros_query." LIMIT $inicio,$maximo","a.status AS status_pesquisa, a.*, b.*");
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
    echo "<th class=\"col-md-2\">Empresa</th>";
    echo "<th class=\"col-md-1\">Data</th>";
    echo "<th class=\"col-md-1\">Título</th>";
    echo "<th class=\"col-md-1\">Qtd. Contratada</th>";
    echo "<th class=\"col-md-1\">Qtd. Realizada</th>";
    echo "<th class=\"col-md-1\">Qtd. Contatos Pendentes</th>";
    echo "<th class=\"col-md-1\">Status</th>";
    echo "<th class=\"col-md-1\">Prazo de Término</th>";
    echo "<th class=\"col-md-2 text-center\">Opções</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($dados as $pesquisa) {
		
		if($pesquisa['status_pesquisa'] == 0){
        $status = "Concluido";
        }else if($pesquisa['status_pesquisa'] == 1){
		$status = "Ativo";
		}else if($pesquisa['status_pesquisa'] == 4){
		$status = "Pausado automaticamente";
        }else if($pesquisa['status_pesquisa'] == 3){
        $status = "Pausado";
        }else if($pesquisa['status_pesquisa'] == 5){
        $status = "Liberado";
        }

		$empresa = DBRead('', 'tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$pesquisa['id_contrato_plano_pessoa']."'");
        $nome_empresa = $empresa[0]['nome']; 
        $nome_contrato = $empresa[0]['nome_contrato'];
		$qtd_contratada = $empresa[0]['qtd_contratada'];   	
        $id = $pesquisa['id_pesquisa'];
        $titulo = $pesquisa['titulo'];
        $data = converteDataHora($pesquisa['data_criacao']);
        $tipo_cobranca = $empresa[0]['tipo_cobranca'];
        //############# Contatos realizados e diferença entre realizado e contratado
        if($tipo_cobranca == 'mensal'){

            $hoje = getDataHora();
            $hoje = converteDataHora($hoje);
            $data_hoje = explode("/", $hoje);
            $hoje = $data_hoje[0];
            $data_de_hoje = getDataHora();
            $data_de_hoje = converteDataHora($data_de_hoje);
            $mes_atual = "01/".$data_hoje[1]."/".$data_hoje[2];
            $mes_agora = explode(" ", $mes_atual);
            $mes_atual = $mes_agora[0]." 00:00";
            $data_de_hoje = converteDataHora($data_de_hoje);
            $mes_atual = converteDataHora($mes_atual);

            $cont_contato = DBRead('', 'tb_contatos_pesquisa', "WHERE status_pesquisa != 0 AND status_pesquisa != 4 AND id_pesquisa = '".$pesquisa['id_pesquisa']."' AND data_ultimo_contato >= '".$mes_atual."' GROUP BY id_pesquisa", "COUNT(*) AS cont, id_pesquisa");
            $cont = $cont_contato[0]['cont'];
        }else{

            $cont_contato = DBRead('', 'tb_contatos_pesquisa', "WHERE status_pesquisa != 0 AND id_pesquisa = '".$pesquisa['id_pesquisa']."' GROUP BY id_pesquisa", "COUNT(*) AS cont, id_pesquisa");
            $cont = $cont_contato[0]['cont'];
        }
            //$cont_falhas_completas = DBRead('', 'tb_falhas_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa WHERE a.falha = 8 AND a.id_pesquisa = '".$pesquisa['id_pesquisa']."' AND b.data_ultimo_contato >= '".$mes_atual."'", "COUNT(*) AS cont");

           //$cont_falhas_completas = $cont_falhas_completas[0]['cont'];
           //$cont = (int)$cont+ $cont_falhas_completas;


        $cont_contatos_faltantes = DBRead('', 'tb_contatos_pesquisa', "WHERE status_pesquisa = '0' AND id_pesquisa = '".$pesquisa['id_pesquisa']."' ", "COUNT(*) AS cont");
        $contatos_faltantes = $cont_contatos_faltantes[0]['cont'];

        if(($qtd_contratada < $cont)){
            $cont_realizada = "<strong><p class = 'text-danger'>".$cont."</p></strong>";
        }else if(($qtd_contratada - $cont) < 20){
            $cont_realizada = "<strong>".$cont."</strong>";
        }else{
            $cont_realizada = $cont;
        }
        if($cont == 0 || $cont == ''){
            $cont_realizada = 0;
        }  

        if($pesquisa['prazo_termino']){
            $prazo_termino = converteData($pesquisa['prazo_termino']);
        }else{
            $prazo_termino = 'N/D';
        }
                
        echo "<tr>";  
        echo "<td style='vertical-align: middle;'>$id</td>";  
        echo "<td style='vertical-align: middle;'>$nome_empresa";

        if($nome_contrato){
            echo " (".$nome_contrato.")";
        }
        
        echo "</td>";
        echo "<td style='vertical-align: middle;'>$data</td>";
        echo "<td style='vertical-align: middle;'>$titulo</td>";
        echo "<td style='vertical-align: middle;'>$qtd_contratada</td>";
        echo "<td style='vertical-align: middle;'>$cont_realizada</td>";
        echo "<td style='vertical-align: middle;'>$contatos_faltantes</td>";
        echo "<td style='vertical-align: middle;'>$status</td>";
        echo "<td style='vertical-align: middle;'>$prazo_termino</td>";
        echo "<td class=\"text-center\" style='vertical-align: middle;'>
        <a style='color:#8B008B;' href='/api/iframe?token=<?php echo $request->token ?>&view=gerenciar-pesquisa-form&alterar=$id' title='Editar'><i class='fa fa-pencil'></i> Editar</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        echo "<a style='color:#FF8C00;' href='/api/iframe?token=<?php echo $request->token ?>&view=gerenciar-pesquisa-pergunta-busca&alterar=$id' title='Perguntas'><i class='fa fa-question-circle'></i> Perguntas</a><br><br>";
        echo "<a style='color:#00008B;' href='/api/iframe?token=<?php echo $request->token ?>&view=gerenciar-pesquisa-contato-form&id_pesquisa=$id' title='Contatos'><i class='fa fa-vcard-o'></i> Contatos</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        echo "<a style='color:#8B0000;' href=\"class/Pesquisa.php?excluir=$id\" title='Excluir' onclick=\"if (!confirm(' Excluir a pesquisa - ".addslashes($titulo)." da empresa - ".addslashes($nome_empresa)."?')) { return false; } else { modalAguarde(); }\"><i class= 'fa fa-trash' ></i> Excluir </a></td>";
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