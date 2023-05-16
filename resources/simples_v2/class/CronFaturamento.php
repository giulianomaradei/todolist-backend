<?php
require_once(__DIR__."/System.php");

require('../inc/e-notas/eNotasGW.php');
use eNotasGW\Api\Exceptions as Exceptions;
eNotasGW::configure(array(
    'apiKey' => getDadosApiNfs('apiKey')
));

$data_inicial = new DateTime(getDataHora('data'));
$data_inicial->modify('first day of last month');
$data_final = new DateTime(getDataHora('data'));
$data_final->modify('last day of last month');

echo "Faturamento: <hr>";
    inserir_call_suporte($data_inicial->format('Y-m-d'), $data_final->format('Y-m-d'));
echo "<hr>";

//Espera 5 minutos
sleep(300);

echo "Conta a Receber: <hr>";

    $dados_faturamento_mes = DBRead('','tb_faturamento',"WHERE data_referencia = '".$data_inicial->format('Y-m-d')."'"); 
    
    foreach ($dados_faturamento_mes as $conteudo_faturamento_mes) {
        $id = $conteudo_faturamento_mes['id_faturamento'];      
        
        $dados_faturamento = DBRead('','tb_conta_receber', "WHERE id_faturamento = '$id' AND (situacao = 'aberta' OR situacao = 'quitada')");
        if(!$dados_faturamento){
            inserir_faturamento($id);
            //Espera 15 segundos pra não sobrecarrecar a prefeitura
            sleep(15);

        }
    }
echo "<hr>";

// //Espera 15 minutos para dar tempo dos boletos serem registrados
sleep(900);

echo "Sincronizar Boleto: <hr>";
    sincronizar_boleto();
echo "<hr>";

sleep(30);

echo "E-MAIL: <hr>";
    $dados_email_mes = DBRead('', 'tb_faturamento a', "INNER JOIN tb_conta_receber b ON a.id_faturamento = b.id_faturamento WHERE a.data_referencia = '".$data_inicial->format('Y-m-d')."' ");
    $descricao_email = 'Olá,
    <br>Me chamo <nome_usuario> e atuo no setor financeiro da Belluno.
    <br>Estou enviando abaixo os links para baixar a nota fiscal, XML e boleto referente aos serviços realizados no mês anterior.
    <br>Link para nota fiscal: <link_nota_fiscal>
    <br>Link para XML: <link_xml>
    <br>Link para boleto: <link_boleto>
    <br>Estes documentos e dos meses anteriores também podem ser acessados através de nosso Painel do Cliente (<a href="https://painel.bellunotec.com.br">Acesse aqui</a>)
    <br>Se você tiver alguma questão, não hesite me chamar pelo telefone ou WhatsApp listados abaixo ou simplesmente respondendo a este e-mail. Ficarei feliz em lhe atender.
    <br>Tenha um ótimo dia!
    <br>
    <br><nome_usuario>
    <br><cargo_usuario>
    <br>E-mail: financeiro@belluno.company (<a href="mailto:financeiro@belluno.company">Enviar e-mail</a>)
    <br>Telefone: (55) 2117-1111
    <br>WhatsApp: (55) 2117-1111 (<a dir="ltr" href="https://wa.me/+555521171111" rel="noopener nofollow noreferrer" target="_blank">Entrar em contato</a>)';
    $nome_funcionario = 'Patricia Marques';
    $cargo_funcionario = 'Controles Internos - Assistência';
    $descricao_email = str_replace("<nome_usuario>", $nome_funcionario, $descricao_email);
    $descricao_email = str_replace("<cargo_usuario>", $cargo_funcionario, $descricao_email);

    foreach ($dados_email_mes as $conteudo_email_mes) {
        enviar_email_conta_receber('Belluno - NFS-e e Boleto', $descricao_email, $conteudo_email_mes['id_conta_receber'], 1, 1, 1);
    }

    echo "<hr>";

function enviar_email_conta_receber($assunto, $descricao_email, $id_conta_receber, $envia_nfs, $envia_xml, $envia_boleto){
    $cont = 0;
    $conta_receber_erros = '';

    $id_usuario = $_SESSION['id_usuario'];
    $dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_perfil_sistema c ON a.id_perfil_sistema = c.id_perfil_sistema WHERE a.id_usuario = '".$id_usuario."' LIMIT 1", "b.nome, c.nome as cargo");

    $dados_conta_receber = DBRead('', 'tb_conta_receber', "WHERE id_conta_receber = '".$id_conta_receber."' ");

    if($dados_conta_receber[0]['id_boleto']){
        $dados_boleto = DBRead('', 'tb_boleto', "WHERE id_boleto = '".$dados_conta_receber[0]['id_boleto']."' AND (situacao = 'REGISTRADO' OR situacao = 'LIQUIDADO') ");
    }

    if($dados_conta_receber[0]['id_nfs']){
        $dados_nfs = DBRead('', 'tb_nfs', "WHERE id_nfs = '".$dados_conta_receber[0]['id_nfs']."' AND status = 'autorizada'");
    }

    $contato_email = '';
    $flag = 0;

    $mensagem = '';
    // <a href="https://www.bellunotec.com.br" target="_blank"><img src="https://rh.bellunotec.com.br/inc/keen/theme/classic/assets/media/logos/logobranco-1.png" style="padding: 10px 10px 10px 10px;"></a>

    

    if( (($envia_nfs && $dados_nfs) || !$envia_nfs) && (($envia_xml && $dados_nfs) || !$envia_xml) && (($envia_boleto && $dados_boleto) || !$envia_boleto) ){
        //Mensagem
        $flag_modelo_email = 0;

            $flag_modelo_email = 1;

            
            if( ($envia_nfs && $dados_nfs) || ($envia_xml && $dados_nfs) || ($envia_boleto && $dados_boleto) ){

                if($envia_nfs && $dados_nfs){
                    $descricao_email = str_replace("<link_nota_fiscal>",'<a href="'.$dados_nfs[0]['link_pdf'].'" style="margin-top: 10px ;font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; line-height: 16px; color: #ffffff; font-weight: 400; text-decoration: none; font-size: 13px; display: inline-block; padding: 6px 24px; background-color: #04B45F; border-radius: 5px; min-width: 90px" target="_blank" rel="noreferrer">Clique aqui para NFS-e</a>', $descricao_email);

                }
                if($envia_xml && $dados_nfs){
                    $descricao_email = str_replace("<link_xml>",'<a href="'.$dados_nfs[0]['link_xml'].'" style="margin-top: 10px; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; line-height: 16px; color: #ffffff; font-weight: 400; text-decoration: none; font-size: 13px; display: inline-block; padding: 6px 24px; background-color: #8A2908; border-radius: 5px; min-width: 90px" target="_blank" rel="noreferrer">Clique aqui para XML</a>', $descricao_email);
                }
                if($envia_boleto && $dados_boleto){
                    $descricao_email = str_replace("<link_boleto>",'<a href="https://plugboleto.com.br/api/v1/boletos/impressao/'.$dados_boleto[0]['id_integracao'].'" style="margin-top: 10px; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; line-height: 16px; color: #ffffff; font-weight: 400; text-decoration: none; font-size: 13px; display: inline-block; padding: 6px 24px; background-color: #610B38; border-radius: 5px; min-width: 90px" target="_blank" rel="noreferrer">Clique aqui para Boleto</a>', $descricao_email);
                }                      
            }
        
            $mensagem = 
            '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border: none; background-color: #0A122A; border-top-left-radius: 6px; border-top-right-radius: 6px;">
                <tr style="border: none;">
                    <td width="260" valign="top" style="border: none;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border: none;">
                            <tr>
                                <td>
                                    <a href="https://www.bellunotec.com.br" target="_blank"><img src="https://rh.belluno.company/inc/keen/theme/classic/assets/media/logos/logobranco-1.png" style="padding: 10px 10px 10px 10px;"></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <tr>
                <td width="8" style="width: 8px">
                </td>
                <td>
                    <div style="border-style: solid; border-width: thin; border-color: #dadce0; border-radius: 0px; padding: 40px 20px" align="center" class="mdv2rw">
                        <div style="font-family: Roboto-Regular,Helvetica,Arial,sans-serif; font-size: 14px; color: rgba(0,0,0,0.87); line-height: 20px; padding-top: 20px; text-align: left">
                            <div class="row">
                                <div class="col-md-12">
                                    <span style="padding-bottom: 24px; text-align: start">'.nl2br($descricao_email).'</span>
                                </div>
                            </div>
                            <br>
                            <table style="font-size: 14px; letter-spacing: 0.2; line-height: 20px; text-align: center">
                                <tbody>';
                                    if($flag_modelo_email == 0){
                                        if( (($envia_nfs && $dados_nfs) ) || (($envia_xml && $dados_nfs) ) || (($envia_boleto && $dados_boleto) ) ){
                                            $mensagem .= '<tr><td>';

                                            if($envia_nfs && $dados_nfs){
                                                $mensagem .='
                                                    <a href="'.$dados_nfs[0]['link_pdf'].'" style="margin-top: 10px ;font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; line-height: 16px; color: #ffffff; font-weight: 400; text-decoration: none; font-size: 13px; display: inline-block; padding: 6px 24px; background-color: #04B45F; border-radius: 5px; min-width: 90px" target="_blank" rel="noreferrer">Clique aqui para NFS-e
                                                    </a>
                                                ';
                                            }
                                            if($envia_xml && $dados_nfs){
                                                $mensagem .='
                                                    <a href="'.$dados_nfs[0]['link_xml'].'" style="margin-top: 10px; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; line-height: 16px; color: #ffffff; font-weight: 400; text-decoration: none; font-size: 13px; display: inline-block; padding: 6px 24px; background-color: #8A2908; border-radius: 5px; min-width: 90px" target="_blank" rel="noreferrer">Clique aqui para XML
                                                    </a>
                                                ';
                                            }
                                            if($envia_boleto && $dados_boleto){
                                                $mensagem .='
                                                    <a href="https://plugboleto.com.br/api/v1/boletos/impressao/'.$dados_boleto[0]['id_integracao'].'" style="margin-top: 10px; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; line-height: 16px; color: #ffffff; font-weight: 400; text-decoration: none; font-size: 13px; display: inline-block; padding: 6px 24px; background-color: #610B38; border-radius: 5px; min-width: 90px" target="_blank" rel="noreferrer">Clique aqui para Boleto
                                                    </a>
                                                ';
                                            }

                                            $mensagem .= '</td></tr><tr style="font-size: 12px; line-height: 150%; text-align: center">';
                                            $mensagem .= '<td style="padding-top: 12px;"><span style="color: rgba(0, 0, 0, 0.54);">Também pode acessar diretamente pelo link:</span><br>';
                                                    

                                            if($envia_nfs && $dados_nfs){
                                                $mensagem .='
                                                <strong>NFS-e: </strong><a href="'.$dados_nfs[0]['link_pdf'].'" style="color: rgba(0, 0, 0, 0.87); text-decoration: inherit;">'.$dados_nfs[0]['link_pdf'].'</a><br>';
                                            }
                                            if($envia_xml && $dados_nfs){
                                                $mensagem .='
                                                <strong>XML: </strong><a href="'.$dados_nfs[0]['link_xml'].'" style="color: rgba(0, 0, 0, 0.87); text-decoration: inherit;">'.$dados_nfs[0]['link_xml'].'</a><br>';
                                            }
                                            if($envia_boleto && $dados_boleto){
                                                $mensagem .='
                                                <strong>Boleto: </strong><a href="https://plugboleto.com.br/api/v1/boletos/impressao/'.$dados_boleto[0]['id_integracao'].'" style="color: rgba(0, 0, 0, 0.87); text-decoration: inherit;">https://plugboleto.com.br/api/v1/boletos/impressao/'.$dados_boleto[0]['id_integracao'].'"</a>';
                                            } 
                                            
                                            $mensagem .= '</td></tr>';
                                        }
                                    }
                                    

                                $mensagem .= 
                                '</tbody>
                            </table>
                        </div>
                    </div>
                    <div style="font-family: Roboto-Regular,Helvetica,Arial,sans-serif; color: rgba(0,0,0,0.54); font-size: 11px; line-height: 18px; padding-top: 12px; text-align: center">
                        <div style="direction: ltr"; font-family: Roboto-Regular,Helvetica,Arial,sans-serif; color: rgba(0,0,0,0.54); font-size: 11px; line-height: 18px; padding-top: 12px; text-align: center>© 2019 Belluno Tecnologia, Caçapava do Sul, Rio Grande do Sul, Brasil
                        </div>
                    </div>
                </td>
                <td width="8" style="width: 8px"></td>
            </tr>';

        //Mensagem

        if($dados_conta_receber[0]['id_contrato_plano_pessoa']){
            $dados_contrato_plano_pessoa = DBRead('', 'tb_contrato_plano_pessoa', "WHERE id_contrato_plano_pessoa = '".$dados_conta_receber[0]['id_contrato_plano_pessoa']."' ");
            $contato_email = $dados_contrato_plano_pessoa[0]['email_nf'];
            if($contato_email){
                $flag++;
            }
        }else{

            $dados_pessoa =DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$dados_conta_receber[0]['id_pessoa']."' ");

            if($dados_pessoa[0]['email1'] || $dados_pessoa[0]['email2']){
                $flag++;

                if($dados_pessoa[0]['email1']){
                    $contato_email = $dados_pessoa[0]['email1'];
                }
                if($dados_pessoa[0]['email2']){
                    if($dados_pessoa[0]['email1']){
                        $contato_email .= ';'.$dados_pessoa[0]['email2'];
                    }else{
                        $contato_email = $dados_pessoa[0]['email2'];
                    }
                }
            }
        }
    }
    $dados_nome_empresa = DBRead('', 'tb_conta_receber a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE id_conta_receber = '".$id_conta_receber."' ", "c.nome");
    // $dados_nome_empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_contrato_plano_pessoa = '46' ", "b.nome");

    if($contato_email && $contato_email != '' && $mensagem !== ''){
        envia_email($assunto, $mensagem, $contato_email.";matheus.desouza@belluno.company;patricia.marques@belluno.company", 'financeiro');
        $dados = array(
            'envio_email' => 1
        );
        DBUpdate('', 'tb_conta_receber', $dados, "id_conta_receber = $id_conta_receber");
        registraLog('Email enviado conta receber.','a','tb_conta_receber',$id_conta_receber,"envio_email: 1");
        echo $dados_nome_empresa[0]['nome']." OK";
    }
    if($flag == 0){


        echo $dados_nome_empresa[0]['nome'].": NÃO";

    }


 
}

