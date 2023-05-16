<?php
require_once(__DIR__."/../class/System.php");

$operacao = 'inserir';
$id = 1;

$selecionar = (!empty($_POST['selecionar_conta_pagar'])) ? $_POST['selecionar_conta_pagar'] : '';

$dados = DBRead('', 'tb_conta_pagar a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_natureza_financeira c ON a.id_natureza_financeira = c.id_natureza_financeira INNER JOIN tb_natureza_financeira_agrupador d ON c.id_natureza_financeira_agrupador = d.id_natureza_financeira_agrupador WHERE id_conta_pagar = '".$selecionar[0]."' ", "a.*, b.nome, c.nome AS nome_natureza, d.nome AS nome_natureza_agrupador");

$natureza = $dados[0]['nome_natureza_agrupador']." (".$dados[0]['nome_natureza'].")";
$nome_pessoa = $dados[0]['nome'];
$valor = converteMoeda($dados[0]['valor']);
$data_vencimento = converteData($dados[0]['data_vencimento']);

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Clonar Conta a Pagar:</h3>
                </div>
                <form method="post" action="/api/ajax?class=ContaPagar.php" id="clonar_conta_pagar" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <input type="hidden" id="id_conta_pagar" name="id_conta_pagar" value="<?=$selecionar[0]?>"/>
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pessoa:</label>
                                    <input name="pessoa" id="pessoa" type="text" class="form-control input-sm money" value="<?= $nome_pessoa?>" required readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Natureza Financeira</label>
                                    <input name="natureza_financeira" id="natureza_financeira" type="text" class="form-control input-sm money" value="<?= $natureza ?>" required readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6" id="div_valor_total">
                                <div class="form-group">
                                    <label>Valor da Conta a Pagar:</label>
                                    <input name="valor_total" id="valor_total" type="text" class="form-control input-sm money" value="<?= $valor ?>" required readonly>
                                </div>
                            </div>
                            <div class="col-md-6" id="div_valor_total">
                                <div class="form-group">
                                    <label>Data do Vencimento:</label>
                                    <input name="data_vencimento" id="data_vencimento" type="text" class="form-control input-sm money" value="<?= $data_vencimento ?>" required readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
							<div class="col-md-12" id="div_qtd_parcelas">
	                            <div class="form-group">
	                                <label>*Quantidade de Vezes:</label>
	                                <select name="qtd_parcela" id="qtd_parcela" class="form-control input-sm">
									<option value="">Selecione a Quantidade de Vezes...</option>
	                                <?php
	                                	$contador = 1;
	                                	while($contador<112){
	                                		echo '<option value="'.$contador.'">'.$contador.'</option>';
	                                		$contador++;
	                                	}
	                                ?>
	                                </select>
	                            </div>
	                        </div>
                    	</div>
                        <div class="row" id='row_resultado'>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="1" name="clonar_conta_pagar"/>
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

    $(document).on('submit', '#clonar_conta_pagar', function () {
    	if(!$('#qtd_parcela').val() && $('#qtd_parcela').val() == ''){
    		alert('Selecione a quantidade de vezes!');
			return false;
    	}

        modalAguarde();
        
    });

    $(document).on('change', 'select[name=qtd_parcela]', function(){

        var valor_total = $("#valor_total").val();

        if(!valor_total){
            return false;
        }
        if (valor_total.indexOf('.') > -1){
            var valor_total = valor_total.split(".")[0]+''+valor_total.split(".")[1];
        }
        var valor_total = valor_total.split(",")[0]+'.'+valor_total.split(",")[1];
        var parcelas = $(this).val();

        atualizaValores(parcelas, valor_total);

    });

    //Funcao para atualizar as parcelas e seus valores
    function atualizaValores(numero_parcelas, valor_total){

        //variavel que recebe os inputs(HTML)
        var geraInputs="";

        //Calculando o valor de cada parcela
        var valor_parcela = parseFloat(valor_total/1).toFixed(2);
        var parcelaObj = getValorParcelas(valor_total,1);
        
            var data_inicial = $('#data_vencimento').val();
			
			dia = data_inicial.split("/")[0];
			mes = data_inicial.split("/")[1];
			ano = data_inicial.split("/")[2];
			var d = new Date(ano, mes-1, dia);
			
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
	
				d.setMonth(d.getMonth() + 1);
				mes = d.getMonth();
				mes =parseInt(mes)+parseInt(1);
				if(mes <10){
					mes = '0'+mes;
				}

				if(d.getDate() < 10){
					dia = '0'+d.getDate();
				}
				var data_proximo = (dia+'/'+mes+'/'+d.getFullYear());

                geraInputs += '<div class="col-md-12"><div class="form-group"><label>*Data do Vencimento ('+contador+'):</label><input name="data_vencimento[]" value="'+data_proximo+'" id="data_vencimento_'+contador+'" type="text" class="form-control input-sm date calendar hasDatePicker hasDatepicker" required></div></div>';
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

    $('#valor_total').keyup(function(){
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

            var valor_total = $(this).val();

            if (valor_total.indexOf('.') > -1){
                var valor_total = valor_total.split(".")[0]+''+valor_total.split(".")[1];
            }
            var valor_total = valor_total.split(",")[0]+'.'+valor_total.split(",")[1];
            atualizaValores(1, valor_total);

            $('#soma_total').text('R$ '+floatMoeda(valor_total));
        }

    });

    $(document).ready(function(){
        $('#row_total_parcelas').hide();

        $('#row_resultado').on('keyup', '.parcela', function(){
            var valor_total = $("#valor_total").val();

            var soma_total = 0.00;

            $("[name ='parcela[]']").each(function(){
                soma_total = parseFloat(soma_total) + parseFloat(moedaFloat($(this).val()));
            });

            if(parseFloat(soma_total.toFixed(2)) == parseFloat(moedaFloat(valor_total))){
                $("#soma_total").css("color", "green");
            }else{
                $("#soma_total").css("color", "red");
            }
            $('#soma_total').text('R$ '+floatMoeda(soma_total.toFixed(2)));
        });
    });

</script>