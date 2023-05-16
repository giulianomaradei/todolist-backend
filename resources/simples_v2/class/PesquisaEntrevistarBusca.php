<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['titulo']);

$letra = addslashes($parametros['nome']);

// Informações da query
$filtros_query  = "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE (c.nome LIKE '%$letra%' OR a.titulo LIKE '%$letra%') AND (a.status = 1 OR a.status = 5) ";

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

$dados = DBRead('', 'tb_pesquisa a',$filtros_query." LIMIT $inicio,$maximo","c.nome, a.titulo, a.id_pesquisa, a.prazo_termino, b.qtd_contratada, a.horas_entre_tentativas");
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
        echo '
        <table class="table table-hover" style="font-size: 14px;">
            <thead>
                <tr>
                    <th class="col-md-1">#</th>
                    <th class="col-md-2">Empresa</th>
                    <th class="col-md-3">Título</th>
                    <th class="col-md-1">Qtd. de Entrevistas Pendentes</th>
                    <th class="col-md-1">Qtd. Contatos Disponíveis</th>
                    <th class="col-md-1">Prazo de Término</th>
                    <th class="col-md-1">Contato</th>
                    <th class="col-md-1">Pesquisa</th>
                    <th class="col-md-1">Próximo Agendamento</th>
                </tr>
            </thead>
            <tbody>';

            foreach($dados as $pesquisa){
                    /*
                        * Só pra saber o primerio dia do mes corrente
                    */
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
                    
                    /*
                        * Busca maximo de contatos com status = 1, ou seja, que já foram entrevistados
                    */

                    $cont_contato = DBRead('', 'tb_contatos_pesquisa', "WHERE status_pesquisa != 0 AND id_pesquisa = '".$pesquisa['id_pesquisa']."' AND data_ultimo_contato >= '".$mes_atual."' GROUP BY id_pesquisa", "COUNT(*) AS cont, id_pesquisa");
                    $cont['cont'] = $cont_contato[0]['cont'];
                    if(!$cont['cont']){
                        $cont['cont'] = 0;
                    }
                    
                    /*
                        * Maximo de contatos com status = 0, am aberto
                    */

                    $select_contatos = DBRead('', 'tb_contatos_pesquisa', "WHERE status_pesquisa = 0 AND id_pesquisa = '".$pesquisa['id_pesquisa']."'GROUP BY id_pesquisa", "COUNT(*) AS cont, id_pesquisa");
                    $max_contatos['cont'] = $select_contatos[0]['cont'];
                
                    if($max_contatos){
                        $entrevistar = '<a class="" href="/api/iframe?token=<?php echo $request->token ?>&view=pesquisa-entrevistar-form&id_pesquisa='.$pesquisa['id_pesquisa'].'">Entrevistar</a>';

                        if(($pesquisa['qtd_contratada'] - $cont['cont']) <= $max_contatos['cont'] ){
                                    
                            $pendente = $pesquisa['qtd_contratada'] - $cont['cont'];
                            
                            if($pesquisa['qtd_contratada'] <= $cont['cont']){
                                $pendente = $max_contatos['cont'];
                            }

                        }
                        if(($pesquisa['qtd_contratada'] - $cont['cont']) <= $pesquisa['qtd_contratada']){
                            $pendente = $pesquisa['qtd_contratada'] - $cont['cont'];
                            if($cont['cont'] > $pesquisa['qtd_contratada']){
                                $pendente = $max_contatos['cont'];
                            }
                        }
                        
                    }else{
                        if(($pesquisa['qtd_contratada'] - $cont['cont']) > '0'){
                            $pendente = $pesquisa['qtd_contratada']  - $cont['cont'];
                        }else{
                            $pendente = $max_contatos['cont'];
                        }
                    

                    }

                    if(!$pendente){
                        if(($pesquisa['qtd_contratada'] - $cont['cont']) > '0'){
                            $pendente = $pesquisa['qtd_contratada'] - $cont['cont'];
                        }else{
                            $pendente = '0';
                            $entrevistar = "";
                        }
                        
                    }
                            echo "<tr>";
                                echo "<td style='vertical-align:middle;'>";
                                    echo $pesquisa['id_pesquisa'];
                                echo "</td>";
                                echo "<td style='vertical-align:middle;'>";
                                    echo $pesquisa['nome'];
                                echo "</td>";
                                echo "<td style='vertical-align:middle;'>";
                                    echo $pesquisa['titulo'];
                                echo "</td>";
                                echo "<td style='vertical-align:middle;'>";
                                    echo $pendente;
                                echo "</td>";

                                $data_horas_entre_tentativas = new DateTime(getDataHora());
                                $data_horas_entre_tentativas->modify('- '.$pesquisa['horas_entre_tentativas'].' hours');
                                $data_horas_entre_tentativas = $data_horas_entre_tentativas->format('Y-m-d H:i:s');

                                $data_hora_intervalo_15_segundos = new DateTime(getDataHora());
                                $data_hora_intervalo_15_segundos->modify('- 15 seconds');
                                $data_hora_intervalo_15_segundos = $data_hora_intervalo_15_segundos->format('Y-m-d H:i:s');
                                    
                                $data_de_hoje = getDataHora();
                                $clientes = DBRead('', 'tb_contatos_pesquisa a', "INNER JOIN tb_pesquisa b ON a.id_pesquisa = b.id_pesquisa WHERE a.id_pesquisa = '".$pesquisa['id_pesquisa']."' AND a.id_contatos_pesquisa NOT IN (SELECT id_contatos_pesquisa FROM tb_agendamento_pesquisa WHERE status_agendamento = 0 AND data_hora > '".$data_de_hoje."') AND a.id_contatos_pesquisa NOT IN (SELECT id_contatos_pesquisa FROM tb_data_contato_pesquisa WHERE data_atualizacao >= ('$data_hora_intervalo_15_segundos')) AND a.data_ultimo_contato < '".$data_horas_entre_tentativas."' AND a.status_pesquisa = 0 ORDER BY a.data_ultimo_contato","COUNT(*) AS cont");

                                $cont_disponivel['cont'] = $clientes[0]['cont'];
                                
                                echo "<td style='vertical-align:middle;'>";
                                    echo $cont_disponivel['cont'];
                                echo "</td>";

                                if($pesquisa['prazo_termino']){
                                    $prazo_termino = converteData($pesquisa['prazo_termino']);
                                }else{
                                    $prazo_termino = "N/D";
                                }
                                echo "<td style='vertical-align:middle;'>";
                                    echo $prazo_termino;
                                echo "</td>";

                                if($cont_disponivel['cont'] == 0){
                                    $entrevistar = "";
                                }else{
                                    $entrevistar = '<a class="" href="/api/iframe?token=<?php echo $request->token ?>&view=pesquisa-entrevistar-form&id_pesquisa='.$pesquisa['id_pesquisa'].'">Entrevistar</a>';
                                }
                                echo "<td style='vertical-align:middle;'>";
                                    echo '<a class="" href="/api/iframe?token=<?php echo $request->token ?>&view=pesquisa-entrevistar-form&id_pesquisa='.$pesquisa['id_pesquisa'].'&adicionar=1">Adicionar contato</a>';
                                echo "</td>";
                                    
                                    echo "<td style='vertical-align:middle;'>";
                                    echo $entrevistar;
                                echo "</td>";
                    
                        $agendamentos = DBRead('', 'tb_agendamento_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa WHERE b.id_pesquisa = '".$pesquisa['id_pesquisa']."' AND a.status_agendamento = 0 ORDER BY data_hora ASC LIMIT 1", "a.*,b.*, a.telefone AS telefone1, b.telefone AS telefone2");


                        if($agendamentos[0]['data_hora']){

                            if($agendamentos[0]['data_hora'] < getDataHora()){
                                echo "<td class='danger'  style='vertical-align:middle;'>";

                                        echo converteDataHora($agendamentos[0]['data_hora']);

                                echo "</td>";
                            }else{

                                echo "<td style='vertical-align:middle;'>";

                                        echo converteDataHora($agendamentos[0]['data_hora']);

                                echo "</td>";

                            }

                        }else{
                            echo "<td style='vertical-align:middle;'>";
                                echo "Não possui agendamento";
                            echo "</td>";
                        }
                            
                    echo "</tr>";
                        
                    }
            echo'
            </tbody>
        </table>';
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