function sincronizar_boleto(){
    	
	$dados_boleto = DBRead('', 'tb_boleto', "WHERE remessa_pendente = 0 AND (situacao = 'EMITIDO' OR situacao = 'REGISTRADO' OR situacao = 'BAIXA PENDENTE' OR situacao = 'ALTERACAO VENCIMENTO PENDENTE' OR situacao = 'ALTERACAO VALOR PENDENTE' OR situacao = 'PENDENTE_RETENTATIVA')");

    $cont = 0;
    if($dados_boleto){
    	foreach ($dados_boleto as $conteudo_boleto) {
            $link = DBConnect('');
            DBBegin($link);
	    	$id_boleto = $conteudo_boleto['id_boleto'];
	    	$id_integracao = $conteudo_boleto['id_integracao'];

	    	$resultado = troca_dados_curl(getDadosApiBoletos('link').'/api/v1/boletos?idIntegracao='.$id_integracao, '', array('Content-Type:application/json','cnpj-sh:'.getDadosApiBoletos('cnpj-sh'), 'token-sh:'.getDadosApiBoletos('token-sh'),'cnpj-cedente:'.getDadosApiBoletos('cnpj-cedente')), 'GET');

			if($resultado['_dados'][0]['situacao'] && $resultado['_dados'][0]['situacao'] != 'SALVO' && $resultado['_dados'][0]['situacao'] != ''){
				if($resultado['_dados'][0]['situacao'] != $conteudo_boleto['situacao']){                   
                    $situacao = $resultado['_dados'][0]['situacao'];
                    $motivo_situacao = $resultado['_dados'][0]['motivo'];
                    $dados_situacao = array(
                        'situacao' => $situacao,
                        'motivo_situacao' => $motivo_situacao,
                        'data_sincronizacao' => getDataHora()
                    );

                    DBUpdateTransaction($link, 'tb_boleto', $dados_situacao, "id_boleto = $id_boleto");
                   	registraLogTransaction($link, 'Alteração situacao boleto.','a','tb_boleto',$id_boleto,"situacao: $situacao");

                    $cont++;
                    echo $conteudo_boleto['sacado_nome']." - - - - Situação: ".$conteudo_boleto['situacao']."<br>";

				}
            }
            DBCommit($link);
	    }
        
    }else{
    	$alert = ('Não existem boletos a serem sincronizados!','w');
    }    
	header("location: /api/iframe?token=$request->token&view=boleto-busca");
}

