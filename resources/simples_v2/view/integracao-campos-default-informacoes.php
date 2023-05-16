<?php 
require_once(__DIR__."/../class/System.php");

$id_contrato_plano_pessoa = (int)$_GET['id_contrato_plano_pessoa'];
$id_subarea_problema = (int)$_GET['id_sub_area_problema'];

$nome_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "b.nome");

$area_subarea = DBRead('', 'tb_subarea_problema a', "INNER JOIN tb_area_problema b ON a.id_area_problema = b.id_area_problema WHERE a.id_subarea_problema = $id_subarea_problema");

$dados = DBRead('', 'tb_integracao_valores_default a', 'INNER JOIN tb_integracao_campos_default b ON a.id_integracao_campos_default = b.id_integracao_campos_default WHERE a.id_contrato_plano_pessoa = "'.$id_contrato_plano_pessoa.'" AND a.id_subarea_problema = "'.$id_subarea_problema.'"', 'a.value_default, codigo_campo');

foreach ($dados as $conteudo) {

    if ($conteudo['codigo_campo'] == 'classificacao') {
        $value_classificacao = $conteudo['value_default'];

        if ($value_classificacao == 1) {
            $classificacao = 'Ordem de serviço';

        } else if ($value_classificacao == 2) {
            $classificacao = 'Atendimento';
        }
    }

    if ($conteudo['codigo_campo'] == 'assunto') {
        $value_assunto = $conteudo['value_default'];
    }

    if ($conteudo['codigo_campo'] == 'filial') {
        $value_filial = $conteudo['value_default'];
    }

    if ($conteudo['codigo_campo'] == 'tecnico') {
        $value_tecnico = $conteudo['value_default'];
    }

    if ($conteudo['codigo_campo'] == 'prioridade') {
        $value_prioridade = $conteudo['value_default'];

        if ($value_prioridade == 'B') {
            $prioridade = 'Baixa';

        } else if ($value_prioridade == 'M' || $value_prioridade == 'N') {
            $prioridade = 'Normal';

        } else if ($value_prioridade == 'A') {
            $prioridade = 'Alta';

        } else if ($value_prioridade == 'C') {
            $prioridade = 'Crítica';
        }
    }

    if ($conteudo['codigo_campo'] == 'origem') {
        $value_origem = $conteudo['value_default'];

        if ($value_origem == 'C') {
            $origem = 'Cliente';

        } else if ($value_origem == 'L') {
            $origem = 'Login';

        } else if ($value_origem == 'CC') {
            $origem = 'Contrato';

        } else if ($value_origem == 'M') {
            $origem = 'Manual';

        } 
    } else {
        $origem = ' --- ';
    }

    if ($conteudo['codigo_campo'] == 'setor') {
        $value_setor = $conteudo['value_default'];
    }

    if ($conteudo['codigo_campo'] == 'departamento') {
        $value_departamento = $conteudo['value_default'];
    }

    if ($conteudo['codigo_campo'] == 'processo') {
        $value_processo = $conteudo['value_default'];
    }
}

?>

<div class="row">
    <div class="col-md-12">
        <div id='alerta' role="alert"></div>
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> Valores default: <?= $nome_contrato[0]['nome'] ?></h3>
                    <div class="panel-title text-right pull-right"><a href="/api/ajax?class=IntegracaoCamposDefault.php?acao=exclui_campos&id_contrato_plano_pessoa=<?=$id_contrato_plano_pessoa?>&id_subarea_problema=<?=$id_subarea_problema?>&token=<?=$request->token?>" onclick="if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }"><button class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Excluir</button></a>
                    </div>
            </div>
            <div class="panel-body" style="padding-bottom: 0;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Área do problema</label>
                            <input class="form-control input-sm" readonly="" value="<?= $area_subarea[0]['nome'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Subarea do problema</label>    
                            <input class="form-control input-sm" readonly="" value="<?= $area_subarea[0]['descricao'] ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Classificação:</label>
                            <input class="form-control input-sm" readonly="" value="<?= $classificacao ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Assunto:</label>
                            <input class="form-control input-sm" readonly="" id="assunto">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Filial:</label>
                            <input class="form-control input-sm" readonly="" id="filial">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Técnico responsável:</label>
                            <input class="form-control input-sm" readonly="" id="tecnico">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Prioridade:</label>
                            <input class="form-control input-sm" readonly="" id="prioridade" value="<?= $prioridade ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Origem do endereço:</label>
                            <input class="form-control input-sm" readonly="" id="origem" value="<?= $origem ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4" id="default-setor">
                        <div class="form-group">
                            <label>Setor:</label>
                            <input class="form-control input-sm" readonly="" id="setor">
                        </div>
                    </div>
                    <div class="col-md-4" id="default-departamento">
                        <div class="form-group">
                            <label>Departamento:</label>
                            <input class="form-control input-sm" readonly="" id="departamento">
                        </div>
                    </div>
                    <div class="col-md-4" id="default-processo">
                        <div class="form-group">
                            <label>Processo:</label>
                            <input class="form-control input-sm" readonly="" id="processo">
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>

