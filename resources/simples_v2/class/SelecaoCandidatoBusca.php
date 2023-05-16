<?php
require_once "System.php";


$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);
$id_selecao = $parametros['idselecao'];
$etapa = $parametros['etapa'];
$status = $parametros['status'];

if ($etapa) {
    $filtro_etapa = "AND a.etapa = $etapa";
}

if ($status) {
    $filtro_status = "AND a.status = $status";
}

// Informações da query
$filtros_query = "INNER JOIN tb_pessoa b ON a.id_pessoa_candidato = b.id_pessoa WHERE (nome LIKE '%$letra%') AND  a.id_selecao = $id_selecao $filtro_etapa $filtro_status ORDER BY nome ASC";

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
$dados = DBRead('', 'tb_selecao_candidato a', $filtros_query, "COUNT(*) AS 'num_registros'");
$total = $dados[0]['num_registros'];

// Calculando o registro inicial
$inicio = $maximo * ($pagina - 1);
if ($inicio >= $total) {
    $inicio = 0;
    $pagina = 1;
}

?>
<style>
    #imagem {
        -moz-background-size: 100% 100%;
        -webkit-background-size: 100% 100%;
        background-size: cover;
        height: 46px;
        width: 100%;
        resize: both;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        background-position: center;
    }

    #img-relatorio {
        border-radius: 80px;
        width: 46px;
        height: 46px;
        object-fit: cover;
    }
</style>

<?php
###################################################################################
// INICIO DO CONTEÚDO
//
$dados = DBRead('', 'tb_selecao_candidato a', $filtros_query . " LIMIT $inicio,$maximo", "a.*, b.*, a.status as status_candidato");

if (!$dados) {
    echo "<p class='alert alert-warning' style='text-align: center'>";
    if (!$letra) {
        echo "Não foram encontrados candidatos!";
    } else {
        echo "Nenhum resultado encontrado na busca por \"<strong>$letra</strong>\"";
    }
    echo "</p>";
} else {
    echo "<div class='table table-responsive table-striped'>";
    echo "<table class='table table-hover' style='font-size: 14px;'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Foto</th>";
    echo "<th>Nome</th>";
    echo "<th>CPF</th>";
    echo "<th>Telefone</th>";
    echo "<th>Email</th>";
    echo "<th>Etapa</th>";
    echo "<th>Status</th>";
    echo "<th>Cidade</th>";
    echo "<th>Atualizado em</th>";
    echo "<th class=\"col-md-1 text-center\">Opções</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach ($dados as $conteudo) {
        $id_selecao_candidato = $conteudo['id_selecao_candidato'];
        $id = $conteudo['id_pessoa'];
        $nome = $conteudo['nome'];
        $etapa = $conteudo['etapa'];
        $cpf = formataCampo('cpf_cnpj', $conteudo['cpf_cnpj']);
        $id_cidade = $conteudo['id_cidade'];
        $data_nascimento = converteDataHora($conteudo['data_nascimento']);
        $data_atualizacao = converteDataHora($conteudo['data_atualizacao']);
        $dados_cidade = DBRead('', 'tb_cidade a', "INNER JOIN tb_estado b ON a.id_estado = b.id_estado WHERE a.id_cidade = '$id_cidade'", 'a.nome, b.sigla');
        $cidade = $dados_cidade[0]['nome'];
        $estado = $dados_cidade[0]['sigla'];

        if ($conteudo['fone1'] && $conteudo['fone2']) {
            $telefone = $conteudo['fone1'].' | '.$conteudo['fone2'];

        } else if ($conteudo['fone1'] && !$conteudo['fone2']) {
            $telefone = $conteudo['fone1'];

        } else {
            $telefone = 'Não consta';
        }

        if ($conteudo['email1']) {
            $email = $conteudo['email1'];

        } else {
            $email = 'Não consta';
        }

        if ($conteudo['status_candidato'] == 1) {
            $status = '<span class="label label-primary" style="font-size: 12px; min-width: 110px; display: inline-block;">Em seleção</span>';
        } else if ($conteudo['status_candidato'] == 2) {
            $status = '<span class="label label-success" style="font-size: 12px; min-width: 110px; display: inline-block;">Aprovado</span>';
        } else if ($conteudo['status_candidato'] == 3) {
            $status = '<span class="label label-danger" style="font-size: 12px; min-width: 110px; display: inline-block;">Reprovado</span>';
        } else if ($conteudo['status_candidato'] == 4) {
            $status = '<span class="label label-warning" style="font-size: 12px; min-width: 110px; display: inline-block;">Não compareceu</span>';
        } else if ($conteudo['status_candidato'] == 5) {
            $status = '<span class="label label-info" style="font-size: 12px; min-width: 110px; display: inline-block;">Pré-aprovado</span>';
        }

        $dados_pessoais = DBRead('', 'tb_pessoa_rh_dados_pessoais', "WHERE id_pessoa = $id");
        $foto = $dados_pessoais[0]['foto'];

        
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=selecao-avaliar-form&idselecao=$id_selecao&idcandidato=$id'\" style='cursor: pointer'><img src='$foto' class='center text-center' id='img-relatorio' height='42' width='42'>";

        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=selecao-avaliar-form&idselecao=$id_selecao&idcandidato=$id'\" style='cursor: pointer' data-toggle='popover' data-html='true' data-placement='right' data-trigger='focus' title=''>$nome";

        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=selecao-avaliar-form&idselecao=$id_selecao&idcandidato=$id'\" style='cursor: pointer'>$cpf";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=selecao-avaliar-form&idselecao=$id_selecao&idcandidato=$id'\" style='cursor: pointer'>$telefone";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=selecao-avaliar-form&idselecao=$id_selecao&idcandidato=$id'\" style='cursor: pointer'>$email";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=selecao-avaliar-form&idselecao=$id_selecao&idcandidato=$id'\" style='cursor: pointer'><span class='label label-default'>$etapa</span>";
        echo "</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=selecao-avaliar-form&idselecao=$id_selecao&idcandidato=$id'\" style='cursor: pointer'>$status";
        echo "</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=selecao-avaliar-form&idselecao=$id_selecao&idcandidato=$id'\" style='cursor: pointer'>$cidade - $estado</td>";
        echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=selecao-avaliar-form&idselecao=$id_selecao&idcandidato=$id'\" style='cursor: pointer'>$data_atualizacao</td>";
        $disabled = "";
        if ($id == 2) {
            $disabled = "style='pointer-events:none; opacity: 0.4;'";
        }
        echo "<td class=\"text-center\">
                <a href='/api/iframe?token=<?php echo $request->token ?>&view=selecao-avaliar-form&idselecao=$id_selecao&idcandidato=$id' title='Avaliar'><i class='fa fa-edit'></i></a>&nbsp
                <a href='class/SelecaoCandidato.php?excluir=$id_selecao_candidato&idselecao=$id_selecao' title='Excluir' onclick=\"if (!confirm('Excluir candidato?')) {  return false; } else { modalAguarde(); }\"><i class='fa fa-trash' style='color:#b92c28;'></i></a>
                
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
$fim_links = ((($pagina + $lim_links) < $pgs) ? $pagina + $lim_links : $pgs);

if ($pgs > 1) {

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