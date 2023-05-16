<?php
require_once(__DIR__."/../class/System.php");


if($_GET['visualizar']){
    $id_nfs = (int)$_GET['visualizar'];


    $dados = DBRead('', 'tb_nfs a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_nfs = '".$id_nfs."' " ,"a.*, b.*, a.status AS status_nota, a.tipo AS tipo_nota, a.numero AS numero_nota, a.descricao AS descricao_nota, a.valor_total AS valor_total_nota, a.id_usuario AS 'id_usuario_nfs'");

    $descricao_nota = $dados[0]['descricao_nota'];
    $valor_total_nota = $dados[0]['valor_total_nota'];

    $data_criacao_compara = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($dados[0]['data_criacao'])));
    $data_hoje = date('Y-m-d H:i:s', strtotime("+0 days",strtotime(getDataHora())));

    $total = (strtotime($data_criacao_compara) - strtotime($data_hoje))/3600;
    $total = $total*(-1);

    $dados_usuario = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$dados[0]['id_usuario_nfs']."' " ,"b.nome");
    $usuario_cadastrou = $dados_usuario[0]['nome'];

    if($dados[0]['nome_contrato']){
        $nome_contrato = " (".$dados[0]['nome_contrato'].") ";
    }else{
        $nome_contrato = '';
    }

    $contrato = $dados[0]['cliente_razao_social'].$nome_contrato;

    $data_criacao = converteDataHora($dados[0]['data_criacao']);

    if($dados[0]['data_autorizacao']){
        $data_autorizacao = explode("T", $dados[0]['data_autorizacao']);
        $data_autorizacao = converteDataHora(date('Y-m-d H:i:s', strtotime("-3 hours",strtotime(substr($data_autorizacao[0].' '.$data_autorizacao[1], 0, -1)))));
    }else{
        $data_autorizacao = 'Não autorizado ainda';
    }

    $logradouro = $dados[0]['cliente_logradouro'];
    $numero = $dados[0]['cliente_numero'];

    if($dados[0]['cliente_complemento']){
        $complemento = $dados[0]['cliente_complemento'];
    }else{
        $complemento = 'N/D';
    }

    $bairro = $dados[0]['cliente_bairro'];

    $cep = formataCampo('cep', $dados[0]['cliente_cep']);

    $dados_cidade_uf = DBRead('', 'tb_cidade a',"INNER JOIN tb_estado b ON a.id_estado = b.id_estado WHERE a.id_cidade = '".$dados[0]['cliente_id_cidade']."' ", "a.*, b.sigla AS uf");
    $cidade = $dados_cidade_uf[0]['nome'];
    $uf = $dados_cidade_uf[0]['uf'];

    $tipo = $dados[0]['tipo_nota'];
    $status = ucfirst($dados[0]['status_nota']);
    if($dados[0]['motivo_status']){
        $motivo_status = $dados[0]['motivo_status'];
    }else{
        $motivo_status = 'N/D';
    }

    $id_nfs_enotas = $dados[0]['id_nfs_enotas'];
    $numero_nota = $dados[0]['numero_nota'];

    if($dados[0]['data_competencia']){
        $data_competencia = explode("T", $dados[0]['data_competencia']);
        $data_competencia = converteDataHora(date('Y-m-d H:i:s', strtotime("-3 hours",strtotime(substr($data_competencia[0].' '.$data_competencia[1], 0, -1)))));
    }else{
        $data_competencia = 'Não autorizado ainda';
    }

    $numero_rps = $dados[0]['numero_rps'];
    $serie_rps = $dados[0]['serie_rps'];
    $codigo_verificacao = $dados[0]['codigo_verificacao'];

    $pdf = $dados[0]['link_pdf'];

    $xml = $dados[0]['link_xml'];

    $valor_pis = $dados[0]['valor_pis'];
    $valor_cofins = $dados[0]['valor_cofins'];
    $valor_csll = $dados[0]['valor_csll'];
    $valor_ir = $dados[0]['valor_ir'];

    $codigo_servico_municipio = $dados[0]['codigo_servico_municipio'];
    $descricao_servico_municipio = $dados[0]['descricao_servico_municipio'];
    $item_lista_servico = $dados[0]['item_lista_servico'];

    $tipo_pessoa = $dados[0]['tipo_pessoa'];
    $cliente_cpf_cnpj = $dados[0]['cliente_cpf_cnpj'];

    if($tipo_pessoa == 'F'){
        $tipo_pessoa = 'Pessoa Física';
    }else{
        $tipo_pessoa = 'Pessoa Jurídica';
    }

    $id_nfs = $dados[0]['id_nfs'];
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Dados da NFS-e #<?=$id_nfs;?> gerada por: <?=$usuario_cadastrou;?>:</h3>
                    <?php 
                    if($status == 'Inserindo' || $status == 'Cancelando' || $status == 'Cancelamentonegado'){

                        echo '<div class="panel-title text-right pull-right"><a href="/api/ajax?class=Nfs.php?sincronizar='.$id_nfs.'&token='. $request->token .'"><button class="btn btn-xs btn-primary"><i class="fa fa-refresh"></i> Sincronização Manual</button></a></div>';
                    }
                    ?>
                </div>
                <form method="post" action="/api/ajax?class=Parametro.php" id="parametro_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
						<h4><strong>Informações do Cliente</strong></h4>
	                 	<hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Razão Social:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$contrato;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Data de Criação:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$data_criacao;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Data de Autorização:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$data_autorizacao;?>" readonly/>
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>CEP:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$cep;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cidade:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$cidade;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>UF:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$uf;?>" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tipo de Pessoa</label>
                                    <input type="text" class="form-control input-sm" value="<?=$tipo_pessoa;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>CPF/CNPJ:</label>
                                    <input type="text" class="form-control input-sm" value="<?=formataCampo('cpf_cnpj', $cliente_cpf_cnpj);?>" readonly/>
                                </div>
                            </div>
                        </div>

                        <hr>
						<h4><strong>Informações da Nota</strong></h4>
	                 	<hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$tipo;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$status;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Motivo Status:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$motivo_status;?>" readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>ID NFS-e:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$id_nfs_enotas;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número da Nota:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$numero_nota;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Data de Competência :</label>
                                    <input type="text" class="form-control input-sm" value="<?=$data_competencia;?>" readonly/>
                                </div>
                            </div>
                        </div>

                       
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número RPS:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$numero_rps;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Série RPS:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$serie_rps;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Código de Verificação:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$codigo_verificacao;?>" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descrição da Nota:</label>
                                    <textarea type="text" class="form-control input-sm" value="<?=$descricao_nota;?>" style="height: 100px;" readonly/><?=$descricao_nota;?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Código de Serviço do Município:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$codigo_servico_municipio;?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Item da Lista de Serviço:</label>
                                    <input type="text" class="form-control input-sm" value="<?=$item_lista_servico;?>" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descrição de Serviço do Município:</label>
                                    <textarea type="text" class="form-control input-sm" value="<?=$descricao_servico_municipio;?>" readonly/><?=$descricao_servico_municipio;?></textarea>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h4><strong>Informações de Valores</strong></h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Valor PIS:</label>
                                    <input type="text" class="form-control input-sm" value="<?=converteMoeda($valor_pis);?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Valor COFINS:</label>
                                    <input type="text" class="form-control input-sm" value="<?=converteMoeda($valor_cofins);?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Valor CSLL:</label>
                                    <input type="text" class="form-control input-sm" value="<?=converteMoeda($valor_csll);?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Valor IR:</label>
                                    <input type="text" class="form-control input-sm" value="<?=converteMoeda($valor_ir);?>" readonly/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Valor Total (R$):</label>
                                    <input type="text" class="form-control input-sm" value="<?=converteMoeda($valor_total_nota);?>" readonly/>
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
                                <a href='".$pdf."' target='_blank'><button class='btn btn-success' type='button'><i class='fa fa-file-pdf-o'></i> Download PDF</button></a>
                                <a href='".$xml."' target='_blank'><button class='btn btn-success' type='button'><i class='fa fa-file-code-o'></i> Download XML</button><a>
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
