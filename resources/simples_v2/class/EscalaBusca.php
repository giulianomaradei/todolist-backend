<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['titulo']);
$id_usuario = $_SESSION['id_usuario'];

// Informações da query
$filtros_query  = "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE b.status != 2 AND a.status = 1 AND b.nome LIKE '%$letra%' AND a.id_perfil_sistema = '3' ORDER BY b.nome ASC";

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
$dados = DBRead('','tb_usuario a',$filtros_query,"COUNT(*) AS 'num_registros'");
$total = $dados[0]['num_registros'];

// Calculando o registro inicial
$inicio = $maximo * ($pagina - 1);
if($inicio >= $total){
    $inicio = 0;
    $pagina = 1;
}

$usuario = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_usuario."' AND a.status = '1' AND b.nome LIKE '%$letra%' AND (a.id_perfil_sistema = '3' OR a.id_perfil_sistema = '15') ORDER BY b.nome ASC","a.id_usuario, b.nome, a.*, b.*");   

if($usuario){

    $dados2 = DBRead('', 'tb_disponibilidade_escala',"WHERE id_usuario = '".$id_usuario."'");

                if($dados2){
                    $visualizar = "<a class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=exibe-escala&visualizar=$id_usuario' title='Visualizar'><i class='fa fa-eye'></i></a></td>";
                }else{
                    $visualizar = "Não cadastrado</td>";
                }

        $total = 1;
         echo "<div class='table-responsive'>";
            echo "<table class='table table-hover' style='font-size: 14px;'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th class=\"col-md-10\">Nome</th>";
          
            echo "<th class=\"col-md-2 text-center\">Opções</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

        
        echo "<tr>";    
        echo "<td>".$usuario[0]['nome']."</td>";

        $condicao = DBRead('', 'tb_perfil_sistema a', "INNER JOIN tb_pagina_sistema_perfil b ON a.id_perfil_sistema = b.id_perfil_sistema INNER JOIN tb_pagina_sistema c ON b.id_pagina_sistema = c.id_pagina_sistema WHERE c.nome_view = 'escala-editar' AND a.nome = 'Call Center - Atendente'");

                          
            if($condicao){
                 echo "<td class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=escala-editar&alterar=$id_usuario' title='Alterar'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                 if($dados2){
                    echo $visualizar;
                 }
                 
            }else{
                echo "<td class=\"text-center\">";

                echo $visualizar;
            }

      
        
        echo "</tr>";
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
}else{

###################################################################################
// INICIO DO CONTEÚDO
// 
    $dados = DBRead('', 'tb_usuario a',$filtros_query." LIMIT $inicio, $maximo","a.id_usuario, b.nome, a.*, b.*");
    if (!$dados) {
        echo "<p class='alert alert-warning' style='text-align: center'>";
        if (!$letra) {
            echo "Não foram encontrados registros!";
        } else {
            echo "Nenhum resultado encontrado na busca por \"<strong>$letra</strong>\"";
        }
        echo "</p>";
    } else {
        echo "<div class='table-responsive'>";
        echo "<table class='table table-hover' style='font-size: 14px;'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th class=\"col-md-10\">Nome</th>";
      
        echo "<th class=\"col-md-2 text-center\">Opções</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($dados as $atendente) {
          
            //############# Contatos realizados e diferença entre realizado e contratado
            $id = $atendente['id_usuario'];
            echo "<tr>";    
            echo "<td>".$atendente['nome']."</td>";

            $dados = DBRead('', 'tb_disponibilidade_escala',"WHERE id_usuario = '".$id."'");
                if($dados){
                    $visualizar  = "<a class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=exibe-escala&visualizar=$id' title='Visualizar'><i class='fa fa-eye'></i></a></td>";
                    
                }else{
                    $visualizar = "&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                }
          
            $condicao = DBRead('', 'tb_perfil_sistema a', "INNER JOIN tb_pagina_sistema_perfil b ON a.id_perfil_sistema = b.id_perfil_sistema INNER JOIN tb_pagina_sistema c ON b.id_pagina_sistema = c.id_pagina_sistema WHERE c.nome_view = 'escala-editar' AND a.nome = 'Call Center - Atendente'");

                          
            if($condicao){
                 echo "<td class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=escala-editar&alterar=$id' title='Alterar'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                 echo $visualizar;
            }else{
                echo "<td class=\"text-center\">";

                echo $visualizar;
            }
            
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    }
}
// FIM DO CONTEUDO
###################################################################################

$menos = $pagina - 1;
$mais = $pagina + 1;
$pgs = ceil($total / $maximo);

// Inicio e fim dos links
$ini_links = ((($pagina - $lim_links) > 1) ? $pagina - $lim_links : 1);
$fim_links = ((($pagina+$lim_links) < $pgs) ? $pagina+$lim_links : $pgs);

if($pgs > 1 ) {

    echo "<nav style=\"text-align: center;\">";
    echo "<ul class=\"pagination\">";

    // Mostragem de pagina
    if($menos > 0) {                                    
        echo "<li><a href=\"#\" class=\"troca_pag\" atr-pagina=\"$menos\" aria-label=\"Previous\"><span aria-hidden=\"true\">&laquo; Anterior</span></a></li>";
        echo "<li><a href=\"#\" class=\"troca_pag\" atr-pagina=\"1\">Pri.</a></li>";
    }else{
        echo "<li class=\"disabled\"><a href=\"#\" aria-label=\"Previous\"><span aria-hidden=\"true\">&laquo; Anterior</span></a></li>";
        echo "<li class=\"disabled\"><a href=\"#\">Pri.</a></li>";
    }

    // Listando as paginas
    for($i = $ini_links; $i <= $fim_links; $i++) {
        if($i != $pagina) {                                        
            echo "<li><a href=\"#\" class=\"troca_pag\" atr-pagina=\"$i\">$i</a></li>";
        } else {
            echo "<li class=\"active\"><a href=\"#\">$i <span class=\"sr-only\">(current)</span></a></li>";
        }
    }

    if($mais <= $pgs) {
        echo "<li><a href=\"#\" class=\"troca_pag\" atr-pagina=\"$pgs\">Últ.</a></li>";
        echo "<li><a href=\"#\" class=\"troca_pag\" atr-pagina=\"$mais\" aria-label=\"Next\"><span aria-hidden=\"true\">Próximo &raquo;</span></a></li>";
    }else{
        echo "<li class=\"disabled\"><a href=\"#\">Últ.</a></li>";
        echo "<li class=\"disabled\"><a href=\"#\" aria-label=\"Next\"><span aria-hidden=\"true\">Próximo &raquo;</span></a></a></li>";
    }

    echo "</ul>";
    echo "</nav>";
}
?>