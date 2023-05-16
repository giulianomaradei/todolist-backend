<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);

// Informações da query

$dados_perfil = DBRead('','tb_usuario', "WHERE id_usuario = '".$_SESSION['id_usuario']."' ", 'id_perfil_sistema');
$id_perfil = $dados_perfil[0]['id_perfil_sistema'];
$id_usuario = $_SESSION['id_usuario'];
$filtros_query = "WHERE (id_usuario = $id_usuario OR perfil = $id_perfil) AND nome_senha LIKE '%$letra%'";

// Maximo de registros por pagina
$maximo = 10;

// Limite de links(antes e depois da pagina atual) da paginação
$lim_links = 5;

// Declaração da pagina inicial
$pagina = $parametros['pagina'];
if($pagina == ''){
    $pagina = 1;
}   

// Conta os resultados no total da query
$dados = DBRead('', 'tb_senha',$filtros_query , "COUNT(*) AS 'num_registros'");
$total = $dados[0]['num_registros'];

// Calculando o registro inicial
$inicio = $maximo * ($pagina - 1);
if($inicio >= $total){
    $inicio = 0;
    $pagina = 1;
}


// INICIO DO CONTEÚDO
###################################################################################################################

$dados = DBRead('', 'tb_senha',$filtros_query." LIMIT $inicio,$maximo");
if (!$dados) { ?>
    <p class='alert alert-warning' style='text-align: center'>
    <?php if (!$letra) { ?>
        Não foram encontrados registros!
    <?php } else { ?>
        Nenhum resultado encontrado na busca por <strong>$letra</strong>
    <?php } ?>
    </p>
    <?php } else { ?>
    <div class='table-responsive'>
        <table class='table table-hover' style='font-size: 14px;'>
            <thead>
                <tr>
                    <th class="col-md-1 text-center">Tipo</th>
                    <th class="col-md-6">Sistema</th>
                    <th class="col-md-1 text-center">Link</th>
                    <th class="col-md-2 text-center">Usuario</th>
                    <th class="col-md-2 text-center">Senha</th>
                    <th class="col-md-1 text-center">Opções</th>
                </tr>
            </thead>
        <tbody>

        <?php foreach($dados as $conteudo){

            $id = $conteudo['id_senha'];
            $nome = $conteudo['nome_senha'];
            $usuario = $conteudo['usuario'];
            $senha = $conteudo['senha'];
            $senha = base64_decode($senha);
            $link = $conteudo['link'];
            $perfil = $conteudo['perfil'];
            $tipo_senha = $conteudo['tipo_senha'];
            $id_usuario_senha = $conteudo['id_usuario']; ?>

            <tr>
                <?php        
                    if($id_usuario_senha == $id_usuario || $perfil == $id_perfil){

                        if($id_usuario_senha == $id_usuario || $tipo_senha == '2'){  

                            if($tipo_senha == 1){ ?>
                                <td class="text-center"><i class="fas fa-user"></i></td>
                            <?php } else { ?>
                                <td class="text-center"><i class="fas fa-users"></i></td>
                            <?php } ?>

                                <td onclick='window.location='/api/iframe?token=<?php echo $request->token ?>&view=gerenciar-senhas-form&alterar='<?=$id?>' style='cursor: pointer'> <?=$nome?>

                            <?php if($link != ''){ ?>
                                <td class="text-center">
                                    <a target='_blank' href='<?=$link?>'><i class='fas fa-external-link-alt'></i></a>
                                </td>

                            <?php } else { ?>
                                <td class="text-center">
                                    <i class='far fa-times-circle'></i>
                                </td>
                            <?php } ?>

                            <td class="text-center find">
                                <button class='btn btn-outline-success btn-sm copiaUsuario'><i class='far fa-clone'></i> Copiar</button>
                                <input class="inputUsuario" type='hidden' id='usuario<?=$id?>' value='<?=$usuario?>'/>            
                            </td>
                            <td class="text-center">
                                <button class='btn btn-outline-success btn-sm copiaSenha'><i class='far fa-clone'></i> Copiar</button>
                                <input class="inputSenha" type='hidden' id='senha<?=$id?>' value='<?=$senha?>'/>
                            </td>
                            <td class="text-center">
                                <a href='/api/iframe?token=<?php echo $request->token ?>&view=gerenciar-senhas-form&alterar=<?=$id?>' title='Alterar'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="class/GerenciarSenhas.php?excluir=<?=$id?>" title='Excluir' <?php "onclick=\"if (!confirm('Excluir ".addslashes($nome)."?')) { return false; } else { modalAguarde(); }>" ?> <i class='fa fa-trash' style='color:#b92c28;'></i></a>
                            </td>
                        </tr>     
                <?php } 
                   } 
                }?>
        </tbody>
    </table>
</div> 
<?php }


// FIM DO CONTEUDO
###################################################################################################################

$menos = $pagina - 1;
$mais = $pagina + 1;
$pgs = ceil($total / $maximo);

// Inicio e fim dos links
$ini_links = ((($pagina - $lim_links) > 1) ? $pagina - $lim_links : 1);
$fim_links = ((($pagina+$lim_links) < $pgs) ? $pagina+$lim_links : $pgs);

if($pgs > 1 ) { ?>

    <nav style="text-align: center;">
        <ul class="pagination">

  <?php // Mostragem de pagina
        if($menos > 0) { ?>                                    
            <li><a href="#" class="troca_pag" atr-pagina="<?=$menos?>" aria-label="Previous"><span aria-hidden="true">&laquo; Anterior</span></a></li>
            <li><a href="#" class="troca_pag" atr-pagina="1">Pri.</a></li>
  <?php }else{ ?>
            <li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo; Anterior</span></a></li>
            <li class="disabled"><a href="#">Pri.</a></li>
  <?php }


// Listando as paginas
###################################################################################################################

    for($i = $ini_links; $i <= $fim_links; $i++) {

        if($i != $pagina) { ?>                                       
            <li><a href="#" class="troca_pag" atr-pagina="<?=$i?>"><?=$i?></a></li>

  <?php } else { ?>
            <li class="active"><a href="#"><?=$i?> <span class="sr-only">(current)</span></a></li>
  <?php }
    }

        if($mais <= $pgs) { ?>
            <li><a href="#" class="troca_pag" atr-pagina="<?=$pgs?>">Últ.</a></li>
            <li><a href="#" class="troca_pag" atr-pagina="<?=$mais?>" aria-label="Next"><span aria-hidden="true">Próximo &raquo;</span></a></li>

  <?php }else{ ?>
            <li class="disabled"><a href="#">Últ.</a></li>
            <li class="disabled"><a href="#" aria-label="Next"><span aria-hidden="true">Próximo &raquo;</span></a></a></li>
  <?php } ?>
        </ul>
    </nav>
<?php } ?>

