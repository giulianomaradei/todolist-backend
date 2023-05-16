<?php
require_once(__DIR__."/../class/System.php");
$dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_cidade c ON b.id_cidade = c.id_cidade INNER JOIN tb_estado d ON c.id_estado = d.id_estado WHERE a.id_usuario = '".$_SESSION['id_usuario']."'","a.id_usuario, b.*, c.nome AS 'nome_cidade', d.sigla");
?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Meus dados:</h3>
                <h3 class="panel-title text-right pull-right" style="margin-top: 2px;"><?=$email_usuario?></h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Nome: </strong><span><?=$dados_usuario[0]['nome']?></span>
                    </div>
                    <div class="col-md-6">
                        <strong>CPF: </strong><span><?=formataCampo('cpf_cnpj',$dados_usuario[0]['cpf_cnpj'])?></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                      <strong>Nascimento: </strong><span><?=converteData($dados_usuario[0]['data_nascimento'])?></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Fone(1): </strong><span class="phone"><?=$dados_usuario[0]['fone1']?></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <strong>E-Mail(1): </strong><span><?=$dados_usuario[0]['email1']?></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Nº: </strong><span><?=$dados_usuario[0]['numero']?></span>
                    </div>     
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Logradouro: </strong><span><?=$dados_usuario[0]['logradouro']?></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Complemento: </strong><span><?=$dados_usuario[0]['complemento']?></span>
                    </div>     
                </div>                
                <div class="row">
                    <div class="col-md-6">
                        <strong>Bairro: </strong><span><?=$dados_usuario[0]['bairro']?></span>
                    </div>        
                    <div class="col-md-6">
                        <strong>Cidade: </strong><span><?=$dados_usuario[0]['nome_cidade'].' - '.$dados_usuario[0]['sigla']?></span>
                    </div>    
                </div> 
                <div class="row">                    
                    <div class="col-md-6">
                        <strong>CEP: </strong><span class="cep"><?=$dados_usuario[0]['cep']?></span>
                    </div>
           
                </div> 
                <hr>
                <div class="panel panel-default" style="margin:0;">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Vínculos:</h3>
                    </div>
                    <div class="panel-body">                        
                        <?php
                            $dados_vinculos = DBRead('','tb_vinculo_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa_filho = b.id_pessoa WHERE a.id_pessoa_pai = '".$dados_usuario[0]['id_pessoa']."' AND b.status = '1'","a.id_vinculo_pessoa, b.*");
                            if($dados_vinculos){
                                foreach ($dados_vinculos as $chave => $conteudo_vinculo) {
                                    $tipo_vinculo = '';                                    
                                    $dados_tipo_vinculo = DBRead('','tb_vinculo_tipo_pessoa a',"INNER JOIN tb_vinculo_tipo b ON a.id_vinculo_tipo = b.id_vinculo_tipo WHERE a.id_vinculo_pessoa = '".$conteudo_vinculo['id_vinculo_pessoa']."' ORDER BY b.nome");
                                    if($dados_tipo_vinculo){
                                        foreach($dados_tipo_vinculo as $conteudo){
                                            $tipo_vinculo .= $conteudo['nome']." | ";
                                        }
                                        $tipo_vinculo = substr($tipo_vinculo, 0, -3);
                                    }
                                    echo '
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Nome: </strong><span>'.$conteudo_vinculo['nome'].'</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Vínculo: </strong><span>'.$tipo_vinculo.'</span>
                                        </div>
                                    </div>   
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Fone(1): </strong><span class="phone">'.$conteudo_vinculo['fone1'].'</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>E-Mail(1): </strong><span>'.$conteudo_vinculo['email1'].'</span>
                                        </div>
                                    </div>    
                                    ';
                                    if ($chave != array_key_last($dados_vinculos)){
                                        echo '<hr>';
                                    }                                        
                                }
                            }else{
                                echo "<p class='alert alert-warning' style='text-align: center'>Não foram encontrados registros!</p>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>