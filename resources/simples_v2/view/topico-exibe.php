<style>
    .conteudo-editor img{
        max-width: 100% !important;
        max-height: 100% !important;
        height: 100% !important;
    }
</style>
<?php
require_once(__DIR__."/../class/System.php");


    $id = (int)$_GET['id'];
    $dados = DBRead('', 'tb_topico', "WHERE id_topico = '$id'");
    $titulo = $dados[0]['titulo'];
    $conteudo = $dados[0]['conteudo'];
    $id_categoria = $dados[0]['id_categoria'];
    $permissao_comentario = $dados[0]['permissao_comentario'];

    $dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$dados[0]['id_usuario']."'");

    $autor = $dados_usuario[0]['nome'];
    $data_criacao = $dados[0]['data_criacao'];


    $visualizado = DBRead('', 'tb_topico_visualizado', "WHERE id_topico = '$id' AND id_usuario = '".$_SESSION['id_usuario']."'");
    $id_topico_visualizado = $visualizado[0]['id_topico_visualizado'];
    if(!$visualizado){
        $dados_visualizado = array(
            'id_topico' => $id,
            'data_visualizado' => getDataHora(),
            'id_usuario' => $_SESSION['id_usuario']
        );
        $id_topico_visualizado = DBCreate('', 'tb_topico_visualizado', $dados_visualizado, true);
    }

    $dados_like = DBRead('', 'tb_likes', "WHERE id_topico = '$id'", "COUNT(*) AS 'total'");
    $likes = $dados_like[0]['total'];

    $dados_topico_perfil = DBRead('', 'tb_perfil_topico', "WHERE id_topico = '$id' AND id_perfil_sistema = '$perfil_usuario'");

    if(!$dados_topico_perfil && $_SESSION['id_usuario'] != $dados[0]['id_usuario']){
        echo '<div class="alert alert-danger text-center"><strong>Você não possui permisssão para visualizar este tópico!</strong></div>';
        exit;
    }

    $dados_like = DBRead('', 'tb_likes', "WHERE id_topico = '$id' AND id_usuario = '".$_SESSION['id_usuario']."'");
    if($dados_like){
        $btn_like = '<button class="btn btn-xs btn-success like" dt-id="'.$id.'"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Curtiu</button>';
    }else{
        $btn_like = '<button class="btn btn-xs btn-default like" dt-id="'.$id.'"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Curtir</button>';
    }

