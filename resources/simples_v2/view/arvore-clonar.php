<?php
require_once(__DIR__."/../class/System.php");


    $tituloPainel = 'Clonar';
    $operacao = 'clonar_arvore';
    $id = (int)$_GET['clonar'];   

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> Árvore</h3>
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=Pesquisa.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } 

                    $dados = DBRead('', 'tb_contrato_plano_pessoa b',"INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_plano d ON b.id_plano = d.id_plano WHERE b.id_contrato_plano_pessoa = '".$id."' GROUP BY b.id_contrato_plano_pessoa ORDER BY c.nome ASC","c.nome, d.nome AS nome_servico, d.cod_servico, b.id_contrato_plano_pessoa, b.nome_contrato");

                    $id_contrato_plano_pessoa_dados = $dados[0]['id_contrato_plano_pessoa'];
                    $nome_pessoa_dados = $dados[0]['nome'];
                    $plano_dados = $dados[0]['plano'];
                    $servico_dados = getNomeServico($dados[0]['cod_servico']);

                    if($dados[0]['nome_contrato']){
                        $nome_pessoa_dados = $nome_pessoa_dados.' ('.$dados[0]['nome_contrato'].')';
                    }
                    ?>

                </div>
                <form method="post" action="/api/ajax?class=Arvore.php" style="margin-bottom: 0;">
                    <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Contrato:</label>
                                    <input type="text" autofocus class="form-control input-sm" value="<?= $nome_pessoa_dados. ' - ' .$servico_dados. ' - ' .$plano_dados.' ('.$id_contrato_plano_pessoa_dados.")"; ?>" autocomplete="off" required readonly>
                                    <input type="hidden" required name="id_contrato_plano_pessoa" id="id_contrato_plano_pessoa" value="<?=$id;?>" />
                                </div>
                            </div>
                          </div>
                        <div class="row">
                            <div class="col-md-12">
                                
                                <div class="form-group">
                                    <label>Clonar árvore de:</label>
                                        <select class="form-control input-sm" name="clone" id="clone">
                                            <?php

                                            $arvores = DBRead('', 'tb_arvore_contrato a',"INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_plano d ON b.id_plano = d.id_plano WHERE a.id_contrato_plano_pessoa AND b.status = 1 GROUP BY a.id_contrato_plano_pessoa ORDER BY c.nome ASC","c.nome, d.nome AS plano, d.cod_servico, a.id_contrato_plano_pessoa, b.nome_contrato");

                                            
                                                foreach($arvores as $arvore){

                                                    $id_contrato_plano_pessoa = $arvore['id_contrato_plano_pessoa'];
                                                    $nome_pessoa = $arvore['nome'];
                                                    $plano = $arvore['plano'];
                                                    $servico = getNomeServico($arvore['cod_servico']);
                                                 
                                                    if($arvore['nome_contrato']){
                                                        $nome_pessoa = $nome_pessoa.' ('.$arvore['nome_contrato'].')';
                                                    }
                                                    echo "<option value='".$arvore['id_contrato_plano_pessoa']."'>".$nome_pessoa. ' - ' .$servico. ' - ' .$plano.' ('.$id_contrato_plano_pessoa.")</option>";
                                                }
                                            ?>
                                        </select>
                                </div>
                            </div> 
                        </div>

                        
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>"/>
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Clonar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>  
<script>
    $(document).on('submit', 'form', function () {        
        modalAguarde();
    });
</script>