<?php
require_once(__DIR__."/System.php");

$id_conta_pagar = (isset($_POST['parametros'])) ? $_POST['parametros'] : '';

$dados = DBRead('', 'tb_conta_pagar a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_natureza_financeira c ON a.id_natureza_financeira = c.id_natureza_financeira INNER JOIN tb_natureza_financeira_agrupador d ON c.id_natureza_financeira_agrupador = d.id_natureza_financeira_agrupador WHERE id_conta_pagar = $id_conta_pagar", "a.*, b.nome, c.nome AS nome_natureza, d.nome AS nome_natureza_agrupador");

$dados_caixa = DBRead('', 'tb_caixa', "WHERE id_caixa = '".$dados[0]['id_caixa']."' LIMIT 1");
$dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$dados[0]['id_usuario']."' LIMIT 1");
$dados_pessoa = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$dados[0]['id_pessoa']."' LIMIT 1");

if($dados_pessoa[0]['cpf_cnpj']){
    $cpf_cnpj = formataCampo('cpf_cnpj', $dados_pessoa[0]['cpf_cnpj']);
}else{
    $cpf_cnpj = '';
}

$titulo_conta = 'Conta a Pagar';                                    

if($dados[0]['id_conta_pai']){
    $conta_pai = 'Possui';
}else{
    $conta_pai = '';
}

if($dados[0]['data_pagamento']){
    $data_pagamento = converteData($dados[0]['data_pagamento']);
}else{
    $data_pagamento = '';
}

if($dados_pessoa[0]['fone1']){
    $telefone = formataCampo('fone', $dados_pessoa[0]['fone1']);
}else{
    $telefone = '';
}


$dados_conta_pagar_centro_custos = DBRead('', 'tb_conta_pagar_centro_custos a', "INNER JOIN tb_centro_custos b ON a.id_centro_custos = b.id_centro_custos WHERE id_conta_pagar = '".$id_conta_pagar."' ");


echo '<div class="container-fluid">
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Data e Hora do Cadastro:</label>
                            <input type="text" class="form-control input-sm" value="'.converteDataHora($dados[0]['data_cadastro']).'" readonly/>
                        </div>
                    </div>
                    <div class="col-md-6">
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
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="text" class="form-control input-sm" value="'.$dados_pessoa[0]['email'].'" readonly/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Telefone:</label>
                            <input type="text" class="form-control input-sm" value="'.$telefone.'" readonly/>
                        </div>
                    </div>
                </div>
                
                <hr>
                <h4><strong>'.$titulo_conta.'</strong></h4>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Data de Emissão:</label>
                            <input type="text" class="form-control input-sm" value="'.converteData($dados[0]['data_emissao']).'" readonly/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Data de Vencimento:</label>
                            <input type="text" class="form-control input-sm" value="'.converteData($dados[0]['data_vencimento']).'" readonly/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Data do Pagamento:</label>
                            <input type="text" class="form-control input-sm" value="'.$data_pagamento.'" readonly/>
                        </div>
                    </div>
                </div>				                      
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Situação:</label>
                            <input type="text" class="form-control input-sm" value="'.ucfirst($dados[0]['situacao']).'" readonly/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Número Parcela:</label>
                            <input type="text" class="form-control input-sm" value="'.$dados[0]['numero_parcela'].'" readonly/>
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
                            <textarea type="text" class="form-control input-sm" style="height: 100px;" readonly>'.$dados[0]['descricao'].'</textarea>
                        </div>
                    </div>
                </div>
                ';

                echo '
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label id="label_editar">Observação: <button class="button_obs" id="editar_obs" href="" title="Editar Observação" style="color: #337ab7; text-decoration: none; background-color: transparent; cursor: pointer; border: none" value="0"><i class="fa fa-pen"></i> Editar</button></label>

                                <label id="label_salvar" style="display: none">Observação: <button class="button_obs" id="salvar_obs" href="" title="Salvar Observação" style="color: #5cb85c ; text-decoration: none; background-color: transparent; cursor: pointer; border: none" value="1"><i class="fa fa-check"></i> Salvar</button></label>


                                <textarea id="obs_textarea" type="text" class="form-control input-sm" style="height: 100px;" readonly>'.$dados[0]['observacao'].'</textarea>
                            </div>
                        </div>
                    </div>
                    ';
            

                    echo'
                    <hr>
                    <h4><strong>Rateio</strong></h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <strong>
                                        <div class="row">
                                            <div class="col-md-4">
                                                Centro de Custos
                                            </div>
                                            <div class="col-md-4">
                                                Porcentual
                                            </div>
                                            <div class="col-md-4">
                                                Valor
                                            </div>
                                        </div>
                                    </strong>
                                </li>
                                ';
                                if($dados_conta_pagar_centro_custos){
                                    foreach ($dados_conta_pagar_centro_custos as $conteudo_conta_pagar_centro_custos) {
                                        echo '
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    '.$conteudo_conta_pagar_centro_custos['nome'].'
                                                </div>
                                                <div class="col-md-4">
                                                    '.$conteudo_conta_pagar_centro_custos['porcentagem'].' %
                                                </div>
                                                <div class="col-md-4">
                                                    R$ '.converteMoeda($conteudo_conta_pagar_centro_custos['valor']).'
                                                </div>
                                            </div>
                                        </li>
                                        ';
                                    }
                                }else{
                                    echo '
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-4">-
                                            </div>
                                            <div class="col-md-4">-
                                            </div>
                                            <div class="col-md-4">-
                                            </div>
                                        </div>
                                    </li>
                                    ';
                                }
                                
                                echo 
                                '
                            </ul>
                        </div>
                    </div>';
                    
                    
                                                
                echo 
                '
            </div>
        </div>
      </div>';

?>
<script>
	$('.button_obs').click(function(){
		if($(this).val() == 0){
			$("#obs_textarea").attr("readonly", false); 
			$("#label_editar").hide();
			$("#label_salvar").show();
			
		}else{
			if (!confirm('Você tem certeza da alteração na observação?')) { 
				return false; 
			} else {
				var id_conta_pagar = '<?=$id_conta_pagar?>';
				var observacao = $("#obs_textarea").val();

				$.ajax({
                    url: "class/ControleContasObservacao.php",
                    dataType: "html",
                    method: 'POST',
                    data: {
                        parametros: { 
                            'id_conta_pagar': id_conta_pagar,
                            'observacao': observacao
                        }
                    },
                    success: function (data) {
                        $("#obs_textarea").attr("readonly", true); 
						$("#label_editar").show();
						$("#label_salvar").hide();
                    }
                });
				
			}

			
		}
	});

</script>