
<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$andamento = $parametros['andamento'];
$letra = addslashes($parametros['nome']);
$id_rd_lead = $parametros['id_rd_lead'];
$negocio = $parametros['negocio'];
$vinculo = $parametros['vinculo'];
$id_usuario = $_SESSION['id_usuario'];

if ($parametros['data_de'] && $parametros['data_ate']) {
    $data_de = converteData($parametros['data_de']);
    $data_ate = converteData($parametros['data_ate']);

    $data_de = $data_de.' 00:00:00';
    $data_ate = $data_ate.' 23:59:59';
    
    $filtro_data = "AND a.data BETWEEN '$data_de' AND '$data_ate'";
}

if ($negocio == 1) {
    $filtro_negocio = "AND a.id_lead_negocio IS NOT NULL";

} else if ($negocio == 2) {
    $filtro_negocio = "AND a.id_lead_negocio IS NULL";
}

if ($vinculo == 1) {
    $filtro_vinculo = "AND a.id_pessoa IS NOT NULL";

} else if ($vinculo == 2) {
    $filtro_vinculo = "AND a.id_pessoa IS NULL";
}

$filtros_query = "WHERE (a.email LIKE '%$letra%' OR a.name LIKE '%$letra%' OR a.company LIKE '%$letra%' OR a.source LIKE '%$letra%') $filtro_data $filtro_rd_lead $filtro_negocio $filtro_vinculo AND a.status = 1 ORDER BY a.id_rd_conversao DESC";

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
$dados = DBRead('','tb_rd_conversao a', $filtros_query, "COUNT(*) AS 'num_registros'");
$total = $dados[0]['num_registros'];

// Calculando o registro inicial
$inicio = $maximo * ($pagina - 1);
if($inicio >= $total){
    $inicio = 0;
    $pagina = 1;
}
?>

<style>
    .label {
        min-width: 94px !important;
        display: inline-block !important;
        font-size: 11.3px;
    }
</style>

<?php
###################################################################################
// INICIO DO CONTEÚDO
// 
$dados = DBRead('', 'tb_rd_conversao a',$filtros_query." LIMIT $inicio, $maximo");

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
    echo "<table class='table table-striped' style='font-size: 14px;'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Excluir</th>";
    echo "<th>#ID RD lead</th>";
    echo "<th>Nome</th>";
    echo "<th>Empresa</th>";
    echo "<th>Razão Social</th>";
    echo "<th>CNPJ</th>";
    echo "<th>Email</th>";
    echo "<th>Telefone</th>";
    echo "<th>Data</th>";
    echo "<th>Source</th>";
    echo "<th>Vínculo</th>";
    echo "<th class='text-center'>Negócio</th>";
    echo "<th class='text-center'>Tags</th>";
    echo "<th class='text-center'>Opções</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach($dados as $conteudo){

        $id = $conteudo['id_rd_conversao'];
        $id_rd_lead = $conteudo['id_rd_lead'];
        $uuid = $conteudo['uuid'];
        $email = $conteudo['email'];
        $nome_lead = $conteudo['name'];
        $telefone = $conteudo['telefone'];
        $empresa = $conteudo['company'];
        $razao = $conteudo['razao'];
        $cnpj = $conteudo['cnpj'];
        $data = converteDataHora($conteudo['data']);
        $status = $conteudo['status'];   
        $source = $conteudo['source'];   
        $id_pessoa = $conteudo['id_pessoa'];   
        $id_lead_negocio = $conteudo['id_lead_negocio'];

        if ($id_lead_negocio !='') {
            $lead_negocio = DBRead('', 'tb_lead_negocio', "WHERE id_lead_negocio = $id_lead_negocio");
        }

        if ($lead_negocio[0]['sinalizacao_rd'] == 1) {
            $cor = '#04B404';
        } else {
            $cor = '#337ab7';
        }
        
        $negocio = "";
        $tags = "";

        if ($id_pessoa) {
            $pessoa = DBRead('', 'tb_pessoa', "WHERE id_pessoa = $id_pessoa", 'nome');
        
            if ($pessoa) {
                $nome_pessoa = "<a href='/api/iframe?token=<?php echo $request->token ?>&view=pessoa-form&alterar=$id_pessoa' target='_blank'>".$pessoa[0]['nome']."</a>";

                if ($id_lead_negocio != '') {
                    $negocio = "<a href='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-informacoes&lead=$id_lead_negocio' target='_blank'><i class='fa fa-eye'></i></a>";
                    
                    if ($uuid !='') {
                        $tags = "<a href='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-tags&id_lead_negocio=$id_lead_negocio' target='_blank'><i class='fa fa-tag' style='color: $cor'></i></a>";

                    } else {
                       $tags =  '<a title="Não constam dados do RD" style="padding-right: 4px;"><i class="fas fa-exclamation-triangle" style="opacity: 0.2; color: red;"></i></a>';
                    }
                

                } else {
                    $btn_negocio = "<a href='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-form&pessoa=$id_pessoa&id_rd_conversao=$id' title='Criar negócio'><i class='fa fa-briefcase' style='color: #04B45F'></i></a>&nbsp";
                }   

            } else {
                $btn_negocio = '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
                $nome_pessoa = 'N/D';
            }

        } else {
            $nome_pessoa = 'N/D';
            $btn_negocio = '&nbsp&nbsp&nbsp&nbsp';
        }
        
        if ($id_lead_negocio != '') {
            
            if ($uuid !='') {
                $tags = "<a href='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-tags&id_lead_negocio=$id_lead_negocio' target='_blank'><i class='fa fa-tag' style='color: $cor'></i></a>";

            } else {
                $tags =  '<a title="Não constam dados do RD" style="padding-right: 4px;"><i class="fas fa-exclamation-triangle" style="opacity: 0.2; color: red;"></i></a>';
             }
            
            $negocio = "<a href='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-informacoes&lead=$id_lead_negocio' target='_blank'><i class='fa fa-eye'></i></a>";

        } 
        
        echo "<tr>";
        echo "<td style='vertical-align: middle;'><input type='checkbox' name='excluirConversao[]' value='$id'></td>";
        echo "<td style='vertical-align: middle;'>".$id_rd_lead."</td>";
        echo "<td>$nome_lead</td>";
        echo "<td>$empresa</td>";
        echo "<td>$razao</td>";
        echo "<td>$cnpj</td>";
        echo "<td>$email</td>";
        echo "<td>$telefone</td>";
        echo "<td>$data</td>";
        echo "<td>$source</td>";
        echo "<td>$nome_pessoa</td>";
        echo "<td class='text-center'>$negocio</td>";
        echo "<td class='text-center'>$tags</td>";

        echo "<td class=\"text-center\" style='vertical-align: middle;'>
                $btn_negocio
                <a href='/api/iframe?token=<?php echo $request->token ?>&view=lead-conversao-cadastro&id_rd_conversao=$id' title='Criar Pessoa/Vínculo'><i class='fa fa-plus'></i></a>
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