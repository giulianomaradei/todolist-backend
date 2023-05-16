<?php
require_once(__DIR__."/../class/System.php");

$tituloPainel = 'Inserir';
$operacao = 'inserir';
$id = 1;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> funcionário:</h3>
                </div>
                <form method="post" action="/api/ajax?class=Funcionario.php" id="funcionario_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Pessoa (funcionário):</label>
                                    <div class="input-group">
                                        <input class="form-control input-sm funcionario" id="busca_pessoa" type="text" name="busca_pessoa"  value="<?=$nome_usuario;?>" placeholder="Informe o nome ou CPF/CNPJ..." autocomplete="off" readonly required>
                                        <div class="input-group-btn">
                                            <button class="btn btn-info btn-sm" id="habilita_busca_pessoa" name="habilita_busca_pessoa" type="button" title="Clique para selecionar a pessoa" style="height: 30px;"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id_usuario" id="id_usuario" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Data admissão:</label>
                                    <input type="text" name="data_inicio" id="data_inicio" required class="form-control input-sm date calendar date" value="" require>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Formato:</label>
                                    <select class="form-control input-sm" name="formato" id="formato" required>
                                        <option value="">Selecione</option>
                                        <option value="1">Efetivo</option>
                                        <option value="2">Estágio</option>
                                        <option value="6">Estágio PCD</option>
                                        <option value="3">Jovem aprendiz</option>
                                        <option value="4">PCD</option>
                                        <option value="5">Terceirizado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="escolaridade">*Escolaridade</label>
                                    <select name="escolaridade" id="escolaridade" class="form-control input-create">
                                        <option value="">Selecione</option>
                                        <option value="1">Primeiro grau completo</option>
                                        <option value="2">Primeiro grau incompleto</option>
                                        <option value="3">Segundo grau completo</option>
                                        <option value="4">Segundo grau incompleto</option> 
                                        <option value="5">Superior completo</option> 
                                        <option value="6">Superior incompleto</option> 
                                        <option value="7">Pós-graduação em andamento</option> 
                                        <option value="8">Pós-graduação em completo</option> 
                                        <option value="9">Mestrando</option> 
                                        <option value="10">Mestre</option> 
                                        <option value="11">Doutorando</option> 
                                        <option value="12">>Doutor</option> 
                                    </select>                            
                                </div>
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
<script>

    //BUSCA PESSOA
    // Atribui evento e função para limpeza dos campos
    $('#busca_pessoa').on('input', limpaCamposPessoa);
    // Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_pessoa").autocomplete({
            minLength: 2,
            source: function(request, response){
                $.ajax({
                    url: "/api/ajax?class=UsuarioAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'funcionarioautocomplete',
                        parametros: { 
                            'nome' : $('#busca_pessoa').val(),
                            'atributo' : 'nenhum',
                        },
                        token: '<?= $request->token ?>'
                    },
                    success: function(data){
                        response(data);
                    }
                });
            },
            focus: function (event, ui) {
                $("#busca_pessoa").val(ui.item.nome);
                carregarDadosPessoa(ui.item.id_pessoa);
                return false;
            },
            select: function (event, ui) {
                $("#busca_pessoa").val(ui.item.nome);
                $('#busca_pessoa').attr("readonly", true);
                return false;
            }
        })
        .autocomplete("instance")._renderItem = function (ul, item) {
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
                url: "/api/ajax?class=UsuarioAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta',
                    parametros: { 
                        'id' : id,                            
                    },
                    token: '<?= $request->token ?>'
                },
                success: function (data) {
                    $('#id_usuario').val(data[0].id_usuario);
                }
            });
        }
    }
    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposPessoa() {
        var busca = $('#busca_pessoa').val();

        if (busca == "") {
            $('#id_pessoa_funcionario').val('');
        }
    }
    
    $(document).on('click', '#habilita_busca_pessoa', function () {
        $('#id_pessoa').val('');
        $('#busca_pessoa').val('');
        $('#busca_pessoa').attr("readonly", false);
        $('#busca_pessoa').focus();
    });

    $(document).on('submit', "#funcionario_form", function(){   
        var id_usuario = $("#id_usuario").val();   
        var formato = $("#formato").val();   
        var escolaridade = $("#escolaridade").val();   

        if (id_usuario == '') {
            alert("Deve-se selecionar o funcionário!");
            return false;
        }

        if (formato == '') {
            alert("Deve-se selecionar o formato!");
            return false;
        }

        if (escolaridade == '') {
            alert("Deve-se selecionar a escolaridade!");
            return false;
        }

        modalAguarde();
    });

</script>