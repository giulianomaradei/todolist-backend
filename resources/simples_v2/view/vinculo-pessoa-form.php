
<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
	$tituloPainel = 'Alterar';
	$operacao = 'alterar';
	$id = (int) $_GET['alterar'];
	$dados = DBRead('', 'tb_vinculo_pessoa', "WHERE id_vinculo_pessoa = '$id'");
	$id_pessoa_pai = $dados[0]['id_pessoa_pai'];
	$id_pessoa_filho = $dados[0]['id_pessoa_filho'];
	$dados = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '$id_pessoa_pai'");
	$nome_pessoa_pai = $dados[0]['nome'];
	$dados = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '$id_pessoa_filho'");
    $nome_pessoa_filho = $dados[0]['nome'];
    $display = 'none';
    $tipo = 'null';
    $display_dados = 'none';
    $nome = '';
    $telefone = '';
    $email = '';
    
} else {
	$tituloPainel = 'Inserir';
	$operacao = 'inserir';
    $id = 1;
	$id_pessoa_pai = (int) $_GET['vincular'];
	$dados = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '$id_pessoa_pai'");
	$nome_pessoa_pai = $dados[0]['nome'];
	$id_pessoa_filho = '';
    $nome_pessoa_filho = '';
    $display = 'block';

    $id_rd_conversao = (!empty($_GET['id_rd_conversao'])) ? $_GET['id_rd_conversao'] : '';

    if ($id_rd_conversao) {
        $dados_rd_conversao = DBRead('', 'tb_rd_conversao', "WHERE id_rd_conversao = $id_rd_conversao");
        $tipo = 'nova';
        $display_dados = 'block';
        $nome = $dados_rd_conversao[0]['name'];
        $telefone = $dados_rd_conversao[0]['telefone'];
        $email = $dados_rd_conversao[0]['email'];

    } else {
        $tipo = 'null';
        $display_dados = 'none';
        $nome = '';
        $telefone = '';
        $email = '';
    }
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?=$tituloPainel?> vínculo de pessoa a <strong><?=$nome_pessoa_pai?></strong>:</h3>
                    <?php

                    $usuario = $_SESSION['id_usuario'];

                    if (isset($_GET['alterar'])) {echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=VinculoPessoa.php?excluir=$id&usuario=$usuario&pessoa=$id_pessoa_filho&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>";}?>
                </div>

                <form method="post" action="/api/ajax?class=VinculoPessoa.php" id="vinculo_pessoa_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <input type="hidden" name="id_usuario" value="<?=$_SESSION['id_usuario']?>">
                    <input type="hidden" name="id_rd_conversao" value="<?=$id_rd_conversao?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row" style="display: <?=$display?>">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Tipo de pessoa</label>
                                    <select class="form-control input-sm" name="tipo_de_pessoa" id="tipo_de_pessoa" required>
                                        <?php $sel_tipo[$tipo] = 'selected'; ?>
                                        <option value="null" <?=$tipo == "null" ? "selected" : "";?>>---</option>
                                        <option value="nova" <?=$tipo == "nova" ? "selected" : "";?>>Nova</option>
                                        <option value="existente" <?=$tipo == "existente" ? "selected" : "";?>>Existente</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="row-pessoa-nova" style="display: <?= $display_dados ?>;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nome:</label>
                                    <input name="nome" id="nome" class="form-control input-sm" value="<?= $nome ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Telefone:</label>
                                    <input name="telefone" id="telefone" class="form-control input-sm phone" value="<?= $telefone ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email:</label>
                                    <input name="email" id="email" class="form-control input-sm" value="<?= $email ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row" id="row-pessoa-existente" style="display: none;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Pessoa:</label>
                                    <div class="input-group">
                                        <input class="form-control input-sm" id="busca_pessoa" type="text" name="busca_pessoa"  value="<?=$nome_pessoa_filho;?>" placeholder="Informe o nome ou CPF/CNPJ..." autocomplete="off" readonly required>
                                        <div class="input-group-btn">
                                            <button class="btn btn-info btn-sm" id="habilita_busca_pessoa" name="habilita_busca_pessoa" type="button" title="Clique para selecionar a pessoa" style="height: 30px;"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id_pessoa_filho" id="id_pessoa" value="<?=$id_pessoa_filho;?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <hr>

                                <div class='table-responsive' style="max-height: 420px; overflow-y:auto;">
                                    <table class='table table-hover table_paginas' style='font-size: 14px;'>
                                        <thead>
                                            <tr>
                                                <th class="col-md-2">Vínculo</th>
                                                <th class="col-md-10">Tipo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $dados = DBRead('', 'tb_vinculo_tipo', 'ORDER BY nome ASC');
                                            foreach($dados as $conteudo){
                                            	$id_vinculo_tipo = $conteudo['id_vinculo_tipo'];
                                            	$nome = $conteudo['nome'];
                                            	$ckecked = '';
                                            	if($operacao == 'alterar'){
                                            		$dados = DBRead('', 'tb_vinculo_tipo_pessoa', "WHERE id_vinculo_tipo = '$id_vinculo_tipo' AND id_vinculo_pessoa = '$id'");
                                            		if($dados){
                                            			$ckecked = 'checked';
                                            		}
                                            		echo "<tr><td><input name=\"tipos_vinculos[]\" type=\"checkbox\" value=\"$id_vinculo_tipo\" id=\"$id_vinculo_tipo\" $ckecked></td> <td>$nome</td></tr>";
                                            	}else{
                                            		echo "<tr><td><input name=\"tipos_vinculos[]\" type=\"checkbox\" value=\"$id_vinculo_tipo\" id=\"$id_vinculo_tipo\" $ckecked></td> <td>$nome</td></tr>";
                                            	}
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">

                                <input type="hidden" id="operacao" value="<?=$id;?>" name="<?=$operacao;?>"/>
                                <input type="hidden" id="id_pessoa_pai" value="<?=$id_pessoa_pai;?>" name="id_pessoa_pai"/>
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>

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
                        'nome' : $('#busca_pessoa').val()
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

    $(document).on('click', '#checkTodos', function () {
        if ( $(this).is(':checked') ){
            $('input:checkbox').prop("checked", true);
        }else{
           $('input:checkbox').prop("checked", false);
        }
    });

    $(document).on('submit', '#vinculo_pessoa_form', function () {

        var id_pessoa_filho = $("#id_pessoa").val();
        var tipo_de_pessoa = $('#tipo_de_pessoa').val();
        var display = '<?php echo $display ?>';

        console.log(display);

        if(tipo_de_pessoa == 'null' && display == 'block'){
            alert("Deve-se informar o tipo de pessoa!");
            $('#tipo_de_pessoa').focus();
            return false;
        }
        
        if(!id_pessoa_filho && tipo_de_pessoa =='existente'){
            alert("Deve-se vincular uma pessoa válida!");
            return false;
        }

        modalAguarde();
    });

    $('#tipo_de_pessoa').on('change', function(){
        tipo_de_pessoa = $(this).val();

        if(tipo_de_pessoa == 'nova'){
           $('#row-pessoa-nova').css('display', 'block');
           $('#row-pessoa-existente').css('display', 'none');
           $('#busca_pessoa').attr('required', false);
           $('#nome').attr('required', true);
        }
        if(tipo_de_pessoa == 'existente'){
           $('#row-pessoa-nova').css('display', 'none');
           $('#row-pessoa-existente').css('display', 'block');
           $('#busca_pessoa').attr('required', true);
           $('#nome').attr('required', false);
        }
        if(tipo_de_pessoa == 'null'){
           $('#row-pessoa-nova').css('display', 'none');
           $('#row-pessoa-existente').css('display', 'none');
        }
    });
</script>