<?php
require_once(__DIR__."/../class/System.php");

    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;

    $data_hoje = converteData(getDataHora('data'));

?>
 
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> Conta a Receber:</h3>
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=Categoria.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
                </div>
                <form method="post" action="/api/ajax?class=ContaReceber.php" id="conta_receber_form" style="margin-bottom: 0;">
					<input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                    	<ul class="nav nav-tabs">
                            <li class="aba1 active">
                                <a data-toggle="tab" href="#tab1">Dados da Conta a Receber</a>
                            </li>
                            <li class="aba2 ">
                                <a data-toggle="tab" href="#tab2">Forma de Pagamento</a>
                            </li>
                        </ul>

                        <input type="hidden" name="impostos" id="impostos" value="2">
                        <div class="tab-content">

                            <!-- tab 1 Dados pessoais  -->
                            <div id="tab1" class="tab-pane fade in active">
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                        	<div class="col-md-12">
				                                <div class="form-group">
				                                    <label>*Origem:</label>
				                                    <select class="form-control input-sm" name="origem" id="origem">
				                                        <option value="2">Contrato</option>
				                                        <option value="1">Pessoa</option>
				                                    </select>
				                                </div>
				                            </div>
				                        </div>
                                        <div class="row">
				                            <div class="col-md-12" style="display:none;" id="row_pessoa">
				                                <div class="form-group">
				                                    <label>*Pessoa (cliente):</label>
				                                    <div class="input-group">
				                                        <input class="form-control input-sm" id="busca_pessoa" type="text" name="busca_pessoa"  value="<?=$nome_pessoa_filho;?>" placeholder="Informe o nome ou CPF/CNPJ..." autocomplete="off" readonly>
				                                        <div class="input-group-btn">
				                                            <button class="btn btn-info btn-sm" id="habilita_busca_pessoa" name="habilita_busca_pessoa" type="button" title="Clique para selecionar a pessoa" style="height: 30px;"><i class="fa fa-search"></i></button>
				                                        </div>
				                                    </div>
				                                    <input type="hidden" name="id_pessoa" id="id_pessoa">
				                                </div>
				                            </div>

				                            <div class="col-md-12" id="row_contrato">
				                                <div class="form-group">
				                                    <label>*Contrato (cliente):</label>
				                                    <div class="input-group">
				                                        <input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato"  value="<?=$contrato?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly required>
				                                        <div class="input-group-btn">
				                                            <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
				                                        </div>
				                                    </div>
				                                    <input type='hidden' name='id_contrato_plano_pessoa' id='id_contrato_plano_pessoa'>
				                                </div>
				                            </div>
										</div>
                                        <div class="row">
				                            <div class="col-md-12">
				                                <div class="form-group">
				                                    <label>*Valor:</label>
				                                    <input class="form-control input-sm money" name="valor" id="valor" type="text" autocomplete="off" value="0,00" required>
				                                </div>
				                            </div>
										</div>
                                        <div class="row">
				                           	<div class="col-md-6">
				                                <div class="form-group">
				                                    <label>*Natureza Financeira:</label>
				                                    <select class="form-control input-sm" name="id_natureza_financeira" id="id_natureza_financeira">
				                                        <option value="">Selecione a Natureza Financeira...</option>
				                                        <?php
				                                            $dados_natureza_financeira = DBRead('', 'tb_natureza_financeira', "WHERE status = 1 AND tipo = 'conta_receber' ORDER BY nome ASC");

				                                            if ($dados_natureza_financeira) {
				                                                foreach ($dados_natureza_financeira as $conteudo_natureza_financeira) {
				                                                    echo "<option value='".$conteudo_natureza_financeira['id_natureza_financeira']."'>".$conteudo_natureza_financeira['nome']."</option>";
				                                                }
				                                            }
				                                        ?>
				                                    </select>
				                                </div>
				                            </div>
				                            <div class="col-md-6">
				                                <div class="form-group">
				                                    <label>*Caixa:</label>
				                                    <select class="form-control input-sm" name="id_caixa" id="id_caixa">
				                                        <option value="">Selecione o Caixa...</option>
				                                        <?php
				                                            $dados_caixa = DBRead('', 'tb_caixa', "WHERE status = 1 ORDER BY nome ASC");

				                                            if ($dados_caixa) {
				                                                foreach ($dados_caixa as $conteudo_caixa) {
				                                                    echo "<option value='".$conteudo_caixa['id_caixa']."' class='aceita_".$conteudo_caixa['aceita_movimentacao']."'>".$conteudo_caixa['nome']."</option>";
				                                                }
				                                            }
				                                        ?>
				                                    </select>
				                                </div>
				                            </div>
										</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
				                            <div class="col-md-12">
				                                <div class="form-group">
				                                    <label>Descrição:</label>
				                                    <textarea name="descricao" id="descricao" rows = '12' cols="100" class="form-control"></textarea>
				                                </div>
				                            </div>
				                        </div>   
                                    </div>
                                </div>
                            </div>
                            <!-- end tab 1 Dados pessoais -->

                            <!-- tab 2 Dados Conta Receber  -->
                            <div id="tab2" class="tab-pane fade in ">
                                <br>
                            	<div class="row">
		                            <div class="col-md-12">
		                                <div class="form-group">
		                                    <div class="row">
												<div class="col-md-4" id="div_qtd_parcelas">
					                                <div class="form-group">
					                                    <label>*Quantidade de Parcelas:</label>
					                                    <select name="qtd_parcela" id="qtd_parcela" class="form-control" disabled>
					                                        <option value="" selected></option>
					                                    </select>
					                                </div>
					                            </div>
		                                    	<div class="col-md-4">
					                                <label>Boleto:</label>
		                                            <div class="input-group">
		                                            <span class="input-group-addon">
		                                                <input type="checkbox" name="emitir_boleto" id="emitir_boleto" value="1">
		                                            </span>
		                                            <input type="text" class="form-control mensagem" aria-label="..." disabled value="Emitir Boleto" style="cursor: context-menu; background-color: white;">
		                                            </div>
		                                        </div>

		                                        <div class="col-md-4">
					                                <label>NFS-e:</label>
		                                            <div class="input-group">
		                                            <span class="input-group-addon">
		                                                <input type="checkbox" name="emitir_nfs" id="emitir_nfs" value="1">
		                                            </span>
		                                            <input type="text" class="form-control mensagem" aria-label="..." disabled value="Emitir NFS-e" style="cursor: context-menu; background-color: white;">
		                                            </div>
		                                        </div>
		                                        
		                                    </div>
		                                </div>
		                            </div>
								</div>    

		                        <div class="row" id='row_resultado'>
		                        </div>

		                        <div class="row" id='row_total_parcelas'>
		                            <hr>
		                            <div class="col-md-6">
		                                <div class="form-group">
		                                    <h3 class='panel-title text-center'>Valor da Conta a Receber: <strong id='valor_conta_receber_exibe'>R$ 0,00</strong></h3>
		                                </div>
		                            </div>
		                            <div class="col-md-6">
		                                <div class="form-group">
		                                    <h3 class='panel-title text-center'>Valor Total da Soma das Parcelas: <strong id='soma_total' style='color:blue;'>R$ 0,00</strong></h3>
		                                </div>
		                            </div>
		                        </div>

		                    </div>		                    
	                        
                            <!-- end tab 2 Dados Conta Receber -->
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

