<?php
require_once "System.php";


require('../inc/e-notas/eNotasGW.php');

use eNotasGW\Api\Exceptions as Exceptions;

if (isset($_GET['sincronizar'])) {

    $id = (int)$_GET['sincronizar'];
    sincronizar($id);

}

function sincronizar($id_nfs){
    $dados_nota_consulta = DBRead('', 'tb_nfs', "WHERE id_nfs = '$id_nfs'");

    if($dados_nota_consulta && ($dados_nota_consulta[0]['status'] == 'inserindo' || $dados_nota_consulta[0]['status'] == 'cancelando' || $dados_nota_consulta[0]['status'] == 'cancelamentonegado')){
        eNotasGW::configure(array(
            'apiKey' => getDadosApiNfs('apiKey')
        ));
        
        $empresaId = getDadosApiNfs('empresaId');
        
        try
        {
            
            $dados_nota = eNotasGW::$NFeApi->consultarPorIdExterno($empresaId, $id_nfs);
        
            $dados_nota_bd = array(
                'tipo' => $dados_nota->tipo,
                'id_empresa' => strtolower($empresaId),
                'id_nfs_enotas' => $dados_nota->id,
                'status' => strtolower($dados_nota->status),
                'motivo_status' => $dados_nota->motivoStatus,
                'link_pdf' => $dados_nota->linkDownloadPDF,
                'link_xml' => $dados_nota->linkDownloadXML,
                'numero' => $dados_nota->numero,
                'codigo_verificacao' => $dados_nota->codigoVerificacao,
                'chave_acesso' => $dados_nota->chaveAcesso,
                'numero_rps' => $dados_nota->numeroRps,
                'serie_rps' => $dados_nota->serieRps,
                'data_competencia' => $dados_nota->dataCompetenciaRps,
                'data_autorizacao' => $dados_nota->dataAutorizacao,
            );       
            
        
            if(array_diff($dados_nota_bd, $dados_nota_consulta[0]) && ($dados_nota_bd['status'] == 'autorizada' || $dados_nota_bd['status'] == 'negada' || $dados_nota_bd['status'] == 'cancelada')){
                DBUpdate('', 'tb_nfs',$dados_nota_bd,"id_nfs = '$id_nfs'");	                
                $alert = ('NFS-e sincronizada com sucesso!','s');
            }else{
                $alert = ('NFS-e sincronizada com sucesso, porém não houveram alterações! Status no enotas: '.$dados_nota->status,'w');
            }

            header("location: /api/iframe?token=$request->token&view=nfs-visualizar&visualizar=$id_nfs");
            exit;            
        
        }catch(Exceptions\invalidApiKeyException $ex) {
            $alert = ('Erro de autenticação: </br></br>'.$ex->getMessage().'','w');
            
        }catch(Exceptions\unauthorizedException $ex) {
            $alert = ('Acesso negado: </br></br>'.$ex->getMessage().'','w');
        
        }catch(Exceptions\apiException $ex) {
            $alert = ('Erro de validação: </></>'.$ex->getMessage().'','w');
        
        }catch(Exceptions\requestException $ex) {
            $alert = ('Erro na requisição web: </br></br>Requested url: ' . $ex->requestedUrl.'</br>Response Code: ' . $ex->getCode().'</br>Message: ' . $ex->getMessage().'</br>Response Body: ' . $ex->responseBody.'','w');
        }
    }else{
        $alert = ('NFS-e não localizada ou com Status diferente de "Inserindo" e "Cancelando"!','e');
        header("location: /api/iframe?token=$request->token&view=nfs-busca");
        exit;
    }

}

?>