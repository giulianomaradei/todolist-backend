<?php
require_once(__DIR__."/../class/System.php");

$primeiro_dia = new DateTime(getDataHora('data'));
$primeiro_dia->modify('first day of this month');
$primeiro_dia = $primeiro_dia->format('d/m/Y');

$ultimo_dia = new DateTime(getDataHora('data'));
$ultimo_dia->modify('last day of this month');
$ultimo_dia = $ultimo_dia->format('d/m/Y');

$tipo_pessoa = '';

if($tipo_pessoa == "contrato" || !$tipo_pessoa){
	$display_col_servico = '';
	$display_col_plano = '';
	$display_tipo_cobranca = '';
    $display_id_responsavel = '';
    
    $display_col_pf_pj = 'style="display:none;"';
	$display_col_candidato = 'style="display:none;"';
	$display_col_cliente = 'style="display:none;"';
	$display_col_fornecedor = 'style="display:none;"';
	$display_col_funcionario = 'style="display:none;"';
	$display_col_prospeccao = 'style="display:none;"';
}else{
	$display_col_servico = 'style="display:none;"';
	$display_col_plano = 'style="display:none;"';
	$display_tipo_cobranca = 'style="display:none;"';
    $display_id_responsavel = 'style="display:none;"';
    
    $display_col_pf_pj = '';
    $display_col_candidato = '';
    $display_col_cliente = '';
    $display_col_fornecedor = '';
    $display_col_funcionario = '';
    $display_col_prospeccao = '';
}

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="row">
                    <h3 class="panel-title text-left col-md-12">Enviar E-mail:</h3>
                    
                    </div>
                </div>
                <div class="panel-body" style="padding-bottom: 0;">

                    <div class="row">

                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label>Busca:</label>
                                <input class="form-control input-sm" type="text" name="nome" id="nome" placeholder="Digite o nome da Pessoa ou Contrato" autocomplete="off">
                                <!-- <input class="form-control input-sm" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Digite o nome da Pessoa ou Contrato" autocomplete="off"> -->
                            </div>
                        </div>
                        
                        <div class="col-md-1">
                            <div class="form-group has-feedback">
                                <label>Tipo:</label>
                                <select class="form-control input-sm" name="tipo_pessoa" id="tipo_pessoa">
                                    <option value="contrato">Contrato</option>
                                    <option value="pessoa">Pessoa</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2" id="col_servico" <?=$display_col_servico?>>
                            <div class="form-group">
                                <label for="">Serviço:</label>
                                <select class="form-control input-sm" name="servico" id="servico">
                                    <option value="">Qualquer</option>
                                    <option value="call_ativo">Call Center - Ativo</option>
                                    <option value="call_monitoramento">Call Center - Monitoramento</option>
                                    <option value="call_suporte">Call Center - Suporte</option>
                                    <option value="gestao_redes">Gestão de Redes</option>
                                </select>
                            </div>
                        </div>
                    
                        <div class="col-md-2" id="col_plano" <?=$display_col_plano?>>
                            <div class="form-group">
                                <label>Plano:</label>
                                <select class="form-control input-sm" name="id_plano" id="id_plano">
                                    <option value="">Qualquer</option>
                                    <?php
                                        $dados = DBRead('', 'tb_plano', "WHERE status = 1 ORDER BY nome ASC");
                                        if ($dados) {
                                            foreach ($dados as $conteudo) {
                                                $id_plano = $conteudo['id_plano'];
                                                $nome_servico = $conteudo['nome'];
                                                echo "<option value='".$id_plano."'>".$nome_servico."</option>";
                                            }
                                        }
                                    ?>                             
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-2" id="col_tipo_cobranca" <?=$display_tipo_cobranca?>>
                            <div class="form-group">
                                <label>Tipo de Cobrança:</label>
                                <select class="form-control input-sm" name="tipo_cobranca" id="tipo_cobranca">
                                    <option value="">Qualquer</option>
                                    <?php
                                        $dados = DBRead('', 'tb_contrato_plano_pessoa', "GROUP BY tipo_cobranca ORDER BY tipo_cobranca ASC", "tipo_cobranca");
                                        if ($dados) {
                                            foreach ($dados as $conteudo) {
                                                if($conteudo['tipo_cobranca'] == 'mensal_desafogo'){
                                                    $tipo_cobranca = "Mensal com Desafogo";
                                                }else if($conteudo['tipo_cobranca'] == 'unitario'){
                                                    $tipo_cobranca = "Unitário";
                                                }else if($conteudo['tipo_cobranca'] == 'x_cliente_base'){
                                                    $tipo_cobranca = "Até X Clientes na Base";
                                                }else if($conteudo['tipo_cobranca'] == 'prepago'){
                                                    $tipo_cobranca = "Pré-pago";
                                                }else{
                                                    $tipo_cobranca = ucfirst($conteudo['tipo_cobranca']);
                                                }
                                                echo "<option value='".$conteudo['tipo_cobranca']."'>".$tipo_cobranca."</option>";
                                            }
                                        }
                                    ?>                             
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2" id="col_id_responsavel" <?=$display_id_responsavel?>>
                            <div class="form-group">
                                <label>Responsável Pelo Relacionamento:</label>
                                <select class="form-control input-sm" name="id_responsavel" id="id_responsavel">
                                    <option value="">Qualquer</option>
                                    <?php
                                        $dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_usuario b ON a.id_responsavel = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa ORDER BY c.nome ASC", "DISTINCT a.id_responsavel, c.nome");
                                        if ($dados) {
                                            foreach ($dados as $conteudo) {
                                                $id_responsavel = $conteudo['id_responsavel'];
                                                $nome_responsavel = $conteudo['nome'];
                                                echo "<option value='".$id_responsavel."'>".$nome_responsavel."</option>";
                                            }
                                        }
                                    ?>                             
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2" id="col_pf_pj" <?=$display_col_pf_pj?>>
                            <div class="form-group">
                                <label for="">Pessoa Física ou Jurídica:</label>
                                <select class="form-control input-sm" name="pf_pj" id="pf_pj">
                                    <option value="">Qualquer</option>
                                    <option value="pf">Pessoa Física</option>
                                    <option value="pj">Pessoa Jurídica</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-1" id="col_candidato" <?=$display_col_candidato?>>
                            <div class="form-group">
                                <label for="">Candidato:</label>
                                <select class="form-control input-sm" name="candidato" id="candidato">
                                    <option value="">Qualquer</option>
                                    <option value="1">Sim</option>
                                    <option value="2">Não</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-1" id="col_cliente" <?=$display_col_cliente?>>
                            <div class="form-group">
                                <label for="">Cliente:</label>
                                <select class="form-control input-sm" name="cliente" id="cliente">
                                    <option value="">Qualquer</option>
                                    <option value="1">Sim</option>
                                    <option value="2">Não</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-1" id="col_fornecedor" <?=$display_col_fornecedor?>>
                            <div class="form-group">
                                <label for="">Fornecedor:</label>
                                <select class="form-control input-sm" name="fornecedor" id="fornecedor">
                                    <option value="">Qualquer</option>
                                    <option value="1">Sim</option>
                                    <option value="2">Não</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-1" id="col_funcionario" <?=$display_col_funcionario?>>
                            <div class="form-group">
                                <label for="">Funcionário:</label>
                                <select class="form-control input-sm" name="funcionario" id="funcionario">
                                    <option value="">Qualquer</option>
                                    <option value="1">Sim</option>
                                    <option value="2">Não</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2" id="col_prospeccao" <?=$display_col_prospeccao?>>
                            <div class="form-group">
                                <label for="">Prospecção:</label>
                                <select class="form-control input-sm" name="prospeccao" id="prospeccao">
                                    <option value="">Qualquer</option>
                                    <option value="1">Sim</option>
                                    <option value="2">Não</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp</label>
                                <button class="btn btn-primary btn-sm form-control" type="button" style="max-height: 30px;" onclick="call_busca_ajax();">
                                    <i class="fa fa-refresh"></i> Filtrar
                                </button>
                            </div>
                        </div>



                    </div>
                    

                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="resultado_busca_conta_receber">
                                <div class='col-md-12'>
                                    <p class='alert alert-warning' style='text-align: center'>Selecione os filtros!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                
                        
        </div>
    </div>
