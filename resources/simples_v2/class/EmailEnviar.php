<?php
require_once(__DIR__."/System.php");

	$assunto = (!empty($_POST['assunto'])) ? $_POST['assunto'] : '';
	$descricao_email = (!empty($_POST['descricao'])) ? $_POST['descricao'] : '';
	$selecionar = (!empty($_POST['selecionar'])) ? $_POST['selecionar'] : '';
	$tipo_pessoa = (!empty($_POST['tipo_pessoa'])) ? $_POST['tipo_pessoa'] : 'pessoa';

if(!empty($_POST['enviar_email'])){
	
	enviar_email($assunto, $descricao_email, $selecionar, $tipo_pessoa);

}else{
    header("location: ../adm.php");
    exit;
}

function enviar_email($assunto, $descricao_email, $selecionar, $tipo_pessoa){
    $cont = 0;
    $erros = '';

    $data_hora = getDataHora();
    $dados = array(
        'assunto' => $assunto,
        'descricao' => $descricao_email,
        'data_hora' => $data_hora
    );
   
    $insertID = DBCreate('', 'tb_email_enviar', $dados, true);
    registraLog('Inserção de e-mail enviado.','i','tb_email_enviar',$insertID,"assunto: $assunto | descricao: $descricao_email | data_hora: $data_hora");
    
    foreach ($selecionar as $conteudo) {
		
		$contato_email = '';
		$flag = 0;

		$mensagem = '';
        //Mensagem
            $mensagem = 
            '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border: none; background-color: #0A122A; border-top-left-radius: 6px; border-top-right-radius: 6px;">
                <tr style="border: none;">
                    <td width="260" valign="top" style="border: none;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border: none;">
                            <tr>
                                <td>
                                    <a href="https://www.bellunotec.com.br" target="_blank"><img src="https://rh.bellunotec.com.br/inc/keen/theme/classic/assets/media/logos/logobranco-1.png" style="padding: 10px 10px 10px 10px;"></a>
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
        $id_pessoa = '';
        if($tipo_pessoa == 'contrato'){
            $dados_contrato_plano_pessoa = DBRead('', 'tb_contrato_plano_pessoa', "WHERE id_contrato_plano_pessoa = '".$conteudo."' ");
            $contato_email = $dados_contrato_plano_pessoa[0]['email_nf'];

            if($contato_email){
                $flag++;
            }
            $id_pessoa = $dados_contrato_plano_pessoa[0]['id_pessoa'];
            $id_contrato_plano_pessoa = $conteudo;
        }else{
            $dados_pessoa =DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$conteudo."' ");
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
            $id_pessoa = $dados_pessoa[0]['id_pessoa'];
            $id_contrato_plano_pessoa = '';
        }
		if($contato_email && $contato_email != '' && $mensagem !== ''){
            envia_email($assunto, $mensagem, $contato_email);
            if($id_contrato_plano_pessoa && $id_contrato_plano_pessoa != ''){
                $dados = array(
                    'id_pessoa' => $id_pessoa,
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'email' => $contato_email,
                    'id_email_enviar' => $insertID
                );
                
                $insertID2 = DBCreate('', 'tb_email_enviar_pessoa', $dados, true);
                registraLog('Inserção de pessoa do e-mail enviado.','i','tb_email_enviar_pessoa',$insertID2,"id_pessoa: $id_pessoa | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | email: $contato_email | id_email_enviar: $insertID");
            }else{
                $dados = array(
                    'id_pessoa' => $id_pessoa,
                    'email' => $contato_email,
                    'id_email_enviar' => $insertID
                );
                
                $insertID2 = DBCreate('', 'tb_email_enviar_pessoa', $dados, true);
                registraLog('Inserção de pessoa do e-mail enviado.','i','tb_email_enviar_pessoa',$insertID2,"id_pessoa: $id_pessoa | email: $contato_email | id_email_enviar: $insertID");
            }
		}
		if($flag == 0){

			$cont++;

			if(!$erros){
				$erros = '#'.$conteudo;
			}else{
				$erros .= ', #'.$conteudo;
			}

		}

	}

    if($cont == 0){
    	$alert = ('E-mail(s) enviados com sucesso!','s');
    }else{
    	$alert = ('Não foi possível enviar e-mail(s) para '.$cont.' '.$tipo_pessoa.'!<br>Ids das contas: '.$erros,'w');
    }
    header("location: /api/iframe?token=$request->token&view=email-enviar-busca");
    exit;
}

?>