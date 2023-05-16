<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['titulo']);
$id_categoria = "";
$id_visibilidade = "";
$id_perfil = "";
$id_responsavel = "";
$id_chamado_origem = "";
$tipo_remetente = "";
$responsavel_tecnico = "";

if($parametros['id_perfil']){
    //$id_perfil = "AND i.id_perfil_sistema = '".$parametros['id_perfil']."'";/*  */
    $id_perfil = "AND (EXISTS (SELECT id_perfil_sistema FROM tb_chamado_perfil WHERE id_perfil_sistema = '".$parametros['id_perfil']."' AND id_chamado = a.id_chamado))";
}

// if($parametros['id_remetente']){
//     $id_remetente = "AND a.id_usuario_remetente = '".$parametros['id_remetente']."'";
// }

if($parametros['id_categoria']){
    $id_categoria = "AND f.id_categoria = '".$parametros['id_categoria']."'";
}

if($parametros['visibilidade']){
    $id_visibilidade = "AND a.visibilidade = '".$parametros['visibilidade']."'";
}

if($parametros['id_chamado']){
    $id_chamado = "AND a.id_chamado = '".$parametros['id_chamado']."'";
}

if($parametros['id_responsavel']){
    
    $id_responsavel = "AND a.id_usuario_responsavel = '".$parametros['id_responsavel']."'";
}

if($parametros['responsavel_tecnico']){
    
    $responsavel_tecnico = "AND g.id_responsavel_tecnico = '".$parametros['responsavel_tecnico']."'";
}

if($parametros['id_status']){
    if($parametros['id_status'] == '-1'){
        $id_status = "AND (a.id_chamado_status != 3 AND a.id_chamado_status != 4)";
    }else if($parametros['id_status'] == '-2'){
        $id_status = "AND (a.id_chamado_status = 3 OR a.id_chamado_status = 4)";
    }else if($parametros['id_status'] == '-3'){
        $id_status = "AND a.data_pendencia IS NOT NULL AND (a.id_chamado_status != 3 AND a.id_chamado_status != 4)";
    }else{
        $id_status = "AND a.id_chamado_status = '".$parametros['id_status']."'";
    }
}

if($parametros['id_chamado_origem']){
    $id_chamado_origem = " AND b.id_chamado_origem = '".$parametros['id_chamado_origem']."'";
}

if($parametros['busca_contrato']){
    $letra_contrato = addslashes($parametros['busca_contrato']);
    $busca_contrato =  " AND h.nome LIKE '%$letra_contrato%'";
}

if($parametros['tipo_remetente']){
    if($parametros['busca_remetente']){
        if($parametros['tipo_remetente'] == 1){
            $letra_remetente = addslashes($parametros['busca_remetente']);
            $consulta_query =  " AND l.nome LIKE '%$letra_remetente%'";
            $variavel = ", l.nome as remetente";
        }else if($parametros['tipo_remetente'] == 2){
            $letra_remetente = addslashes($parametros['busca_remetente']);
            $consulta_query =  " AND j.nome LIKE '%$letra_remetente%'";
            $variavel = ", j.nome as remetente";
        }
    }

}


$usuario = $_SESSION['id_usuario'];

$id_perfil_sistema = DBRead('', 'tb_usuario', "WHERE id_usuario = '$usuario'", 'id_perfil_sistema');

$vinculos = getVinculos($id_perfil_sistema[0]['id_perfil_sistema']);
$vinculos = mudaVinculoArray($vinculos);

if ($vinculos != '') {
    $filtro_in = '(';

    foreach ($vinculos as $key => $vinculo) {
        if ($key === array_key_last($vinculos)) {
            $filtro_in .= $vinculo;
        } else {
            $filtro_in .= $vinculo.',';
        }
        
    }
    $filtro_in .= ')';

    $filtro_vinculos = " OR (EXISTS (SELECT id_perfil_sistema FROM tb_chamado_perfil WHERE id_perfil_sistema IN $filtro_in AND id_chamado = a.id_chamado)) ";
}

