<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);

$tipo = $parametros['tipo'];
$classificacao = $parametros['classificacao'];
$analista = $parametros['analista'];

if ($tipo == 1) {
    $filtro_voz = "AND d.voz = 1";
    $canal = 'telefone';

    if ($analista) {
        $filtro_analista = "AND d.id_analista_telefone = $analista";
    }

} else if ($tipo == 2){
    $filtro_texto = "AND d.texto = 1";
    $canal = 'texto';

    if ($analista) {
        $filtro_analista = "AND d.id_analista_texto = $analista";
    }
} 

if ($classificacao == 1) {
    $filtro_classificacao = "AND d.tipo_classificacao = 1";

} else if ($classificacao == 2) {
    $filtro_classificacao = "AND d.tipo_classificacao = 2";

} else  if ($classificacao == 3) {
    $filtro_classificacao = "AND d.tipo_classificacao = 3";

}

$data_hoje = getDataHora();
$data_hoje = explode(" ", $data_hoje);
$dia_hoje = explode("-", $data_hoje[0]);

$mes_referencia = $dia_hoje[0].'-'.$dia_hoje[1].'-01';

$date = new DateTime('now');
$date->modify('first day of last month');
$mes_passado = $date->format('Y-m-d');

$existe_formulario = DBRead('', 'tb_monitoria_mes', "WHERE data_referencia = '$mes_passado' AND tipo_monitoria = $tipo AND classificacao_atendente = $classificacao AND status = 1");

if ($existe_formulario) {
    
    $id_monitoria_mes = $existe_formulario[0]['id_monitoria_mes'];

    $verifica_plano_acao = DBRead('', 'tb_monitoria_mes_plano_acao_chamado', "WHERE id_monitoria_mes = '$id_monitoria_mes'");

    if($verifica_plano_acao){
    
        $verifica = DBRead('', 'tb_monitoria_mes', "WHERE data_referencia = '$mes_referencia' AND status = 1 AND tipo_monitoria = $tipo AND classificacao_atendente = $classificacao");

        $span = 'label label-info';
    
    }else{
        
        $verifica = DBRead('', 'tb_monitoria_mes', "WHERE data_referencia = '$mes_passado' AND status = 1 AND tipo_monitoria = $tipo AND classificacao_atendente = $classificacao");

        $span = 'label label-warning';
    }

} else {

    $verifica = DBRead('', 'tb_monitoria_mes', "WHERE data_referencia = '$mes_referencia' AND status = 1 AND tipo_monitoria = $tipo AND classificacao_atendente = $classificacao");

    $span = 'label label-info';
}

