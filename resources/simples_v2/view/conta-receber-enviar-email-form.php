<?php
require_once(__DIR__."/../class/System.php");

$selecionar_conta_receber = (!empty($_POST['selecionar_conta_receber'])) ? $_POST['selecionar_conta_receber'] : '';

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Envio de E-mail:</h3>
                </div>
                <form method="post" action="/api/ajax?class=ContaReceber.php" id="enviar_email_conta_receber" style="margin-bottom: 0;">
                    <input type="hidden" name="token" value="<?php echo $request->token ?>">
                	
                    <?php
                		foreach ($selecionar_conta_receber as $conteudo_conta_receber) {
                    		echo '<input type="hidden" id="selecionar_conta_receber" name="selecionar_conta_receber[]" value="'.$conteudo_conta_receber.'"/>';
                		}
                	?>
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Modelo de E-mail:</label>
                                    <select class="form-control input-sm" name="email_modelo" id="email_modelo">
                                        <option value="">E-mail Personalizado</option>
                                        <?php
                                            $dados_email_modelo = DBRead('', 'tb_email_modelo', "WHERE status = 1 ORDER BY titulo ASC");
                                            $cont = 0;
                                            if ($dados_email_modelo) {
                                                foreach ($dados_email_modelo as $conteudo_email_modelo) {
                                                    echo "<option value='".$conteudo_email_modelo['id_email_modelo']."'>".$conteudo_email_modelo['titulo']."</option>";
                                                    $cont++;
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
						</div>
                        <div class="row">
							<div class="col-md-12">
                                <div class="form-group">
                                    <label>*Assunto:</label>
                                    <input name="assunto" id="assunto" type="text" class="form-control input-sm" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Descrição:</label>
                                    <textarea class="form-control" name="descricao" id="descricao" style="resize: vertical; height: 250px;"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Anexos:</label>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="input-group">
	                                            <span class="input-group-addon">
	                                                <input type="checkbox" name="envia_nfs" id="envia_nfs" value="1">
	                                            </span>
                                            	<input type="text" class="form-control mensagem" aria-label="..." disabled value="NFS-e" style="cursor: context-menu; background-color: white;">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="input-group">
	                                            <span class="input-group-addon">
	                                                <input type="checkbox" name="envia_xml" id="envia_xml" value="1">
	                                            </span>
	                                            <input type="text" class="form-control mensagem" aria-label="..." disabled value="XML" style="cursor: context-menu; background-color: white;">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="input-group">
	                                            <span class="input-group-addon">
	                                                <input type="checkbox" name="envia_boleto" id="envia_boleto" value="1">
	                                            </span>
	                                            <input type="text" class="form-control mensagem" aria-label="..." disabled value="Boleto" style="cursor: context-menu; background-color: white;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>                   
                    </div>
                    
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" name="enviar_email_conta_receber" value="abc"/>
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fas fa-envelope-open-text"></i> Enviar</button>
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

    $(document).on('submit', '#enviar_email_conta_receber', function () {
        var email_modelo = $("select[name=email_modelo]").val();
        var assunto = $("#assunto").val();
        var descricao = $("#descricao").val();
        
       	if(!assunto || assunto == ""){
            alert("Deve-se adicionar um assunto!");
            return false;
        }else if(!descricao || descricao == ""){
            alert("Deve-se adicionar uma descricao!");
            return false;
        }

        modalAguarde();
    });

    $(document).on('change', 'select[name=email_modelo]', function(){

    	var id_email_modelo = $(this).val();

    	if(id_email_modelo){
    		$.ajax({
	            type: "POST",
	            url: "/api/ajax?class=SelectEmailModelo.php",
	            dataType: "json",
	            data: {
	                id_email_modelo: id_email_modelo,
                    token: '<?= $request->token ?>'
	            },
	            success: function(data){
	                $("#assunto").val(data['assunto']);
	                $("#descricao").text(data['descricao']);
	            }
	        });
    	}else{
    		$("#assunto").val('');
	        $("#descricao").text('');
    	}
        

    });
</script>