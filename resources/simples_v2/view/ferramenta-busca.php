<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Ferramentas:</h3>
                    <div class="panel-title text-right pull-right"><a href="/api/iframe?token=<?php echo $request->token ?>&view=ferramenta-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Novo</button></a></div>
                </div>
                <div class="panel-body">
                    <?php
                        $dados_grupos = DBRead('','tb_grupo_ferramenta',"ORDER BY nome ASC");
                        if($dados_grupos){
                            foreach ($dados_grupos as $conteudo_grupo) {
                                echo '
                                <table class="table table-hover" style="margin-bottom:0;">
                                    <thead>
                                        <tr>
                                            <th class="col-md-1"></th>		
                                            <th class="col-md-10 text-center" style="font-size:20px;">'.$conteudo_grupo['nome'].'</th>		
                                            <th class="col-md-1 text-right"><a href="/api/iframe?token='.$request->token.'&view=ferramenta-form&alterar='.$conteudo_grupo['id_grupo_ferramenta'].'" title="Alterar"<i class="fa fa-pencil"></i></a></th>		
                                        </tr>
                                    </thead>
                                    <tbody>
                                ';

                                $dados_itens = DBRead('','tb_grupo_ferramenta_item',"WHERE id_grupo_ferramenta = '".$conteudo_grupo['id_grupo_ferramenta']."' ORDER BY nome ASC");
                                if($dados_itens){
                                    foreach ($dados_itens as $conteudo_item) {

                                        if($conteudo_item['observacao']){
                                            $obs = ' <a tabindex="0" class="a-obs" role="button" style="cursor:pointer;" data-toggle="popover" data-html="true" data-trigger="click" title="Observação:" data-content="'.nl2br($conteudo_item['observacao']).'"><i class="fa fa-question-circle"></i></a>';
                                        }else{
                                            $obs = '';
                                        }

                                        if($conteudo_item['link']){
                                            $ferramenta = '<a href="'.$conteudo_item['link'].'" target="_blank">'.$conteudo_item['nome'].'</a>'.$obs; 
                                        }else{
                                            $ferramenta = $conteudo_item['nome'].$obs;
                                        }

                                        echo '
                                        <tr>
                                            <td class="text-center" colspan="3">'.$ferramenta.'</td>
                                        </tr>
                                        ';
                                        
                                    }
                                }
                                echo '
                                    </tbody>
                                </table>
                                ';
                            }
                        }else{
                            echo '<div class="alert alert-info text-center">Nenhum grupo cadastrado!</div>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('[data-toggle="popover"]').popover();
    })    
</script>