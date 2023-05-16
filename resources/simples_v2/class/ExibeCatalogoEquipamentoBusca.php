<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);
$id_contrato_plano_pessoa = addslashes($parametros['id_contrato_plano_pessoa']);

// Informações da query
$filtros_query = "WHERE pergunta like '%".$letra."%' ".$filtro_categoria." ";


$dados = DBRead('', 'tb_catalogo_equipamento_qi_contrato a', "INNER JOIN tb_catalogo_equipamento_qi b ON a.id_catalogo_equipamento_qi_contrato = b.id_catalogo_equipamento_qi_contrato INNER JOIN tb_catalogo_equipamento c ON b.id_catalogo_equipamento = c.id_catalogo_equipamento INNER JOIN tb_catalogo_equipamento_marca d ON c.id_catalogo_equipamento_marca = d.id_catalogo_equipamento_marca WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND (c.modelo like '%".$letra."%' OR d.nome like '%".$letra."%') ORDER BY d.nome, c.modelo ASC");

if (!$dados) {
	echo "<p class='alert alert-warning' style='text-align: center'>";
	if (!$letra) {
		echo "Não foram encontrados registros!";
	} else {
		echo "Nenhum resultado encontrado na busca por \"<strong>$letra</strong>\"";
	}
	echo "</p>";
} else {

    foreach($dados as $conteudo){
    ?>               
    
        <div class="panel panel-default painel-quadro-informativo">
            <div class="panel-heading clearfix">
                <div class="row">
                    <h3 class="panel-title pull-center text-center col-md-12"><strong><?=$conteudo['nome']?> : <?=$conteudo['modelo']?></strong></h3>
                </div>
            </div>
            <div class="panel-body" style="padding-bottom: 0;">     

            <div class="row">
                <div class="col-md-6">
                    <div class="row text-center">
                        <div class="col-md-12">
                            <div class="form-group">
                            <label>Leds (da esquerda para direita):</label>
                            <br>
                            <span><?= $conteudo['led']; ?></span>
                            </div>
                        </div>
                    </div>
                    

                    <?php
                    $arquivo = 'inc/upload-catalogo-equipamento/'.$conteudo['foto_led'].'.jpg';
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row text-center">
                                    <img id="imagem" src="<?=$arquivo?>" alt="Imagem responsiva" class="img-thumbnail" style = "width:360px; height: 270px;">
                                </div>
                                    
                            </div>    
                        </div>    
                    </div> 
                </div>

                <div class="col-md-6">
                    <div class="row text-center">
                        <div class="col-md-12">
                            <div class="form-group">
                            <label>Portas (da esquerda para direita):</label>
                            <br>
                            <span><?= $conteudo['porta']; ?></span>
                            </div>
                        </div>
                    </div>
                    

                    <?php
                    $arquivo_porta = 'inc/upload-catalogo-equipamento/'.$conteudo['foto_porta'].'.jpg';
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row text-center">
                                    <img id="imagem_porta" src="<?=$arquivo_porta?>" alt="Imagem responsiva" class="img-thumbnail" style = "width:360px; height: 270px;">
                                </div>
                                    
                            </div>    
                        </div>    
                    </div> 
                </div>
                
            </div>
                           

                
            </div>
        </div>
        
    <?php 
    }
}