function inserir_faturamento($id_faturamento){
    $descricao = "FATURA EM ABERTO, AGUARDAMOS SEU PAGAMENTO VIA BOLETO BANCÁRIO. \r\n";

    $id_usuario = $_SESSION['id_usuario'];
    
    $dados_consulta = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e  ON a.id_plano = e.id_plano WHERE b.contrato_pai = '1' AND a.id_faturamento = '".$id_faturamento."'", "a.*, b.*, c.nome_contrato, d.nome, a.status AS status_contrato, a.id_usuario AS id_usuario_faturamento, e.cod_servico, e.nome AS 'nome_plano'");

    $id_contrato_plano_pessoa = $dados_consulta[0]['id_contrato_plano_pessoa'];

    $dados_empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_cidade c ON b.id_cidade = c.id_cidade INNER JOIN tb_estado d ON c.id_estado = d.id_estado INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ", "a.*, b.*, c.id_cidade, c.nome AS 'nome_cidade', d.sigla AS 'uf', e.cod_servico");
    
    $id_pessoa = $dados_empresa[0]['id_pessoa'];

    $valor_bruto = $dados_consulta[0]['valor_cobranca'];

    $reter_cofins = $dados_empresa[0]['reter_cofins'];
    $reter_csll = $dados_empresa[0]['reter_csll'];
    $reter_ir = $dados_empresa[0]['reter_ir'];
    $reter_pis = $dados_empresa[0]['reter_pis'];

    if($reter_cofins == 1){   
        $valor_cofins = sprintf("%01.2f", round(($valor_bruto*3)/100, 2));
    }else{
        $valor_cofins = 0;
    }

    if($reter_csll == 1){
        $valor_csll = sprintf("%01.2f", round(($valor_bruto*1)/100, 2));
    }else{
        $valor_csll = 0;
    }

    if($reter_ir == 1){
        $valor_ir = sprintf("%01.2f", round(($valor_bruto*1.5)/100, 2));
    }else{
        $valor_ir = 0;
    }

    if($reter_pis == 1){
        $valor_pis = sprintf("%01.2f", round(($valor_bruto*0.65)/100, 2));
    }else{
        $valor_pis = 0;
    }

    if(($valor_cofins + $valor_csll + $valor_pis) < 10){
        $valor_cofins = 0;
        $valor_csll = 0;
        $valor_pis = 0;
    }

    if($valor_ir < 10){
        $valor_ir = 0;
    }
    $valor_liquido = $valor_bruto - $valor_cofins - $valor_csll - $valor_ir - $valor_pis;

    //INICIO CONTA RECEBER


        $dados_faturamento_configuracao = DBRead('', 'tb_faturamento_configuracao a', "INNER JOIN tb_servico b ON a.id_servico = b.id_servico WHERE a.cod_servico = '".$dados_empresa[0]['cod_servico']."' AND adesao = 0");
        $id_natureza_financeira = $dados_faturamento_configuracao[0]['id_natureza_financeira'];        
        $id_caixa = $dados_faturamento_configuracao[0]['id_caixa'];
        
        $id_faturamento = $dados_consulta[0]['id_faturamento'];

        $data_emissao = getDataHora('data');
        $data = explode("-",$data_emissao);
        $dia_pagamento = sprintf('%02d', $dados_empresa[0]['dia_pagamento']);
        $data_vencimento = $data[0].'-'.$data[1].'-'.$dia_pagamento;
        $valor = $valor_liquido;

            $dia_pagamento = sprintf('%02d', $dados_empresa[0]['dia_pagamento']);
            $data_vencimento = $data[0].'-'.$data[1].'-'.$dia_pagamento;
            $valor = $valor_liquido;
        
            if($dados_consulta[0]['cod_servico'] == 'call_suporte'){
                if($dados_consulta[0]['contrato_filho_separar'] == 1){
                    
                    $descricao .= 'Call Center - Plano: '.$dados_consulta[0]['nome_plano'].'. Valor do contrato: R$ '.converteMoeda($valor_bruto, 'moeda');

                }else{
                    $dados_porporcional = DBRead('', 'tb_faturamento_proporcional',"WHERE id_faturamento = '".$id_faturamento."' LIMIT 1");
                    if($dados_porporcional){

                        $dados_meses = array(
                            "01" => "Janeiro",
                            "02" => "Fevereiro",
                            "03" => "Março",
                            "04" => "Abril",
                            "05" => "Maio",
                            "06" => "Junho",
                            "07" => "Julho",
                            "08" => "Agosto",
                            "09" => "Setembro",
                            "10" => "Outubro",
                            "11" => "Novembro",
                            "12" => "Dezembro",
                        );
                        $data_referencia_porporcional = new DateTime($dados_consulta[0]['data_referencia']);
                        $mes_referencia_porporcional = $data_referencia_porporcional->format('m');
                        $ano_referencia_porporcional = $data_referencia_porporcional->format('Y');

                        if($dados_consulta[0]['qtd_excedente'] > 0){
                            $descricao .= "Proporcional a ".$dados_porporcional[0]['qtd_dias']." dias em ".$dados_meses[$mes_referencia_porporcional]." de ".$ano_referencia_porporcional.".\r\nQuantidade contratada proporcional: ".$dados_consulta[0]['qtd_contratada'].". Quantidade efetuada: ".$dados_consulta[0]['qtd_efetuada'].". Quantidade de excedentes: ".$dados_consulta[0]['qtd_excedente'].". Valor unitário do excedente: R$ ".converteMoeda($dados_consulta[0]['valor_excedente_contrato'], 'moeda').". ";
                        }else{
                            $descricao .= "Proporcional a ".$dados_porporcional[0]['qtd_dias']." dias em ".$dados_meses[$mes_referencia_porporcional]." de ".$ano_referencia_porporcional.".\r\nQuantidade contratada proporcional: ".$dados_consulta[0]['qtd_contratada'].".";

                        }


                        // $descricao.= "\r\nAntecipação de ".$dados_meses[$mes_referencia_antecipacao]." de ".$ano_referencia_antecipacao." referente a ".$dados_antecipacao[0]['qtd_dias']." dias. Valor: R$ ".converteMoeda($dados_antecipacao[0]['valor'], 'moeda');
                        // $valor = $valor_liquido+$dados_antecipacao[0]['valor'];

                    }else{
                        if($dados_consulta[0]['valor_diferente_texto'] == 1){
                            $descricao .= 'Call Center - Plano: '.$dados_consulta[0]['nome_plano'].' - '.$dados_consulta[0]['qtd_contratada'].' atendimentos via Telefone - '.$dados_consulta[0]['qtd_contratada_texto'].' atendimentos via Texto. Valor do contrato: R$ '.converteMoeda($dados_consulta[0]['valor_total_contrato'], 'moeda');
                        }else{
                            if($dados_consulta[0]['tipo_cobranca'] == 'x_cliente_base'){
    
                                $total_soma_excedente = $dados_consulta[0]['valor_excedente_contrato'] * $dados_consulta[0]['qtd_excedente'];
                                if($dados_consulta[0]['qtd_excedente'] > 0){
                                    $descricao .= "Call Center - Plano: ".$dados_consulta[0]['nome_plano']." - ".$dados_consulta[0]['qtd_contratada']." clientes na base. Valor do contrato: R$ ".converteMoeda($dados_consulta[0]['valor_total_contrato'], 'moeda').".\r\nQuantidade de excedentes: ".$dados_consulta[0]['qtd_excedente'].". Valor unitário por excedente R$ ".converteMoeda($dados_consulta[0]['valor_excedente_contrato'], 'moeda').". Valor Total de excedentes R$ ".converteMoeda($total_soma_excedente, 'moeda');    
                                }else{
                                    $descricao .= 'Call Center - Plano: '.$dados_consulta[0]['nome_plano'].' - '.$dados_consulta[0]['qtd_contratada'].' clientes na base. Valor do contrato: R$ '.converteMoeda($dados_consulta[0]['valor_total_contrato'], 'moeda');    
                                }
    
                            }else{
                                $descricao .= 'Call Center - Plano: '.$dados_consulta[0]['nome_plano'].' - '.$dados_consulta[0]['qtd_contratada'].' atendimentos. Valor do contrato: R$ '.converteMoeda($dados_consulta[0]['valor_total_contrato'], 'moeda');
    
                                $total_de_atendimentos = $dados_consulta[0]['qtd_efetuada'];
                                $descricao .= "\r\nQuantidade efetuada: ".$total_de_atendimentos." atendimentos.";
                            }
                            
                        }
                    }
                }
                

                // $total_de_atendimentos = $dados_consulta[0]['qtd_efetuada'] + $dados_consulta[0]['qtd_efetuada_texto'];
                // $descricao .= "\r\nQuantidade efetuada: ".$total_de_atendimentos." atendimentos.";
                
                $dados_antecipacao = DBRead('', 'tb_faturamento_antecipacao',"WHERE id_faturamento = '".$id_faturamento."' LIMIT 1");
                if($dados_antecipacao){

                    $dados_meses = array(
                        "01" => "Janeiro",
                        "02" => "Fevereiro",
                        "03" => "Março",
                        "04" => "Abril",
                        "05" => "Maio",
                        "06" => "Junho",
                        "07" => "Julho",
                        "08" => "Agosto",
                        "09" => "Setembro",
                        "10" => "Outubro",
                        "11" => "Novembro",
                        "12" => "Dezembro",
                    );
                    $data_referencia_antecipacao = new DateTime($dados_antecipacao[0]['data_referencia']);
                    
                    $mes_referencia_antecipacao = $data_referencia_antecipacao->format('m');
                    $ano_referencia_antecipacao = $data_referencia_antecipacao->format('Y');

                    $descricao .= "\r\nAntecipação de ".$dados_meses[$mes_referencia_antecipacao]." de ".$ano_referencia_antecipacao." referente a ".$dados_antecipacao[0]['qtd_dias']." dias. Valor: R$ ".converteMoeda($dados_antecipacao[0]['valor'], 'moeda');
                    $valor = $valor_liquido+$dados_antecipacao[0]['valor'];
                }

                $descricao .= "\r\nValor total da fatura: R$ ".converteMoeda($valor_liquido+$dados_antecipacao[0]['valor'], 'moeda');
                
                $ancora_servico = 'call_suporte';

            }

        $situacao_conta_receber = 'aberta';
        $numero_parcela = 1;
        $data_cadastro = getDataHora();
        $envio_email = 0;

        $dados = array(
            'id_natureza_financeira' => $id_natureza_financeira,
            'valor' => $valor,
            'valor_bruto' => $valor_bruto,
            'data_emissao' =>  $data_emissao,
            'data_vencimento' =>  $data_vencimento,
            'situacao' =>  $situacao_conta_receber,
            'numero_parcela' =>  $numero_parcela,
            'id_faturamento' =>  $id_faturamento,
            'descricao' =>  $descricao,
            'id_contrato_plano_pessoa' =>  $id_contrato_plano_pessoa,
            'id_usuario' =>  $id_usuario,
            'id_caixa' =>  $id_caixa,
            'data_cadastro' =>  $data_cadastro,
            'id_pessoa' =>  $id_pessoa,
            'envio_email' =>  $envio_email
        );

        // 1º var_dump
        // echo "<pre>";
        // 	var_dump($dados);
        // echo "</pre>";


        $id_conta_receber = DBCreate('', 'tb_conta_receber', $dados, true);
        registraLog('Inserção de nova conta a receber.','i','tb_conta_receber',$id_conta_receber,"id_natureza_financeira: $id_natureza_financeira | valor: $valor | data_emissao: $data_emissao | data_vencimento: $data_vencimento | situacao: $situacao_conta_receber | numero_parcela: $numero_parcela | id_faturamento: $id_faturamento | descricao: $descricao | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_usuario: $id_usuario | id_caixa: $id_caixa | data_cadastro: $data_cadastro | id_pessoa: $id_pessoa | envio_email: $envio_email");

        //AVISO CONTA RECEBER
        echo "Conta Receber: ".$dados_empresa[0]['nome'];

    //FIM CONTA RECEBER

        //AQUI
        // $valor_liquido = $valor;

    //INICIO GERAR BOLETO
        
        //CONFIGURÇÃO DO BOLETO
            $dados_boleto_configuracao = DBRead('', 'tb_boleto_configuracao', "LIMIT 1");
            
            $cedente_conta_numero = $dados_boleto_configuracao[0]['conta_numero'];
            $cedente_conta_numero_dv = $dados_boleto_configuracao[0]['conta_numero_dv'];
            $cedente_convenio_numero = $dados_boleto_configuracao[0]['convenio_numero'];
            $cedente_conta_codigo_banco = $dados_boleto_configuracao[0]['conta_codigo_banco'];

            $titulo_nosso_numero = $dados_boleto_configuracao[0]['nosso_numero'];
            $titulo_numero_documento = "V201".$dados_boleto_configuracao[0]['numero_documento'];

            $titulo_mensagem_01 = $dados_boleto_configuracao[0]['mensagem_1'];
            $titulo_mensagem_02 = $dados_boleto_configuracao[0]['mensagem_2'];

            $titulo_local_pagamento = $dados_boleto_configuracao[0]['local_pagamento'];
            $titulo_aceite = $dados_boleto_configuracao[0]['aceite'];
            $titulo_doc_especie = $dados_boleto_configuracao[0]['especie_documento'];

        //PRÓXIMOS 'NOSSO NUMERO' E 'NUMERO DO DOCUMENTO'
            $proximo_titulo_nosso_numero = (int)$dados_boleto_configuracao[0]['nosso_numero']+1;
            $proximo_titulo_numero_documento = (int)$dados_boleto_configuracao[0]['numero_documento']+1;

        //PESSOA
            $tipo_pessoa = strtoupper(substr($dados_empresa[0]['tipo'], 1));

            $sacado_cpf_cnpj = $dados_empresa[0]['cpf_cnpj'];
            $sacado_endereco_numero = $dados_empresa[0]['numero'];
            $sacado_endereco_bairro = $dados_empresa[0]['bairro'];
            $sacado_endereco_cep = $dados_empresa[0]['cep'];
            $sacado_endereco_cidade = $dados_empresa[0]['nome_cidade'];
            $sacado_endereco_complemento = $dados_empresa[0]['complemento'];
            $sacado_endereco_logradouro = $dados_empresa[0]['logradouro'];
            $sacado_endereco_pais = 'Brasil';
            $sacado_endereco_uf = $dados_empresa[0]['uf'];
            $sacado_nome = $dados_empresa[0]['razao_social'];      

        //DATAS
            if($dados_consulta[0]['adesao'] == 1){
                $titulo_data_emissao = getDataHora('data');
                $titulo_data_vencimento = $dados_consulta[0]['dia_pagamento_adesao'];
                $titulo_data_multa_juros = date('Y-m-d', strtotime("+1 days",strtotime($titulo_data_vencimento)));                        
            }else{
                $titulo_data_emissao = getDataHora('data');
                $data = explode("-",$titulo_data_emissao);
                $dia_pagamento = sprintf('%02d', $dados_empresa[0]['dia_pagamento']);
                $titulo_data_vencimento = $data[0].'-'.$data[1].'-'.$dia_pagamento;
                $titulo_data_multa_juros = date('Y-m-d', strtotime("+1 days",strtotime($titulo_data_vencimento)));
            }
            
            //VERIFICA SE DATA DO VENCIMENTO CAI EM FINAL DE SEMANA OU FERIADO E MUDA A DATA DA MULTA JUROS
            $numero_dia_vencimento = date('w', strtotime($titulo_data_vencimento));
            $dados_feriado = DBRead('','tb_feriado',"WHERE tipo = 'Nacional' AND data = '".substr($titulo_data_vencimento, 5, 5)."'");
            if($dados_feriado && $numero_dia_vencimento == 5){
                $titulo_data_multa_juros = date('Y-m-d', strtotime("+3 days",strtotime($titulo_data_multa_juros)));
            }else if($numero_dia_vencimento == 6){
                $titulo_data_multa_juros = date('Y-m-d', strtotime("+2 days",strtotime($titulo_data_multa_juros)));
            }else if($numero_dia_vencimento == 0 || ($dados_feriado && $numero_dia_vencimento != 6)){
                $titulo_data_multa_juros = date('Y-m-d', strtotime("+1 days",strtotime($titulo_data_multa_juros)));
            }

        //NOVOS
            $situacao = "EMITIDO";
            $dados_antecipacao = DBRead('', 'tb_faturamento_antecipacao',"WHERE id_faturamento = '".$id_faturamento."' LIMIT 1");
            if($dados_antecipacao){
                $titulo_valor = $valor_liquido+$dados_antecipacao[0]['valor'];
            }else{
                $titulo_valor = $valor_liquido;
            }
            //$titulo_valor_multa_taxa = sprintf("%01.2f", round($titulo_valor*0.02, 2));
            $titulo_valor_multa_taxa = '2.00';

        if(($sacado_nome && $sacado_endereco_uf && $sacado_endereco_cidade && $sacado_endereco_cidade != 'Não Definida' && $sacado_endereco_logradouro && $sacado_endereco_numero && $sacado_endereco_bairro && $sacado_endereco_cep && $titulo_valor > 0) && (($tipo_pessoa == 'J' && valida_cnpj($sacado_cpf_cnpj)) || ($tipo_pessoa == 'F' && valida_cpf($sacado_cpf_cnpj)))){

            $dados = array(
                'id_pessoa' => $id_pessoa,
                'cedente_conta_numero' => $cedente_conta_numero,
                'cedente_conta_numero_dv' => $cedente_conta_numero_dv,
                'cedente_convenio_numero' => $cedente_convenio_numero,
                'cedente_conta_codigo_banco' => $cedente_conta_codigo_banco,
                    'sacado_cpf_cnpj' => $sacado_cpf_cnpj,
                    'sacado_endereco_numero' => $sacado_endereco_numero,
                    'sacado_endereco_bairro' => $sacado_endereco_bairro,
                    'sacado_endereco_cep' => $sacado_endereco_cep,
                    'sacado_endereco_cidade' => $sacado_endereco_cidade,
                    'sacado_endereco_complemento' => $sacado_endereco_complemento,
                    'sacado_endereco_logradouro' => $sacado_endereco_logradouro,
                    'sacado_endereco_pais' => $sacado_endereco_pais,
                    'sacado_endereco_uf' => $sacado_endereco_uf,
                    'sacado_nome' => $sacado_nome,
                'titulo_data_emissao' => $titulo_data_emissao,
                'titulo_data_vencimento' => $titulo_data_vencimento,
                'titulo_mensagem_01' => $titulo_mensagem_01,
                'titulo_mensagem_02' => $titulo_mensagem_02,
                'titulo_nosso_numero' => $titulo_nosso_numero,
                'titulo_numero_documento' => $titulo_numero_documento,
                'titulo_valor' => $titulo_valor,
                'titulo_local_pagamento' => $titulo_local_pagamento,
                'titulo_aceite' => $titulo_aceite,
                'titulo_doc_especie' => $titulo_doc_especie,
                'situacao' => $situacao,
                'id_usuario' => $id_usuario,
                'remessa_pendente' => '0'

            );

            //2º var_dump
            // echo "<pre>";
            // 	var_dump($dados);
            // echo "</pre>";

                $id_boleto = DBCreate('', 'tb_boleto', $dados, true);
                registraLog('Inserção de boleto.','i','tb_boleto',$id_boleto,"id_pessoa: $id_pessoa | cedente_conta_numero: $cedente_conta_numero | cedente_conta_numero_dv: $cedente_conta_numero_dv | cedente_convenio_numero: $cedente_convenio_numero | cedente_conta_codigo_banco: $cedente_conta_codigo_banco | sacado_cpf_cnpj: $sacado_cpf_cnpj | sacado_endereco_numero: $sacado_endereco_numero | sacado_endereco_bairro: $sacado_endereco_bairro | sacado_endereco_cep: $sacado_endereco_cep | sacado_endereco_cidade: $sacado_endereco_cidade | sacado_endereco_complemento: $sacado_endereco_complemento | sacado_endereco_logradouro: $sacado_endereco_logradouro | sacado_endereco_pais: $sacado_endereco_pais | sacado_endereco_uf: $sacado_endereco_uf | sacado_nome: $sacado_nome | titulo_data_emissao: $titulo_data_emissao | titulo_data_vencimento: $titulo_data_vencimento | titulo_mensagem_01: $titulo_mensagem_01 | titulo_mensagem_02: $titulo_mensagem_02 | titulo_nosso_numero: $titulo_nosso_numero | titulo_valor: $titulo_valor | titulo_local_pagamento: $titulo_local_pagamento | titulo_aceite: $titulo_aceite | titulo_doc_especie: $titulo_doc_especie | situacao: $situacao | id_usuario: $id_usuario");
            
                //AVISO BOLETO
                echo " - - - Boleto: OK";
            //incluir boleto
            $parametros = '
            [
                {
                    "CedenteContaNumero": "'.$cedente_conta_numero.'",
                    "CedenteContaNumeroDV": "'.$cedente_conta_numero_dv.'",
                    "CedenteConvenioNumero": "'.$cedente_convenio_numero.'",
                    "CedenteContaCodigoBanco": "'.$cedente_conta_codigo_banco.'",
                    "SacadoCPFCNPJ": "'.$sacado_cpf_cnpj.'",
                    "SacadoEnderecoNumero": "'.$sacado_endereco_numero.'",
                    "SacadoEnderecoBairro": "'.$sacado_endereco_bairro.'",
                    "SacadoEnderecoCEP": "'.$sacado_endereco_cep.'",
                    "SacadoEnderecoCidade": "'.$sacado_endereco_cidade.'",
                    "SacadoEnderecoComplemento": "'.$sacado_endereco_complemento.'",
                    "SacadoEnderecoLogradouro": "'.$sacado_endereco_logradouro.'",
                    "SacadoEnderecoPais": "'.$sacado_endereco_pais.'",
                    "SacadoEnderecoUF": "'.$sacado_endereco_uf.'",
                    "SacadoNome": "'.$sacado_nome.'",
                    "SacadoTelefone": "5532819200",
                    "TituloDataEmissao": "'.converteData($titulo_data_emissao).'",
                    "TituloDataVencimento": "'.converteData($titulo_data_vencimento).'",
                    "TituloMensagem01": "'.$titulo_mensagem_01.'",
                    "TituloMensagem02": "'.$titulo_mensagem_02.'",
                    "TituloNossoNumero": "'.$titulo_nosso_numero.'",
                    "TituloNumeroDocumento": "'.$titulo_numero_documento.'",
                    "TituloValor": "'.str_replace('.', ',', sprintf("%01.2f", $titulo_valor)).'",
                    "TituloLocalPagamento": "'.$titulo_local_pagamento.'",
                    "TituloAceite": "'.$titulo_aceite.'",
                    "TituloDocEspecie": "'.$titulo_doc_especie.'",
                    "TituloCodigoMulta": "1",
                    "TituloValorMultaTaxa": "'.str_replace('.', ',', $titulo_valor_multa_taxa).'",                  
                    "TituloDataMulta": "'.converteData($titulo_data_multa_juros).'",
                    "TituloCodigoJuros": "2",
                    "TituloValorJuros": "0,03",                
                    "TituloDataJuros": "'.converteData($titulo_data_multa_juros).'"
                }
            ]
            ';
            
            //3º var_dump
            // echo "<pre>";
            // 	var_dump($parametros);
            // echo "</pre>";

            $dados_json = array(
                'json' => $parametros
            );
            DBUpdate('', 'tb_boleto', $dados_json, "id_boleto = $id_boleto");
            
            $resultado = troca_dados_curl(getDadosApiBoletos('link').'/api/v1/boletos/lote', $parametros, array('Content-Type:application/json','cnpj-sh:'.getDadosApiBoletos('cnpj-sh'), 'token-sh:'.getDadosApiBoletos('token-sh'),'cnpj-cedente:'.getDadosApiBoletos('cnpj-cedente')));

            //TESTE BOLETO
                if(!$resultado['_dados']['_sucesso']){
                    $dados_situacao = array(
                        'situacao' => 'FALHA'
                    );

                    DBUpdate('', 'tb_boleto', $dados_situacao, "id_boleto = $id_boleto");
                    registraLog('Alteração situacao boleto.','a','tb_boleto',$id_boleto,"situacao: FALHA");

                    $falhas = '';
                    if($resultado['_dados']['_falha']){
                        foreach ($resultado['_dados']['_falha'] as $conteudo_resultado){	
                            $falhas.= '<br>Erro(s):';
                            if($conteudo_resultado['_erro']){
                                foreach ($conteudo_resultado['_erro']['erros'] as $conteudo) {
                                    $falhas.= ' '.$conteudo;
                                }
                            }
                        }
                    }

                    

                }else{
                    $id_integracao = $resultado['_dados']['_sucesso'][0]['idintegracao'];
                    $dados_id_integrcao = array(
                        'id_integracao' => $id_integracao
                    );
                    DBUpdate('', 'tb_boleto', $dados_id_integrcao, "id_boleto = $id_boleto");
                    registraLog('Inserçao id_integracao boleto.','a','tb_boleto',$id_boleto,"id_integracao: $id_integracao");

                    $dados_configuracao_boleto = array(
                        'nosso_numero' => $proximo_titulo_nosso_numero,
                        'numero_documento' => $proximo_titulo_numero_documento
                    );
                    DBUpdate('', 'tb_boleto_configuracao', $dados_configuracao_boleto, "");

                    $dados = array(
                        'id_boleto' => $id_boleto    
                    );
                    DBUpdate('', 'tb_conta_receber', $dados, "id_conta_receber = $id_conta_receber");
                }
            
        //fim incluir boleto
        
        }else{
            $descricao_erro = '';
            if(!$sacado_nome){
                $descricao_erro .= 'Nome inválido';
            }
            if(!$sacado_cpf_cnpj){
                if($descricao_erro == ''){
                    $descricao_erro .= 'CNPJ/CPF inválido';
                }else{
                    $descricao_erro .= ', CNPJ/CPF inválido';
                }
            }
            if(!$sacado_endereco_uf){
                if($descricao_erro == ''){
                    $descricao_erro .= 'UF inválida';
                }else{
                    $descricao_erro .= ', UF inválida';
                }
            }
            if(!$sacado_endereco_cidade || $sacado_endereco_cidade == 'Não Definida'){
                if($descricao_erro == ''){
                    $descricao_erro .= 'Cidade inválida';
                }else{
                    $descricao_erro .= ', cidade inválida';
                }
            }
            if(!$sacado_endereco_logradouro){
                if($descricao_erro == ''){
                    $descricao_erro .= 'Logradouro inválido';
                }else{
                    $descricao_erro .= ', logradouro inválido';
                }
            }
            if(!$sacado_endereco_numero){
                if($descricao_erro == ''){
                    $descricao_erro .= 'Número do logradouro inválido';
                }else{
                    $descricao_erro .= ', número do logradouro inválido';
                }
            }
            if(!$sacado_endereco_bairro){
                if($descricao_erro == ''){
                    $descricao_erro .= 'Bairro do logradouro inválido';
                }else{
                    $descricao_erro .= ', bairro do logradouro inválido';
                }
            }
            if(!$sacado_endereco_cep){
                if($descricao_erro == ''){
                    $descricao_erro .= 'CEP do logradouro inválido';
                }else{
                    $descricao_erro .= ', CEP do logradouro inválido';
                }
            }
            if(!$titulo_valor || $titulo_valor < 0){
                if($descricao_erro == ''){
                    $descricao_erro .= 'Valor do boleto inválido';
                }else{
                    $descricao_erro .= ', valor do boleto inválido';
                }
            }
            $descricao_erro .= '.';
            //AVISO ERRO BOLETO
            echo $descricao_erro;
        }


    //FIM GERAR BOLETO

    //INICIO NFS-e
        
        $nome_empresa = $dados_empresa[0]['nome'];        
        $cliente_id_cidade = $dados_empresa[0]['id_cidade'];
        $cliente_logradouro = $dados_empresa[0]['logradouro'];
        $cliente_numero = $dados_empresa[0]['numero'];
        $cliente_complemento = $dados_empresa[0]['complemento'];
        if(!$cliente_complemento){
            $cliente_complemento = '-';
        }
        $cliente_bairro = $dados_empresa[0]['bairro'];
        $cliente_cep = $dados_empresa[0]['cep'];
        $cliente_uf = $dados_empresa[0]['uf'];
        $id_contrato_plano_pessoa = $dados_empresa[0]['id_contrato_plano_pessoa'];
        
        $cliente_razao_social = $dados_empresa[0]['razao_social'];
        $cliente_cpf_cnpj = $dados_empresa[0]['cpf_cnpj'];

        $data_criacao = getDataHora();
        
        $tipo_pessoa = strtoupper(substr($dados_empresa[0]['tipo'], 1));

        $tipo_nota = 'NFS-e';
        
        if($dados_antecipacao){
            $valor_total = $valor_bruto+$dados_antecipacao[0]['valor'];
        }else{
            $valor_total = $valor_bruto;
        }
    
        



        /*
        if($dados_empresa[0]['cod_servico'] == 'gestao_redes'){
            $codigo_servico_municipio = '010700188';
            $item_lista_servico = '01.07';
            $descricao_servico_municipio = 'Suporte técnico em informática, inclusive instalação, configuração e manutenção de programas de computação e bancos de dados.';
        }else{
            $codigo_servico_municipio = '010700188';
            $item_lista_servico = '01.07';
            $descricao_servico_municipio = 'Datilografia, digitação, estenografia, expediente, secretaria em geral, resposta audível, redação, edição, interpretação, revisão, tradução, apoio e infra-estrutura administrativa e congêneres.';
        }*/

        //BELLUNO

            $codigo_servico_municipio = $dados_faturamento_configuracao[0]['codigo_servico_municipio'];
            $item_lista_servico = $dados_faturamento_configuracao[0]['item_lista_servico'];
            $descricao_servico_municipio = $dados_faturamento_configuracao[0]['descricao_servico_municipio'];
        
        $data_faturamento = new DateTime(getDataHora('data'));
        $data_faturamento->modify('first day of last month');

        $meses = array(
            "01" => "janeiro",
            "02" => "fevereiro",
            "03" => "março",
            "04" => "abril",
            "05" => "maio",
            "06" => "junho",
            "07" => "julho",
            "08" => "agosto",
            "09" => "setembro",
            "10" => "outubro",
            "11" => "novembro",
            "12" => "dezembro",
        );
        if($dados_consulta[0]['adesao'] == 0){
            $descricao .= "\r\nReferente a ".$meses[$data_faturamento->format('m')].' de '.$data_faturamento->format('Y').'.';
           
            $dados_faturamento_ajuste = DBRead('','tb_faturamento_ajuste',"WHERE id_faturamento = '".$dados_consulta[0]['id_faturamento']."'");
    
            if($dados_faturamento_ajuste){
                $descricao .= "\r\n\r\nObservações:";
                foreach ($dados_faturamento_ajuste as $conteudo_faturamento_ajuste) {
                    if($conteudo_faturamento_ajuste['tipo'] == 'desconto'){
                        $tipo_ajuste = 'Desconto';
                    }else{
                        $tipo_ajuste = 'Acréscimo';
                    }
                    $descricao .= "\r\n".$tipo_ajuste.' de R$ '.converteMoeda($conteudo_faturamento_ajuste['valor'], 'moeda').'. Referente a: '.$conteudo_faturamento_ajuste['descricao'];
                }
            }
        }
        

        $id_usuario = $_SESSION['id_usuario'];

        if(($cliente_razao_social && $cliente_cpf_cnpj && $cliente_uf && $cliente_id_cidade && $cliente_id_cidade != '9999999' && $cliente_logradouro && $cliente_numero && $cliente_bairro) && ($tipo_pessoa == 'J' && valida_cnpj($cliente_cpf_cnpj) || $tipo_pessoa == 'F' && valida_cpf($cliente_cpf_cnpj)) && $cliente_cep && $valor_total){
            
            $dados = array(
                'cliente_id_cidade' => $cliente_id_cidade,
                'cliente_logradouro' => $cliente_logradouro,
                'cliente_numero' => $cliente_numero,
                'cliente_complemento' => $cliente_complemento,
                'cliente_bairro' => $cliente_bairro,
                'cliente_cep' => $cliente_cep,
                'id_pessoa' => $id_pessoa,
                'data_criacao' => $data_criacao,
                'descricao' => $descricao,
                'valor_total' => $valor_total,
                'valor_pis' => $valor_pis,
                'valor_cofins' => $valor_cofins,
                'valor_csll' => $valor_csll,
                'valor_ir' => $valor_ir,
                'codigo_servico_municipio' => $codigo_servico_municipio,
                'descricao_servico_municipio' => $descricao_servico_municipio,
                'item_lista_servico' => $item_lista_servico,
                'cliente_razao_social' => $cliente_razao_social,
                'cliente_cpf_cnpj' => $cliente_cpf_cnpj,
                'tipo_pessoa' => $tipo_pessoa,
                'id_usuario' => $id_usuario
            );

            //4º var_dump
            // echo "<pre>";
            // 	var_dump($dados);
            // echo "</pre>";

            $id_externo_nfs = DBCreate('', 'tb_nfs', $dados, true);
            registraLog('Inserção de nota fiscal.','i','tb_nfs',$id_externo_nfs,"id_pessoa: $id_pessoa | cliente_id_cidade: $cliente_id_cidade | cliente_logradouro: $cliente_logradouro | cliente_numero: $cliente_numero | cliente_complemento: $cliente_complemento | cliente_bairro: $cliente_bairro | cliente_cep: $cliente_cep | data_criacao: $data_criacao | descricao: $descricao | valor_total: $valor_total | valor_pis: $valor_pis | valor_cofins: $valor_cofins | valor_csll: $valor_csll | valor_ir: $valor_ir | codigo_servico_municipio: $codigo_servico_municipio | descricao_servico_municipio: $descricao_servico_municipio | item_lista_servico: $item_lista_servico | cliente_razao_social: $cliente_razao_social | cliente_cpf_cnpj: $cliente_cpf_cnpj | tipo_pessoa: $tipo_pessoa | id_usuario: $id_usuario");

            $dados = array(
                'status' => 'nao enviado'
            );

            DBUpdate('', 'tb_nfs', $dados, "id_nfs = $id_externo_nfs");
            registraLog('Alteração status nota fiscal, nao enviado.','a','tb_nfs',$id_externo_nfs,"status: nao enviado");
                
            //AVISO NOTA
            echo " - - - NOTA: OK";

            // TESTE NOTA
            try{
                    $nfeId = eNotasGW::$NFeApi->emitir(getDadosApiNfs('empresaId'), array(
                        'tipo' => $tipo_nota,
                        'idExterno' => "$id_externo_nfs",
                        'ambienteEmissao' => 'Producao', //'Homologacao' ou 'Producao'       
                        'cliente' => array(
                            'nome' => $cliente_razao_social,
                            'cpfCnpj' => $cliente_cpf_cnpj,
                            'tipoPessoa' => $tipo_pessoa, //F - pessoa física | J - pessoa jurídica
                            'endereco' => array(
                                'uf' => $cliente_uf, 
                                'cidade' => $cliente_id_cidade,
                                'logradouro' => $cliente_logradouro,
                                'numero' => $cliente_numero,
                                'complemento' => $cliente_complemento,
                                'bairro' => $cliente_bairro,
                                'cep' => $cliente_cep
                            )
                        ),
                        
                        'servico' => array(
                            'descricao' => $descricao,
                            'issRetidoFonte' => false,
                            'valorPis' => $valor_pis,
                            'valorCofins' => $valor_cofins,
                            'valorCsll' => $valor_csll,
                            'valorIr' => $valor_ir,
                            'codigoServicoMunicipio' => $codigo_servico_municipio,
                            'descricaoServicoMunicipio' => $descricao_servico_municipio,
                            'itemListaServicoLC116' => $item_lista_servico
                        ),

                        'valorTotal' => $valor_total
                    ));
                    
                    $dados_inserindo = array(
                        'status' => 'inserindo',
                    );

                    DBUpdate('', 'tb_nfs', $dados_inserindo, "id_nfs = $id_externo_nfs");
                    registraLog('Alteração status nota fiscal, inserindo.','a','tb_nfs',$id_externo_nfs,"status: inserindo");
                    
                    $dados = array(
                        'id_nfs' => $id_externo_nfs    
                    );
                    DBUpdate('', 'tb_conta_receber', $dados, "id_conta_receber = $id_conta_receber");
                
                }catch(Exceptions\invalidApiKeyException $ex) {
                    $alert = ('Não foi possível gerar a nota fiscal! ('.$nome_empresa.') Erro de autenticação<br>'.$ex->getMessage().'','w');
                    // header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$ancora_servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
                    exit;
                }catch(Exceptions\unauthorizedException $ex) {
                    $alert = ('Não foi possível gerar a nota fiscal! ('.$nome_empresa.') Acesso negado<br>'.$ex->getMessage().'','w');
                    // header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$ancora_servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
                    exit;
                }catch(Exceptions\apiException $ex) {
                    $alert = ('Não foi possível gerar a nota fiscal! ('.$nome_empresa.') Erro de validação<br>'.$ex->getMessage().'','w');
                    // header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$ancora_servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
                    exit;
                }catch(Exceptions\requestException $ex) {
                    $alert = ('Não foi possível gerar a nota fiscal! Erro de requisição!','w');
                    // header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=".$ancora_servico."&cancelados=".$cancelados."&ordenacao=".$ordenacao);
                    exit;
            }
        // fim nota
        
        }else{
            $descricao_erro = '';
            if(!$cliente_razao_social){
                $descricao_erro .= 'Nome inválido';
            }
            if((!$cliente_cpf_cnpj) || (!valida_cnpj($cliente_cpf_cnpj) && !valida_cpf($cliente_cpf_cnpj))){
                if($descricao_erro == ''){
                    $descricao_erro .= 'CNPJ/CPF inválido';
                }else{
                    $descricao_erro .= ', CNPJ/CPF inválido';
                }
            }
            if(!$cliente_uf){
                if($descricao_erro == ''){
                    $descricao_erro .= 'UF inválida';
                }else{
                    $descricao_erro .= ', UF inválida';
                }
            }
            if(!$cliente_id_cidade || $cliente_id_cidade == '9999999'){
                if($descricao_erro == ''){
                    $descricao_erro .= 'Cidade inválida';
                }else{
                    $descricao_erro .= ', cidade inválida';
                }
            }
            if(!$cliente_logradouro){
                if($descricao_erro == ''){
                    $descricao_erro .= 'Logradouro inválido';
                }else{
                    $descricao_erro .= ', logradouro inválido';
                }
            }
            if(!$cliente_numero){
                if($descricao_erro == ''){
                    $descricao_erro .= 'Número do logradouro inválido';
                }else{
                    $descricao_erro .= ', número do logradouro inválido';
                }
            }
            if(!$cliente_bairro){
                if($descricao_erro == ''){
                    $descricao_erro .= 'Bairro inválido';
                }else{
                    $descricao_erro .= ', bairro do logradouro inválido';
                }
            }
            $descricao_erro .= '.';
            echo $descricao_erro;
            echo "<br>";
        }

    //FIM NFS-e

}

