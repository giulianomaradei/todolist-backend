<?php
namespace Integracao\Ixc;

class Parametros{

    private $host = '';
    private $token = '';
    private $selfSigned = false;
    private $id_contrato_plano_pessoa = 0;

    public function getHost(){
        return $this->host;
    }

    public function getToken(){
        return $this->token;
    }

    public function getSelfSigned(){
        return $this->selfSigned;
    }

    public function setParametros($id_contrato_plano_pessoa){

        $host = DBRead('', 'tb_integracao_contrato_parametro a', "INNER JOIN tb_integracao_contrato b ON a.id_integracao_contrato = b.id_integracao_contrato WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' GROUP BY b.host", "b.host");

        $token = DBRead('', 'tb_integracao_contrato_parametro a', "INNER JOIN tb_integracao_contrato b ON a.id_integracao_contrato = b.id_integracao_contrato INNER JOIN tb_integracao_parametro c ON b.id_integracao = c.id_integracao WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND a.id_integracao_parametro = (select id_integracao_parametro from tb_integracao_parametro where id_integracao = 1 and codigo = 'token') GROUP BY a.valor", "a.valor");

        $selfSigned = DBRead('', 'tb_integracao_contrato_parametro a', "INNER JOIN tb_integracao_contrato b ON a.id_integracao_contrato = b.id_integracao_contrato INNER JOIN tb_integracao_parametro c ON b.id_integracao = c.id_integracao WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND a.id_integracao_parametro = (select id_integracao_parametro from tb_integracao_parametro where id_integracao = 1 and codigo = 'selfSigned') GROUP BY a.valor", "a.valor");

        if($selfSigned[0]['valor'] == "sim"){
            $selfSigned = true;
        }else if($selfSigned[0]['valor'] == "nao"){
            $selfSigned = false;
        }

        $this->host = $host[0]['host'];
        $this->token = $token[0]['valor'];
        $this->selfSigned = $selfSigned;
    }
}