if($verifica){

    $id_monitoria_mes = $verifica[0]['id_monitoria_mes'];
    $arrayData = explode("-",$verifica[0]['data_referencia']);

    $mes = $arrayData[1];
    $ano = $arrayData[0];
    
    $data_formulario = $arrayData[1].'/'.$arrayData[0];
    
    // Informações da query
    $filtros_query  = "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa LEFT JOIN tb_monitoria_avaliacao_audio c ON a.id_usuario = c.id_usuario_atendente INNER JOIN tb_monitoria_classificacao_usuario d ON a.id_usuario = d.id_usuario WHERE (b.nome LIKE '%$letra%') AND (id_perfil_sistema = '3' OR id_perfil_sistema = 15) AND a.status = 1 $filtro_voz $filtro_texto $filtro_classificacao $filtro_analista";

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
    $dados = DBRead('','tb_usuario a',$filtros_query,"COUNT(*) AS 'num_registros'");
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
    $dados = DBRead('', 'tb_usuario a',$filtros_query." GROUP BY a.id_usuario ORDER BY b.nome ASC LIMIT $inicio,$maximo", "a.id_usuario, b.nome, a.id_ponto");

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
                        echo "<th>Nome</th>";
                        echo "<th class='text-center'>Classificação de atendente</th>";
                        echo "<th class='text-center'>Mês referência</th>";
                        echo "<th class='text-center'>Canal de atendimento</th>";
                        echo "<th class='text-center'>Quantidade de áudios JÁ avaliados <i class='fa fa fa-check-circle' style='color: green;'></i></th>";
                        echo "<th class='text-center'>Quantidade de áudios A SEREM avaliados <i class='fa fa-exclamation-circle' style='color: #B40404'></i> </th>";
                        echo "<th class='text-center'>Analista Telefone</th>";
                        echo "<th class='text-center'>Analista Texto</th>";
                        echo "<th class='text-center'>Carga horária</th>";
                        echo "<th class='text-center'>Avaliar</th>";
                    echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

        $soma_total_audios = 0;
        $soma_total_audios_a_serem_avaliados = 0;

        foreach ($dados as $conteudo) {

            $id = $conteudo['id_usuario'];
            $nome = $conteudo['nome'];
            
            $soma = 0;
            $qtd_audios_avaliados = 0;

            if ($tipo == 1) {

                $numero_ligacoes = DBRead('', 'tb_monitoria_avaliacao_audio', '', "COUNT(CASE WHEN id_monitoria_mes = '".$id_monitoria_mes."' AND id_usuario_atendente = '".$id."' AND nota = 5 AND id_erro IS NULL AND considerar = 1 THEN 1 END) AS cont_n5, COUNT(CASE WHEN id_monitoria_mes = '".$id_monitoria_mes."' AND id_usuario_atendente = '".$id."' AND nota = 4 AND id_erro IS NULL AND considerar = 1 THEN 1 END) AS cont_n4, COUNT(CASE WHEN id_monitoria_mes = '".$id_monitoria_mes."' AND id_usuario_atendente = '".$id."' AND nota = 3 AND id_erro IS NULL AND considerar = 1 THEN 1 END) AS cont_n3, COUNT(CASE WHEN id_monitoria_mes = '".$id_monitoria_mes."' AND id_usuario_atendente = '".$id."' AND nota = 2 AND id_erro IS NULL AND considerar = 1 THEN 1 END) AS cont_n2, COUNT(CASE WHEN id_monitoria_mes = '".$id_monitoria_mes."' AND id_usuario_atendente = '".$id."' AND nota = 1 AND id_erro IS NULL AND considerar = 1 THEN 1 END) AS cont_n1, COUNT(CASE WHEN id_monitoria_mes = '".$id_monitoria_mes."' AND id_usuario_atendente = '".$id."' AND nota IS NULL AND id_erro IS NULL AND considerar = 1 THEN 1 END) AS cont_sn");

            } else if ($tipo == 2) {
                $numero_ligacoes = DBRead('', 'tb_monitoria_avaliacao_texto', '', "COUNT(CASE WHEN id_monitoria_mes = '".$id_monitoria_mes."' AND id_usuario_atendente = '".$id."' AND id_erro IS NULL AND considerar = 1 THEN 1 END) AS qtd_atendimentos");

            }

            $turno = DBRead('', 'tb_horarios_escala', "WHERE id_usuario = '$id' AND data_inicial = '$mes_referencia' ", 'inicial_seg, final_seg');

            if($turno){
                $inicial_seg = $turno[0]['inicial_seg'];
                $final_seg = $turno[0]['final_seg'];

                if($inicial_seg > $final_seg){
                    $hora1 = '2000-10-10 '.$inicial_seg.':00';
                    $hora2 = '2000-10-11 '.$final_seg.':00';
                    $data1 = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($hora1)));
                    $data2 = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($hora2)));
                    $resultado = strtotime($data2) - strtotime($data1);

                }else{
                    $hora1 = strtotime(''.$inicial_seg.'');
                    $hora2 = strtotime(''.$final_seg.'');
                    $resultado = ($hora2-$hora1);
                }

                $h = ($resultado/(60*60))%24;

                if($h >= 5){
                    $turno = 'Integral';

                    if ($tipo == 1) {

                        $soma = $verifica[0]['qtd_audios_monitoria_integral_sn'] + $verifica[0]['qtd_audios_monitoria_integral_n1'] + $verifica[0]['qtd_audios_monitoria_integral_n2'] + $verifica[0]['qtd_audios_monitoria_integral_n3'] + $verifica[0]['qtd_audios_monitoria_integral_n4'] + $verifica[0]['qtd_audios_monitoria_integral_n5'];

                        $sem_nota = $verifica[0]['qtd_audios_monitoria_integral_sn'];
                        $nota1 = $verifica[0]['qtd_audios_monitoria_integral_n1'];
                        $nota2 = $verifica[0]['qtd_audios_monitoria_integral_n2'];
                        $nota3 = $verifica[0]['qtd_audios_monitoria_integral_n3'];
                        $nota4 = $verifica[0]['qtd_audios_monitoria_integral_n4'];
                        $nota5 = $verifica[0]['qtd_audios_monitoria_integral_n5'];

                        if($numero_ligacoes[0]['cont_sn'] <= $sem_nota){
                            $qtd_audios_avaliados += $numero_ligacoes[0]['cont_sn'];
                        }else{
                            $qtd_audios_avaliados += $sem_nota;
                        }

                        if($numero_ligacoes[0]['cont_n1'] <= $nota1){
                            $qtd_audios_avaliados += $numero_ligacoes[0]['cont_n1'];
                        }else{
                            $qtd_audios_avaliados += $nota1;
                        }

                        if($numero_ligacoes[0]['cont_n2'] <= $nota2){
                            $qtd_audios_avaliados += $numero_ligacoes[0]['cont_n2'];
                        }else{
                            $qtd_audios_avaliados += $nota2;
                        }

                        if($numero_ligacoes[0]['cont_n3'] <= $nota3){
                            $qtd_audios_avaliados += $numero_ligacoes[0]['cont_n3'];
                        }else{
                            $qtd_audios_avaliados += $nota3;
                        }

                        if($numero_ligacoes[0]['cont_n4'] <= $nota4){
                            $qtd_audios_avaliados += $numero_ligacoes[0]['cont_n4'];
                        }else{
                            $qtd_audios_avaliados += $nota4;
                        }

                        if($numero_ligacoes[0]['cont_n5'] <= $nota5){
                            $qtd_audios_avaliados += $numero_ligacoes[0]['cont_n5'];
                        }else{
                            $qtd_audios_avaliados += $nota5;
                        }

                    } else if ($tipo == 2) {
                        $soma = $verifica[0]['qtd_texto_monitoria_integral'];
                        $qtd_audios_avaliados = $numero_ligacoes[0]['qtd_atendimentos'];

                    }

                }else{
                    $turno = 'Meio turno';

                    if ($tipo == 1) {

                        $soma = $verifica[0]['qtd_audios_monitoria_meio_turno_sn'] + $verifica[0]['qtd_audios_monitoria_meio_turno_n1'] + $verifica[0]['qtd_audios_monitoria_meio_turno_n2'] + $verifica[0]['qtd_audios_monitoria_meio_turno_n3'] + $verifica[0]['qtd_audios_monitoria_meio_turno_n4'] + $verifica[0]['qtd_audios_monitoria_meio_turno_n5'];

                        $sem_nota = $verifica[0]['qtd_audios_monitoria_meio_turno_sn'];
                        $nota1 = $verifica[0]['qtd_audios_monitoria_meio_turno_n1'];
                        $nota2 = $verifica[0]['qtd_audios_monitoria_meio_turno_n2'];
                        $nota3 = $verifica[0]['qtd_audios_monitoria_meio_turno_n3'];
                        $nota4 = $verifica[0]['qtd_audios_monitoria_meio_turno_n4'];
                        $nota5 = $verifica[0]['qtd_audios_monitoria_meio_turno_n5'];

                        if($numero_ligacoes[0]['cont_sn'] <= $sem_nota){
                            $qtd_audios_avaliados += $numero_ligacoes[0]['cont_sn'];
                        }else{
                            $qtd_audios_avaliados += $sem_nota;
                        }

                        if($numero_ligacoes[0]['cont_n1'] <= $nota1){
                            $qtd_audios_avaliados += $numero_ligacoes[0]['cont_n1'];
                        }else{
                            $qtd_audios_avaliados += $nota1;
                        }

                        if($numero_ligacoes[0]['cont_n2'] <= $nota2){
                            $qtd_audios_avaliados += $numero_ligacoes[0]['cont_n2'];
                        }else{
                            $qtd_audios_avaliados += $nota2;
                        }

                        if($numero_ligacoes[0]['cont_n3'] <= $nota3){
                            $qtd_audios_avaliados += $numero_ligacoes[0]['cont_n3'];
                        }else{
                            $qtd_audios_avaliados += $nota3;
                        }

                        if($numero_ligacoes[0]['cont_n4'] <= $nota4){
                            $qtd_audios_avaliados += $numero_ligacoes[0]['cont_n4'];
                        }else{
                            $qtd_audios_avaliados += $nota4;
                        }

                        if($numero_ligacoes[0]['cont_n5'] <= $nota5){
                            $qtd_audios_avaliados += $numero_ligacoes[0]['cont_n5'];
                        }else{
                            $qtd_audios_avaliados += $nota5;
                        }

                        $sem_nota = $verifica[0]['qtd_audios_monitoria_meio_turno_sn'];
                        $nota1 = $verifica[0]['qtd_audios_monitoria_meio_turno_n1'];
                        $nota2 = $verifica[0]['qtd_audios_monitoria_meio_turno_n2'];
                        $nota3 = $verifica[0]['qtd_audios_monitoria_meio_turno_n3'];
                        $nota4 = $verifica[0]['qtd_audios_monitoria_meio_turno_n4'];
                        $nota5 = $verifica[0]['qtd_audios_monitoria_meio_turno_n5'];

                    } else if ($tipo == 2) {
                        $soma = $verifica[0]['qtd_texto_monitoria_meio_turno'];
                        $qtd_audios_avaliados = $numero_ligacoes[0]['qtd_atendimentos'];
                    }
                }

                $soma_total_audios_a_serem_avaliados += $soma;
            }else{
                $turno = 'Não possui escala definida!';
                $soma = 'N/D';
                $sem_turno = 'danger';
            }

            if($qtd_audios_avaliados < $soma){
                $class = "";
            }else if($qtd_audios_avaliados >= $soma){
                $class = "success";
            }

            if($turno == 'Não possui escala definida!'){
                 $class = "danger";
            }

            $dados_classificacao = DBRead('', 'tb_monitoria_classificacao_usuario', "WHERE id_usuario = $id");

            if ($dados_classificacao[0]['tipo_classificacao'] == 1) {
                $classificacao = 'Em treinamento';

            } else if ($dados_classificacao[0]['tipo_classificacao'] == 2) {
                $classificacao = 'Período de experiência';

            } else {
                $classificacao = 'Efetivado';
            }

            $texto = $dados_classificacao[0]['texto'];
            $voz = $dados_classificacao[0]['voz'];

            if ($texto == '1' && $voz == '1') {
                $legenda_avaliar = '<strong>Telefone</strong> e <strong>Texto</strong>';

            } else if ($texto == '1' && $voz == '2') {
                $legenda_avaliar = '<strong>Texto</strong>';

            } else if ($texto == '2' && $voz == '1') {
                $legenda_avaliar = '<strong>Telefone</strong>';

            } else {
                $legenda_avaliar = 'Nenhum';
            }

            $nome_analista_telefone = 'N/D';
            if ($dados_classificacao[0]['id_analista_telefone']) {
                $analista_telefone = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$dados_classificacao[0]['id_analista_telefone']."' ", 'b.nome');

                $nome_analista_telefone = $analista_telefone[0]['nome'];
            }

            $nome_analista_texto= 'N/D';
            if ($dados_classificacao[0]['id_analista_texto']) {
                $analista_texto = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$dados_classificacao[0]['id_analista_texto']."' ", 'b.nome');
                $nome_analista_texto = $analista_texto[0]['nome'];
            }

            echo "<tr class='$class'>";
            echo "<td>$nome</td>";
            echo "<td class='text-center'>$classificacao</td>";
            echo "<td class='text-center'><span class='$span' style='font-size: 13px;'>$data_formulario</span></td>";
		    echo "<td class='text-center'>$legenda_avaliar</td>";
            echo "<td class='text-center'>$qtd_audios_avaliados</td>";
            echo "<td class='text-center'>$soma</td>";
            echo "<td class='text-center'>$nome_analista_telefone</td>";
            echo "<td class='text-center'>$nome_analista_texto</td>";
            echo "<td class='text-center'>$turno</td>";

            $soma_total_audios += $qtd_audios_avaliados;

            if($class != "danger"){
                echo "<td class=\"text-center\">
                    <a href='/api/iframe?token=<?php echo $request->token ?>&view=monitoria-$canal-busca&id_usuario=$id&id_monitoria_mes=$id_monitoria_mes' title='Alterar'>
                        <i class='fa fa fa-gavel' style='font-size: 17;'></i>
                    </a>
                </td>";
            }else{
                echo "<td class=\"text-center\"></td>";
            }
           
        }
        echo "</tbody>";
        echo "<tfoot>";
        echo "<tr class='active'>
                <td><strong>Soma total de áudios</strong></td>
                <td class='text-center'></td>
                <td class='text-center'></td>
                <td class='text-center'></td>
                <td class='text-center'><strong>$soma_total_audios</strong></td>
                <td class='text-center'><strong>$soma_total_audios_a_serem_avaliados</strong></td>
                <td class='text-center'></td>
                <td class='text-center'></td>
                <td class='text-center'></td>
                <td class='text-center'></td>
              </tr>";
        echo "</tfoot>";
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
}else{
    echo '<p class="alert alert-warning" style="text-align: center">Não há formulário de monitoria cadastrado para este mês com estas opções de <strong>canal de atendimento</strong> e <strong>classificação de atendente</strong>!</strong>"</p>';
}


?>