<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['titulo']);
$id_usuario = $_SESSION['id_usuario'];

// Informações da query
$filtros_query  = "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_horarios_escala c ON a.id_usuario = c.id_usuario WHERE c.chat = 1 AND (a.id_perfil_sistema = '3' OR a.id_perfil_sistema = '15' OR a.id_perfil_sistema = '13') AND b.status != 2 AND a.status = 1 AND b.nome LIKE '%$letra%' GROUP BY a.id_usuario ORDER BY b.nome ASC";

// Maximo de registros por pagina
$maximo = 10000;

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

$usuario = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE (a.id_perfil_sistema = '3' OR a.id_perfil_sistema = '15') AND a.id_usuario = '".$id_usuario."' AND a.status = 1 AND b.nome LIKE '%$letra%' ORDER BY b.nome ASC","a.id_usuario, b.nome");	

if($usuario){

	$dados2 = DBRead('', 'tb_horarios_escala',"WHERE id_usuario = '".$id_usuario."'");

		        if($dados2){
		        	$visualizar = "<td class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=exibe-escala-horarios&visualizar=$id_usuario' title='Visualizar'><i class='fa fa-eye'></i></a></td>";
		        }else{
		        	$visualizar = "<td>Ainda não cadastrado</td>";

		        }

		$total = 1;
    	 echo "<div class='table-responsive'>";
		    echo "<table class='table table-hover' style='font-size: 14px;'>";
		    echo "<thead>";
		    echo "<tr>";
		    echo "<th class=\"col-md-8\">Nome</th>";
		  
		    echo "<th class=\"col-md-4 text-center\">Opções</th>";
		    echo "</tr>";
		    echo "</thead>";
		    echo "<tbody>";
    	
    	echo "<tr>";    
        echo "<td>".$usuario[0]['nome']."</td>";

        echo $visualizar;
      
        echo "</tr>";
        echo "</tbody>";
	    echo "</table>";
	    echo "</div>";
}else{

	###################################################################################
	// INICIO DO CONTEÚDO

	$dados = DBRead('', 'tb_usuario a',$filtros_query." LIMIT $inicio, $maximo","a.id_usuario, b.nome");
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
	    echo "<th class=\"col-md-8\">Nome</th>";
	  
	    echo "<th class=\"col-md-4 text-center\">Opções</th>";
	    echo "</tr>";
	    echo "</thead>";
	    echo "<tbody>";
	    
		    foreach ($dados as $atendente) {
		      
		        //############# Contatos realizados e diferença entre realizado e contratado
		        $id = $atendente['id_usuario'];
		        echo "<tr>";    
		        echo "<td>".$atendente['nome']."</td>";
		      	
		        // $dados = DBRead('', 'tb_horarios_escala',"WHERE id_usuario = '".$id."'");
		        // if($dados){
					//href='/api/iframe?token=<?php echo $request->token ?>&view=escala-editar-horarios&alterar=$id'
		        	$visualizar  = "<td class=\"text-center\"><a style='cursor:pointer;' title='Editar' data-toggle='modal' data-target='#modal_".$atendente['id_usuario']."'><i class='fa fa-pencil'></i></a></td>";

					// $visualizar  = "<td class=\"text-center\"><a style='cursor:pointer;' title='Editar' data-toggle='modal' data-target='#modal_".$atendente['id_usuario']."'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=exibe-escala-horarios&visualizar=$id' title='Visualizar'><i class='fa fa-eye'></i></a></td>";
		        	
		        // }else{
		        // 	$visualizar = "<td class=\"text-center\"><a href='/api/iframe?token=<?php echo $request->token ?>&view=chat-escala-editar-horarios&alterar=$id' title='Inserir'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		        // }
		        echo $visualizar;

	            //MODAL    
		      	echo '
				  <div class="modal fade" id="modal_'.$atendente['id_usuario'].'" role="dialog">
				    <div class="modal-dialog">
				    
				      <div class="modal-content">
				        <div class="modal-header">
                    		<h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Escalas do(a) '.$atendente['nome'].'</h3>
							<div class="panel-title text-right pull-right"><a href="/api/iframe?token=<?php echo $request->token ?>&view=chat-escala-editar-horarios-data&novo='.$atendente['id_usuario'].'"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Nova</button></a></div>
				        </div>
				        <div class="modal-body" style="overflow: auto; max-height: 180px !important;">
				         	';
						        
							//$dados_escala = DBRead('', 'tb_horarios_escala',"WHERE id_usuario = '".$id."' ORDER BY data_inicial ASC limit 5");
							$dados_escala = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_chat_horarios_escala b ON a.id_horarios_escala = b.id_horarios_escala WHERE a.id_usuario = '".$id."' ORDER BY data_inicial DESC", "a.*, b.*, a.id_horarios_escala AS id_escala");
							//sort($dados_escala);

							//style='overflow: auto; height: 565px !important;'

							if($dados_escala){

								echo '<ul class="list-group">
										  <li class="list-group-item d-flex justify-content-between align-items-center">
										    <strong>Mês/Ano</strong>
										    <span class="badge" style="background-color:#FFFFFF; color:#000000;"><strong>Opções</strong></span>
										  </li>';

				        			foreach ($dados_escala as $conteudo_escala) {
				        				$data = explode("-", $conteudo_escala['data_inicial']);
				        				$nome = $atendente['nome'];
				        				$id_escala = $conteudo_escala['id_escala'];
										if($data[1] == "01"){
											$mes = "Janeiro";
										}else if($data[1] == "02"){
											$mes = "Fevereiro";
										}else if($data[1] == "03"){
											$mes = "Março";
										}else if($data[1] == "04"){
											$mes = "Abril";
										}else if($data[1] == "05"){
											$mes = "Maio";
										}else if($data[1] == "06"){
											$mes = "Junho";
										}else if($data[1] == "07"){
											$mes = "Julho";
										}else if($data[1] == "08"){
											$mes = "Agosto";
										}else if($data[1] == "09"){
											$mes = "Setembro";
										}else if($data[1] == "10"){
											$mes = "Outubro";
										}else if($data[1] == "11"){
											$mes = "Novembro";
										}else if($data[1] == "12"){
											$mes = "Dezembro";
										}
										$id_chat_horarios_escala = $conteudo_escala['id_chat_horarios_escala'];

										
										 echo '
										  <li class="list-group-item d-flex justify-content-between align-items-center">
										    '.$mes."/".$data[0];
										    echo "<span class='badge' style='background-color:#FFFFFF; color:#000000;'>

										    <a style='text-align: center' href='/api/iframe?token=<?php echo $request->token ?>&view=chat-escala-editar-horarios&alterar=$id_escala' title='Editar ".$mes.'/'.$data[0]."' ><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"class/ChatEscalaHorarios.php?excluir=$id_escala\" title='Excluir' onclick=\"if (!confirm('Excluir a escala de ".addslashes($data[1]."/".$data[0])." do(a) ".addslashes($nome)."?')) { return false; } \" title='Excluir'><i class='fa fa-trash' style='color:#b92c28;'></i></a>

										    </span>
										  </li>";
									
				        			}
				        		echo "</ul>";
							}else{
								echo "<p class='alert alert-warning' style='text-align: center'>";
									echo "Não existem escalas de chat cadastradas para este(a) operador(a)!";
								echo "</p>";
							}
				     	   echo '
				        </div>
				      </div>
				      
				    </div>
				  </div>';
		        
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