function inserir_call_suporte($data_inicial, $data_final){

    $dados_consulta = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE c.cod_servico = 'call_suporte' AND realiza_cobranca = '1' AND recebe_ligacao = '1' ORDER BY b.nome ASC", "a.*, b.*, a.status AS status_contrato, c.nome AS nome_plano, c.cod_servico");  
	
	$data_referencia = $data_inicial;

	$dados_verificacao_faturamento = DBRead('', 'tb_faturamento a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano WHERE b.cod_servico = 'call_suporte' AND a.data_referencia = '".$data_referencia."' AND a.adesao = '0' LIMIT 1");

	if(!$dados_verificacao_faturamento && $dados_consulta){

		foreach($dados_consulta as $dado_consulta){

			$dados_consulta_historico = DBRead('','tb_contrato_plano_pessoa_historico',"WHERE id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND data_atualizacao <= '".$data_final." 23:59:59' ");  

			$dados_consulta_status = DBRead('','tb_contrato_plano_pessoa',"WHERE id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND data_status <= '".$data_final." 23:59:59' ");  

			if($dados_consulta_historico || $dados_consulta_status){

				$valor_cobranca_texto = 0;

				$cont_faturado_texto = 0;

				$cont_faturado = 0;
				$contador_duplicados = 0;
				$exibir = 0;

				if($dado_consulta['status_contrato'] == '1' || ($dado_consulta['data_status'] >= $data_inicial && $dado_consulta['status_contrato'] != 7 && $dado_consulta['status_contrato'] != 5)){
					$exibir = 1;
				}
								
				if($exibir != 0 && (!$dado_consulta['contrato_pai'] || $dado_consulta['contrato_pai'] == '0')){

					//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

				    $dados_consulta_filho = DBRead('','tb_contrato_plano_pessoa',"WHERE contrato_pai = '".$dado_consulta['id_contrato_plano_pessoa']."' ");

				    if($dados_consulta_filho){

                        $cont_faturado_filho = 0;                            
                        $contador_duplicados_filho = 0;
                        $cont_faturado_filho_texto = 0;
					
					    foreach ($dados_consulta_filho as $conteudo_consulta_filho) {
                            

                            $dados_filho = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_contrato_plano_pessoa = '".$conteudo_consulta_filho['id_contrato_plano_pessoa']."' ");

                            $dados_faturado_filho = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND desconsiderar = '0' AND data_inicio BETWEEN '".$data_inicial." 00:00:00' AND '".$data_final." 23:59:59' AND id_contrato_plano_pessoa = '".$dados_filho[0]['id_contrato_plano_pessoa']."' ");
												
						    if($dado_consulta['remove_duplicados'] == '1'){
						
							    if($dados_faturado_filho){
								    foreach($dados_faturado_filho as $conteudo_faturado_filho){

                                        $data_fim_filho = date('Y-m-d H:i:s', strtotime("-".$dado_consulta['minutos_duplicados']." minutes",strtotime($conteudo_faturado_filho['data_inicio_contrato'])));

                                        if(valida_cpf($conteudo_faturado_filho['cpf_cnpj']) || valida_cnpj($conteudo_faturado_filho['cpf_cnpj'])){

                                            $dados_duplicado = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND desconsiderar = '0' AND data_inicio <= '".$conteudo_faturado_filho['data_inicio']."' AND data_inicio >= '".$data_fim_filho."' AND id_contrato_plano_pessoa = '".$dados_filho[0]['id_contrato_plano_pessoa']."' AND cpf_cnpj = '".$conteudo_faturado_filho['cpf_cnpj']."' AND id_atendimento != '".$conteudo_faturado_filho['id_atendimento']."'");                                           
											
											if(!$dados_duplicado){
												if($conteudo_faturado_filho['via_texto'] == 1 && $dado_consulta['valor_diferente_texto'] == '1'){
													$cont_faturado_filho_texto++;
												}else{
													$cont_faturado_filho++;
												}
		
											}else{
												$contador_duplicados_filho++;
											}
		
										}else{
											if($conteudo_faturado_filho['via_texto'] == 1 && $dado_consulta['valor_diferente_texto'] == '1'){
												$cont_faturado_filho_texto++;
											}else{
												$cont_faturado_filho++;
											}
										}

                                       
								    }
							    }

						    }else{

                                $cont_dados_faturado_filho = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND desconsiderar = '0' AND data_inicio BETWEEN '".$data_inicial." 00:00:00' AND '".$data_final." 23:59:59' AND id_contrato_plano_pessoa = '".$dados_filho[0]['id_contrato_plano_pessoa']."' ","COUNT(*) AS cont");
                            
                                $cont_faturado_filho = $cont_dados_faturado_filho[0]['cont'];
						    }
						
                            $dados_monitoramento_filho = DBRead('', 'tb_monitoramento_queda',"WHERE id_contrato_plano_pessoa = '".$dados_filho[0]['id_contrato_plano_pessoa']."' AND data_registro BETWEEN '".$data_inicial." 00:00:00' AND '".$data_final." 23:59:59' GROUP BY id_contrato_plano_pessoa", "id_contrato_plano_pessoa, COUNT(id_contrato_plano_pessoa) as cont");
                            
                            $cont_monitoramento_filho = $dados_monitoramento_filho[0]['cont'] ? $dados_monitoramento_filho[0]['cont'] : 0;
					    }

                    }else{
                        $cont_monitoramento_filho = 0;
                        $cont_faturado_filho = 0;
						$contador_duplicados_filho = 0;
						$cont_faturado_filho_texto = 0;

                    }


					//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------
					if($dado_consulta['tipo_cobranca'] == 'x_cliente_base'){

						$cont_dados_faturado = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND desconsiderar = '0' AND data_inicio BETWEEN '".$data_inicial." 00:00:00' AND '".$data_final." 23:59:59' AND id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' ","COUNT(*) AS cont");
						
						$cont_faturado = $cont_dados_faturado[0]['cont'];

						$dados_monitoramento = DBRead('', 'tb_monitoramento_queda',"WHERE id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND data_registro BETWEEN '".$data_inicial." 00:00:00' AND '".$data_final." 23:59:59' GROUP BY id_contrato_plano_pessoa", "id_contrato_plano_pessoa, COUNT(id_contrato_plano_pessoa) as cont");

						$cont_monitoramento = $dados_monitoramento[0]['cont'] ? $dados_monitoramento[0]['cont'] : 0;

						$qtd_efetuada = $cont_faturado + $cont_monitoramento + $cont_faturado_filho + $cont_monitoramento_filho;

						$qtd_clientes = $dado_consulta['qtd_clientes'];
						$qtd_clientes_teto = $dado_consulta['qtd_clientes_teto'];

						$excedente_realizado = ($qtd_clientes) - $qtd_clientes_teto;
						
						if($excedente_realizado <= 0){
							$excedente_realizado = 0;
						}

						$qtd_efetuada_texto = 0;
						$desafogo_realizado = 0;
						$qtd_desafogo_texto = 0;
						$excedente_realizado_texto = 0;
						
						$valor_excedente_realizado = $excedente_realizado * $dado_consulta['valor_excedente'];

						if($excedente_realizado == 0){
							$valor_cobranca_total = $dado_consulta['valor_total'];
						}else{
							$valor_cobranca_total = $dado_consulta['valor_total'] + $valor_excedente_realizado;
						}


						$qtd_duplicados = $contador_duplicados + $contador_duplicados_filho;

						$data_gerado = getDataHora();
						$id_usuario = $_SESSION['id_usuario'];



						//Minha implementação
						$qtd_monitoramento = 0;
						


						

					
					
					
					}else{
						if($dado_consulta['remove_duplicados'] == '1'){

							$dados_faturado = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND desconsiderar = '0' AND data_inicio BETWEEN '".$data_inicial." 00:00:00' AND '".$data_final." 23:59:59' AND id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' ");

							if($dados_faturado){
								foreach($dados_faturado as $conteudo_faturado){

									$data_fim = date('Y-m-d H:i:s', strtotime("-".$dado_consulta['minutos_duplicados']." minutes",strtotime($conteudo_faturado['data_inicio'])));

									if(valida_cpf($conteudo_faturado['cpf_cnpj']) || valida_cnpj($conteudo_faturado['cpf_cnpj'])){

										$dados_duplicado = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND desconsiderar = '0' AND data_inicio <= '".$conteudo_faturado['data_inicio']."' AND data_inicio >= '".$data_fim."' AND id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND cpf_cnpj = '".$conteudo_faturado['cpf_cnpj']."' AND id_atendimento != '".$conteudo_faturado['id_atendimento']."'");
									
										if(!$dados_duplicado){
											if($conteudo_faturado['via_texto'] == 1 && $dado_consulta['valor_diferente_texto'] == '1'){
												$cont_faturado_texto++;
											}else{
												$cont_faturado++;
											}

										}else{
											$contador_duplicados++;
										}

									}else{
										if($conteudo_faturado['via_texto'] == 1 && $dado_consulta['valor_diferente_texto'] == '1'){
											$cont_faturado_texto++;
										}else{
											$cont_faturado++;
										}
									}
								}
							}

						}else{
							if($dado_consulta['valor_diferente_texto'] == 1){
								$cont_dados_faturado = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND desconsiderar = '0' AND via_texto != 1 AND data_inicio BETWEEN '".$data_inicial." 00:00:00' AND '".$data_final." 23:59:59' AND id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' ","COUNT(*) AS cont");
						
								$cont_faturado = $cont_dados_faturado[0]['cont'];

								$cont_dados_faturado_texto = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND desconsiderar = '0' AND via_texto = 1 AND data_inicio BETWEEN '".$data_inicial." 00:00:00' AND '".$data_final." 23:59:59' AND id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' ","COUNT(*) AS cont");

								$cont_faturado_texto = $cont_dados_faturado_texto[0]['cont'];
							}else{
								$cont_dados_faturado = DBRead('','tb_atendimento',"WHERE gravado = '1' AND falha != 2 AND desconsiderar = '0' AND data_inicio BETWEEN '".$data_inicial." 00:00:00' AND '".$data_final." 23:59:59' AND id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' ","COUNT(*) AS cont");
						
								$cont_faturado = $cont_dados_faturado[0]['cont'];

								$cont_dados_faturado_texto = 0;
							}
						}

						
						$dados_monitoramento = DBRead('', 'tb_monitoramento_queda',"WHERE id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND data_registro BETWEEN '".$data_inicial." 00:00:00' AND '".$data_final." 23:59:59' GROUP BY id_contrato_plano_pessoa", "id_contrato_plano_pessoa, COUNT(id_contrato_plano_pessoa) as cont");
						
						$cont_monitoramento = $dados_monitoramento[0]['cont'] ? $dados_monitoramento[0]['cont'] : 0;
						
						//Voz
						$qtd_efetuada = $cont_faturado + $cont_monitoramento + $cont_faturado_filho + $cont_monitoramento_filho;

						//CONTADOR EXCEDENTE
						$cont_excedente = ($qtd_efetuada) - $dado_consulta['qtd_contratada'];
						
						if($cont_excedente <= 0){
							$cont_excedente = 0;
						}

						//Texto
						if($dado_consulta['valor_diferente_texto'] == 1){
							$qtd_efetuada_texto = $cont_faturado_texto + $cont_faturado_filho_texto;

							//CONTADOR EXCEDENTE
							$cont_excedente_texto = ($qtd_efetuada_texto) - $dado_consulta['qtd_contratada_texto'];
							
							if($cont_excedente_texto <= 0){
								$cont_excedente_texto = 0;
							}
						}else{
							$qtd_efetuada_texto = 0;

							//CONTADOR EXCEDENTE
							$cont_excedente_texto = 0;
						}

						

						


						if($dado_consulta['tipo_cobranca'] == 'unitario'){
							//Voz
							$desafogo_realizado = 0;
							$excedente_realizado = 0;

							$valor_excedente_realizado = 0;
							$valor_total_desafogo = 0;

							$valor_cobranca = $dado_consulta['valor_inicial'] + ($qtd_efetuada * $dado_consulta['valor_unitario']);

							//Texto
							if($dado_consulta['valor_diferente_texto'] == 1){
								$desafogo_realizado_texto = 0;
								$excedente_realizado_texto = 0;

								$valor_excedente_realizado_texto = 0;
								$valor_total_desafogo_texto = 0;

								$valor_cobranca_texto = $cont_excedente_texto * $dado_consulta['valor_unitario_texto'];
							}				

						}else{
							if($dado_consulta['tipo_cobranca'] == 'mensal_desafogo'){
								
								$qtd_desafogo = $dado_consulta['qtd_contratada']*($dado_consulta['desafogo']/100);
								
								//SE FOR MAIOR DO QUE 5 ELE ARREDONDA PRA CIMA, SENÃO PRA BAIXO
								$qtd_desafogo = round($qtd_desafogo);

								//CONTAGEM DESAFOGO
								if(($cont_excedente - $qtd_desafogo) > 0){
									$desafogo_realizado = $qtd_desafogo;
									$excedente_realizado = $cont_excedente - $qtd_desafogo;
								}else if(($cont_excedente - $qtd_desafogo) == 0){
									$desafogo_realizado = $qtd_desafogo;
									$excedente_realizado = 0;

								}else if(($cont_excedente - $qtd_desafogo) < 0){

									if($cont_excedente == 0){
										$desafogo_realizado = 0;
										$excedente_realizado = 0;
									}else{
										$desafogo_realizado = $cont_excedente;
										$excedente_realizado = 0;
									}
								}

								//Texto
								if($dado_consulta['valor_diferente_texto'] == 1){
									//CONTAGEM DESAFOGO 

									$qtd_desafogo_texto = $dado_consulta['qtd_contratada_texto']*($dado_consulta['desafogo_texto']/100);
									$qtd_desafogo_texto = round($qtd_desafogo_texto);

									if(($cont_excedente_texto - $qtd_desafogo_texto) > 0){
										$desafogo_realizado_texto = $qtd_desafogo_texto;
										$excedente_realizado_texto = $cont_excedente_texto - $qtd_desafogo_texto;
									}else if(($cont_excedente_texto - $qtd_desafogo_texto) == 0){
										$desafogo_realizado_texto = $qtd_desafogo_texto;
										$excedente_realizado_texto = 0;
		
									}else if(($cont_excedente_texto - $qtd_desafogo_texto) < 0){
		
										if($cont_excedente_texto == 0){
											$desafogo_realizado_texto = 0;
											$excedente_realizado_texto = 0;
										}else{
											$desafogo_realizado_texto = $cont_excedente_texto;
											$excedente_realizado_texto = 0;
										}
									}
			
								}else{
									$qtd_desafogo_texto = 0;
									$desafogo_realizado_texto = 0;
									$excedente_realizado_texto = 0;

								}
								
							}else{
								$desafogo_realizado = 0;
								$excedente_realizado = $cont_excedente;

								if($dado_consulta['valor_diferente_texto'] == 1){
									//Texto
									$desafogo_realizado_texto = 0;
									$excedente_realizado_texto = $cont_excedente_texto;
			
								}else{
									$desafogo_realizado_texto = 0;
									$excedente_realizado_texto = 0;

								}
								
							}

							$valor_excedente_realizado = $excedente_realizado * $dado_consulta['valor_excedente'];
							$valor_total_desafogo = $desafogo_realizado * $dado_consulta['valor_unitario'];

							if($cont_excedente == 0){
								$valor_cobranca = $dado_consulta['valor_total'];
							}else{
								$valor_cobranca = $dado_consulta['valor_total'] + $valor_excedente_realizado + $valor_total_desafogo;
							}

							if($dado_consulta['valor_diferente_texto'] == 1){
								//Texto
								$valor_excedente_realizado_texto = $excedente_realizado_texto * $dado_consulta['valor_excedente_texto'];
								$valor_total_desafogo_texto = $desafogo_realizado_texto * $dado_consulta['valor_unitario_texto'];

									$valor_cobranca_texto = $valor_excedente_realizado_texto + $valor_total_desafogo_texto;
		
							}else{
								$valor_excedente_realizado_texto = 0;
								$valor_total_desafogo_texto = 0;
								$valor_cobranca_texto = 0;
								
							}
						}	

						$valor_cobranca_total = $valor_cobranca + $valor_cobranca_texto;

						$qtd_duplicados = $contador_duplicados + $contador_duplicados_filho;

						$data_gerado = getDataHora();
						$id_usuario = $_SESSION['id_usuario'];

						$qtd_monitoramento = $cont_monitoramento + $cont_monitoramento_filho;
						$qtd_clientes_teto = 0;
						$qtd_clientes = $dado_consulta['qtd_clientes'];

					}

					// echo $dado_consulta['status_contrato'];

					//AQUI É O SEPARADO
					if($dado_consulta['separar_contrato'] != 0 && $dado_consulta['separar_contrato']){
						$valor_cobranca_total = $valor_cobranca_total/2;
						$dados_faturamento = array(
							//'id_contrato_plano_pessoa' => $dado_consulta['id_contrato_plano_pessoa'],
							'id_plano' => $dado_consulta['id_plano'],
							'id_usuario' => $id_usuario,
							'data_referencia' => $data_referencia,
							'valor_total' => $valor_cobranca_total,
							'valor_cobranca' => $valor_cobranca_total,
							'acrescimo' => '0',
							'desconto' => '0',
							'status' => '1',
							'qtd_contratada' => $dado_consulta['qtd_contratada'],
							'qtd_efetuada' => $qtd_efetuada,
							'qtd_excedente' => $excedente_realizado,
							'valor_excedente_contrato' => $dado_consulta['valor_excedente'],
							'qtd_desafogo' => $desafogo_realizado,
							'valor_inicial_contrato' => $dado_consulta['valor_inicial'],
							'tipo_cobranca' => $dado_consulta['tipo_cobranca'],
							'data_gerado' => $data_gerado,
							'valor_unitario_contrato' => $dado_consulta['valor_unitario'],
							'valor_total_contrato' => $dado_consulta['valor_total'],
							'desafogo_contrato' => $dado_consulta['desafogo'],
							'remove_duplicados_contrato' => $dado_consulta['remove_duplicados'],
							'qtd_duplicados' => $qtd_duplicados,
							'minutos_duplicados_contrato' => $dado_consulta['minutos_duplicados'],
							'qtd_contratada_texto' => $dado_consulta['qtd_contratada_texto'],
							'valor_unitario_texto_contrato' => $dado_consulta['valor_unitario_texto'],
							'valor_excedente_texto_contrato' => $dado_consulta['valor_excedente_texto'],
							'qtd_efetuada_texto' => $qtd_efetuada_texto,
							'qtd_desafogo_texto' => $qtd_desafogo_texto,
							'qtd_excedente_texto' => $excedente_realizado_texto,
							'valor_diferente_texto' => $dado_consulta['valor_diferente_texto'],
							'qtd_monitoramento' => $qtd_monitoramento,
							'qtd_clientes_teto' => $qtd_clientes_teto,
							'qtd_clientes' => $qtd_clientes,
							'contrato_filho_separar' => 1,
						);		
	
						// echo "<pre>";
						// 	var_dump($dados_faturamento);
						// echo "</pre>";
	
						$insertID_contrato = DBCreate('', 'tb_faturamento', $dados_faturamento, true);
						registraLog('Inserção de faturamento.','i','tb_faturamento',$insertID_contrato,"id_plano: ".$dado_consulta['id_plano']." | id_usuario: $id_usuario | data_referencia: $data_referencia | valor_total: $valor_cobranca_total | valor_cobranca: $valor_cobranca_total | acrescimo: 0 | desconto: 0 | status: 1 | qtd_contratada: ".$dado_consulta['qtd_contratada']." | qtd_efetuada: $qtd_efetuada | qtd_excedente: $excedente_realizado | valor_excedente_contrato: ".$dado_consulta['valor_excedente']." | qtd_desafogo: $desafogo_realizado | valor_inicial_contrato: ".$dado_consulta['valor_inicial']." | tipo_cobranca: ".$dado_consulta['tipo_cobranca']." | data_gerado: $data_gerado | valor_unitario_contrato: ".$dado_consulta['valor_unitario']." | valor_total_contrato: ".$dado_consulta['valor_total']." | desafogo_contrato: ".$dado_consulta['desafogo']." | remove_duplicados_contrato: ".$dado_consulta['remove_duplicados']." | qtd_duplicados: $qtd_duplicados | minutos_duplicados_contrato: ".$dado_consulta['minutos_duplicados']." | qtd_contratada_texto: ".$dado_consulta['qtd_contratada_texto']." | valor_unitario_texto_contrato: ".$dado_consulta['valor_unitario_texto']." | valor_excedente_texto_contrato: ".$dado_consulta['valor_excedente_texto']." | qtd_efetuada_texto: $qtd_efetuada_texto | qtd_desafogo_texto: $qtd_desafogo_texto | qtd_excedente_texto: $excedente_realizado_texto | valor_diferente_texto: ".$dado_consulta['valor_diferente_texto']." | qtd_monitoramento: $qtd_monitoramento ");
						
						$dados_contrato = array(
							'id_faturamento' => $insertID_contrato,
							'id_contrato_plano_pessoa' => $dado_consulta['separar_contrato'],
							'contrato_pai' => 1
						);
						// echo "<pre>";
						// 	var_dump($dados_contrato);
						// echo "</pre>";
						// die();
	
						$insertID = DBCreate('', 'tb_faturamento_contrato', $dados_contrato, true);
						registraLog('Inserção de contrato de faturamento.','i','tb_faturamento_contrato',$insertID,"id_faturamento: $insertID | id_contrato_plano_pessoa: ".$dado_consulta['id_contrato_plano_pessoa']." | contrato_pai: 1");
	                        
						$data_inicial_antecipacao = new DateTime(getDataHora('data'));
						$data_inicial_antecipacao->modify('first day of this month');
						$data_referencia_antecipacao = $data_inicial_antecipacao->format('Y-m-d');
						$data_inicial_antecipacao = $data_inicial_antecipacao->format('Y-m');
	
						$data_final_cobranca = new DateTime($dado_consulta['data_final_cobranca']);
						$dia_data_final_cobranca = $data_final_cobranca->format('d');
						$data_final_cobranca = $data_final_cobranca->format('Y-m');
	
						if($data_inicial_antecipacao == $data_final_cobranca){
							$data_inicial_antecipacao = $data_inicial_antecipacao."-10";
							if($dado_consulta['data_final_cobranca'] <= $data_inicial_antecipacao){
								// $data_final_antecipacao = new DateTime(getDataHora('data'));
								// $data_final_antecipacao->modify('last day of this month');
								// $data_final_antecipacao = $data_final_antecipacao->format('d');
	
								$qtd_dias = $dia_data_final_cobranca;
								$data_referencia_antecipacao = $data_referencia_antecipacao;
								// $valor_antecipacao = ($dado_consulta['valor_total']/$data_final_antecipacao)*$dia_data_final_cobranca;
								$valor_antecipacao = ($dado_consulta['valor_total']/30)*$dia_data_final_cobranca;
	
								$dados_faturamento_antecipacao = array(
									'id_faturamento' => $insertID_contrato,
									'qtd_dias' => $qtd_dias,
									'valor' => $valor_antecipacao,
									'data_referencia' => $data_referencia_antecipacao
								);
	
								$insertID_antecipacao = DBCreate('', 'tb_faturamento_antecipacao', $dados_faturamento_antecipacao, true);
								registraLog('Inserção de antecipacao de faturamento.','i','tb_faturamento_contrato',$insertID_antecipacao,"id_faturamento: $insertID_contrato | qtd_dias: ".$qtd_dias." | valor: ".$valor_antecipacao." | data_referencia: ".$data_referencia_antecipacao." ");
							}
						}
					}			
					
					$dados_faturamento = array(
						//'id_contrato_plano_pessoa' => $dado_consulta['id_contrato_plano_pessoa'],
						'id_plano' => $dado_consulta['id_plano'],
						'id_usuario' => $id_usuario,
						'data_referencia' => $data_referencia,
						'valor_total' => $valor_cobranca_total,
						'valor_cobranca' => $valor_cobranca_total,
						'acrescimo' => '0',
						'desconto' => '0',
						'status' => '1',
						'qtd_contratada' => $dado_consulta['qtd_contratada'],
						'qtd_efetuada' => $qtd_efetuada,
						'qtd_excedente' => $excedente_realizado,
						'valor_excedente_contrato' => $dado_consulta['valor_excedente'],
						'qtd_desafogo' => $desafogo_realizado,
						'valor_inicial_contrato' => $dado_consulta['valor_inicial'],
						'tipo_cobranca' => $dado_consulta['tipo_cobranca'],
						'data_gerado' => $data_gerado,
						'valor_unitario_contrato' => $dado_consulta['valor_unitario'],
						'valor_total_contrato' => $dado_consulta['valor_total'],
						'desafogo_contrato' => $dado_consulta['desafogo'],
						'remove_duplicados_contrato' => $dado_consulta['remove_duplicados'],
						'qtd_duplicados' => $qtd_duplicados,
						'minutos_duplicados_contrato' => $dado_consulta['minutos_duplicados'],
						'qtd_contratada_texto' => $dado_consulta['qtd_contratada_texto'],
						'valor_unitario_texto_contrato' => $dado_consulta['valor_unitario_texto'],
						'valor_excedente_texto_contrato' => $dado_consulta['valor_excedente_texto'],
						'qtd_efetuada_texto' => $qtd_efetuada_texto,
						'qtd_desafogo_texto' => $qtd_desafogo_texto,
						'qtd_excedente_texto' => $excedente_realizado_texto,
						'valor_diferente_texto' => $dado_consulta['valor_diferente_texto'],
						'qtd_monitoramento' => $qtd_monitoramento,
						'qtd_clientes_teto' => $qtd_clientes_teto,
						'qtd_clientes' => $qtd_clientes,
					);		

					// echo "<pre>";
					// 	var_dump($dados_faturamento);
					// echo "</pre>";
					// die();

					$insertID_contrato = DBCreate('', 'tb_faturamento', $dados_faturamento, true);
			        registraLog('Inserção de faturamento.','i','tb_faturamento',$insertID_contrato,"id_plano: ".$dado_consulta['id_plano']." | id_usuario: $id_usuario | data_referencia: $data_referencia | valor_total: $valor_cobranca_total | valor_cobranca: $valor_cobranca_total | acrescimo: 0 | desconto: 0 | status: 1 | qtd_contratada: ".$dado_consulta['qtd_contratada']." | qtd_efetuada: $qtd_efetuada | qtd_excedente: $excedente_realizado | valor_excedente_contrato: ".$dado_consulta['valor_excedente']." | qtd_desafogo: $desafogo_realizado | valor_inicial_contrato: ".$dado_consulta['valor_inicial']." | tipo_cobranca: ".$dado_consulta['tipo_cobranca']." | data_gerado: $data_gerado | valor_unitario_contrato: ".$dado_consulta['valor_unitario']." | valor_total_contrato: ".$dado_consulta['valor_total']." | desafogo_contrato: ".$dado_consulta['desafogo']." | remove_duplicados_contrato: ".$dado_consulta['remove_duplicados']." | qtd_duplicados: $qtd_duplicados | minutos_duplicados_contrato: ".$dado_consulta['minutos_duplicados']." | qtd_contratada_texto: ".$dado_consulta['qtd_contratada_texto']." | valor_unitario_texto_contrato: ".$dado_consulta['valor_unitario_texto']." | valor_excedente_texto_contrato: ".$dado_consulta['valor_excedente_texto']." | qtd_efetuada_texto: $qtd_efetuada_texto | qtd_desafogo_texto: $qtd_desafogo_texto | qtd_excedente_texto: $excedente_realizado_texto | valor_diferente_texto: ".$dado_consulta['valor_diferente_texto']." | qtd_monitoramento: $qtd_monitoramento ");
					
					$dados_contrato = array(
						'id_faturamento' => $insertID_contrato,
						'id_contrato_plano_pessoa' => $dado_consulta['id_contrato_plano_pessoa'],
						'contrato_pai' => 1
					);

					$insertID = DBCreate('', 'tb_faturamento_contrato', $dados_contrato, true);
				    registraLog('Inserção de contrato de faturamento.','i','tb_faturamento_contrato',$insertID,"id_faturamento: $insertID | id_contrato_plano_pessoa: ".$dado_consulta['id_contrato_plano_pessoa']." | contrato_pai: 1");

                    //AVISO DE EMPRESAS FATURAMENTO
                    echo $dado_consulta['nome']."<br>";



					//AQUI É O PROPORCIONAL CANCELADO
					if($dado_consulta['status_contrato'] == 3){

						$data_final_cobranca = new DateTime($dado_consulta['data_final_cobranca']);
						$dia_data_final_cobranca = $data_final_cobranca->format('d');
						$data_final_cobranca = $data_final_cobranca->format('Y-m');

						if($data_referencia == $data_final_cobranca."-01"){
							if($dia_data_final_cobranca <= 29){
								$qtd_dias = $dia_data_final_cobranca;
								$valor_proporcionalidade_cancelado = ($dado_consulta['valor_total']/30)*$dia_data_final_cobranca;
								$qtd_contratada = round(($dado_consulta['qtd_contratada']/30)*$qtd_dias, 0);
								$dados_faturamento_proporcional = array(
									'id_faturamento' => $insertID_contrato,
									'qtd_dias' => $qtd_dias,
									'tipo' => 1,
									'qtd_contratada' => $qtd_contratada,
								);
	
								$insertID_proporcional_cancelado = DBCreate('', 'tb_faturamento_proporcional', $dados_faturamento_proporcional, true);
								registraLog('Inserção de prporcionalidade de faturamento cancelado.','i','tb_faturamento_proporcional',$insertID_proporcional_cancelado,"id_faturamento: $insertID_contrato | qtd_dias: ".$qtd_dias." | tipo: 1 | qtd_contratada: ".$qtd_contratada."");
								
								$qtd_excedente = $qtd_efetuada - $qtd_contratada;
	
								if($qtd_excedente <= 0){
									$qtd_excedente = 0;
									$valor_excedente = 0;
								}else{
									$valor_excedente = $dado_consulta['valor_excedente'] * $qtd_excedente;
									
								}
								$dados_faturamento = array(
									'valor_total' => $valor_proporcionalidade_cancelado+$valor_excedente,
									'valor_cobranca' => $valor_proporcionalidade_cancelado+$valor_excedente,
									'qtd_excedente' => $qtd_excedente,
								);
							
								DBUpdate('', 'tb_faturamento', $dados_faturamento, "id_faturamento = $insertID_contrato");
							}
							
						}						
					}

					//AQUI É O PROPORCIONAL ATIVO
					if($dado_consulta['status_contrato'] == 1){

						$data_inicial_cobranca = new DateTime($dado_consulta['data_inicial_cobranca']);
						$dia_data_inicial_cobranca = $data_inicial_cobranca->format('d');
						$data_inicial_cobranca = $data_inicial_cobranca->format('Y-m');

						if($data_referencia == $data_inicial_cobranca."-01"){
							$qtd_dias = 30 - ($dia_data_inicial_cobranca - 1);
							$valor_proporcionalidade_ativo = ($dado_consulta['valor_total']/30)*$qtd_dias;
							$qtd_contratada = round(($dado_consulta['qtd_contratada']/30)*$qtd_dias, 0);

							$dados_faturamento_proporcional = array(
								'id_faturamento' => $insertID_contrato,
								'qtd_dias' => $qtd_dias,
								'tipo' => 2,
								'qtd_contratada' => $qtd_contratada,
							);

							$insertID_proporcional_ativo = DBCreate('', 'tb_faturamento_proporcional', $dados_faturamento_proporcional, true);
				    		registraLog('Inserção de prporcionalidade de faturamento cancelado.','i','tb_faturamento_proporcional',$insertID_proporcional_ativo,"id_faturamento: $insertID_contrato | qtd_dias: ".$qtd_dias." | tipo: 2 | qtd_contratada: ".$qtd_contratada."");

							$qtd_excedente = $qtd_efetuada - $qtd_contratada;

							if($qtd_excedente <= 0){
								$qtd_excedente = 0;
								$valor_excedente = 0;
							}else{
								$valor_excedente = $dado_consulta['valor_excedente'] * $qtd_excedente;
								
							}

							$dados_faturamento = array(
								'valor_total' => $valor_proporcionalidade_ativo+$valor_excedente,
								'valor_cobranca' => $valor_proporcionalidade_ativo+$valor_excedente,
								'qtd_excedente' => $qtd_excedente,
							);

							DBUpdate('', 'tb_faturamento', $dados_faturamento, "id_faturamento = $insertID_contrato");
						}						
					}
					

					$data_inicial_antecipacao = new DateTime(getDataHora('data'));
					$data_inicial_antecipacao->modify('first day of this month');
					$data_referencia_antecipacao = $data_inicial_antecipacao->format('Y-m-d');
					$data_inicial_antecipacao = $data_inicial_antecipacao->format('Y-m');

					$data_final_cobranca = new DateTime($dado_consulta['data_final_cobranca']);
					$dia_data_final_cobranca = $data_final_cobranca->format('d');
					$data_final_cobranca = $data_final_cobranca->format('Y-m');

					if($data_inicial_antecipacao == $data_final_cobranca){
						$data_inicial_antecipacao = $data_inicial_antecipacao."-10";
						if($dado_consulta['data_final_cobranca'] <= $data_inicial_antecipacao){
							// $data_final_antecipacao = new DateTime(getDataHora('data'));
							// $data_final_antecipacao->modify('last day of this month');
							// $data_final_antecipacao = $data_final_antecipacao->format('d');

							$qtd_dias = $dia_data_final_cobranca;
							$data_referencia_antecipacao = $data_referencia_antecipacao;
							// $valor_antecipacao = ($dado_consulta['valor_total']/$data_final_antecipacao)*$dia_data_final_cobranca;
							$valor_antecipacao = ($dado_consulta['valor_total']/30)*$dia_data_final_cobranca;

							$dados_faturamento_antecipacao = array(
								'id_faturamento' => $insertID_contrato,
								'qtd_dias' => $qtd_dias,
								'valor' => $valor_antecipacao,
								'data_referencia' => $data_referencia_antecipacao
							);

							// echo "<pre>";
							// 	var_dump($dados_faturamento_antecipacao);
							// echo "</pre>";

							$insertID_antecipacao = DBCreate('', 'tb_faturamento_antecipacao', $dados_faturamento_antecipacao, true);
				    		registraLog('Inserção de antecipacao de faturamento.','i','tb_faturamento_contrato',$insertID_antecipacao,"id_faturamento: $insertID_contrato | qtd_dias: ".$qtd_dias." | valor: ".$valor_antecipacao." | data_referencia: ".$data_referencia_antecipacao." ");



							$dados_faturamento = array(
								'valor_total' => $valor_antecipacao+$valor_cobranca_total,
								'valor_cobranca' => $valor_antecipacao+$valor_cobranca_total,
							);

							DBUpdate('', 'tb_faturamento', $dados_faturamento, "id_faturamento = $insertID_contrato");
						}
					}

				    if($dados_consulta_filho){
					
						foreach ($dados_consulta_filho as $conteudo_consulta_filho) {
						
							$dados_contrato_filho = array(
								'id_faturamento' => $insertID_contrato,
								'id_contrato_plano_pessoa' => $conteudo_consulta_filho['id_contrato_plano_pessoa'],
								'contrato_pai' => 0
							);

							$insertID_filho = DBCreate('', 'tb_faturamento_contrato', $dados_contrato_filho, true);
						    registraLog('Inserção de contrato de faturamento.','i','tb_faturamento_contrato',$insertID_filho,"id_faturamento: $insertID_contrato | id_contrato_plano_pessoa: ".$conteudo_consulta_filho['id_contrato_plano_pessoa']." | contrato_pai: 0");

						}
					}

					$dados_acrescimo_desconto = DBRead('','tb_acrescimo_desconto',"WHERE id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND data_referencia = '".$data_referencia."' ");

					if($dados_acrescimo_desconto){					
						foreach($dados_acrescimo_desconto as $conteudo_acrescimo_desconto){					
							$dados_faturamento_ajuste = array(
								'data' => getDataHora(),
								'valor' => $conteudo_acrescimo_desconto['valor'],
								'tipo' => $conteudo_acrescimo_desconto['tipo'],
								'descricao' => $conteudo_acrescimo_desconto['descricao'],
								'id_usuario' => $conteudo_acrescimo_desconto['id_usuario'],
								'id_faturamento' => $insertID_contrato
							);
						
							// $insertID = DBCreate('', 'tb_faturamento_ajuste', $dados_faturamento_ajuste, true);
						    
							$dados_verificacao_faturamento = DBRead('', 'tb_faturamento a', "INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento WHERE a.data_referencia = '".$data_referencia."' AND b.id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND a.status = '1' AND a.adesao = '0' ");

							if($conteudo_acrescimo_desconto['tipo'] == 'acrescimo'){
								$acrescimo = $dados_verificacao_faturamento[0]['acrescimo'] + $conteudo_acrescimo_desconto['valor'];
								$desconto = $dados_verificacao_faturamento[0]['desconto'];
								$valor_cobranca = $valor_cobranca_total + $conteudo_acrescimo_desconto['valor'];
							}else{
								$acrescimo = $dados_verificacao_faturamento[0]['acrescimo'];
								$desconto = $dados_verificacao_faturamento[0]['desconto'] + $conteudo_acrescimo_desconto['valor'];
								$valor_cobranca = $valor_cobranca_total - $conteudo_acrescimo_desconto['valor'];
							}
						
							$dados_faturamento = array(
								'acrescimo' => $acrescimo,
								'desconto' => $desconto,
								'valor_cobranca' => $valor_cobranca
							);
						
							DBUpdate('', 'tb_faturamento', $dados_faturamento, "id_faturamento = $insertID_contrato");

							$dados_atualiza_acrescimo_desconto = array(
								'id_faturamento' => $insertID_contrato,
							);
						
							DBUpdate('', 'tb_acrescimo_desconto', $dados_atualiza_acrescimo_desconto, "id_acrescimo_desconto = '".$conteudo_acrescimo_desconto['id_acrescimo_desconto']."' ");
						}
					}


			    }
			}			
		}
	}

    // header("location: /api/iframe?token=$request->token&view=faturamento-gerar&gerar=call_suporte&cancelados=".$cancelados."&ordenacao=".$ordenacao);
    
    // exit;
}
?>