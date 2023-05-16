<?php
require_once(__DIR__."/System.php");

$id_caixa_movimentacao = (isset($_POST['parametros'])) ? $_POST['parametros'] : '';
 
//nesse vai o header e o body

$filtros_query  = " ";

$dados = DBRead('', 'tb_caixa_movimentacao a', "INNER JOIN tb_natureza_financeira c ON a.id_natureza_financeira = c.id_natureza_financeira INNER JOIN tb_natureza_financeira_agrupador d ON c.id_natureza_financeira_agrupador = d.id_natureza_financeira_agrupador INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_caixa_movimentacao = '".$id_caixa_movimentacao."' ", "a.*, b.nome, c.nome AS nome_natureza, d.nome AS nome_natureza_agrupador");
		        
$nome = $dados[0]['nome'];
$natureza = $dados[0]['nome_natureza_agrupador']." (".$dados[0]['nome_natureza'].")";
$nome_natureza = $dados[0]['nome_natureza'];

$data_movimentacao = converteData($dados[0]['data_movimentacao']);
$soma_total_caixa += $dados[0]['valor'];
$valor = converteMoeda($dados[0]['valor']);

if ($dados[0]['tipo'] == 'entrada') {
    $tipo = '<span class="label label-success" style="display: inline-block; min-width: 70px; font-size: 13px;"><i class="fas fa-arrow-circle-down" aria-hidden="true"></i> Entrada</span>';
    $tipo_modal = '<span class="label label-success" style="display: inline-block; min-width: 100px;"><i class="fas fa-arrow-circle-down" aria-hidden="true"></i> Entrada </span>';

} else {
    $tipo = '<span class="label label-danger" style="display: inline-block; min-width: 70px; font-size: 13px;"><i class="fas fa-arrow-circle-up" aria-hidden="true"></i> Saída </span>';
    $tipo_modal = '<span class="label label-danger" style="display: inline-block; min-width: 100px;"><i class="fas fa-arrow-circle-up" aria-hidden="true"></i> Saída </span>';
}

$id_boleto = '';
$btn_visualizar_boleto = '';
 $id_nfs = '';
$btn_visualizar_nfs = '';

if ($dados[0]['origem'] == 'conta_receber') {
    $origem = 'Conta a Receber';

} else if ($conteudo['origem'] == 'transferencia') {
    $origem = 'Transferência';

} else {
    $origem = 'Conta a Pagar';
}



$dados_caixa = DBRead('', 'tb_caixa', "WHERE id_caixa = '".$dados[0]['id_caixa']."' ");
$dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$dados[0]['id_usuario']."' ");
$dados_pessoa = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$dados[0]['id_pessoa']."' ");

if ($dados_pessoa[0]['cpf_cnpj']) {
    $cpf_cnpj = formataCampo('cpf_cnpj', $dados_pessoa[0]['cpf_cnpj']);
} else {
    $cpf_cnpj = 'Não cadastrado';
}

if ($dados[0]['tipo'] == 'entrada') {
    $titulo_conta =  'Conta a Receber';
    $dados_conta = DBRead('', 'tb_conta_receber', "WHERE id_caixa_movimentacao = '".$dados[0]['id_caixa_movimentacao']."' ");

} else if ($dados[0]['tipo'] == 'saida') {
    $titulo_conta = 'Conta a Pagar';
    $dados_conta = DBRead('', 'tb_conta_pagar', "WHERE id_caixa_movimentacao = '".$dados[0]['id_caixa_movimentacao']."' ");
}

if ($dados[0]['tipo'] == 'entrada') {
    $titulo_conta =  'Conta a Receber';
    $dados_conta = DBRead('', 'tb_conta_receber', "WHERE id_caixa_movimentacao = '".$dados[0]['id_caixa_movimentacao']."' ");

} else if($conteudo['tipo'] == 'saida') {
    $titulo_conta = 'Conta a Pagar';
    $dados_conta = DBRead('', 'tb_conta_pagar', "WHERE id_caixa_movimentacao = '".$dados[0]['id_caixa_movimentacao']."' ");
}

if ($dados_conta[0]['id_conta_pai']) {
    $conta_pai = 'Possui';
} else {
    $conta_pai = 'Não Possui';
}

if ($dados_conta[0]['id_boleto']) {
    $id_boleto = $dados_conta[0]['id_boleto'];

    $btn_visualizar_boleto = ' <a href="/api/iframe?token=<?php echo $request->token ?>&view=boleto-visualizar&visualizar='.$id_boleto.'" target="_blank"><i class="fa fa-eye"></i></a>';
    $dados_boleto = DBRead('','tb_boleto', "WHERE id_boleto = '$id_boleto'");
    if($dados_boleto[0]['situacao'] != 'EMITIDO' && $dados_boleto[0]['situacao'] != 'REJEITADO'){
        $informacoes .= '<span><i class="fa fa-barcode" aria-hidden="true"></i> Boleto registrado</span>';
    }else if($dados_boleto[0]['situacao'] == 'REJEITADO'){
        $informacoes .= '<span class="text-danger faa-flash animated"><i class="fa fa-barcode" aria-hidden="true"></i> Boleto rejeitado</span>';
    }else{
        $informacoes .= '<span class="text-warning faa-flash animated"><i class="fa fa-barcode" aria-hidden="true"></i> Boleto não registrado</span>';
    }

} else {
    $id_boleto = 'Não possui';
}

