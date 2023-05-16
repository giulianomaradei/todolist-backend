
<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$andamento = $parametros['andamento'];
$letra = addslashes($parametros['nome']);
$id_usuario = $_SESSION['id_usuario'];

if ($parametros['andamento'] == 3){
    $filtro_andamento = "AND a.andamento = '0'";

} else if ($parametros['andamento'] == 1 || $parametros['andamento'] == 2) {
    $filtro_andamento = "AND a.andamento = $andamento";
}

if ($parametros['responsavel']) {
    $filtro_responsavel = "AND a.id_usuario_responsavel = ".$parametros['responsavel']." ";
}

if ($parametros['sinalizacao'] == 1){
    $filtro_sinalizacao = "AND a.sinalizacao_rd = 1";

} else if ($parametros['sinalizacao'] == 2) {
    $filtro_sinalizacao = "AND a.sinalizacao_rd = 0";
}

if($parametros['data_de'] && $parametros['data_ate']){
    $data_de = converteData($parametros['data_de']);
    $data_ate = converteData($parametros['data_ate']);

    $data_de = $data_de.' 00:00:00';
    $data_ate = $data_ate.' 23:59:59';

    if ($parametros['tipo_data'] == 1) {
        $filtro_data = "AND a.data_inicio BETWEEN '$data_de' AND '$data_ate'";

    } else if ($parametros['tipo_data'] == 2) {
        $filtro_data = "AND a.data_conclusao BETWEEN '$data_de' AND '$data_ate'";

    } else if ($parametros['tipo_data'] == 3) {
        $filtro_data = "AND g.data_perda BETWEEN '$data_de' AND '$data_ate'";
    }
}

if ($parametros['lembrete'] == 1){
    $filtro_lembrete = "AND g.data_lembrete IS NOT NULL";

} else if ($parametros['lembrete'] == 2) {
    $filtro_lembrete = "AND g.data_lembrete IS NULL";
}

$filtros_query = "INNER JOIN tb_usuario b ON a.id_usuario_responsavel = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_lead_status d ON a.id_lead_status = d.id_lead_status INNER JOIN tb_pessoa e ON a.id_pessoa = e.id_pessoa LEFT JOIN tb_plano f ON a.id_plano = f.id_plano LEFT JOIN tb_lead_negocio_perdido g ON a.id_lead_negocio = g.id_lead_negocio LEFT JOIN tb_lead_motivo_perda h ON g.id_lead_motivo_perda = h.id_lead_motivo_perda WHERE a.excluido = 1 AND (e.nome LIKE '%$letra%' OR e.razao_social LIKE '%$letra%') $filtro_andamento $filtro_data $filtro_sinalizacao $filtro_responsavel $filtro_lembrete ORDER BY a.id_lead_negocio DESC";

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
?>

<style>
    .label {
        min-width: 94px !important;
        display: inline-block !important;
        font-size: 11.3px;
    }
    .select2{
        width: 100% !important;
    }
</style>

