<?php
require_once(__DIR__."/../class/System.php");

    $data_hoje = converteData(getDataHora('data'));

	$dados_centro_custos = DBRead('', 'tb_centro_custos', "WHERE status = 1 ORDER BY nome ASC");
	if ($dados_centro_custos) {
		foreach ($dados_centro_custos as $conteudo_centro_custos) {
	    
			$option_centro_custos .= '<option value="'.$conteudo_centro_custos['id_centro_custos'].'">'.$conteudo_centro_custos['nome'].'</option>,';

		}
	}else{
		$option_centro_custos = '<option value="">Não existem centro de custos cadastrados!</option>';
	}

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados_conta_pagar = DBRead('', 'tb_conta_pagar', "WHERE id_conta_pagar = $id");
    
    $id_natureza_financeira = $dados_conta_pagar[0]['id_natureza_financeira'];
    $id_caixa = $dados_conta_pagar[0]['id_caixa'];
    $valor = converteMoeda($dados_conta_pagar[0]['valor'], 'moeda');
    $data_vencimento = converteData($dados_conta_pagar[0]['data_vencimento']);
    $data_emissao = converteData($dados_conta_pagar[0]['data_emissao']);
    $descricao = $dados_conta_pagar[0]['descricao'];
    $numero_parcela = $dados_conta_pagar[0]['numero_parcela'];
    $id_conta_pai = $dados_conta_pagar[0]['id_conta_pai'];

	$dados_pessoa = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$dados_conta_pagar[0]['id_pessoa']."' ", "id_pessoa, nome");
    
    $id_pessoa = $dados_pessoa[0]['id_pessoa'];
    $nome_pessoa = $dados_pessoa[0]['nome'];
    
    $botao_pessoa = 'disabled';
    if($dados_conta_pagar[0]['situacao'] != 'aberta'){
		echo "<div class=\"container-fluid text-center\"><div class=\"alert alert-danger\"><i class=\"fa fa-ban\" aria-hidden=\"true\"></i> Ops! Você não tem permissão de acesso!</div></div>";
		exit;
	}
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $valor = "0,00";
    $data_emissao = $data_hoje;
    $id_pessoa = '';
    $nome_pessoa = '';
    $numero_parcela = '';
    $id_conta_pai = '';

    $botao_pessoa = '';
}

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> Conta a Pagar:</h3>
                </div>
                <form method="post" action="/api/ajax?class=ContaPagar.php" id="conta_pagar_form" style="margin-bottom: 0;">
					<input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                    	<ul class="nav nav-tabs">
                            <li class="aba1 active">
                                <a data-toggle="tab" href="#tab1">Dados da Conta a Pagar</a>
                            </li>
                            <li class="aba2 ">
                                <a data-toggle="tab" href="#tab2">Rateio</a>
                            </li>
                        </ul>
                        <div class="tab-content">

                            <!-- tab 1 Dados pessoais  -->
                            <div id="tab1" class="tab-pane fade in active">
                                <br>
                                <div class="row" id="row-lead">
                                    <div class="col-md-12">
                                        <div class="row">
				                            <div class="col-md-4">
				                                <div class="form-group">
				                                    <label>*Pessoa (fornecedor ou funcionário):</label>
				                                    <div class="input-group">
				                                        <input class="form-control input-sm" id="busca_pessoa" type="text" name="busca_pessoa"  value="<?=$nome_pessoa;?>" placeholder="Informe o nome ou CPF/CNPJ..." autocomplete="off" readonly required>
				                                        <div class="input-group-btn">
				                                            <button class="btn btn-info btn-sm" id="habilita_busca_pessoa" name="habilita_busca_pessoa" type="button" title="Clique para selecionar a pessoa" style="height: 30px;" <?=$botao_pessoa;?>><i class="fa fa-search"></i></button>
				                                        </div>
				                                    </div>
				                                    <input type="hidden" name="id_pessoa" id="id_pessoa" value="<?=$id_pessoa;?>">
				                                </div>
				                            </div>
				                            <div class="col-md-4">
				                                <div class="form-group">
				                                    <label>*Natureza Financeira:</label>
				                                    <select class="form-control input-sm" name="id_natureza_financeira" id="id_natureza_financeira">
				                                        <option value="">Selecione a Natureza Financeira...</option>
				                                        <?php
				                                            $dados_natureza_financeira = DBRead('', 'tb_natureza_financeira', "WHERE status = 1 AND tipo = 'conta_pagar' ORDER BY nome ASC");			                                            
				                                            if ($dados_natureza_financeira) {
				                                                foreach ($dados_natureza_financeira as $conteudo_natureza_financeira) {
																	$selected = $id_natureza_financeira == $conteudo_natureza_financeira['id_natureza_financeira'] ? "selected" : "";

				                                                    echo "<option value='".$conteudo_natureza_financeira['id_natureza_financeira']."' ".$selected.">".$conteudo_natureza_financeira['nome']."</option>";
				                                                }
				                                            }
				                                        ?>
				                                    </select>
				                                </div>
				                            </div>
				                            <div class="col-md-4">
				                                <div class="form-group">
				                                    <label>*Caixa:</label>
				                                    <select class="form-control input-sm" name="id_caixa" id="id_caixa">
				                                        <option value="">Selecione o Caixa...</option>
				                                        <?php
				                                            $dados_caixa = DBRead('', 'tb_caixa', "WHERE status = 1 ORDER BY nome ASC");
				                                            $sel_caixa[$id_caixa] = 'selected';

				                                            if ($dados_caixa) {
				                                                foreach ($dados_caixa as $conteudo_caixa) {
																	$selected = $id_caixa == $conteudo_caixa['id_caixa'] ? "selected" : "";
				                                                    echo "<option value='".$conteudo_caixa['id_caixa']."' ".$selected.">".$conteudo_caixa['nome']."</option>";
				                                                }
				                                            }
				                                        ?>
				                                    </select>
				                                </div>
				                            </div>
										</div>
                                        <div class="row">
				                            <div class="col-md-4">
				                                <div class="form-group">
				                                    <label>*Valor:</label>
				                                    <input class="form-control input-sm money" name="valor" id="valor" type="text" autocomplete="off" value="<?=$valor?>" required>
				                                </div>
				                            </div>
				                            <div class="col-md-4">
				                                <div class="form-group">
				                                    <label>*Data de Vencimento:</label>
				                                    <input class="form-control date calendar hasDatePicker hasDatepicker input-sm" name="data_vencimento" id="data_vencimento" type="text" placeholder="dd/mm/aaaa" autocomplete="off" maxlength="10" required value="<?= $data_vencimento ?>">
				                                </div>
				                            </div>
				                            <div class="col-md-4">
				                                <div class="form-group">
				                                    <label>*Data de Emissão:</label>
				                                    <input class="form-control date calendar hasDatePicker hasDatepicker input-sm" name="data_emissao" id="data_emissao" type="text" placeholder="dd/mm/aaaa" autocomplete="off" maxlength="10" required value="<?= $data_emissao ?>">
				                                </div>
				                            </div>
				                            
										</div>
										<div class="row">
				                            <div class="col-md-12">
				                                <div class="form-group">
				                                    <label>Descrição:</label>
				                                    <textarea name="descricao" id="descricao" rows = '2' cols="100" class="form-control"><?= $descricao ?></textarea>
				                                </div>
				                            </div>
				                        </div>    
                                    </div>
                                </div>
                            </div>
				            <input type="hidden" name="numero_parcela" id="numero_parcela" value="<?=$numero_parcela;?>">
				            <input type="hidden" name="id_conta_pai" id="id_conta_pai" value="<?=$id_conta_pai;?>">
                            <!-- end tab 1 Dados pessoais -->

                            <!-- tab 2 Dados Conta Pagar  -->
                            <div id="tab2" class="tab-pane fade in ">
                                <br>
                                <div class="row">
		                            <div class="col-md-12">
		                                <div class="form-group">
		                                    <label>Adionar Rateio em:</label>
		                                    <div class="row">

		                                    	<div class="col-sm-6">
		                                            <div class="input-group">
		                                            <span class="input-group-addon">
		                                                <input type="radio" name="radio_selecao" id="radio_selecao_porcentagem" checked>
		                                            </span>
		                                            <input type="text" class="form-control mensagem" aria-label="..." disabled value="Porcentagens" style="cursor: context-menu; background-color: white;">
		                                            </div><!-- /input-group -->
		                                        </div><!-- /.col-lg-6 -->   

		                                        <div class="col-sm-6">
		                                            <div class="input-group">
		                                            <span class="input-group-addon">
		                                                <input type="radio" name="radio_selecao" id="radio_selecao_valor">
		                                            </span>
		                                            <input type="text" class="form-control mensagem" aria-label="..." disabled value="Valores" style="cursor: context-menu; background-color: white;">
		                                            </div><!-- /input-group -->
		                                        </div><!-- /.col-lg-6 -->
		                                        
		                                    </div><!-- /.row -->
		                                </div>
		                            </div>
								</div>    
                                	<?php
                                        $dados_centro_custos = DBRead('', 'tb_centro_custos', "WHERE status = 1 ORDER BY nome ASC");

                                        if ($dados_centro_custos) {
                                            foreach ($dados_centro_custos as $conteudo_centro_custos) {
											    if($operacao == 'alterar'){
													$dados_centro_custos_rateio = DBRead('', 'tb_conta_pagar_centro_custos', "WHERE id_conta_pagar = '".$id."' AND id_centro_custos= '".$conteudo_centro_custos['id_centro_custos']."' ");
											    }                                            	
                                            	if($dados_centro_custos){
                                            		$porcentagem = $dados_centro_custos_rateio[0]['porcentagem'];
                                            		$valor_centro_custos = converteMoeda($dados_centro_custos_rateio[0]['valor'], 'moeda');
                                            	}else{
                                            		$porcentagem = '';
                                            		$valor_centro_custos = "0,00";
                                            	}
                                            	echo '                                
	                                           	<div class="row">

	                                            	<div class="col-md-4">
						                                <div class="form-group">
						                                    <label>Centro de Custos:</label>
						                                    <input class="form-control input-sm" name="nome_centro_custos[]" type="text" autocomplete="off" value="'.$conteudo_centro_custos['nome'].'" readonly>
						                                </div>
						                            </div>

													<div class="col-md-4">
						                                <div class="form-group">
						                                    <label>*Porcentagem (%):</label>
						                                    <input class="form-control input-sm number_float porcentagem_centro_custos" name="porcentagem_centro_custos[]" type="text" autocomplete="off" value="'.$porcentagem.'">
						                                </div>
						                            </div>		

						                            <div class="col-md-4">
						                                <div class="form-group">
						                                    <label>*Valor:</label>
						                                    <input class="form-control input-sm money valor_centro_custos" name="valor_centro_custos[]" type="text" autocomplete="off" value="'.$valor_centro_custos.'" required>
						                                </div>
						                            </div>
						                                                    
						                        </div>

					                            ';
                                            }
                                        }
                                    ?>
				                            
				                            
		                        <!-- Contador/Soma -->
		                        <div class="row" id='row_total_parcelas'>
		                            <hr>
		                            <div class="col-md-6">
		                                <div class="form-group">
		                                    <h3 class='panel-title text-center'>Valor da Conta a Pagar: <strong id='valor_conta_pagar_rateio'>R$ 0,00</strong></h3>
		                                </div>
		                            </div>
		                            <div class="col-md-6">
		                                <div class="form-group">
		                                    <h3 class='panel-title text-center'>Valor Total do Rateio: <strong id='soma_total' style='color:blue;'>R$ 0,00</strong></h3>
		                                </div>
		                            </div>
		                        </div> 
                            <!-- end tab 2 Dados prospecção -->
                        	</div>
                        </div>
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

    
    $(document).ready(function(){
     	$('#valor_conta_pagar_rateio').text('R$ '+$('#valor').val());
     	var id = '<?= $id?>';
     	if(id != 1){
     		$('#soma_total').text('R$ '+$('#valor').val());
            $("#soma_total").css("color", "green");
     	}
	});
	 

    $("#valor").on('keyup', function(){
     	$('#valor_conta_pagar_rateio').text('R$ '+$(this).val());
     	
     	var valor_conta_pagar_rateio = $('#valor_conta_pagar_rateio').text();
     	var soma_total = $('#soma_total').text();
     	valor_conta_pagar_rateio = valor_conta_pagar_rateio.replace("R$ ", "");
     	soma_total = soma_total.replace("R$ ", "");

     	if(soma_total != '0,00'){
     		// var valor_conta_pagar = moedaFloat($("#valor").val());

			var total = $("#valor").val().length;
			var i = 0;
			var palavra = '';
			for(i=0; i<total; i++){
				if($("#valor").val()[i] != '.'){
					palavra = palavra+''+$("#valor").val()[i];

				}
			}
			
			palavra = palavra.replace(",", ".");
			var valor_conta_pagar = palavra;

     		$("[name ='valor_centro_custos[]']").each(function(){
	            if($(this).val() != '0,00' || $(this).val() != ''){
					// var porcentagem = (moedaFloat($(this).val()))*100;
					// porcentagem = porcentagem/valor_conta_pagar;
					var total = $(this).val().length;
					var i = 0;
					var palavra = '';
					for(i=0; i<total; i++){
						if($(this).val()[i] != '.'){
							palavra = palavra+''+$(this).val()[i];

						}
					}
					
					palavra = palavra.replace(",", "");
					var porcentagem = palavra;
					// console.log(porcentagem+' ----------- '+$(this).val());

					porcentagem = porcentagem/valor_conta_pagar;
						$(this).parent().parent().parent().find('.porcentagem_centro_custos').val(porcentagem.toFixed(2));
	            }

	        });
     		if(moedaFloat(soma_total) > moedaFloat(valor_conta_pagar_rateio)){
				$("#soma_total").css("color", "red");
	        	$("#ok").prop('disabled', true);
	        }else if(moedaFloat(soma_total) == moedaFloat(valor_conta_pagar_rateio)){
	            $("#soma_total").css("color", "green");
	        	$("#ok").prop('disabled', false);
	        }else{
	        	$("#soma_total").css("color", "blue");
	        	$("#ok").prop('disabled', true);
	        }
     	}

    });

    $("[name ='porcentagem_centro_custos[]']").on('keyup', function(){

     	var radio_selecao_porcentagem = $('#radio_selecao_porcentagem').prop('checked');
     	var valor_total = 0;
     	// var valor_conta_pagar = moedaFloat($("#valor").val());

		var total = $("#valor").val().length;
		var i = 0;
		var palavra = '';
		for(i=0; i<total; i++){
			if($("#valor").val()[i] != '.'){
				palavra = palavra+''+$("#valor").val()[i];

			}
		}
		
		palavra = palavra.replace(",", ".");
		var valor_conta_pagar = palavra;
     	
		var valor = $(this).val()*valor_conta_pagar;
		// console.log($(this).val()+' ----------- '+valor+' ----------- '+valor_conta_pagar);

		valor = valor/100;

		if(radio_selecao_porcentagem){
			if($(this).val() == '' || !$(this).val()){
				$(this).parent().parent().parent().find('.valor_centro_custos').val('0,00');
			}else{
				$(this).parent().parent().parent().find('.valor_centro_custos').val(floatMoeda(valor.toFixed(2)));
			}
     	}

     	$("[name ='valor_centro_custos[]']").each(function(){
            if($(this).val() != '0,00' || $(this).val() != ''){
				var total = $(this).val().length;
				var i = 0;
				var palavra = '';
				for(i=0; i<total; i++){
					if($(this).val()[i] != '.'){
						palavra = palavra+''+$(this).val()[i];

					}
				}
				
				palavra = palavra.replace(",", ".");

				valor_total = valor_total + parseFloat(palavra);
				// console.log(		valor_total	+' ----------- '+(palavra)+' ----------- '+($(this).val()));

            }
        });

     	var soma_porcentagem = 0;
        $("[name ='porcentagem_centro_custos[]']").each(function(){
        	if($(this).val() != ''){
            	soma_porcentagem = parseFloat(soma_porcentagem) + parseFloat($(this).val());
			}
        });

		if(valor_total.toFixed(2) > valor_conta_pagar){
			$("#soma_total").css("color", "red");
        	$("#ok").prop('disabled', true);
        }else if(valor_total.toFixed(2) == valor_conta_pagar){
            $("#soma_total").css("color", "green");
        	$("#ok").prop('disabled', false);
        	if(soma_porcentagem == 100){
	        	$("#ok").prop('disabled', false);
	        }else{
	        	alert('A porcentagem está diferente de 100%!');
	        	$("#ok").prop('disabled', true);
	        }
        }else if(valor_total == '0.00'){
        	$("#soma_total").css("color", "blue");
        	$("#ok").prop('disabled', false);
        }else{
        	$("#soma_total").css("color", "blue");
        	$("#ok").prop('disabled', true);
        }

        //console.log(soma_porcentagem);

        //console.log(valor_conta_pagar+' - - - - - - '+valor_total);
       	
        $('#soma_total').text('R$ ' + floatMoeda(valor_total.toFixed(2)));

    });

    $("[name ='valor_centro_custos[]']").on('keyup', function(){

     	var radio_selecao_porcentagem = $('#radio_selecao_porcentagem').prop('checked');
     	var valor_total = '00.00';
     	// var valor_conta_pagar = moedaFloat($("#valor").val());
		


		var total = $("#valor").val().length;
		var i = 0;
		var palavra = '';
		for(i=0; i<total; i++){
			if($("#valor").val()[i] != '.'){
				palavra = palavra+''+$("#valor").val()[i];

			}
		}
		
		palavra = palavra.replace(",", ".");
		var valor_conta_pagar = palavra;



  		// var porcentagem = (moedaFloat($(this).val()))*100;
		
		var total = $(this).val().length;
		var i = 0;
		var palavra = '';
		for(i=0; i<total; i++){
			if($(this).val()[i] != '.'){
				palavra = palavra+''+$(this).val()[i];

			}
		}
		
		palavra = palavra.replace(",", "");
		var porcentagem = palavra;
		// console.log(porcentagem+' ----------- '+$(this).val());

		porcentagem = porcentagem/valor_conta_pagar;

		if(!radio_selecao_porcentagem){
			if($(this).val() == '' || !$(this).val() || $(this).val() == '0,00'){
				$(this).parent().parent().parent().find('.porcentagem_centro_custos').val('');
			}else{
				$(this).parent().parent().parent().find('.porcentagem_centro_custos').val(porcentagem.toFixed(2));
			}
     	}

     	$("[name ='valor_centro_custos[]']").each(function(){
            if($(this).val() != '0,00' || $(this).val() != ''){
				var total = $(this).val().length;
				var i = 0;
				var palavra = '';
				for(i=0; i<total; i++){
					if($(this).val()[i] != '.'){
						palavra = palavra+''+$(this).val()[i];

					}
				}
				
				palavra = palavra.replace(",", ".");

				valor_total = parseFloat(valor_total) + parseFloat(palavra);
				// console.log(valor_total.toFixed(2) +'        '+ (valor_conta_pagar));

				// valor_total = parseFloat(valor_total) + parseFloat(moedaFloat($(this).val()));
            }
        });

        var soma_porcentagem = 0;
        $("[name ='porcentagem_centro_custos[]']").each(function(){
        	if($(this).val() != ''){
            	soma_porcentagem = parseFloat(soma_porcentagem) + parseFloat($(this).val());
			}
        });

		if(valor_total.toFixed(2) > valor_conta_pagar){
			$("#soma_total").css("color", "red");
        	$("#ok").prop('disabled', true);
        }else if(valor_total.toFixed(2) == valor_conta_pagar){
            $("#soma_total").css("color", "green");
        	$("#ok").prop('disabled', false);
        	if(soma_porcentagem == 100){
	        	$("#ok").prop('disabled', false);
	        }else{
	        	alert('A porcentagem está diferente de 100%!');
	        	$("#ok").prop('disabled', true);
	        }
        }else if(valor_total == '0.00'){
        	$("#soma_total").css("color", "blue");
        	$("#ok").prop('disabled', false);
        }else{
        	$("#soma_total").css("color", "blue");
        	$("#ok").prop('disabled', true);
        }
        //console.log('valor '+valor_conta_pagar+' - - - - - - '+valor_total);
        $('#soma_total').text('R$ ' + floatMoeda(valor_total.toFixed(2)));

    });

	// Atribui evento e função para limpeza dos campos
    $('#busca_pessoa').on('input', limpaCamposPessoa);

    // Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_pessoa").autocomplete({
        minLength: 2,
        source: function(request, response){
            $.ajax({
                url: "/api/ajax?class=PessoaAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'autocomplete',
                    parametros: {
                        'nome' : $('#busca_pessoa').val(),
                        'atributo' : 'fornecedor_funcionario'
                    },
					token: '<?= $request->token ?>'
                },
                success: function(data){
                    response(data);
                }
            });
        },
        focus: function(event, ui){
            $("#busca_pessoa").val(ui.item.nome);
            carregarDadosPessoa(ui.item.id_pessoa);
            return false;
        },
        select: function(event, ui){
            $("#busca_pessoa").val(ui.item.nome);
            $('#busca_pessoa').attr("readonly", true);
            return false;
        }
    })
    .autocomplete("instance")._renderItem = function(ul, item){
        if(!item.razao_social){
            item.razao_social = '';
        }

        if(!item.cpf_cnpj){
            item.cpf_cnpj = '';
        }

        return $("<li>").append("<a><strong>"+item.id_pessoa+" - "+ item.nome + " </strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
    };

    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosPessoa(id) {
        var busca = $('#busca_pessoa').val();

        if (busca != "" && busca.length >= 2) {
            $.ajax({
                url: "/api/ajax?class=PessoaAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta',
                    parametros: {
                        'id' : id,
                    },
					token: '<?= $request->token ?>'
                },
                success: function (data) {
                    $('#id_pessoa').val(data[0].id_pessoa);
                }
            });
        }
    }

    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposPessoa() {
        var busca = $('#busca_pessoa').val();

        if (busca == "") {
            $('#id_pessoa').val('');
        }
    }
    
    $(document).on('click', '#habilita_busca_pessoa', function () {
        $('#id_pessoa').val('');
        $('#busca_pessoa').val('');
        $('#busca_pessoa').attr("readonly", false);
        $('#busca_pessoa').focus();
    });

    $(document).on('submit', '#conta_pagar_form', function () {
        
        var id_pessoa = $("#id_pessoa").val();
        var id_natureza_financeira = $("#id_natureza_financeira").val();
        var valor = $("#valor").val();
        var data_vencimento = $('input[name="data_vencimento"]').val();
        var data_emissao = $('input[name="data_emissao"]').val();
        var id_caixa = $("#id_caixa").val();
        var descricao = $("#descricao").val();
        
        var ano_data_vencimento = data_vencimento.split("/")[2];
		var mes_data_vencimento = data_vencimento.split("/")[1];
		var dia_data_vencimento = data_vencimento.split("/")[0];

		var ano_data_emissao = data_emissao.split("/")[2];
		var mes_data_emissao = data_emissao.split("/")[1];
		var dia_data_emissao = data_emissao.split("/")[0];

		var compara_data_vencimento = new Date (ano_data_vencimento, mes_data_vencimento -1, dia_data_vencimento);
		var compara_data_emissao = new Date (ano_data_emissao, mes_data_emissao - 1, dia_data_emissao);

		var valor_conta_pagar_rateio = $('#valor_conta_pagar_rateio').text();
     	var soma_total = $('#soma_total').text();
	     	valor_conta_pagar_rateio = valor_conta_pagar_rateio.replace("R$ ", "");
	     	soma_total = soma_total.replace("R$ ", "");

        if(!id_pessoa || id_pessoa == ""){
            alert("Deve-se selecionar uma pessoa!");
            return false;
        }else if(!id_natureza_financeira || id_natureza_financeira == ""){
            alert("Deve-se selecionar uma natureza financeira!");
            return false;
        }else if(!valor || valor == "" || valor == "0,00"){
            alert("Deve-se inserir um valor!");
            return false;
        }else if(!data_vencimento || data_vencimento == ""){
            alert("Deve-se inserir uma data de vencimento!");
            return false;
        }else if(!id_caixa || id_caixa == ""){
            alert("Deve-se selecionar um caixa!");
            return false;
        }else if(!data_emissao || data_emissao == ""){
            alert("Deve-se inserir uma data de emissão!");
            return false;
        }else if(compara_data_vencimento < compara_data_emissao){
			alert("A data de vencimento deve ser maior que a data de emissão!");
        	return false;
		}else if(soma_total != valor_conta_pagar_rateio){
			alert("Deve-se adicionar o rateio!");
        	return false;
		}

		modalAguarde();

    });
</script>