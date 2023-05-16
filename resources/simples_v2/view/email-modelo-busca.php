<?php
require_once(__DIR__."/../class/System.php");

// $dados_conta_pagar = DBRead('', 'tb_conta_pagar', "where data_vencimento >= '2025-12-01' AND data_vencimento <= '2025-12-31' 
// AND situacao = 'aberta' AND id_natureza_financeira = 60 AND id_pessoa != 1498");


// foreach ($dados_conta_pagar as $conteudo_conta_pagar) {

//     $data_vencimento = new DateTime($conteudo_conta_pagar['data_vencimento']);
//     $data_vencimento->modify('+2 year');

//     while($data_vencimento->format('Y-m') != '2031-12'){
//         // echo $data_vencimento->format('Y-m-d')."<hr>";
//         $dados_novos = array(
//             'id_natureza_financeira' => $conteudo_conta_pagar['id_natureza_financeira'],
//             'id_pessoa' => $conteudo_conta_pagar['id_pessoa'],
//             'data_emissao' => getDataHora('data'),
//             'data_vencimento' => $data_vencimento->format('Y-m-d'),
//             'data_pagamento' => $conteudo_conta_pagar['data_pagamento'],
//             'valor' => $conteudo_conta_pagar['valor'],
//             'descricao' => $conteudo_conta_pagar['descricao'],
//             'situacao' => $conteudo_conta_pagar['situacao'],
//             'id_usuario' => $conteudo_conta_pagar['id_usuario'],
//             'numero_parcela' => $conteudo_conta_pagar['numero_parcela'],
//             'id_conta_pai' => $conteudo_conta_pagar['id_conta_pai'],
//             'id_caixa' => $conteudo_conta_pagar['id_caixa'],
//             'id_caixa_movimentacao' => $conteudo_conta_pagar['id_caixa_movimentacao'],
//             'data_cadastro' => getDataHora(),
//             'observacao' => $conteudo_conta_pagar['observacao'],
//         );
//         var_dump($dados_novos);
//         $data_vencimento->modify('+1 year');
//         DBCreate('', 'tb_conta_pagar', $dados_novos);
//     }
// }

//_____________________________________CORREA_____________________________________________

        // $dados_lead_negocio_perdido = DBRead('', 'tb_lead_negocio_perdido', "");
        //     $id_aux = '';
		// 	foreach($dados_lead_negocio_perdido as $conteudo_do_correa){
                
		// 		$dados_para_apagar = DBRead('', 'tb_lead_negocio_perdido', "WHERE id_lead_negocio = '".$conteudo_do_correa['id_lead_negocio']."' AND id_lead_negocio_perdido != '".$conteudo_do_correa['id_lead_negocio_perdido']."' ");
        //         if($id_aux != $conteudo_do_correa['id_lead_negocio']){
        //             if($dados_para_apagar){
        //                 foreach($dados_para_apagar as $conteudo_apagar){
        //                     if($conteudo_apagar['id_lead_negocio_perdido'] > $conteudo_do_correa['id_lead_negocio_perdido']){
        //                         DBDelete('', 'tb_lead_negocio_perdido_visualizado', "id_lead_negocio_perdido = '".$conteudo_apagar['id_lead_negocio_perdido']."'");

        //                         DBDelete('', 'tb_lead_negocio_perdido', "id_lead_negocio_perdido = '".$conteudo_apagar['id_lead_negocio_perdido']."'");

        //                         echo "Exclui  - - - - ".$conteudo_apagar['id_lead_negocio_perdido']."<br>";
        //                     }
        //                 }
        //                 echo "<hr>";
        //             }
        //         }
        //         $id_aux = $conteudo_do_correa['id_lead_negocio'];
		// 	}

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Modelos de E-mail:</h3>
                    <div class="panel-title text-right pull-right"><a href="token: '<?= $request->token ?>'&view=email-modelo-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Novo</button></a></div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label class="control-label sr-only">Hidden label</label>
                                <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe o título ou assunto..." autocomplete="off" autofocus>
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="resultado_busca"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function call_busca_ajax(pagina){
        var inicia_busca = 1;
        var nome = $('#nome').val();
        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>' , 'EmailModeloBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>