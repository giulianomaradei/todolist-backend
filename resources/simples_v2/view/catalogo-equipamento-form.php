<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_catalogo_equipamento', "WHERE id_catalogo_equipamento = $id");
    $id_catalogo_equipamento_marca = $dados[0]['id_catalogo_equipamento_marca'];
    $modelo = $dados[0]['modelo'];
    $led = $dados[0]['led'];
    $foto_led = $dados[0]['foto_led'];

    $porta = $dados[0]['porta'];
    $foto_porta = $dados[0]['foto_porta']; 

}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $modelo = '';
    $led = '';
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Catálogo de equipamentos - <?= $tituloPainel ?> </h3>
                    <?php 
                    $dados = DBRead('', 'tb_catalogo_equipamento_qi', "WHERE id_catalogo_equipamento  = '".$id."' ");
                    if (isset($_GET['alterar']) && !$dados) {
                        
                        echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=CatalogoEquipamento.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o equipamento?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; 
                        
                    } 
                    ?>
                </div>
                <form method="post" action="/api/ajax?class=CatalogoEquipamento.php" id="catalogo_equipamento_form" style="margin-bottom: 0;" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                       
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Marca:</label>
                                    <select class="form-control input-sm" autofocus id="id_catalogo_equipamento_marca" name="id_catalogo_equipamento_marca">
                                        <?php
                                            $dados_catalogo_equipamento_marca = DBRead('', 'tb_catalogo_equipamento_marca', "WHERE status = 1 ORDER BY nome ASC");
                                            
                                            if ($dados_catalogo_equipamento_marca) {
                                                foreach ($dados_catalogo_equipamento_marca as $conteudo_catalogo_equipamento_marca) {
                                                    $selected = $id_catalogo_equipamento_marca == $conteudo_catalogo_equipamento_marca['id_catalogo_equipamento_marca'] ? "selected" : "";
                                                    echo "<option value='".$conteudo_catalogo_equipamento_marca['id_catalogo_equipamento_marca']."' ".$selected.">".$conteudo_catalogo_equipamento_marca['nome']."</option>";
                                                }
                                            }
                                        ?>
                                    </select> 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Modelo:</label>
                                    <input name="modelo" id="modelo" type="text" class="form-control input-sm" value="<?= $modelo; ?>" autocomplete="off" required>
                                </div>
                            </div>
                        </div>

                        <!-- leds -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Leds(da esquerda para direita):</label>
                                    <input name="led" id="led" type="text" class="form-control input-sm" value="<?= $led; ?>" autocomplete="off" required>
                                </div>
                            </div>
                        </div>

                        <?php

                            $arquivo = 'inc/upload-catalogo-equipamento/'.$foto_led.'.jpg';

                            if (file_exists($arquivo)){
                                $auxiliar = 1;
                            ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row text-center">
                                                <img id="imagem" src="<?=$arquivo?>" alt="Imagem responsiva" class="img-thumbnail" style = "width:120px; height: 90px;">
                                            </div>
                                            <div class="row text-center">
                                                <small class="form-text text-muted text-center">Tamanho máximo 5MB!</small>
                                            </div>
                                            <div class="row text-center" style="margin-top: 5px;">
                                                <label class="btn btn-sm" style="background-color: #52abb7; color: white;">
                                                    Alterar foto dos leds!
                                                    <input type="file" id="foto_led" name="foto_led" accept=".png, .jpg, .jpeg" value="" style="display: none;">
                                                </label>

                                            </div>    
                                        </div>    
                                    </div>    
                                </div>    

                            <?php                                
                            }else{
                            ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row text-center">
                                                <img id="imagem" src="inc/img/router.png" alt="Imagem responsiva" class="img-thumbnail" style = "width:120px; height: 90px;">
                                            </div>
                                            <div class="row text-center">
                                                <small class="form-text text-muted text-center">Tamanho máximo 5MB!</small>
                                            </div>
                                            <div class="row text-center" style="margin-top: 5px;">
                                                <label class="btn btn-sm" style="background-color: #52abb7; color: white;">
                                                    <span id='texto_foto_led'>Adicionar foto dos leds!</span>
                                                    <input type="file" id="foto_led" name="foto_led" accept=".png, .jpg, .jpeg" value="" style="display: none;">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php  
                            }
                        ?>
                        <!-- leds fim -->


                        <!-- leds -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Portas(da esquerda para direita):</label>
                                    <input name="porta" id="porta" type="text" class="form-control input-sm" value="<?= $porta; ?>" autocomplete="off" required>
                                </div>
                            </div>
                        </div>

                        <?php

                            $arquivo_porta = 'inc/upload-catalogo-equipamento/'.$foto_porta.'.jpg';

                            if (file_exists($arquivo_porta)){
                                $auxiliar_porta = 1;
                            ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row text-center">
                                                <img id="imagem_porta" src="<?=$arquivo_porta?>" alt="Imagem responsiva" class="img-thumbnail" style = "width:120px; height: 90px;">
                                            </div>
                                            <div class="row text-center">
                                                <small class="form-text text-muted text-center">Tamanho máximo 5MB!</small>
                                            </div>
                                            <div class="row text-center" style="margin-top: 5px;">
                                                <label class="btn btn-sm" style="background-color: #52abb7; color: white;">
                                                    Alterar foto das portas!
                                                    <input type="file" id="foto_porta" name="foto_porta" accept=".png, .jpg, .jpeg" value="" style="display: none;">
                                                </label>

                                            </div>    
                                        </div>    
                                    </div>    
                                </div>    

                            <?php                                
                            }else{
                            ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row text-center">
                                                <img id="imagem_porta" src="inc/img/router.png" alt="Imagem responsiva" class="img-thumbnail" style = "width:120px; height: 90px;">
                                            </div>
                                            <div class="row text-center">
                                                <small class="form-text text-muted text-center">Tamanho máximo 5MB!</small>
                                            </div>
                                            <div class="row text-center" style="margin-top: 5px;">
                                                <label class="btn btn-sm" style="background-color: #52abb7; color: white;">
                                                    <span id='texto_foto_porta'>Adicionar foto das portas!</span>
                                                    <input type="file" id="foto_porta" name="foto_porta" accept=".png, .jpg, .jpeg" value="" style="display: none;">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php  
                            }
                        ?>
                        <!-- leds fim -->


                    </div>

                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>"/>
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>     
<script>
    $(document).on('submit', '#catalogo_equipamento_form', function () {
        var modelo = $("#modelo").val();
        var foto_led = $("#foto_led").val();
        var foto_porta = $("#foto_porta").val();

        var auxiliar = '<?=$auxiliar?>';
        var auxiliar_porta = '<?=$auxiliar_porta?>';

        if(!modelo || modelo == ""){
            alert("Deve-se descrever o modelo!");
            return false;
        }

        if(!auxiliar || auxiliar == ""){
            if(!foto_led || foto_led == ""){
                alert("Deve-se adicionar uma imagem dos leds!");
                return false;
            }
        }

        if(!auxiliar_porta || auxiliar_porta == ""){
            if(!foto_porta || foto_porta == ""){
                alert("Deve-se adicionar uma imagem das portas!");
                return false;
            }
        }
        
        modalAguarde();
    });

    $(document).ready( function() {
    	
		function readURL(input) {
		    if (input.files && input.files[0]) {
		        var reader = new FileReader();
		        
		        reader.onload = function (e) {
		            $('#imagem').attr('src', e.target.result);
		        }
		        
		        reader.readAsDataURL(input.files[0]);
                $('#texto_foto_led').text('Alterar foto dos leds!');
		    }
		}

        function readURLPorta(input) {
            if (input.files && input.files[0]) {
		        var reader = new FileReader();
		        
		        reader.onload = function (e) {
		            $('#imagem_porta').attr('src', e.target.result);
		        }
		        
		        reader.readAsDataURL(input.files[0]);
                $('#texto_foto_porta').text('Alterar foto das portas!');
		    }		    
		}

		$("#foto_led").change(function(){
		    readURL(this);
		}); 
        
        $("#foto_porta").change(function(){
		    readURLPorta(this);
		}); 

	});
</script>