</div>

<script>

    //Contrato
        $(document).ready(function() {
            selectplano($('select[name=servico]').val());
        });

        function selectplano(cod_servico, id_plano){        
            var id_plano = $('#id_plano').val();
            $("select[name=id_plano]").html('<option value="">Carregando...</option>');
            $.post("/api/ajax?class=SelectPlano.php",
                {cod_servico: cod_servico,
                id_plano: id_plano,
                pagina: 'enviar-email-busca',
                token: '<?= $request->token ?>'},
                function(valor){
                    $("select[name=id_plano]").html(valor);
                }
            )        
        }

        $('#servico').on('change',function(){
            servico = $(this).val();
            selectplano(servico);
        });
    //Contrato

    $('#tipo_pessoa').on('change',function(){
		tipo_pessoa = $(this).val();
		if(tipo_pessoa == 'contrato'){
			$('#col_servico').show();
			$('#col_plano').show();
			$('#col_tipo_cobranca').show();
			$('#col_id_responsavel').show();

            $('#col_pf_pj').hide();
			$('#col_candidato').hide();
			$('#col_cliente').hide();
			$('#col_fornecedor').hide();
			$('#col_funcionario').hide();
			$('#col_prospeccao').hide();
		}else{
            $('#col_servico').hide();
			$('#col_plano').hide();
			$('#col_tipo_cobranca').hide();
			$('#col_id_responsavel').hide();
            
            $('#col_pf_pj').show();
			$('#col_candidato').show();
			$('#col_cliente').show();
			$('#col_fornecedor').show();
			$('#col_funcionario').show();
			$('#col_prospeccao').show();
		}
	}); 

    //Início aba contas a receber

        function call_busca_ajax(pagina){
            var inicia_busca = 3;

            var tipo_pessoa = $('#tipo_pessoa').val();
            var id_plano = $('#id_plano').val();
            var servico = $('#servico').val();
            var tipo_cobranca = $('#tipo_cobranca').val();
            var id_responsavel = $('#id_responsavel').val();
            var nome = $('#nome').val();
            // if(nome){
            //     tipo_pessoa = 'pessoa';
            // }

            var pf_pj = $('#pf_pj').val();
            var candidato = $('#candidato').val();
            var cliente = $('#cliente').val();
            var fornecedor = $('#fornecedor').val();
            var funcionario = $('#funcionario').val();
            var prospeccao = $('#prospeccao').val();
            

            // if (nome.length < inicia_busca && nome.length >=1){
            //     return false;
            // }            
            if(pagina === undefined){
                pagina = 1;
            }
            if(tipo_pessoa == 'contrato'){

                var parametros = {
                    'nome': nome,
                    'tipo_pessoa': tipo_pessoa,
                    'id_plano': id_plano,
                    'servico': servico,
                    'tipo_cobranca': tipo_cobranca,
                    'id_responsavel': id_responsavel,
                   
                    'pagina': pagina
                };
            }else{
                var parametros = {
                    'nome': nome,
                    'tipo_pessoa': tipo_pessoa,
                    'pf_pj': pf_pj,
                    'candidato': candidato,
                    'cliente': cliente,
                    'fornecedor': fornecedor,
                    'funcionario': funcionario,
                    'prospeccao': prospeccao,
                    
                    'pagina': pagina
                };
            }
            
            busca_ajax('<?= $request->token ?>' , 'EmailEnviarBusca', 'resultado_busca_conta_receber', parametros);
        }

    //Fim aba contas a receber

</script>