$(document).on('change', '#id_caixa', function(){
	if($('#id_caixa option:selected').attr('class') == 'aceita_0'){
		$("#emitir_boleto").attr("disabled", true);
		$("#emitir_nfs").attr("disabled", true);
		$("#emitir_boleto").prop("checked", false);
		$("#emitir_nfs").prop("checked", false);
		
	}else{
		$("#emitir_boleto").attr("disabled", false);
		$("#emitir_nfs").attr("disabled", false);
	}

});

	$('#descricao').keyup(function(){
        var string = this.value;
        var novastring = "";
        var linhas = new Array();
        linhas = string.split("\n");
        var contador = linhas.length;
        var trocarLinha = false;
        
        for (x in linhas){
            if(linhas[x].length > this.cols-2){
                linhas[x] = linhas[x].substring(0, this.cols);
                //alert(linhas[x].substring(0, this.cols));
                trocarLinha=true;
            }
            if(x < this.rows){
                novastring += linhas[x] + "\n";
            }
        }
        if (contador > this.rows || trocarLinha) {
            //alert('Não é possível inserir mais de 12 linhas!');
            this.value = novastring.substring(0, novastring.length-1);
        }
        return contador <= this.rows;
    });

	$(document).on('change', 'select[name=origem]', function(){
        
		if($(this).val() == '1'){
			$('#row_contrato').hide();
			$('#row_pessoa').show();

            $("#busca_pessoa").attr("required", "req");
            $("#busca_contrato").removeAttr('required');

		}else if($(this).val() == '2'){
			$('#row_contrato').show();
			$('#row_pessoa').hide();

			$("#busca_contrato").attr("required", "req");
            $("#busca_pessoa").removeAttr('required');

		}
    });

    $(document).on('change', '.data_vencimento', function(){

    	var data_vencimento_parcelas = $(this).val();
    	var data_emissao = '<?=$data_hoje?>';

    	var ano_data_vencimento = data_vencimento_parcelas.split("/")[2];
		var mes_data_vencimento = data_vencimento_parcelas.split("/")[1];
		var dia_data_vencimento = data_vencimento_parcelas.split("/")[0];

		var ano_data_emissao = data_emissao.split("/")[2];
		var mes_data_emissao = data_emissao.split("/")[1];
		var dia_data_emissao = data_emissao.split("/")[0];

		var compara_data_vencimento = new Date (ano_data_vencimento, mes_data_vencimento -1, dia_data_vencimento);
		var compara_data_emissao = new Date (ano_data_emissao, mes_data_emissao - 1, dia_data_emissao);
    	//alert(data_vencimento_parcelas+' - - - - - - - '+data_emissao);

		if(compara_data_vencimento < compara_data_emissao){
			alert("A data de vencimento deve ser maior que a data de emissão!");
	        $("#ok").prop('disabled', true);
	        $(this).css("color", "red");
		}else{

	        $("#ok").prop('disabled', false);

			$(".data_vencimento").each(function(){
    			
    			var data_vencimento_parcelas_each = $(this).val();
				
				var ano_data_vencimento = data_vencimento_parcelas_each.split("/")[2];
				var mes_data_vencimento = data_vencimento_parcelas_each.split("/")[1];
				var dia_data_vencimento = data_vencimento_parcelas_each.split("/")[0];					
				
				var compara_data_vencimento_each = new Date (ano_data_vencimento, mes_data_vencimento -1, dia_data_vencimento);
				
				if(compara_data_vencimento_each < compara_data_emissao){

					alert("A data de vencimento deve ser maior que a data de emissão!");
	        		$("#ok").prop('disabled', true);
	        		$(this).css("color", "red");
				}else{
	        		$(this).css("color", "black");
				}

	        });
		}
    });

    $(document).on('keyup', '.parcela', function(){
     	
     	var valor_total = '00.00';
        var valor_conta_receber = ($("#valor").val());
		
		var total = valor_conta_receber.length;
		var i = 0;
		var palavra = '';
		for(i=0; i<total; i++){
			if(valor_conta_receber[i] != '.'){
				palavra = palavra+''+valor_conta_receber[i];

			}
		}
		
		palavra = palavra.replace(",", ".");
		valor_conta_receber = palavra;
		$(".parcela").each(function(){
            
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
				console.log(valor_total.toFixed(2) +'        '+ (valor_conta_receber));

            } 

			if(valor_total.toFixed(2) > (valor_conta_receber)){
				$("#soma_total").css("color", "red");
	        	$("#ok").prop('disabled', true);
	        }else if(valor_total.toFixed(2) == (valor_conta_receber)){
	            $("#soma_total").css("color", "green");
	        	$("#ok").prop('disabled', false);
	        }else if(valor_total.toFixed(2) == '0.00'){
	        	$("#soma_total").css("color", "blue");
	        	$("#ok").prop('disabled', false);
	        }else{
	        	$("#soma_total").css("color", "blue");
	        	$("#ok").prop('disabled', true);
	        }
			

	        $('#soma_total').text('R$ ' + floatMoeda(valor_total.toFixed(2)));

		});

    });

    $("#valor").on('keyup', function(){
     	$('#valor_conta_receber_exibe').text('R$ '+$(this).val());
     	

     	$("#div_qtd_parcelas").show();
        $("#row_resultado").show();
        $("#row_total_parcelas").show();


		
        var valor_total = $(this).val();
        if(valor_total != '' && valor_total != '0,00'){


			var total = valor_total.length;
			var i = 0;
			var palavra = '';
			for(i=0; i<total; i++){
				if(valor_total[i] != '.'){
					palavra = palavra+''+valor_total[i];

				}
			}
			
			palavra = palavra.replace(",", ".");
			valor_total = palavra;


			
            // if (valor_total.indexOf('.') > -1){
            //     var valor_total = valor_total.split(".")[0]+''+valor_total.split(".")[1];

            // }
			// console.log(valor_total.split(".")[0] + ' ----- ' +valor_total.indexOf('.'));
			

            // var valor_total = valor_total.split(",")[0]+'.'+valor_total.split(",")[1];
			// console.log(valor_total);

            atualizaValores(1, valor_total);

        }
        $('#qtd_parcela option[value=1]').attr('selected','selected');

		//AQUI
        $("#qtd_parcela").prop('disabled', false);

        if($(this).val() == '0,00' || $(this).val() == ''){
            $("#qtd_parcela").prop('disabled', true);
            $("select[name=qtd_parcela]").html('<option value=""></option>');
            atualizaValores(0, $(this).val());
            $('#row_total_parcelas').hide();
        }else{
            var options = '';
            for (i = 1; i < 11; i++) {
                options += '<option value="'+i+'">'+i+'</option>';
            }
            $("select[name=qtd_parcela]").html(options);
        }

        var soma_total = $('#soma_total').text();
        var valor_conta_receber_exibe = $('#valor_conta_receber_exibe').text();
     	valor_conta_receber_exibe = valor_conta_receber_exibe.replace("R$ ", "");
     	soma_total = soma_total.replace("R$ ", "");

        var valor_total = 0;
 		$(".parcela").each(function(){
            // if($(this).val() != '0,00' || $(this).val() != ''){
			// 	var total = valor_total.length;
			// 	var i = 0;
			// 	var palavra = '';
			// 	for(i=0; i<total; i++){
			// 		if(valor_total[i] != '.'){
			// 			palavra = palavra+''+valor_total[i];

			// 		}
			// 	}
				
			// 	var palavra = palavra.replace(",", ".");
			// 	valor_total = palavra;
			// 	console.log(valor_total + '      '+(($(this).val())));
            // }
			/*if(valor_total > valor_conta_receber_exibe){
				$("#soma_total").css("color", "red");
	        	$("#ok").prop('disabled', true);
	        }else if(valor_total == valor_conta_receber_exibe){
	            $("#soma_total").css("color", "green");
	        	$("#ok").prop('disabled', false);
	        }else if(valor_total == '0.00'){
	        	$("#soma_total").css("color", "blue");
	        	$("#ok").prop('disabled', false);
	        }else{*/
	        	$("#soma_total").css("color", "green");
	        	$("#ok").prop('disabled', false);
	        //}
	       	
	        $('#soma_total').text('R$ ' + floatMoeda($(this).val()));
		});
    });

    $(document).on('change', 'select[name=qtd_parcela]', function(){
        
        var valor_total = $("#valor").val();
        if(!valor_total){
            return false;
        }
        var total = valor_total.length;
		var i = 0;
		var palavra = '';
		for(i=0; i<total; i++){
			if(valor_total[i] != '.'){
				palavra = palavra+''+valor_total[i];

			}
		}
		
		palavra = palavra.replace(",", ".");
		valor_total = palavra;
        var parcelas = $(this).val();

        atualizaValores(parcelas, valor_total);

    });

    //Funcao para atualizar as parcelas e seus valores
    function atualizaValores(numero_parcelas, valor_total){

		// console.log(numero_parcelas+ ' ------ ' +valor_total);
        //variavel que recebe os inputs(HTML)
        var geraInputs="";

        //Calculando o valor de cada parcela
        var valor_parcela = parseFloat(valor_total/numero_parcelas).toFixed(2);
        var parcelaObj = getValorParcelas(valor_total,numero_parcelas);
        
            var hoje = new Date();
            var hoje = new Date();
            var ano = hoje.getFullYear();
            var mes = hoje.getMonth();
                mes = mes+1;
            var dia = hoje.getDate();
            var hora = hoje.getHours();
            var minutos = hoje.getMinutes();
            var segundos = hoje.getSeconds();

            var data_inicial = dia+'/'+mes+'/'+ano;
            
             //gerando os inputs com os valores de cada parcela
            var contador = 0;


            for(var i=0; i<numero_parcelas;i++){
                contador ++;

                //parcelas  
                if(geraInputs == ""){
                    var valor_parcela = floatMoeda(parcelaObj.valor_primeira_parcela.toFixed(2));
                }else{
                    var valor_parcela = floatMoeda(parseFloat(parcelaObj.valor_parcela).toFixed(2));
                }

                //data do vencimento
                var mes_contador = mes+contador

                if(mes_contador == 12){
                    var mes_proximo = '12';
                }else if(mes_contador > 12){
                    var mes_proximo = (mes_contador)%12
                }else{
                    var mes_proximo = mes+1;
                }
                if(dia <10 && i == 0){
                    var dia = '0'+dia;
                }

                if(mes_proximo <10){
                    var mes_proximo = '0'+mes_proximo;
                }

                mes_contador = mes_contador/12;
                mes_contador = mes_contador.toString();
        
                if (mes_contador.indexOf('.') > -1 && mes_contador != 1){
                    var ano_exemplo = mes_contador.split(".")[0];
                }else if(mes_contador == 1){
                    var ano_exemplo = 0;
                }
                
                ano_proximo = parseInt(ano_exemplo) + parseInt(ano);

                var data_proximo = dia+'/'+mes_proximo+'/'+ano_proximo;
                
                geraInputs += '<div class="col-md-6"><div class="form-group"><label>*Valor (Parcela '+contador+'):</label><input id="id_'+contador+'" name="parcela[]" value="'+valor_parcela+'" type="text" class="form-control money parcela" required ></div></div><div class="col-md-6"><div class="form-group"><label>*Data do Vencimento (Parcela '+contador+'):</label><input name="data_vencimento[]" value="'+data_proximo+'" id="data_vencimento_'+contador+'" type="text" class="form-control date calendar hasDatePicker hasDatepicker data_vencimento" required></div></div>';
            }

        // inserindo as parcelas 
        $("#row_resultado").html(geraInputs);
            
        configuraDatepicker();
        configuraMascaras();
      
    }

    function getValorParcelas(precoTotal,numeroParcelas){
        
        var valorParcela            = parseFloat(precoTotal/numeroParcelas).toFixed(2);
        var valorPrimeiraParcela    = precoTotal-(valorParcela*(numeroParcelas-1));
        return { 'valor_primeira_parcela': valorPrimeiraParcela , 'valor_parcela': valorParcela, 'numero_parcelas': numeroParcelas };
    }	

    $(document).on('submit', '#conta_receber_form', function () {
		
		var impostos = $("#impostos").val();
		var qtd_parcela = $("#qtd_parcela").val();
        
        var id_pessoa = $("#id_pessoa").val();
        var id_natureza_financeira = $("#id_natureza_financeira").val();
        var valor = $("#valor").val();
        var data_vencimento_conta_receber = $('input[name="data_vencimento_conta_receber"]').val();
        var id_caixa = $("#id_caixa").val();
        var descricao = $("#descricao").val();

		var origem = $("#origem").val();
        var id_contrato_plano_pessoa = $("#id_contrato_plano_pessoa").val();

        if((!id_pessoa || id_pessoa == "") && origem == '1'){
            alert("Deve-se selecionar uma pessoa!");
            return false;
        }if((!id_contrato_plano_pessoa || id_contrato_plano_pessoa == "") && origem == '2'){
            alert("Deve-se selecionar um contrato!");
            return false;
        }else if(!id_natureza_financeira || id_natureza_financeira == ""){
            alert("Deve-se selecionar uma natureza financeira!");
            return false;
        }else if(!id_caixa || id_caixa == ""){
            alert("Deve-se selecionar um caixa!");
            return false;
        }else if(!valor || valor == "" || valor == "0,00"){
            alert("Deve-se inserir um valor!");
            return false;
        }else if ($('#emitir_nfs').is(':checked')){
			if(!descricao || descricao == ''){
				alert('Deve-se inserir uma descricao para emitir NFS-e!');
				return false;
			}
		}else if(qtd_parcela > 1 && impostos == 1 && $('#emitir_nfs').is(':checked')){
			alert("Este contrato retém algum imposto e não pode ser parcelado!");
			return false;
		}
		
		modalAguarde();

    });

    //Busca Pessoa

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
	                        'atributo' : 'cliente'
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

    //Busca Pessoa

    //Busca Contrato

	    // Atribui evento e função para limpeza dos campos
	    $('#busca_contrato').on('input', limpaCamposContrato);

	    // Dispara o Autocomplete da pessoa a partir do segundo caracter
	    $("#busca_contrato").autocomplete({
	            minLength: 2,
	            source: function (request, response) {
	                $.ajax({
	                    url: "/api/ajax?class=ContratoAutocomplete.php",
	                    dataType: "json",
	                    data: {
	                        acao: 'autocomplete',
	                        parametros: { 
	                            'nome' : $('#busca_contrato').val(),
	                            'pagina' : 'conta_receber',
	                        },
							token: '<?= $request->token ?>'
	                    },
	                    success: function (data) {
	                        response(data);
	                    }
	                });
	            },
	            focus: function (event, ui) {
	                $("#busca_contrato").val(ui.item.nome + " " + ui.item.nome_contrato +" - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
	                carregarDadosContrato(ui.item.id_contrato_plano_pessoa);
	                return false;
	            },
	            select: function (event, ui) {
		            if(ui.item.reter_cofins == 1 || ui.item.reter_csll == 1 || ui.item.reter_ir == 1 || ui.item.reter_pis == 1){
						$("#impostos").val('1');
		            }else{
						$("#impostos").val('2');
		            }

	                $("#busca_contrato").val(ui.item.nome + " "+ ui.item.nome_contrato + " - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
	                $('#busca_contrato').attr("readonly", true);
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
	        if(!item.nome_contrato){
	            item.nome_contrato = '';
	        }else{
	            item.nome_contrato = ' ('+item.nome_contrato+') '; 
	        }
	        return $("<li>").append("<a><strong>"+item.id_contrato_plano_pessoa + " - " + item.nome + ""+item.nome_contrato+" </strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "<br>" + item.servico + " - " + item.plano + " (" + item.id_contrato_plano_pessoa + ")" + "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
	    };

	    // Função para carregar os dados da consulta nos respectivos campos
	    function carregarDadosContrato(id) {
	        var busca = $('#busca_contrato').val();
	        if(busca != "" && busca.length >= 2){
	            $.ajax({
	                url: "/api/ajax?class=ContratoAutocomplete.php",
	                dataType: "json",
	                data: {
	                    acao: 'consulta',
	                    parametros: {
	                        'id' : id,
	                    },
						token: '<?= $request->token ?>'
	                },
	                success: function (data) {
	                    $('#id_contrato_plano_pessoa').val(data[0].id_contrato_plano_pessoa);
	                }
	            });
	        }
	    }

	    // Função para limpar os campos caso a busca esteja vazia
	    function limpaCamposContrato() {
	        var busca = $('#busca_contrato').val();
	        if (busca == "") {
	            $('#id_contrato_plano_pessoa').val('');
	        }
	    }

	    $(document).on('click', '#habilita_busca_contrato', function () {
	        $('#id_contrato_plano_pessoa').val('');
	        $('#busca_contrato').val('');
	        $('#busca_contrato').attr("readonly", false);
	        $('#busca_contrato').focus();
	    });

    //Busca Contrato

</script>