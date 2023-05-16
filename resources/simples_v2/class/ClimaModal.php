<?php
require_once(__DIR__."/System.php");
    $id_contrato_plano_pessoa = (isset($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
    $localizacao_contrato = DBRead('', 'tb_localizacao_contrato a', "INNER JOIN tb_localizacao b ON a.id_localizacao_contrato = b.id_localizacao_contrato INNER JOIN tb_cidade c ON b.id_cidade = c.id_cidade INNER JOIN tb_estado d ON b.id_estado = d.id_estado WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.id_cidade, c.nome AS nome_cidade, d.nome AS estado, d.sigla");
    if($localizacao_contrato && $localizacao_contrato[0]['id_cidade'] != '9999999'){
        foreach($localizacao_contrato as $conteudo){
            $cidade_tempo = removeAcentos($conteudo['nome_cidade']);
            $xml = simplexml_load_file("http://servicos.cptec.inpe.br/XML/listaCidades?city=".$cidade_tempo."");
            foreach($xml as $item){
                if(utf8_decode($item->uf) == $conteudo['sigla']){
                    if($item->nome == $conteudo['nome_cidade']){
                        echo '<iframe allowtransparency="true" marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="no" src="https://www.cptec.inpe.br/widget/widget.php?p='.utf8_decode($item->id).'&w=h&c=909090&f=ffffff" height="200px" width="215px"></iframe><noscript>Previs&atilde;o de <a href="http://www.cptec.inpe.br/cidades/tempo/'.utf8_decode($item->id).'">'.utf8_decode($item->nome).'/'.utf8_decode($item->uf).'</a> oferecido por <a href="http://www.cptec.inpe.br">CPTEC/INPE</a></noscript>';
                    }
                }
            }
        }
    }else if(!$localizacao_contrato || $localizacao_contrato[0]['id_cidade'] == '9999999'){
        echo "<div class='alert alert-warning text-center' role='alert'>Localização não configurada no quadro informativo!</div>";
    }
?>