$filtros_query = " INNER JOIN tb_chamado_origem b ON a.id_chamado_origem = b.id_chamado_origem INNER JOIN tb_usuario d ON d.id_usuario = a.id_usuario_responsavel INNER JOIN tb_pessoa e ON e.id_pessoa = d.id_pessoa INNER JOIN tb_chamado_categoria f ON f.id_chamado = a.id_chamado LEFT JOIN tb_contrato_plano_pessoa g ON a.id_contrato_plano_pessoa = g.id_contrato_plano_pessoa LEFT JOIN tb_pessoa h ON g.id_pessoa = h.id_pessoa LEFT JOIN tb_usuario i ON a.id_usuario_remetente = i.id_usuario LEFT JOIN tb_pessoa j ON j.id_pessoa = i.id_pessoa LEFT JOIN tb_usuario_painel k ON a.id_usuario_remetente = k.id_usuario_painel LEFT JOIN tb_pessoa l ON k.id_pessoa_usuario = l.id_pessoa  WHERE (a.titulo LIKE '%$letra%' $busca_contrato $consulta_query) $id_remetente $id_visibilidade $id_categoria $id_status $id_chamado $id_perfil $id_responsavel $id_chamado_origem $responsavel_tecnico AND ((EXISTS (SELECT id_perfil_sistema FROM tb_chamado_perfil WHERE id_perfil_sistema = '".$id_perfil_sistema[0]['id_perfil_sistema']."' AND id_chamado = a.id_chamado) $filtro_vinculos OR EXISTS (SELECT id_usuario FROM tb_chamado_usuario WHERE id_usuario = '$usuario' AND id_chamado = a.id_chamado) ) OR a.id_usuario_remetente = '$usuario' OR a.id_usuario_responsavel = '$usuario') GROUP BY a.id_chamado, e.nome ORDER BY a.id_chamado DESC";

//Maximo de registros por pagina
$maximo = 10;

//Limite de links(antes e depois da pagina atual) da paginação
$lim_links = 5;

//Declaração da pagina inicial
$pagina = $parametros['pagina'];
if($pagina == ''){
    $pagina = 1;
}

// Conta os resultados no total da query  
$dados = DBRead('','tb_chamado a',$filtros_query,"COUNT(*) AS 'num_registros'");

if($dados){
    $total = sizeof($dados);
}

// Calculando o registro inicial
$inicio = $maximo * ($pagina - 1);

if($inicio >= $total){
    $inicio = 0;
    $pagina = 1;
}

###################################################################################
// INICIO DO CONTEÚDO
$dados = DBRead('', 'tb_chamado a', $filtros_query." LIMIT $inicio,$maximo","a.id_chamado, e.nome AS responsavel, a.id_chamado_origem, a.id_usuario_remetente, a.titulo, a.descricao, a.id_chamado_status, a.data_pendencia, b.descricao as origem, j.nome AS remetente1, l.nome AS remetente2, g.id_contrato_plano_pessoa AS id_contrato ".$variavel.", CASE WHEN a.id_chamado_origem = 4 THEN (SELECT bb.nome FROM bd_simples.tb_usuario_painel aa INNER JOIN tb_pessoa bb ON aa.id_pessoa_usuario = bb.id_pessoa WHERE aa.id_usuario_painel = a.id_usuario_remetente $consulta_query2) ELSE (SELECT bb.nome FROM bd_simples.tb_usuario aa INNER JOIN tb_pessoa bb ON aa.id_pessoa = bb.id_pessoa WHERE aa.id_usuario = a.id_usuario_remetente $consulta_query2) END AS remetente ");

// echo "<pre>";
//     var_dump($dados[0]['remetente']);
// echo "</pre>";

