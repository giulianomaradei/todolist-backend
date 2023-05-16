<?php
require_once(__DIR__."/System.php");


$candidatos = (!empty($_POST['candidatos'])) ? $_POST['candidatos'] : '';

if (!empty($_POST['enviar_email'])) {

    $id_vaga = (int) $_POST['enviar_email'];

    buscar_usuarios($id_vaga, $id_cargo, $id_setor, $candidatos);

} else {
    header("location: ../adm.php");
    exit;
}

function buscar_usuarios($id_vaga, $id_cargo, $id_setor, $candidatos) 
{
    if ($id_vaga != '' && $candidatos !='') {

        $dados_vaga = DBRead('', 'tb_vaga a', "INNER JOIN tb_cargo b ON a.id_cargo = b.id_cargo INNER JOIN tb_setor c ON c.id_setor = b.id_setor WHERE id_vaga = $id_vaga ", 'a.*, b.descricao as descricao_cargo, c.id_setor, c.descricao as descricao_setor, c.id_setor');

        $descricao_vaga = $dados_vaga[0]['descricao'];
        $data_fim = $dados_vaga[0]['data_fim'];
        $id_cargo = $dados_vaga[0]['id_cargo'];
        $id_setor = $dados_vaga[0]['id_setor'];
        $descricao_setor = $dados_vaga[0]['descricao_setor'];
        $descricao_cargo = $dados_vaga[0]['descricao_cargo'];
        $tipo = $dados_vaga[0]['tipo'];

        if ($tipo == 1) {
            $formato = 'Efetivo';

        } else if ($tipo == 2) {
            $formato = 'Estágio';
        
        } else if ($tipo == 3) {
            $formato = 'Jovem aprendiz';
            
        } else if ($tipo == 4) {
            $formato = 'PCD';

        } else if ($tipo == 5) {
            $formato = 'Terceirizado';

        } else if ($tipo == 4) {
            $formato = 'Estágio PCD';
        }

        if ($candidatos == 2) {
            $filtro = "WHERE b.id_setor = $id_setor AND a.funcionario != 1";

        } else if ($candidatos == 3) {
            $filtro = "WHERE b.id_cargo = $id_cargo AND a.funcionario != 1";

        } else {
            $filtro = "WHERE a.funcionario != 1";
        }

        //filtro do usuario para teste
        //$filtro_usuario = "AND a.id_pessoa = 3740 OR a.id_pessoa = 3741 OR a.id_pessoa = 1139";

        //filtro do usuario para teste
        $usuarios = DBRead('', 'tb_pessoa a', "INNER JOIN tb_pessoa_rh_area_interesse b ON a.id_pessoa = b.id_pessoa $filtro", 'distinct a.id_pessoa, a.nome, a.email1, b.id_setor, b.id_cargo');//

        $emails = array();
        foreach ($usuarios as $conteudo) {
            if( !in_array($conteudo['email1'], $emails) ) {
                array_push($emails, $conteudo['email1']);
            }
        }

        $assunto = "Oportunidade para o setor $descricao_setor";

        $mensagem = '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border: none; background-color: #0A122A; border-top-left-radius: 6px; border-top-right-radius: 6px;">
                    <tr style="border: none;">
                        <td width="260" valign="top" style="border: none;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border: none;">
                                <tr>
                                    <td>
                                        <a href="https://www.bellunotec.com.br" target="_blank"><img src="https://rh.bellun.company/inc/keen/theme/classic/assets/media/logos/logobranco-1.png" style="padding: 10px 10px 10px 10px;"></a>
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

                            <span style="font-family: Roboto-Regular,Helvetica,Arial,sans-serif; font-size: 15px;">Oportunidade para o setor: <strong>'.$descricao_setor.'</strong></span><br>

                            <span style="font-family: Roboto-Regular,Helvetica,Arial,sans-serif; font-size: 15px;">Cargo: <strong>'.$descricao_cargo.'</strong></span><br>

                            <span style="font-family: Roboto-Regular,Helvetica,Arial,sans-serif; font-size: 15px;">Formato: <strong>'.$formato.'</strong></span><br>

                            <span style="font-family: Roboto-Regular,Helvetica,Arial,sans-serif; font-size: 15px;">Manifestar interesse até <strong>'.converteData($data_fim).'</strong></span>

                            <div style="font-family: Roboto-Regular,Helvetica,Arial,sans-serif; font-size: 14px; color: rgba(0,0,0,0.87); line-height: 20px; padding-top: 20px;">
                                <table style="font-size: 14px; letter-spacing: 0.2; line-height: 20px; text-align: center">
                                    <tbody>
                                        <tr>
                                            <td style="padding-bottom: 24px; text-align: start">'.nl2br($descricao_vaga).'
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="https://rh.belluno.company/vaga-descricao.php?vaga='.$id_vaga.'.php" style="font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; line-height: 16px; color: #ffffff; font-weight: 400; text-decoration: none; font-size: 13px; display: inline-block; padding: 6px 24px; background-color: #179c8e; border-radius: 5px; min-width: 90px" target="_blank" rel="noreferrer">Visualizar vaga
                                                </a>
                                            </td>
                                        </tr>
                                        <tr style="color: rgba(0, 0, 0, 0.54); font-size: 12px; line-height: 150%; text-align: center">
                                            <td style="padding-top: 12px">Também pode acessar diretamente pelo link:<br><span class="adgl" style="color: rgba(0, 0, 0, 0.87); text-decoration: inherit">https://rh.belluno.company/vaga-descricao.php?vaga='.$id_vaga.'.php</span>
                                            </td>
                                        </tr>
                                        <tr style="color: rgba(0, 0, 0, 0.54); font-size: 12px; line-height: 150%; text-align: center">
                                            <td style="padding-top: 12px; font-size: 15;" ><b>Este é um e-mail automático, não responda!</b>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div style="text-align: center">
                            <div style="font-family: Roboto-Regular,Helvetica,Arial,sans-serif; color: rgba(0,0,0,0.54); font-size: 11px; line-height: 18px; padding-top: 12px; text-align: center">
                                <div>
                                </div>
                                <div style="direction: ltr">© 2019 Belluno Tecnologia, <span style="font-family: Roboto-Regular,Helvetica,Arial,sans-serif; color: rgba(0,0,0,0.54); font-size: 11px; line-height: 18px; padding-top: 12px; text-align: center">Caçapava do Sul, Rio Grande do Sul, Brasil</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td width="8" style="width: 8px"></td>
                </tr>';   

        foreach($emails as $destino) {

            envia_email($assunto, $mensagem, $destino, 'rh');
        }

        $dados = array(
            'divulgado' => 1,
        );

        DBUpdate('', 'tb_vaga', $dados, "id_vaga = $id_vaga");
        registraLog('Alteracao de vaga.', 'a', 'tb_vaga', $id_vaga, "divulgado: 1");

        $alert = ('Vaga divulgada com sucesso!', 's');
        header("location: /api/iframe?token=$request->token&view=vaga-busca");

    } else {
        $alert = ('Não foi possível enviar os emails!', 'd');
        header("location: /api/iframe?token=$request->token&view=vaga-email-form&id_vaga=$id_vaga");
    }

}