<script>
    //Preenche com seus dados
    function busca_campos() {

        id_assunto = '<?php echo $value_assunto ?>';
        if (id_assunto != '') {
            $.ajax({
                type: "GET",
                url: "/api/ajax?class=IntegracaoTipoSistemaAjax.php",
                dataType: "json",
                data: {
                    acao: "busca_assunto",
                    id_contrato_plano_pessoa: '<?php echo $id_contrato_plano_pessoa ?>',
                    id_assunto: id_assunto,
                    token: '<?= $request->token ?>'
                },
                success: function(data) {
                    $('#assunto').val(data.registros[0].assunto);
                }
            });
        } else {
            $('#assunto').val(' --- ');
        }

        id_filial = '<?php echo $value_filial ?>';
        if (id_filial != '') {
            $.ajax({
                type: "GET",
                url: "/api/ajax?class=IntegracaoTipoSistemaAjax.php",
                dataType: "json",
                data: {
                    acao: "busca_filial",
                    id_contrato_plano_pessoa: '<?php echo $id_contrato_plano_pessoa ?>',
                    id_filial: id_filial,
                    token: '<?= $request->token ?>'
                },
                success: function(data) {
                    $('#filial').val(data.registros[0].razao);
                }
            });
        } else {
            $('#filial').val(' --- ');
        }

        id_tecnico = '<?php echo $value_tecnico ?>';
        if (id_tecnico != '') {
            $.ajax({
                type: "GET",
                url: "/api/ajax?class=IntegracaoTipoSistemaAjax.php",
                dataType: "json",
                data: {
                    acao: "busca_tecnico",
                    id_contrato_plano_pessoa: '<?php echo $id_contrato_plano_pessoa ?>',
                    id_tecnico: id_tecnico,
                    token: '<?= $request->token ?>'
                },
                success: function(data) {
                    for( i=0; i<data.registros.length; i++ ){
                        if (data.registros[i].id == id_tecnico) {
                            tecnico = data.registros[i].funcionario;
                        }
                    }
                   $('#tecnico').val(tecnico);
                }
            });
        }else {
            $('#tecnico').val(' --- ');
        }

        id_setor = '<?php echo $value_setor ?>';
        if (id_setor != '') {
            $.ajax({
                type: "GET",
                url: "/api/ajax?class=IntegracaoTipoSistemaAjax.php",
                dataType: "json",
                data: {
                    acao: "busca_setor2",
                    id_contrato_plano_pessoa: '<?php echo $id_contrato_plano_pessoa ?>',
                    id_setor: id_setor,
                    token: '<?= $request->token ?>'
                },
                success: function(data) {
                    console.log(data);
                    $('#setor').val(data.registros[0].setor);
                }
            });
        } else {
            $('#setor').val(' --- ');
        }

        id_setor2 = '<?php echo $value_departamento ?>';
        if (id_setor2 != '') {
            $.ajax({
                type: "GET",
                url: "/api/ajax?class=IntegracaoTipoSistemaAjax.php",
                dataType: "json",
                data: {
                    acao: "busca_setor",
                    id_contrato_plano_pessoa: '<?php echo $id_contrato_plano_pessoa ?>',
                    id_setor2: id_setor2,
                    token: '<?= $request->token ?>'
                },
                success: function(data) {
                    $('#departamento').val(data.registros[0].setor);
                }
            });
        } else {
            $('#departamento').val(' --- ');
        }

        id_processo = '<?php echo $value_processo ?>';
        if (id_setor2 != '') {
            $.ajax({
                type: "GET",
                url: "/api/ajax?class=IntegracaoTipoSistemaAjax.php",
                dataType: "json",
                data: {
                    acao: "busca_processo",
                    id_contrato_plano_pessoa: '<?php echo $id_contrato_plano_pessoa ?>',
                    id_processo: id_processo,
                    token: '<?= $request->token ?>'
                },
                success: function(data) {
                    $('#processo').val(data.registros[0].descricao);
                }
            });
        } else {
            $('#processo').val(' --- ');
        }
    }

    busca_campos();
</script>