?>
<script src="inc/ckeditor/ckeditor.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $titulo ?></h3>
                    <?php
                    if($dados_usuario[0]['id_usuario'] == $_SESSION['id_usuario']){
                        echo "<div class=\"panel-title text-right pull-right\">";
                        $dados_lido = DBRead('', 'tb_topico_visualizado', "WHERE data_lido IS NULL AND id_usuario = '".$_SESSION['id_usuario']."' AND id_topico = '$id'");
                        if($dados_lido){
                            $btn_lido = '<button class="btn btn-xs btn-info pull-right" id="lido" dt-lido="'.$id_topico_visualizado.'"><i class="fa fa-eye" aria-hidden="true"></i> Lido</button>&nbsp;&nbsp;&nbsp;';
                        }
                        echo $btn_lido;
<<<<<<< HEAD
                        echo "<a class=\"btn btn-xs btn-info\" href='#' data-toggle=\"modal\" data-target=\".modal_visualizado\" style='color:#fff;' title='Visualizações'><i class='fa fa-eye'></i> Visualizações</a>&nbsp;&nbsp;&nbsp;<a class=\"a_modalAguarde btn btn-xs btn-primary\" href='/api/iframe?token=<?php echo $request->token ?>&view=topico-form&alterar=$id' style='color:#fff;' title='Alterar'><i class='fa fa-pencil'></i> Alterar</a>&nbsp;&nbsp;&nbsp;</a><a href=\"class/Topico.php?excluir= $id\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a>";
=======
                        echo "<a class=\"btn btn-xs btn-info\" href='#' data-toggle=\"modal\" data-target=\".modal_visualizado\" style='color:#fff;' title='Visualizações'><i class='fa fa-eye'></i> Visualizações</a>&nbsp;&nbsp;&nbsp;<a class=\"a_modalAguarde btn btn-xs btn-primary\" href=' /api/iframe?token=".$request->token."&view=topico-form&alterar=$id' style='color:#fff;' title='Alterar'><i class='fa fa-pencil'></i> Alterar</a>&nbsp;&nbsp;&nbsp;</a><a href=\"/api/ajax?class=Topico.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a>";
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b

                        echo "</div>";

                    }else{
                        $dados_lido = DBRead('', 'tb_topico_visualizado', "WHERE data_lido IS NULL AND id_usuario = '".$_SESSION['id_usuario']."' AND id_topico = '$id'");
                        echo "<div class=\"panel-title text-right pull-right\">";
                        if($dados_lido){
                            $btn_lido = '<button class="btn btn-xs btn-info pull-right" id="lido" dt-lido="'.$id_topico_visualizado.'"><i class="fa fa-check" aria-hidden="true"></i> Lido</button>&nbsp;&nbsp;&nbsp;';
                        }
                        echo $btn_lido;
                        echo "</div>";
                    }
                    ?>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p title=""><strong><?= $autor ?></strong></p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-right"><strong><?= converteDataHora($data_criacao) ?></strong></p>
                        </div>
                    </div>
                    <hr style='margin-top: 0'>
                    <div class="row">
                        <div class="col-md-12">
                            <div class='conteudo-editor'>
                                <?= $conteudo ?>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12 text-right"><a href='#' data-toggle="modal" data-target=".modal_likes">
                            <span class="likes"><?= $likes ?></span> Curtidas</a>
                            <?= $btn_like ?>
                            
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <?php if($permissao_comentario == 1){ ?>
                        <form method="post" action="/api/ajax?class=Topico.php" id="topico_form" style="margin-bottom: 0;">
		                    <input type="hidden" name="token" value="<?php echo $request->token ?>">
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Comentário:</label>
                                        <textarea cols="12" name="conteudo" id="comentario" class="form-control ckeditor"></textarea>
                                    </div>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <input type="hidden" value="<?= $id_categoria ?>" name="id_categoria">
                                    <input type="hidden" value="<?= $id ?>" name="id_pai">
                                    <input type="hidden" id="operacao" value="<?= $id ?>" name="inserirComentario">
                                    <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                                </div>
                            </div>
                            
                        </form>

                    <?php } ?>
                    <hr>
                        
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="list-group" style="margin-top: 10px;">
                                <?php
                                $dados_comentarios = DBRead('', 'tb_topico', "WHERE id_pai = '$id' AND status = 1 ORDER BY data_criacao ASC");
                                if($dados_comentarios){
                                    foreach($dados_comentarios as $conteudoComentario){

                                        $visualizado_comentario = DBRead('', 'tb_topico_visualizado', "WHERE id_topico = '".$conteudoComentario['id_topico']."' AND id_usuario = '".$_SESSION['id_usuario']."'");
                                        if(!$visualizado_comentario){
                                            $dados_visualizado_comentario = array(
                                                'id_topico' => $conteudoComentario['id_topico'],
                                                'data_visualizado' => getDataHora(),
                                                'id_usuario' => $_SESSION['id_usuario']
                                            );
                                            DBCreate('', 'tb_topico_visualizado', $dados_visualizado_comentario);
                                        }

                                        $dados_usuario_comentario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudoComentario['id_usuario']."'");

                                        $dados_like = DBRead('', 'tb_likes', "WHERE id_topico = '".$conteudoComentario['id_topico']."'", "COUNT(*) AS 'total'");
                                        $likes = $dados_like[0]['total'];
                                        $dados_like = DBRead('', 'tb_likes', "WHERE id_topico = '".$conteudoComentario['id_topico']."' AND id_usuario = '".$_SESSION['id_usuario']."'");
                                        if($dados_like){
                                            $btn_like = '<button class="btn btn-xs btn-success like"  dt-id="'.$conteudoComentario['id_topico'].'"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Curtiu</button>';
                                        }else{
                                            $btn_like = '<button class="btn btn-xs btn-default like"  dt-id="'.$conteudoComentario['id_topico'].'"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Curtir</button>';
                                        }


                                        echo "<li class='list-group-item clearfix'>";
                                            echo "<div class='row'>";
                                                echo "<div class='col-md-6'>";
                                                    echo "<p><strong>".$dados_usuario_comentario[0]['nome']."</strong></p>";
                                                echo "</div>";
                                                echo "<div class='col-md-6'>";
                                                    echo " <p class='text-right'><strong>".converteDataHora($conteudoComentario['data_criacao'])."</strong>";
                                                    if($conteudoComentario['id_usuario'] == $_SESSION['id_usuario']){
                                                        echo "&nbsp;&nbsp;<a href=\"/api/ajax?class=Topico.php?excluirComentario=".$conteudoComentario['id_topico']."&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i></button></a></p>";
                                                    }
                                                echo "</div>";
                                            echo "</div>";
                                            echo "<hr style='margin-top: 0'>";
                                            echo "<div class='row'>";
                                                echo "<div class='col-md-12'>";
                                                echo "<div class='conteudo-editor'>";
                                                    echo $conteudoComentario['conteudo'];
                                                echo "</div>";
                                                echo "</div>";
                                            echo "</div>";
                                            echo "<hr>";
                                            echo "<div class='row'>";
                                                echo "<div class='col-md-12 text-right'><a href='#' data-toggle=\"modal\" data-target=\".modal_likes".$conteudoComentario['id_topico']."\"><span class='likes'>".$likes."</span> Curtidas</a> ".$btn_like."</div>";
                                            echo "</div>";
                                        echo "</li>";

                                        echo '
                                            <div class="modal fade modal_likes'.$conteudoComentario['id_topico'].'" role="dialog" tabindex="-1" aria-labelledby="modal_likes'.$conteudoComentario['id_topico'].'" style="display: none;">
                                                <div class="modal-dialog modal-sm" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                            <h4 class="modal-title" id="modal_likes'.$conteudoComentario['id_topico'].'">Curtidas</h4>
                                                        </div> 
                                                        <div class="modal-body">
                                        ';
                                        $dados_likes = DBRead('', 'tb_likes a', "INNER JOIN tb_usuario b on a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE id_topico = '".$conteudoComentario['id_topico']."'  ORDER BY c.nome ASC", "c.nome");
                                        if($dados_likes){
                                            echo "<div class='text-center' style='max-height:200px; overflow-y: auto'>";
                                            foreach ($dados_likes as $conteudo_likes) {      
                                                echo $conteudo_likes['nome'].'<br>';
                                            }
                                            echo "</div>";
                                        }else{
                                            echo "<div class='text-center'>Ninguém curtiu ainda!</div>";
                                        }
                                        echo '        
                                                        </div> 
                                                    </div> 
                                                </div> 
                                            </div>
                                         ';
                                        
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
             </div>
        </div>
    </div>
</div>
<?php
if($dados_usuario[0]['id_usuario'] == $_SESSION['id_usuario']){
 echo '
    <div class="modal fade modal_visualizado" role="dialog" tabindex="-1" aria-labelledby="modal_visualizado" style="display: none;">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="modal_visualizado">Visualizações</h4>
                </div> 
                <div class="modal-body">
';
$dados_topico_visualizado = DBRead('','tb_topico_visualizado a'," INNER JOIN tb_usuario b on a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_topico = '$id' ORDER BY c.nome ASC", 'c.nome, a.data_lido');
if($dados_topico_visualizado){
    echo "<div class='text-center' style='max-height:200px; overflow-y: auto'>";
    foreach ($dados_topico_visualizado as $conteudo_topico_visualizado) {
        if($conteudo_topico_visualizado['data_lido']){
            echo $conteudo_topico_visualizado['nome'].'<br>';
        }else{
            echo $conteudo_topico_visualizado['nome'].' (não marcou lido)<br>';
        }        
    }
    echo "</div>";
}else{
    echo "<div class='text-center'>Ninguém visualizou ainda!</div>";
}
echo '        
                </div> 
            </div> 
        </div> 
    </div>
 ';
}
echo '
    <div class="modal fade modal_likes" role="dialog" tabindex="-1" aria-labelledby="modal_likes" style="display: none;">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="modal_likes">Curtidas</h4>
                </div> 
                <div class="modal-body">
';
$dados_likes = DBRead('','tb_likes a'," INNER JOIN tb_usuario b on a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_topico = '$id' ORDER BY c.nome ASC", 'c.nome');
if($dados_likes){
    echo "<div class='text-center' style='max-height:200px; overflow-y: auto'>";
    foreach ($dados_likes as $conteudo_likes) {      
        echo $conteudo_likes['nome'].'<br>';
    }
    echo "</div>";
}else{
    echo "<div class='text-center'>Ninguém curtiu ainda!</div>";
}
echo '        
                </div> 
            </div> 
        </div> 
    </div>
 ';
?>   
<script>
    
    var permissao_comentario = "<?=$permissao_comentario?>";
    if(permissao_comentario == 1){
        CKEDITOR.replace('comentario', {
            height: 220
        });
    }    

    $(document).on('submit', '#topico_form', function(){
        var comentario = $("#comentario").val();
        if(!comentario){
            alert("Deve haver um comentário válido!");
            return false;
        }
        modalAguarde();
    });

    $(document).on('click', '.like', function(){
        var obj = $(this);
        $.ajax({
            type: "GET",
            url: "/api/ajax?class=Like.php",
            dataType: "json",
            data: {
                id_topico: obj.attr('dt-id'),
                token: '<?= $request->token ?>'
            },
            success: function(data){
                if(data['like'] == 1){
                    obj.removeClass('btn-default').addClass('btn-success').html('<i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Curtiu');
                }else{
                    obj.removeClass('btn-success').addClass('btn-default').html('<i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Curtir');
                }
                obj.parent().find(".likes").html(data['total']);
            }
        });
    });

    $(document).on('click', '#lido', function(){
        var obj = $(this);
        $.ajax({
            type: "GET",
            url: "/api/ajax?class=Lido.php",
            dataType: "json",
            data: {
                id_topico: obj.attr('dt-lido'),
                token: '<?= $request->token ?>'
            },
            success: function(data){
                if(data['lido'] == 1){
                    obj.remove();
                    $(location).attr('href', '/api/iframe?token=<?php echo $request->token ?>&view=home');
                }
            }
        });
    });
</script>