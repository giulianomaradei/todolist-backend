<?php
require_once(__DIR__."/../class/System.php");

if($_GET['visualizar']){

    $id_boleto = (int)$_GET['visualizar'];
    $dados = DBRead('', 'tb_boleto a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_boleto = '".$id_boleto."' " ,"a.*, b.*, a.id_usuario AS 'id_usuario_boleto'");
            
    $id_integracao = $dados[0]['id_integracao'];
    if($id_integracao){
        $pdf = 'ok';
    }   


    $contrato = $dados[0]['razao_social'];
    $cpf_cnpj = formataCampo('cpf_cnpj',$dados[0]['sacado_cpf_cnpj']);
    $logradouro = $dados[0]['sacado_endereco_logradouro'];
    $numero = $dados[0]['sacado_endereco_numero'];
    if($dados[0]['sacado_endereco_complemento']){
    	$complemento = $dados[0]['sacado_endereco_complemento'];
    }else{
    	$complemento = 'N/D';
    }
    $bairro = $dados[0]['sacado_endereco_bairro'];
    $cep = formataCampo('cep', $dados[0]['sacado_endereco_cep']);
    $cidade = $dados[0]['sacado_endereco_cidade'];
    $uf = $dados[0]['sacado_endereco_uf'];
    $pais = $dados[0]['sacado_endereco_pais'];
    
    

    if($dados[0]['pagamento_data']){
        $pagamento_data = converteDataHora($dados[0]['pagamento_data']);
    }else{
        $pagamento_data = 'N/D';
    }

    if($dados[0]['pagamento_valor_pago']){
        $pagamento_valor_pago = converteMoeda($dados[0]['pagamento_valor_pago']);
    }else{
        $pagamento_data = 'N/D';
    }

    if($dados[0]['pagamento_valor_taxa_cobranca']){
        $pagamento_valor_taxa_cobranca = converteMoeda($dados[0]['pagamento_valor_taxa_cobranca']);
    }else{
        $pagamento_valor_taxa_cobranca = 'N/D';
    }

    if($dados[0]['pagamento_data_taxa_bancaria']){
        $pagamento_data_taxa_bancaria = converteDataHora($dados[0]['pagamento_data_taxa_bancaria']);
    }else{
        $pagamento_data_taxa_bancaria = 'N/D';
    }



    $dados_usuario = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$dados[0]['id_usuario_boleto']."' " ,"b.nome");
    $usuario_cadastrou = $dados_usuario[0]['nome'];

    $titulo_valor = converteMoeda($dados[0]['titulo_valor']);
    $titulo_data_emissao = converteDataHora($dados[0]['titulo_data_emissao']);
    $titulo_data_vencimento = converteDataHora($dados[0]['titulo_data_vencimento']);
    $titulo_nosso_numero = $dados[0]['titulo_nosso_numero'];
    $titulo_numero_documento = $dados[0]['titulo_numero_documento'];
    $situacao = $dados[0]['situacao'];
    if($dados[0]['motivo_situacao']){
        $motivo_situacao = $dados[0]['motivo_situacao'];
    }else{
        $motivo_situacao = 'N/D';
    }
    $id_faturamento = $dados[0]['id_faturamento'];
    $id_integracao = $dados[0]['id_integracao'];

    $titulo_doc_especie = $dados[0]['titulo_doc_especie'];
    $titulo_local_pagamento = $dados[0]['titulo_local_pagamento'];
    $titulo_mensagem_01 = $dados[0]['titulo_mensagem_01'];
    $titulo_mensagem_02 = $dados[0]['titulo_mensagem_02'];

}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Dados do Boleto #<?=$id_boleto;?>:</h3>
                    <h3 class="panel-title text-right pull-right">Boleto gerado por: <?=$usuario_cadastrou;?></h3>
                </div>
                <form method="post" action="/api/ajax?class=Parametro.php" id="parametro_form" style="margin-bottom: 0;">
                    <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <h4><strong>Informações do Cliente</strong></h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Razão Social:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$contrato;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>CPF/CNPJ:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$cpf_cnpj;?>" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Logradouro:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$logradouro;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Número:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$numero;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Complemento:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$complemento;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Bairro:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$bairro;?>" readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>CEP:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$cep;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Cidade:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$cidade;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>UF:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$uf;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>País:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$pais;?>" readonly/>
                                </div>
                            </div>
                        </div>

                        <hr>
						<h4><strong>Informações do Boleto</strong></h4>
	                 	<hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Valor (R$):</label>
                                    <input type="text" class="form-control input-sm" value="<?=$titulo_valor;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Data de Emissão:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$titulo_data_emissao;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Data de Vencimento:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$titulo_data_vencimento;?>" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Identificador da Integração:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$id_integracao;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Nosso Número:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$titulo_nosso_numero;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Número do Documento:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$titulo_numero_documento;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Situação:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$situacao;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Motivo Situação:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$motivo_situacao;?>" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mensagem 01:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$titulo_mensagem_01;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mensagem 02:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$titulo_mensagem_02;?>" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Doc. Espécie:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$titulo_doc_especie;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Mensagem Local do Pagamento:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$titulo_local_pagamento;?>" readonly/>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h4><strong>Informações de Pagamento</strong></h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Valor Pago(R$):</label>
                                    <input type="text" class="form-control input-sm" value="<?=$pagamento_valor_pago;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Data do Pagamento:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$pagamento_data;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Valor do Pagamento da Taxa Bancária:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$pagamento_valor_taxa_cobranca;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Data do Pagamento da Taxa Bancária:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$pagamento_data_taxa_bancaria;?>" readonly/>
                                </div>
                            </div>
                        </div>

                    </div>
                    <?php 
                    if($pdf){
                    echo "
                    <div class='panel-footer'>
                        <div class='row'>
                            <div class='col-md-12' style='text-align: center'>
                                <a href='/api/ajax?class=Boleto.php?gerar_pdf=$id_boleto'&token=". $request->token ."><button class='btn btn-success' type='button'><i class='fa fa-file-pdf-o'></i> Download PDF</button></a>
                            </div>
                        </div>
                    </div>";
                    }
                    ?>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
