<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);

if($parametros['id_lead_origem']){
    $id_origem = "AND c.id_lead_origem = '".$parametros['id_lead_origem']."'";
}

if($parametros['id_lead_motivo_pausa']){
    $id_lead_motivo_pausa = "AND f.id_lead_motivo_pausa = '".$parametros['id_lead_motivo_pausa']."'";
}

if($parametros['data']){
    $data = converteData($parametros['data']);

    $data1 = $data.' 00:00:00';
    $data2 = $data.' 23:59:59';

    $data_pausa = "AND e.data_pausa BETWEEN '$data1' AND '$data2'";
    $data_lembrete = "OR e.data_lembrete BETWEEN '$data1' AND '$data2'";
}

// Informações da query
$filtros_query  = "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa LEFT JOIN tb_pessoa_prospeccao c ON b.id_pessoa = c.id_pessoa LEFT JOIN tb_lead_origem d ON c.id_lead_origem = d.id_lead_origem INNER JOIN tb_lead_negocio_pausado e ON a.id_lead_negocio = e.id_lead_negocio INNER JOIN tb_lead_motivo_pausa f ON e.id_lead_motivo_pausa = f.id_lead_motivo_pausa WHERE (nome LIKE '%$letra%') $id_origem $id_lead_motivo_pausa $data_pausa $data_lembrete AND a.id_lead_status = 6";

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
$dados = DBRead('','tb_lead_negocio a', $filtros_query, "COUNT(*) AS 'num_registros'");
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
$dados = DBRead('', 'tb_lead_negocio a',$filtros_query." LIMIT $inicio, $maximo", 'a.*, b.*, c.*, d.*, e.*, f.*, d.descricao AS descricao_origem, f.descricao AS descricao_motivo');

if(!$dados){
    echo "<p class='alert alert-warning' style='text-align: center'>";
    if(!$letra){
        echo "Não foram encontrados registros!";
    }else{
        echo "Nenhum resultado encontrado na busca por \"<strong>$letra</strong>\"";
    }
    echo "</p>";
}else{

    echo "<div class='row'>
            <div class='col-md-12' >
                <div class='pull-right' id='teste'></div>
            </div>
         </div>";

    echo "<div class='table-responsive'>";
    echo "<table class='table table-hover dataTable' style='font-size: 14px;'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>#</th>";
    echo "<th>Lead</th>";
    echo "<th>Email</th>";
    echo "<th>Segmento</th>";
    echo "<th>Origem</th>";
    echo "<th>Data da pausa</th>";
    echo "<th>Motivo</th>";
    echo "<th>Lembrete para</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach($dados as $conteudo){

        $id = $conteudo['id_lead_negocio'];
        $nome = $conteudo['nome'];
        $descricao_origem = $conteudo['descricao_origem'];
        $email = $conteudo['email1'];
        $segmento = $conteudo['segmento'];
        $origem = $conteudo['d.descricao'];
        $descricao_motivo = $conteudo['descricao_motivo'];
        $data_pausa = $conteudo['data_pausa'];
        $data_lembrete = $conteudo['data_lembrete'];

        if($segmento == ''){
            $segmento = 'N/D';
        }

        if($origem == ''){
            $origem = 'N/D';
        }

        $motivo = DBRead('', 'tb_lead_negocio a', "INNER JOIN tb_lead_negocio_pausado b ON a.id_lead_negocio = b.id_lead_negocio INNER JOIN tb_lead_motivo_pausa c ON b.id_lead_motivo_pausa = c.id_lead_motivo_pausa WHERE a.id_lead_negocio = '$id' ORDER BY data_pausa DESC limit 1");

        echo "<tr>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-informacoes&lead=$id'\" style='cursor: pointer'>$id</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-informacoes&lead=$id'\" style='cursor: pointer'>$nome</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-informacoes&lead=$id'\" style='cursor: pointer'>$email";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-informacoes&lead=$id'\" style='cursor: pointer'>".$segmento."</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-informacoes&lead=$id'\" style='cursor: pointer'>$descricao_origem</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-informacoes&lead=$id'\" style='cursor: pointer'>".convertedatahora($data_pausa)."</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-informacoes&lead=$id'\" style='cursor: pointer'>".$descricao_motivo."</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-informacoes&lead=$id'\" style='cursor: pointer'>".convertedata($data_lembrete)."</td>";
        echo "</tr>";

        /* echo "<td class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=lead-form&lead=$id' title='Alterar'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"class/Lead.php?excluir=$id\" title='Excluir' onclick=\"if (!confirm('Excluir ".addslashes($nome)."?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-trash' style='color:#b92c28;'></i></a></td>";
        echo "</tr>"; */
    }
    echo "</tbody>";
    echo "</table>";
    echo "</div>";

    echo "<script>
            $(document).ready(function(){
               var table = $('.dataTable').DataTable({
                    \"language\": {
                        \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
                    },			        
                    \"searching\": false,
                    \"paging\":   false,
                    \"info\":     false
				});
                var buttons = new $.fn.dataTable.Buttons(table, {
						buttons: [
							{
								extend: 'excelHtml5',
								text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
								filename: 'leads_negociacoes_pausadas',
								title : null,
								exportOptions: {
								  modifier: {
									page: 'all'
								  }
								}
							  },
						],	
						dom:
						{
							button: {
								tag: 'button',
								className: 'btn btn-info'
							},
							buttonLiner: { tag: null }
						}
				   }).container().appendTo($('#teste'));
            });
        </script>			
        ";
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