<?php
###################################################################################
// INICIO DO CONTEÚDO
// 
$dados = DBRead('', 'tb_lead_negocio a',$filtros_query." LIMIT $inicio, $maximo", "a.id_lead_negocio, a.id_plano, a.descricao AS negocio_descricao, a.valor_contrato, a.valor_adesao, a.data_inicio, a.data_conclusao, a.tipo_negocio, a.andamento, a.obs_ganhou, c.nome AS nome_responsavel, d.descricao AS status_descricao, e.nome AS nome_lead, f.cod_servico, f.nome AS nome_plano, a.id_lead_status, a.sinalizacao_rd, g.id_lead_negocio_perdido, g.observacao, g.data_lembrete, h.descricao as motivo_perda");

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
    echo "<th class='col-md-1'>&nbsp;&nbsp;&nbsp;&nbsp;#Id</th>";
    echo "<th class='col-md-2'>Empresa/Pessoa</th>";
    echo "<th>Responsável</th>";
    //echo "<th>Valor do contrato</th>";
    /* echo "<th>Serviço</th>"; */
    echo "<th>Status</th>";
    echo "<th>Motivo</th>";
    echo "<th class='col-md-5'>Observação</th>";
    echo "<th>Data do lembrete</th>";
    echo "<th class='text-center'>Opções</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach($dados as $conteudo){

        $id = $conteudo['id_lead_negocio'];
        $nome_lead = $conteudo['nome_lead'];
        $valor_contrato = converteMoeda($conteudo['valor_contrato']);
        $valor_adesao = converteMoeda($conteudo['valor_adesao']);
        $responsavel = $conteudo['nome_responsavel'];
        $data_inicio = converteData($conteudo['data_inicio']);
        $descricao = $conteudo['negocio_descricao'];
        $id_lead_negocio_perdido = $conteudo['id_lead_negocio_perdido'];
        $sinalizacao_rd = $conteudo['sinalizacao_rd'];

        if($conteudo['cod_servico'] == ''){
            $servico = 'N/D';
        }else{
            $servico = getNomeServico($conteudo['cod_servico']);
        }

        if ($sinalizacao_rd == 1) {
            $cor = 'style="color: #5cb85c"';

        } else {
            $cor = '';
        }

        $dados_rd = DBRead('', 'tb_rd_conversao', "WHERE id_lead_negocio = $id AND uuid IS NOT NULL");

        $notifica = '&nbsp;&nbsp;&nbsp;&nbsp;';
        if ($conteudo['andamento'] == 1) {
            $andamento = '<label class="label label-success">Ganhou</label>';
            $motivo_perda = '';
            $data_lembrete = '';
            $observacao_perda = $conteudo['obs_ganhou'];
            $opcao_rd = "<a title='Sinalizar no RD' id='$id' onclick='modal(this.id)' style='padding-right: 5px;cursor: pointer;'><i class='$icone' aria-hidden='true'></i></a>";
            
            if ($dados_rd) {
                $opcao_rd = "<a href='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-tags&id_lead_negocio=$id' title='Sinalizar no RD' id='$id' style='padding-right: 5px;cursor: pointer;'><i class='fa fa-tags' aria-hidden='true' $cor></i></a>";

            } else {
                $opcao_rd = "<a title='Não constam dados do RD' style='padding-right: 4px;'><i class='fas fa-exclamation-triangle' style='opacity: 0.2; color: red;'></i></a>";
            }

        } else if ($conteudo['andamento'] == 0) {
            $andamento = '<label class="label label-default">Em andamento</label>';
            $motivo_perda = '';
            $data_lembrete = '';
            $observacao_perda = '';
            $opcao_rd = "<a title='Indisponível para este status' style='padding-right: 4px;'><i class='fas fa-exclamation-triangle' style='opacity: 0.2; color: gray;'></i></a>";

        } else if ($conteudo['andamento'] == 2) {
            $andamento = '<label class="label label-danger">Perdeu</label>';
            $motivo_perda = $conteudo['motivo_perda'];
            $data_lembrete = convertedata($conteudo['data_lembrete']);
            $observacao_perda = $conteudo['observacao'];
            $opcao_rd = "<a title='Sinalizar no RD' id='$id' onclick='modal(this.id)' style='padding-right: 5px;cursor: pointer;'><i class='$icone' aria-hidden='true'></i></a>";

            if ($dados_rd) {
                $opcao_rd = "<a href='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-tags&id_lead_negocio=$id' title='Sinalizar no RD' id='$id' style='padding-right: 5px;cursor: pointer;'><i class='fa fa-tags' aria-hidden='true' $cor></i></a>";

            } else {
                $opcao_rd = "<a title='Não constam dados do RD!' style='padding-right: 4px;'><i class='fas fa-exclamation-triangle' style='opacity: 0.2; color: red;'></i></a>";
            }

            $data = new DateTime(getDataHora('data'));
            $data_agora = $data->format('Y-m-d');

            if($conteudo['data_lembrete'] && ($data_agora >= $conteudo['data_lembrete'])){

                $verifica_visualizacao = DBRead('', 'tb_lead_negocio_perdido_visualizado', "WHERE id_lead_negocio_perdido = $id_lead_negocio_perdido AND id_usuario = $id_usuario");

                if (!$verifica_visualizacao) {
                    $notifica = '<i class="fa fa-exclamation-circle faa-flash animated" style="color: #eea236;"></i> ';
                } else {
                    $notifica = '&nbsp;&nbsp;&nbsp;&nbsp;';
                }
                
            } else {
                $notifica = '&nbsp;&nbsp;&nbsp;&nbsp;';
            }
        }

        echo "<tr>";
        echo "<td style='vertical-align: middle;'>".$notifica.$id."</td>";
        echo "<td>$nome_lead</td>";
        echo "<td>$responsavel</td>";
        //echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-informacoes&lead=$id'\" style='cursor: pointer'>$valor_contrato</td>";
        /* echo "<td onclick=\"window.location='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-informacoes&lead=$id'\" style='cursor: pointer'>$servico</td>"; */
        echo "<td>$andamento</td>";
        echo "<td>$motivo_perda</td>";
        echo "<td>$observacao_perda</td>";
        echo "<td>$data_lembrete</td>";
        echo "<td class=\"text-center\" style='vertical-align: middle;'>
                $opcao_rd
                <a href='/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-informacoes&lead=$id' title='Alterar'>
                    <i class='fa fa-eye' aria-hidden='true'></i>
                </a>
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

<!-- Modal obs -->
<div class="modal fade" id="negocio_tags" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Inserir tags RD</h4>
            </div>
            <form method="post" action="class/Rd.php" id="rd_tags_modal" style="margin-bottom: 0;">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Tags:</label>
                            <select class="js-example-basic-multiple chamado_usuario" name="tags[]" multiple="multiple">
                                <?php
                                    $dados_tags = DBRead('', 'tb_lead_tag', "ORDER BY descricao ASC");
                                    if ($dados_tags) {
                                        foreach($dados_tags as $conteudo){

                                            $descricao = strtolower($conteudo['descricao']);
                                            
                                            echo "<option value='$descricao'>$descricao</option>";                                          
                                        }
                                    }
                                    ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="id_lead_negocio" value="" name="id_lead_negocio" />
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.js-example-basic-multiple').select2();
    });

    function modal(id) {
        $('#negocio_tags').modal('show');
        $('#id_lead_negocio').val(id);
    }

    function sinalizaRD(id){
        
        btn = $('#'+id);

        $.ajax({
            url: "class/LeadTimelineAjax.php",
            dataType: "JSON",
            method: 'POST',
            data: {
                acao: 'sinalizar_RD',
                parametros: {                       
                'id_lead_negocio' : id,                          
                }
            },
            success: function(data){
                if (data == 1) {
                    btn.children().removeClass('far fa-circle').addClass('fa fa-check');

                } else {
                    btn.children().removeClass('fa fa-check').addClass('far fa-circle');
                }
            }
        });
    }
</script>