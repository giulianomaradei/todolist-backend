<?php
require_once "System.php";


$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);
$id_treinamento = $parametros['idtreinamento'];
$avaliar_em = $parametros['avaliar_em'];
$status = $parametros['status'];
$avaliacao = $parametros['avaliacao'];
$id_usuario = $_SESSION['id_usuario'];

$verifica = DBRead('', 'tb_treinamento_responsavel', "WHERE id_treinamento = $id_treinamento AND id_usuario = $id_usuario");

if ($status == 1) {
    $filtro_status = "AND d.eficaz IS NOT NULL";
} elseif ($status == 2) {
    $filtro_status = "AND d.eficaz IS NULL";
} else {
    $filtro_status = '';
}

if ($avaliacao == 1) {
    $filtro_avaliacao = "AND d.eficaz = 'Eficaz'";
} elseif ($avaliacao == 2) {
    $filtro_avaliacao = "AND d.eficaz = 'Ineficaz'";
} else {
    $filtro_avaliacao = "";
}

// Informações da query
$filtros_query = "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa LEFT JOIN tb_treinamento_avaliacao d ON a.id_treinamento_participante = d.id_treinamento_participante WHERE (nome LIKE '%$letra%') AND a.id_treinamento = $id_treinamento $filtro_status $filtro_avaliacao ORDER BY nome ASC";

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
$dados = DBRead('', 'tb_treinamento_participante a', $filtros_query, "COUNT(*) AS 'num_registros'");
$total = $dados[0]['num_registros'];

// Calculando o registro inicial
$inicio = $maximo * ($pagina - 1);
if ($inicio >= $total) {
    $inicio = 0;
    $pagina = 1;
}

?>
<!-- <style>
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

</style> -->

<?php
###################################################################################
// INICIO DO CONTEÚDO
//
$dados = DBRead('', 'tb_treinamento_participante a', $filtros_query . " LIMIT $inicio,$maximo", "a.id_treinamento_participante, a.id_usuario, a.obs, c.nome, d.eficaz, d.data_avaliacao, d.plano_acao");

if (!$dados) {
    echo "<p class='alert alert-warning' style='text-align: center'>";
    if (!$letra) {
        echo "Não foram encontrados candidatos!";
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
    echo "<div class='table table-responsive table-striped'>";
    echo "<table class='table table-hover' style='font-size: 14px;'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Nome</th>";
    echo "<th>Avaliação</th>";
    echo "<th>Data da avaliação</th>";
    echo "<th class='col-md-6'>Plano de ação</th>";
    echo "<th class='text-center'>&nbsp&nbsp&nbsp&nbspObservações</th>";
    echo "<th class='text-center'>Opções</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach ($dados as $conteudo) {
        $id = $conteudo['id_treinamento_participante'];
        $nome = $conteudo['nome'];
        $avaliacao = $conteudo['eficaz'];
        $data_avaliacao = converteDataHora($conteudo['data_avaliacao']);
        $plano_acao = $conteudo['plano_acao'];

        $verifica_avaliacao = DBRead('', 'tb_treinamento_avaliacao', "WHERE id_treinamento_participante = $id");

        if ($avaliacao == '') {
            $avaliacao = 'N/A';
            $class = 'warning';

        } else {
            $class = '';
        }

        $obs = '';
        $cor_icone = '';
        if ($conteudo['obs']) {
            $cor_icone = 'color: #04B404';
            $obs = $conteudo['obs'];
        }

        echo "<tr class='$class'>";
        echo "<td>$nome</td>";
        echo "<td>$avaliacao</td>";
        echo "<td>$data_avaliacao</td>";
        echo "<td><span data-toggle='tooltip' data-placement='left' title='$plano_acao'>$plano_acao</span></td>";
        echo "<td class=\"text-center\" >";
                echo "<a title='adicionar obs' id='$id' onclick='obsModal(this.id);'><i class='fa fa-edit' style='cursor: pointer; $cor_icone'></i></a>";
        echo "</td>";

        $avaliar_em = $avaliar_em . ' 00:00:00';

        if ($verifica) {

            if ($avaliar_em < getDataHora()) {

                if (!$verifica_avaliacao) {
                    echo "<td class=\"text-center\" id='$id' onclick='modal(this.id);'><a title='Avaliar'><i class='fa fa-gavel' style='cursor: pointer'></i></a></td>";
                    echo "</tr>";
                } else {
                    echo "<td class=\"text-center\">Já avaliado!</td>";
                    echo "</tr>";
                }
            } else {
                echo "<td class=\"text-center\">Ainda não é possivel avaliar</td>";
                echo "</tr>";
            }
        } else {
            echo "<td class=\"text-center\"> - - -</td>";
            echo "</tr>";
        }
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

<!-- Modal avaliar -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Avaliar participante</h4>
            </div>
            <form method="post" action="class/Treinamento.php" id="treinamento_modal" style="margin-bottom: 0;">
                <input type="hidden" name="id_treinamento" id="id_treinamento" value="<?= $id_treinamento ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="avaliar">*Avaliação:</label>
                                <select name="avaliacao" id="avaliacao_participante" class="form-control input-sm" required>
                                    <option value=""></option>
                                    <option value="1">Eficaz</option>
                                    <option value="2">Ineficaz</option>
                                    <option value="3">Não se aplica</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="avaliar">*Plano de ação:</label>
                                <textarea class="form-control" name="plano_acao" id="plano_acao" rows="7"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="data">*Data:</label>
                                <input class="form-control input-sm date calendar hasDatepicker" id="data_avaliacao" name="data_avaliacao" value="" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="operacao_modal" value="" name="avaliar" />
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal obs -->
<div class="modal fade" id="obsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Observação do participante</h4>
            </div>
            <form method="post" action="class/Treinamento.php" id="treinamento_obs_modal" style="margin-bottom: 0;">
                <input type="hidden" name="id_treinamento_obs" id="id_treinamento_obs" value="<?= $id_treinamento ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="avaliar">*Observação:</label>
                                <textarea class="form-control" name="obs" id="obs" rows="7"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="obs_modal" value="" name="adicionar_observacao" />
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="inc/js/system.js"></script>
<script>
    function modal(id) {
        $('#myModal').modal('show');
        $('#operacao_modal').val(id);
    }

    function obsModal(id) {
        $('#obsModal').modal('show');
        $('#obs_modal').val(id);

        $.ajax({
            cache: false,
            type: "POST",
            url: 'class/TreinamentoAjax.php',
            data: {
                acao: 'busca_obs',
                parametros: {
                    id_treinamento_participante: id
                }
            },
            success: function(data) {
                if (data != 0) {
                    //obs = data.replace(/"/g, '');]
                    obs = JSON.parse(data);
                    $("#obs").text(obs);

                } else {
                    $("#obs").text('');
                }
            }
        });
    }

    configuraDatepicker();

    $(document).on('submit', '#treinamento_modal', function() {

        var avaliacao = $('#avaliacao_participante').val();
        var plano_acao = $('#plano_acao').val();
        var cont = 0;

        if (avaliacao == '') {
            alert('Selecione a eficácia!');
            return false;
        }

        if (plano_acao == '') {
            alert('Informe o plano de ação!');
            return false;
        }

        modalAguarde();
    });

    $(document).on('submit', '#treinamento_obs_modal', function() {

        modalAguarde();
    });
</script>