if (!$dados) {
    echo "<p class='alert alert-warning' style='text-align: center'>";
    if (!$letra) {
        echo "Não foram encontrados registros!";
    } else {
        echo "Nenhum resultado encontrado na busca por \"<strong>$letra</strong>\"";
    }
    echo "</p>";
} else {
    echo '
    <script>
        $(function(){
            $(\'[data-toggle="tooltip"]\').tooltip();
        });
    </script>
    ';
    echo "<div class='table-responsive'>";
    echo "<table class='table table-hover' style='font-size: 14px;'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>#</th>";
    echo "<th></th>";
    echo "<th>Título</th>";
    echo "<th>Origem</th>";
    echo "<th>Remetente</th>";
    echo "<th>Responsável</th>";
    echo "<th>Categoria</th>";
    echo "<th>Data da criação</th>";
    echo "<th>Status</th>";
    echo "<th>Contrato</th>";
    echo "<th class=\"text-center\">Visualizar</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach($dados as $conteudo){
        $dados_acao = DBRead('', 'tb_chamado_acao a', "INNER JOIN tb_chamado_status b ON a.id_chamado_status = b.id_chamado_status WHERE a.id_chamado = '".$conteudo['id_chamado']."' ORDER BY a.data DESC LIMIT 1", "a.*, b.descricao AS status_descricao");

        $data_criacao = DBRead('', 'tb_chamado_acao', "WHERE id_chamado = '".$conteudo['id_chamado']."' AND acao = 'criacao'", 'data');

        $id_chamado_acao = DBRead('', 'tb_chamado_acao', " WHERE id_chamado = '".$conteudo['id_chamado']."' ORDER BY data DESC LIMIT 1", 'id_chamado_acao');

        $visualizado = DBRead('', 'tb_chamado_visualizacao a', "WHERE a.id_chamado_acao = '".$id_chamado_acao[0]['id_chamado_acao']."' AND a.id_usuario = '".$usuario."' ", 'COUNT(*) as visualizado');

        $ignora = DBRead('', 'tb_chamado_ignora', "WHERE id_chamado = '".$conteudo['id_chamado']."' AND id_usuario = '".$_SESSION['id_usuario']."'", 'COUNT(*) as ignora');

        $ultima_acao = DBRead('', 'tb_chamado_acao a', "WHERE id_chamado NOT IN (SELECT id_chamado FROM tb_chamado_pendencia WHERE id_chamado = '".$conteudo['id_chamado']."') AND a.id_chamado = '".$conteudo['id_chamado']."' AND (a.id_chamado_status != 3 AND a.id_chamado_status != 4) ORDER BY data DESC limit 1;", 'data');

        $notifica = "";
        $icone_acao = "";

        if($ultima_acao == false){

        }else{
            if( (strtotime($ultima_acao[0]['data']) <= strtotime('-7 day')) && ($conteudo['id_chamado_status'] != 3 && $conteudo['id_chamado_status'] != 4) ) {
                $icone_acao = '<i class="fa fa-clock-o" data-toggle="tooltip" title="Sem atualizações desde: '.converteDataHora($ultima_acao[0]['data']).'"></i>';
            }
        }

        if($conteudo['data_pendencia'] == ''){
            $icone_pendencia = '';
        }else{
            if($conteudo['id_chamado_status'] == 3 || $conteudo['id_chamado_status'] == 4){
                $icone_pendencia = '';
            }else{
                if( converteDataHora($conteudo['data_pendencia']) <= converteDataHora(getDataHora()) ){
                    $icone_pendencia = '<i class="fa fa-thumb-tack" data-toggle="tooltip" title="Pendência vencida: '.converteDataHora($conteudo['data_pendencia']).'" style="color: #8B1A1A"></i>';
                }else{
                    $notifica = '';
                    $icone_pendencia = '<i class="fa fa-thumb-tack" data-toggle="tooltip" title="Pendência para: '.converteDataHora($conteudo['data_pendencia']).'" "></i>';
                }
            }
        }

        if($visualizado[0]['visualizado'] == 0 && $ignora[0]['ignora'] == 0 ){
            $notifica = "warning";
        }else if($visualizado[0]['visualizado'] == 1 && $ignora[0]['ignora'] == 1){
            $icone_ignora = '<i class="fa fa-bell-slash" data-toggle="tooltip" title="Não recebendo notificações" aria-hidden="true"></i>';
        }else if($visualizado[0]['visualizado'] == 0 && $ignora[0]['ignora'] == 1){
            $icone_ignora = '<i class="fa fa-bell-slash" data-toggle="tooltip" title="Não recebendo notificações" aria-hidden="true"></i>';
        }else{
            $icone_ignora = '';
        }

        $categorias = DBRead('', 'tb_chamado_categoria a', "INNER JOIN tb_categoria b ON a.id_categoria = b.id_categoria WHERE a.id_chamado = '".$conteudo['id_chamado']."' ", 'b.nome');
        
        $nome_categoria = '';
        
        if($categorias){
            foreach($categorias as $c){
                $nome_categoria .= $c['nome'].", ";
            }
        }

        $contrato_plano_pessoa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa = '".$conteudo['id_contrato']."'", "a.id_contrato_plano_pessoa, a.nome_contrato, b.nome, c.cod_servico AS 'servico', c.nome AS 'plano'");
        if($contrato_plano_pessoa){
            if($contrato_plano_pessoa[0]['nome_contrato']){
                $nome_contrato = " (".$contrato_plano_pessoa[0]['nome_contrato'].") ";
            }else{
                $nome_contrato = "";
            }
            $contrato = $contrato_plano_pessoa[0]['nome'] . " ". $nome_contrato ." - " . getNomeServico($contrato_plano_pessoa[0]['servico']) . " - " . $contrato_plano_pessoa[0]['plano'] . " (" . $contrato_plano_pessoa[0]['id_contrato_plano_pessoa'] . ")";
            //$contrato = $contrato_plano_pessoa[0]['nome'] . " ". $nome_contrato ." (" . $contrato_plano_pessoa[0]['id_contrato_plano_pessoa'] . ")";
        }else{
            $contrato = '';
        }
        
        $nome_categoria = substr($nome_categoria, 0, strlen($nome_categoria) - 2);

        echo "<tr class='$notifica'>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&chamado=".$conteudo['id_chamado']."'\" style='cursor: pointer'>".$conteudo['id_chamado']."</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&chamado=".$conteudo['id_chamado']."'\" style='cursor: pointer'>".$icone_ignora." ".$icone_acao." ".$icone_pendencia."</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&chamado=".$conteudo['id_chamado']."'\" style='cursor: pointer'><span data-toggle=\"tooltip\" title=\"".$conteudo['titulo']."\">".limitarTexto($conteudo['titulo'], 30)."</span></td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&chamado=".$conteudo['id_chamado']."'\" style='cursor: pointer'>".$conteudo['origem']."</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&chamado=".$conteudo['id_chamado']."'\" style='cursor: pointer'>".$conteudo['remetente']."</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&chamado=".$conteudo['id_chamado']."'\" style='cursor: pointer'>".$conteudo['responsavel']."</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&chamado=".$conteudo['id_chamado']."'\" style='cursor: pointer' title='$nome_categoria'>".limitarTexto($nome_categoria, 35)."</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&chamado=".$conteudo['id_chamado']."'\" style='cursor: pointer'>".converteDataHora($data_criacao[0]['data'])."</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&chamado=".$conteudo['id_chamado']."'\" style='cursor: pointer'>".$dados_acao[0]['status_descricao']."</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&chamado=".$conteudo['id_chamado']."'\" style='cursor: pointer'>".$contrato."</td>";
        echo "<td class=\"text-center\" onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&chamado=".$conteudo['id_chamado']."'\" style='cursor: pointer'>";

        echo "<a class='a_modalAguarde' href='/api/iframe?token=<?php echo $request->token ?>&view=chamado-informacoes&chamado=".$conteudo['id_chamado']."' title='Visualizar'><i class='fa fa fa-eye'></i></a>";

        echo "</td>";
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