if($dados_conta[0]['id_nfs']){
    $id_nfs = $dados_conta[0]['id_nfs'];

    $dados_nfs = DBRead('','tb_nfs',"WHERE id_nfs = '$id_nfs'");
    $btn_visualizar_nfs = ' <a href="/api/iframe?token=<?php echo $request->token ?>&view=nfs-visualizar&visualizar='.$id_nfs.'" target="_blank"><i class="fa fa-eye"></i></a>';
    if($dados_nfs[0]['status'] == 'autorizada'){                                       
        $informacoes .= '<br><span><i class="fas fa-file-invoice-dollar"></i> NFS-e emitida</span>';
    }else if($dados_nfs[0]['status'] == 'negada'){
        $informacoes .= '<br><span class="text-danger faa-flash animated"><i class="fas fa-file-invoice-dollar"></i> NFS-e negada</span>';
    }else{
        $informacoes .= '<br><span class="text-warning faa-flash animated"><i class="fas fa-file-invoice-dollar"></i> NFS-e pendente</span>';
    }

}else{
    $id_nfs = 'Não possui';
}

if($dados_conta[0]['id_faturamento']){
    $id_faturamento = $dados_conta[0]['id_faturamento'];
}else{
    $id_faturamento = 'Não possui';
}

echo '<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="panel-title text-center pull-center" style="margin-top: 2px; font-size: 150%;">'.$tipo_modal.'</h3>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h4><strong>Movimentação</strong></h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Valor:</label>
                                <input type="text" class="form-control input-sm" value="R$ '.converteMoeda($dados[0]['valor']).'" readonly/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Caixa:</label>
                                <input type="text" class="form-control input-sm" value="'.$dados_caixa[0]['nome'].'" readonly/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data da Movimentação:</label>
                                <input type="text" class="form-control input-sm" value="'.converteData($dados[0]['data_movimentacao']).'" readonly/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data e Hora do Cadastro:</label>
                                <input type="text" class="form-control input-sm" value="'.converteDataHora($dados[0]['data_cadastro']).'" readonly/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Usuário que Cadastrou:</label>
                                <input type="text" class="form-control input-sm" value="'.$dados_usuario[0]['nome'].'" readonly/>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h4><strong>Natureza Financeira</strong></h4>
                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Natureza:</label>
                                <input type="text" class="form-control input-sm" value="'.$dados[0]['nome_natureza'].'" readonly/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Agrupador:</label>
                                <input type="text" class="form-control input-sm" value="'.$dados[0]['nome_natureza_agrupador'].'" readonly/>
                            </div>
                        </div>
                        
                    </div>
                    <hr>
                    <h4><strong>Pessoa</strong></h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nome:</label>
                                <input type="text" class="form-control input-sm" value="'.$dados_pessoa[0]['nome'].'" readonly/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>CPF/CNPJ:</label>
                                <input type="text" class="form-control input-sm" value="'.$cpf_cnpj.'" readonly/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Razão Social:</label>
                                <input type="text" class="form-control input-sm" value="'.$dados_pessoa[0]['razao_social'].'" readonly/>
                            </div>
                        </div>
                    </div>';

                    if($dados[0]['origem'] == 'transferencia'){

                        $dados_conta_caixa = DBRead('', 'tb_caixa', "WHERE id_caixa = '".$dados[0]['id_caixa_transferencia']."' ");
                        
                        echo '
                        <hr>
                        <h4><strong>Tranferência</strong></h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Caixa de Movimentação:</label>
                                    <input type="text" class="form-control input-sm" value="'.$dados_conta_caixa[0]['nome'].'" readonly/>
                                </div>
                            </div>
                            
                        </div>				                      
                        ';
                    }else{

                        echo '
                        <hr>
                        <h4><strong>'.$titulo_conta.'</strong></h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Data de Emissão:</label>
                                    <input type="text" class="form-control input-sm" value="'.converteData($dados_conta[0]['data_emissao']).'" readonly/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Data de Vencimento:</label>
                                    <input type="text" class="form-control input-sm" value="'.converteData($dados_conta[0]['data_vencimento']).'" readonly/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Data do Pagamento:</label>
                                    <input type="text" class="form-control input-sm" value="'.converteData($dados_conta[0]['data_pagamento']).'" readonly/>
                                </div>
                            </div>
                        </div>				                      
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Situação:</label>
                                    <input type="text" class="form-control input-sm" value="'.ucfirst($dados_conta[0]['situacao']).'" readonly/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Parcela:</label>
                                    <input type="text" class="form-control input-sm" value="'.$dados_conta[0]['numero_parcela'].'" readonly/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Conta Pai:</label>
                                    <input type="text" class="form-control input-sm" value="'.$conta_pai.'" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descrição:</label>
                                    <textarea type="text" class="form-control input-sm" style="height: 100px;" readonly>'.$dados_conta[0]['descricao'].'</textarea>
                                </div>
                            </div>
                        </div>
                        ';

                        if($dados[0]['origem'] == 'conta_receber'){
                            
                            echo 
                            '					                      
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Boleto:'.$btn_visualizar_boleto.'</label>
                                        <input type="text" class="form-control input-sm" value="'.$id_boleto.'" readonly/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>NFS-e:'.$btn_visualizar_nfs.'</label>
                                        <input type="text" class="form-control input-sm" value="'.$id_nfs.'" readonly/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Faturamento:</label>
                                        <input type="text" class="form-control input-sm" value="'.$id_faturamento.'" readonly/>
                                    </div>
                                </div>
                            </div>';
                        }

                        echo '
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label id="label_editar">Observação: </label>
                                    <textarea type="text" class="form-control input-sm" style="height: 100px;" readonly>'.$dados_conta[0]['observacao'].'</textarea>
                                </div>
                            </div>
                        </div>
                        ';
                    }

                    echo 
                    '
                </div>
            </div>
        </div>   
      </div>';