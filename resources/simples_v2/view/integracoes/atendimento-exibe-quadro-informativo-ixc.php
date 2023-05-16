<?php
/**
 * Arquivo de suporte para a integração em atendimento-exibe-quadro-informativo.php
 * Neste arquivo é montado todo o conteúdo de 'Informações do cliente (Sistema de gestão):' e contém funções ajax para a busca de contratos do cliente, 
 * logins do cliente, planos de velocidade do cliente, cidades, atendimentos e ordens de serviço abertos e os últimos fechados, interações de ordens de
 * serviço e mensagens vinculadas a atendimentos, contém as funções utilitárias para reinicialização remota de equipamentos, desbloqueio de contrato,
 * diagnóstico de conexão e a segunda via do boleto
 */

$reiniciar_conexao = 1;
$diagnosticar_conexao = 1;
$desbloquear_contrato = 1;
$enviar_boleto = 1;
$acesso_login = 1;
$zerar_mac = 1;
$sinal_rx = 1;
$senha_wifi = 1;
$desbloquear_vel_reduzida = 1;


$integracao_recursos = DBRead('', 'tb_integracao_recursos', "WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");

if($integracao_recursos){
    foreach($integracao_recursos as $recurso){
        if($recurso['nome'] == 'reiniciar_conexao'){
            $reiniciar_conexao = $recurso['ativo'];
        }
        if($recurso['nome'] == 'diagnosticar_conexao'){
            $diagnosticar_conexao = $recurso['ativo'];
        }
        if($recurso['nome'] == 'desbloquear_contrato'){
            $desbloquear_contrato = $recurso['ativo'];
        }
        if($recurso['nome'] == 'enviar_boleto'){
            $enviar_boleto = $recurso['ativo'];
        }
        if($recurso['nome'] == 'acesso_login'){
            $acesso_login = $recurso['ativo'];
        }
        if($recurso['nome'] == 'zerar_mac'){
            $zerar_mac = $recurso['ativo'];
        }
        if($recurso['nome'] == 'sinal_rx'){
            $sinal_rx = $recurso['ativo'];
        }
        if($recurso['nome'] == 'senha_wifi'){
            $senha_wifi = $recurso['ativo'];
        }
    }
}

?>
<!-- Modal para envio de segunda via do boleto -->
<div class="modal fade" tabindex="-1" id="modal-2-boleto" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <p>Email: <span id="email-segunda-via-boleto"></span></p>
                <p>Telefone: <span id="telefone-segunda-via-boleto"></span></p>
            </div>
            <div class="modal-body">
                <label>Envio via SMS:</label>
                <button class="btn btn-primary" style="width: 100% !important; margin-bottom: 15px !important;" id='boleto-sms'>SMS</button>
                <label>Envio via E-mail:</label>
                <button class="btn btn-primary" style="width: 100% !important;" id='boleto-email'>E-mail</button>
            </div>
        </div>
    </div>
</div>

<!--Modal para exibição de atendimento pendentes do assinante-->
<script>

    reiniciar_conexao = "<?=$reiniciar_conexao?>";
    desconectar_disabled = '';
    if(reiniciar_conexao == "0"){
        desconectar_disabled = "disabled";
    }

    diagnosticar_conexao = "<?=$diagnosticar_conexao?>";
    diagnosticar_disabled = '';
    if(diagnosticar_conexao == "0"){
        diagnosticar_disabled = "disabled";
    }

    desbloquear_contrato = "<?=$desbloquear_contrato?>";
    desbloquear_disabled = '';
    if(desbloquear_contrato == "0"){
        desbloquear_disabled = "disabled";
    }

    enviar_boleto = "<?=$enviar_boleto?>";
    boleto_disabled = '';
    if(enviar_boleto == "0"){
        boleto_disabled = "disabled";
    }

    zerar_mac = "<?=$zerar_mac?>";
    zerar_mac_disabled = '';
    if(zerar_mac == "0"){
        zerar_mac_disabled = "disabled";
    }

    sinal_rx = "<?=$sinal_rx?>";
    sinal_rx_disabled = '';
    if(sinal_rx == "0"){
        sinal_rx_disabled = "display: none !important;";
    }

    senha_wifi = "<?=$senha_wifi?>";
    senha_wifi_disabled = '';
    if(senha_wifi == "0"){
        senha_wifi_disabled = "display: none !important;";
    }

    desbloquear_vel_reduzida = "<?=$desbloquear_vel_reduzida?>";
    desbloquear_vel_reduzida_disabled = '';
    if(desbloquear_vel_reduzida == "0"){
        desbloquear_vel_reduzida_disabled = "disabled";
    }

    // Adiciona ou remove icone do colapse /////////////////////////////////
    $("#informacoes_cliente").on('show.bs.collapse', function(){
        $("#i_collapse").removeClass("fa fa-plus");
        $("#i_collapse").addClass("fa fa-minus");
    });
    $("#informacoes_cliente").on('hide.bs.collapse', function(){
        $("#i_collapse").removeClass("fa fa-minus");
        $("#i_collapse").addClass("fa fa-plus");
    });

    $("#utilitarios").on('show.bs.collapse', function(){
        $("#u_collapse").removeClass("fa fa-plus");
        $("#u_collapse").addClass("fa fa-minus");
    });
    $("#utilitarios").on('hide.bs.collapse', function(){
        $("#u_collapse").removeClass("fa fa-minus");
        $("#u_collapse").addClass("fa fa-plus");
    });
    //////////////////////////////////////////////////////////////////////////

        //Script que solicita o envio da segunda via do boleto do cliente do provedor(assinante)
        if(sessionStorage.getItem("email")){
            $("#email-segunda-via-boleto").text(sessionStorage.getItem("email")).addClass("text-primary");
        }else{
            $("#email-segunda-via-boleto").text("Não há um e-mail configurado para esse ciente no sistema de gestão!").addClass("text-danger");
        }
        
        if(sessionStorage.getItem("telefone")){
            $("#telefone-segunda-via-boleto").text(sessionStorage.getItem("telefone_celular")).addClass("text-primary");
        }else{
            $("#telefone-segunda-via-boleto").text("Não há um telefone celular configurado para esse cliente no sistema de gestão!").addClass("text-danger");
        }

        $("#boleto-sms").on("click", function(){
            $("#modal-2-boleto").modal('hide');
            $.ajax({
                url: "class/IntegracaoTipoSistemaAjax.php",
                dataType: "json",
                data: {
                    acao: 'envia_boleto',
                    id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
                    id_assinante: sessionStorage.getItem('id_assinante'),
                    tipo_boleto: 'sms'
                },
                success: function(data){
                    if(data){
                        modalAguarde(false);

                        $(".alert-utilitarios").fadeIn().addClass("alert-success");
                        $(".conteudo-alerta").html("SMS enviado com sucesso!");
                        //$(".alert-utilitarios").delay(1200).fadeOut();
                    }else if(!data){
                        modalAguarde(false);
                        $(".alert-utilitarios").fadeIn().addClass("alert-danger");
                        $(".conteudo-alerta").html("Erro ao enviar SMS!");
                        //$(".alert-utilitarios").delay(1200).fadeOut();
                    }
                },
                beforeSend: function(){
                    modalAguarde();
                },
                error: function(){
                    console.log("Erro ao enviar boleto por SMS!");
                }
            });
        });

        $("#boleto-email").on("click", function(){
            $("#modal-2-boleto").modal('hide');
        });

        $("#boleto-email").on("click", function(){
            $("#modal-2-boleto").modal('hide');
            $.ajax({
                url: "class/IntegracaoTipoSistemaAjax.php",
                dataType: "json",
                data: {
                    acao: 'envia_boleto',
                    id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
                    id_assinante: sessionStorage.getItem('id_assinante'),
                    tipo_boleto: 'mail'
                },
                success: function(data){
                    if(data){
                        modalAguarde(false);
                        $(".alert-utilitarios").fadeIn().addClass("alert-success");
                        $(".conteudo-alerta").html("E-mail enviado com sucesso!");
                        //$(".alert-utilitarios").delay(1200).fadeOut();
                    }else if(!data){
                        modalAguarde(false);
                        $(".alert-utilitarios").fadeIn().addClass("alert-danger");
                        $(".conteudo-alerta").html("Erro ao enviar e-mail!");
                        //$(".alert-utilitarios").delay(1200).fadeOut();
                    }
                },
                beforeSend: function(){
                    modalAguarde();
                },
                error: function(){
                    modalAguarde(false);
                    console.log("Erro ao enviar boleto por E-Mail");
                }
            });
        });

            assuntos = <?= $retorno_assunto ? $retorno_assunto : "" ?>;
            departamentos = <?= $retorno_departamento ? $retorno_departamento : "" ?>;
            filiais = <?= $retorno_filial ? $retorno_filial : "" ?>;
            setores = <?= $retorno_setor ? $retorno_setor : "" ?>;
            tecnicos = <?= $retorno_tecnico ? $retorno_tecnico : "" ?>;

            //Status referente a atendimentos
            status_atendimento = {
                'N': 'Novo',
                'P': 'Pendente',
                'EP': 'Em progresso',
                'S': 'Solucionado',
                'C': 'Cancelado',
                '': ''
            };

            //Status referente a origem do endereço
            origem_endereco = {
                "C": "Cliente",
                "L": "Login",
                "CC": "Contrato",
                "M": "Manual",
                '': ''
            };

            //Status referente a prioridade dps atendimentos
            prioridade = {
                "B": "Baixa",
                "M": "Normal",
                "N": "Normal",
                "A": "Alta",
                "C": "Crítica",
                '': ''
            };

            //Status referente ao contrato de serviço entre o cliente e o provedor
            status_contrato = {
                'P': {
                    'nome':'Pré-contrato',
                    'cor':'text-warning',
                    'icone': 'fa-minus-square'
                },
                'A': {
                    'nome':'Ativo',
                    'cor':'text-success',
                    'icone': 'fa-check-square'
                },
                'I': {
                    'nome':'Inativo',
                    'cor':'text-danger',
                    'icone': 'fa-window-close'
                },
                'N': {
                    'nome':'Negativado',
                    'cor':'text-danger',
                    'icone': 'fa-window-close'
                },
                'D': {
                    'nome':'Desistiu',
                    'cor':'text-warning',
                    'icone': 'fa-minus-square'
                },
                '': {
                    'nome':'Sem Status',
                    'cor':'text-warning',
                    'icone': 'fa-minus-square'
                }
            };

            //Status relativo a conexão de internet do cliente
            status_internet = {
                'A': {
                    'nome':'Ativo',
                    'cor':'text-success',
                    'icone': 'fa-check-square'
                },
                'D': {
                    'nome':'Desativado',
                    'cor':'text-warning',
                    'icone': 'fa-minus-square'
                },
                'CM': {
                    'nome':'Bloqueio Manual',
                    'cor':'text-danger',
                    'icone': 'fa-window-close'
                },
                'CA': {
                    'nome':'Bloqueio Automatico',
                    'cor':'text-danger',
                    'icone': 'fa-window-close'
                },
                'CE': {
                    'nome':'Data Expirou',
                    'cor':'text-warning',
                    'icone': 'fa-minus-square'
                },
                'FA': {
                    'nome':'Financeiro em Atraso',
                    'cor':'text-danger',
                    'icone': 'fa-window-close'
                },
                'AA': {
                    'nome':'Aguardando Assinatura',
                    'cor':'text-warning',
                    'icone': 'fa-minus-square'
                },
                '': {
                    'nome':'Sem Status',
                    'cor':'text-warning',
                    'icone': 'fa-minus-square'
                }
            };

            //Status referente a franquia de velocidade da internet contratada pelo cliente
            status_velocidade = {
                'N': {
                    'nome':'Normal',
                    'cor':'text-success',
                    'icone': 'fa-check-square'
                },
                'R': {
                    'nome':'Reduzida',
                    'cor':'text-danger',
                    'icone': 'fa-window-close'
                },
                '': {
                    'nome':'Sem Status',
                    'cor':'text-warning',
                    'icone': 'fa-minus-square'
                }
            }

            //status referente a o status do login do cliente
            status_online = {
                'S': {
                    'nome':'Conectado',
                    'cor':'text-success',
                    'icone': 'fa-check-square'
                },
                'N': {
                    'nome':'Desconectado',
                    'cor':'text-danger',
                    'icone': 'fa-window-close'
                },
                'SS': {
                    'nome':'Sem Status',
                    'cor':'text-warning',
                    'icone': 'fa-minus-square'
                },
                '': {
                    'nome':'Sem Status',
                    'cor':'text-warning',
                    'icone': 'fa-minus-square'
                }
            }

            //Função executada no click do panel "Informações do cliente (Sistema de gestão):"
            $("#informacoes_cliente_integracao").on('click', function(){

                $.ajax({
                    url: "class/IntegracaoTipoSistemaAjax.php",
                    method: "GET",
                    dataType: "json",
                    data: {
                        acao: "busca_contas_receber",
                        id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
                        id_assinante: sessionStorage.getItem("id_assinante")
                    },
                    success: function(data){
                        if(data.registros){
                            /*html = '';
                            $.each(data.registros, function(i){
                                html += converteData(data.registros[0].data_vencimento)+"<br>";
                            });
                            $("#data_vencimento").html(html);*/
                            $("#data_vencimento").html(converteData(data.registros[0].data_vencimento));
                            $("#valor-receber").html(data.registros[0].valor);
                        }
                    }
                });

                nome_assinante = $(".span-assinante").text();

                id_assinante = sessionStorage.getItem("id_assinante");

                var razao_social = sessionStorage.getItem("razao_social");
                var cpf_cnpj = sessionStorage.getItem("cpf_cnpj");
                var endereco = sessionStorage.getItem("endereco");
                var complemento = sessionStorage.getItem("complemento");
                var bairro = sessionStorage.getItem("bairro");
                var observacao = sessionStorage.getItem("observacao");
                var cidade = sessionStorage.getItem("cidade");
                var numero_endereco = sessionStorage.getItem("numero_endereco");

                var endereco_completo = "";                
                if(endereco && endereco != '' && endereco != 0){
                    endereco_completo+=endereco;
                }
                if(numero_endereco && numero_endereco != '' && numero_endereco != 0){
                    endereco_completo+=`, `+numero_endereco;
                }
                if(bairro){
                    endereco_completo+=`, Bairro: `+bairro;
                }  
                if(cidade && cidade != '' && cidade != 0){
                    endereco_completo+=` - Cidade: <span class='cidade'>`+cidade+`</span>`;
                }

                //Carregamento dos contratos, auxilia na verificação se o sistema de gestão está ou não fora do ar.
                $.ajax({
                    url: "class/IntegracaoTipoSistemaAjax.php",
                    method: "GET",
                    dataType: "json",
                    data: {
                        acao: "busca_contrato_cliente_assinante",
                        id_assinante: id_assinante,
                        id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>
                    },
                    success: function(data){
                        //console.log(data);
                        //console.log("id assinante: " + id_assinante);
                        var html = "<option value='0'></option>";
                        $.each(data.registros, function(i){
                            select = data.registros[i].id == sessionStorage.getItem('contrato') ? 'selected' : '';
                            html += "<option "+select+" value='"+data.registros[i].id+"'>"+data.registros[i].contrato+"</option>";
                        });
                        $("#escolha-contrato").html(html);
                        if(!data){
                            $("#confirmacao-sistema-gestao").html("<div class='alert alert-danger'>Sistema de gestão fora do ar!</div>");
                        }
                    },
                    error: function(){
                        console.log("Erro ao buscar o contrato do cliente pelo id do assinante!");
                    }
                });
                
                //Converte a data para o formato Brasileiro
                function converteData(data) {
                    if (data) {
                        return data[8] + data[9] + "/" + data[5] + data[6] + "/" + data[0] + data[1] + data[2] + data[3];
                    } else {
                        return "";
                    }
                }

                //template com informações do cliente na aba de "Informações do cliente (Sistema de gestão):" semelhante ao que aparece no inicio do atendimento
                if(sessionStorage.getItem("nome") == 'Ativo'){
                    var class_status_assinante = 'text-success';
                }else{
                    var class_status_assinante = 'text-danger';
                }
                $(".painel-informacoes-cliente").html(
                    `
                    <div class="row" >
                        <div class="col-md-12">
                            <div style="border: 1px solid #D8D8D8; border-radius: 3px; background-color: #F2F2F2; padding: 10px 15px 10px 15px;">
                            <span class="`+class_status_assinante+`"><i class="fas fa-circle"></i> (`+sessionStorage.getItem("nome")+`)</span>
                            <p><strong>Nome (Razão social):</strong> `+razao_social+`</p>
                            <p><strong>CPF/CNPJ:</strong> `+cpf_cnpj+`</p>
                            <p><strong>Endereço:</strong> `+endereco_completo+`</p>
                            <p><strong>Complemento:</strong> ` + complemento + `</p>
                            <p><strong>Data de vencimento à receber:</strong> <span id='data_vencimento'></span></p>
                            <p><strong>Valor à receber:</strong> R$ <span id='valor-receber'></span></p>
                            <p><strong>Observação:</strong> `+observacao+`</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12">
                            <label>Contrato:</label>
                            <div class="input-group">
                                <select class="form-control" id="escolha-contrato"></select>
                                <span class="input-group-addon btn btn-primary" id="refresh-dados-contrato" title="Recarregar dados" style="border-radius: 2px;"><i class="fa fa-refresh" style="color: #fff;" aria-hidden="true"></i></button></span>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12" id="seleciona-login">

                        </div>
                    </div>
                    <div class="row">

                        <div class="alert alert-desconecta-login alert-dismissible" role="alert" style="text-align: center; display: none; padding-left: 30px; padding-right: 30px;"><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><div class='conteudo-alerta'></div></div> 

                        <div class="alert alert-utilitarios alert-dismissible" role="alert" style="text-align: center; display: none; margin: 5px 15px 10px 15px;"><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><div class='conteudo-alerta'></div></div>

                        <div class="alert alert-desbloqueio-vel-reduzida alert-dismissible" role="alert" style="text-align: center; display: none; margin: 5px 15px 10px 15px;"><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><div class='conteudo-alerta'></div></div>

                        <div class="col-md-12 dados-assinante" style="padding-left: 30px; padding-right: 30px;">

                        </div>
                    </div>
                    `
                );
                $("#id_cliente_integracao").val(id_assinante);
                $("#assinante").val(razao_social);
                $('#modal-lista-assinantes').modal('hide');

                //Carrega os dados de contrato selecionado anteriormente automaticamente no bloco ".dados-contrato-carregado"
                busca_dados_contrato(sessionStorage.getItem('contrato'));

                $("#escolha-contrato").on("change", function(){
                    //Limpa os dados de seleção do login ao lado do select do contrato para a correta atualização dos logins vinculados ao contrato selecionado
                    $("#escolha-login").html("");
                    //com a mudança do select é alterado os dados de contrato do bloco ".dados-contrato-carregado" e atualizado a sessionStorage que guarda o id do contrato para posteriormente seja carregado esses mesmo dados configurados aqui no "busca_dados_contrato" acima
                    busca_dados_contrato($(this).val());
                    sessionStorage.setItem('contrato', $(this).val());
                });

                //Função que atualiza dados de login do cliente, em "busca_dados_contrato" também é carregado os dados de login devido a sua dependencia com contrato
                $("#refresh-dados-contrato").on("click", function(){
                    busca_dados_contrato($("#escolha-contrato option:selected").val());
                });

                //Função que atualiza os dados de login (provisório)
                /*$("#escolha-login").on("change", function(){
                    busca_dados_contrato($("#escolha-contrato option:selected").val());
                });*/

                //Busca contrato
                //Funções relacionadas a login
                function busca_dados_contrato(id_contrato){
                    
                    if(id_contrato == ""){
                        $(".dados-assinante").children().remove();
                    }

                    $.ajax({
                        url: "class/IntegracaoTipoSistemaAjax.php",
                        method: "GET",
                        dataType: "json",
                        data: {
                            acao: "busca_contrato_cliente",
                            id_contrato: id_contrato,
                            id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>
                        },
                        success: function(data){
                            //console.log(data);
                            registros = data.registros;
                            if(registros){

                                plano_venda = data.registros[0].id_vd_contrato;              
                                
                                //variável javascript que monta um mini template com os dados de contrato e de login do cliente
                                $html = '';
                                
                                /**
                                Quando o contrato inativo e internet desativada não é possível salvar ordens de serviço, somente atendimentos, se não for possível salvar em
                                outro contrato ou em atendimento é inserido esse aviso para ser salvo em não clientes.
                                 */
                                if(status_internet[data.registros[0].status_internet]['nome'] == 'Desativado' && status_contrato[data.registros[0].status]['nome'] == 'Inativo'){
                                    $html += `<div class="col-md-12" style="padding-left: 0px; padding-right: 5px;">
                                                <p style="margin-left: 4px !important;" class='alert alert-warning'>
                                                    Contrato inativo e internet desativada, não será possível salvar em ordem de serviço - verifique se é possível selecionar outro contrato ou edite os dados para o cadastro de não clientes!
                                                </p>
                                              </div>`;
                                }

                                $html += `<div class='row dados-contrato-carregado' style='border: 1px solid #D8D8D8; border-radius: 3px; background-color: #F2F2F2; padding-left: 10px !important; padding-right: 10px !important;'>
                                            
                                            <div class="col-md-7" style="padding-left: 0px;">
                                                <p style="margin-left: 4px !important;"><strong>Nº:</strong> `+data.registros[0].id+`</p>
                                                <p style="margin-left: 4px !important;" class='`+status_contrato[data.registros[0].status]['cor']+`'>
                                                    <strong>Status do contrato: </strong>
                                                    <span class='status-contrato'>`+status_contrato[data.registros[0].status]['nome']+`</span>
                                                    <i class="fa `+status_contrato[data.registros[0].status]['icone']+`" aria-hidden="true"></i>
                                                </p>
                                                <p style="margin-left: 4px !important;" class='`+status_internet[data.registros[0].status_internet]['cor']+`'>
                                                    <strong>Status da internet: </strong>
                                                    <span class='status-internet'>`+status_internet[data.registros[0].status_internet]['nome']+` <i class="fa `+status_internet[data.registros[0].status_internet]['icone']+`" aria-hidden="true"></i></span>
                                                </p>`;

                                                if(data.registros[0].status_internet == 'CA' || data.registros[0].status_internet == 'CM'){

                                                    d = new Date();
                                                    dia_atual = d.getFullYear() + '-' + (parseInt(d.getMonth()) + 1) + '-' + d.getDate();

                                                    status_data_bloqueio = 'color: #3c763d';
                                                    if((data.registros[0].dt_ult_bloq_auto < dia_atual || data.registros[0].dt_ult_bloq_manual < dia_atual)){
                                                        status_data_bloqueio = 'color: #a94442';
                                                    }

                                                    $html += `<p style="margin-left: 4px !important;">
                                                    <strong style='`+status_data_bloqueio+`'>Data de bloqueio: </strong>
                                                    <span class='data-bloqueio' style='`+status_data_bloqueio+`'>`;
                                                    
                                                    if(data.registros[0].dt_ult_bloq_auto && data.registros[0].dt_ult_bloq_auto > data.registros[0].dt_ult_bloq_manual && data.registros[0].dt_ult_bloq_auto != '0000-00-00'){

                                                        $html += data.registros[0].dt_ult_bloq_auto[8] + data.registros[0].dt_ult_bloq_auto[9] + "/" + data.registros[0].dt_ult_bloq_auto[5] + data.registros[0].dt_ult_bloq_auto[6] + "/" + data.registros[0].dt_ult_bloq_auto[0] + data.registros[0].dt_ult_bloq_auto[1] + data.registros[0].dt_ult_bloq_auto[2] + data.registros[0].dt_ult_bloq_auto[3]
                                                    }else if(data.registros[0].dt_ult_bloq_manual != '0000-00-00'){

                                                        $html += data.registros[0].dt_ult_bloq_manual[8] + data.registros[0].dt_ult_bloq_manual[9] + "/" + data.registros[0].dt_ult_bloq_manual[5] + data.registros[0].dt_ult_bloq_manual[6] + "/" + data.registros[0].dt_ult_bloq_manual[0] + data.registros[0].dt_ult_bloq_manual[1] + data.registros[0].dt_ult_bloq_manual[2] + data.registros[0].dt_ult_bloq_manual[3]
                                                    }
                                                    
                                                    $html += `</span>`;
                                                }

                                                $html += `</p>
                                                `;

                                                $html += `<p style="margin-left: 4px !important;" class='`+status_velocidade[data.registros[0].status_velocidade]['cor']+`'>
                                                    <strong>Status da velocidade: </strong>
                                                    <span class='status-velocidade'>`+status_velocidade[data.registros[0].status_velocidade]['nome']+` <i class="fa `+status_velocidade[data.registros[0].status_velocidade]['icone']+`" aria-hidden="true"></i></span>
                                                </p>
                                                <p style="margin-left: 4px !important;" class=''>
                                                    <strong>Status da conexão: </strong>
                                                    <span class='status-logado'></span>
                                                </p>
                                                <p style="margin-left: 4px !important;" class=''>
                                                    <strong>Tecnologia: </strong>
                                                    <span class='tecnologia'></span>
                                                </p>`;

                                    //Descreve o endereço do contrato para fácil localização em casos onde o cliente tem mais de um
                                    endereco_completo = "";
                                    endereco_contrato = data.registros[0].endereco ? data.registros[0].endereco : "";
                                    bairro_contrato = data.registros[0].bairro ? data.registros[0].bairro : "";
                                    numero_contrato = data.registros[0].numero ? data.registros[0].numero : "";
                                    cidade_contrato = data.registros[0].cidade ? data.registros[0].cidade : "";

                                    if(endereco_contrato && endereco_contrato != '' && endereco_contrato != 0){
                                        endereco_completo+=endereco_contrato;
                                    }
                                    if(numero_contrato && numero_contrato != '' && numero_contrato != 0){
                                        endereco_completo+=`, `+numero_contrato;
                                    }
                                    if(bairro_contrato){
                                        endereco_completo+=`, Bairro: `+bairro_contrato;
                                    }                                    
                                    if(cidade_contrato && cidade_contrato != '' && cidade_contrato != 0){
                                        endereco_completo+=` - Cidade: `+cidade_contrato;
                                    }
                                    
                                    $html += `<p style="margin-left: 4px !important;" >
                                                    <strong>Endereço: </strong><span class="endereco_contrato">`+endereco_completo+`</span>
                                                </p>
                                                <p style="margin-left: 4px !important; `+senha_wifi_disabled+`" >
                                                    <strong>SSID (nome da rede): </strong><span class="ssid-router"></span>
                                                </p>
                                                <p style="margin-left: 4px !important; `+senha_wifi_disabled+`" >
                                                    <strong>Senha do roteador wi-fi: </strong><span class="senha-router"></span>
                                                </p>
                                                <p style="margin-left: 4px !important; `+sinal_rx_disabled+`" >
                                                    <strong>Sinal RX: </strong><span class="sinal-rx"></span>
                                                </p>
                                                <p style="margin-left: 4px !important;" >
                                                    <strong>Obs. do login: </strong><span class='obs_login'></span>
                                                </p>
                                            </div>
                                            <div class="col-md-5" style="padding-right: 3px;">
                                                <p>
                                                    <strong>Descrição: </strong>
                                                    <span class='descricao-contrato'>`+data.registros[0].contrato+`</span>
                                                </p>`;
                                                if(data.registros[0].descricao_aux_plano_venda){
                                                    $html += `<p>
                                                        <strong>Descrição auxiliar do Contrato: </strong>
                                                        <span class=''>`+data.registros[0].descricao_aux_plano_venda+`</span>
                                                    </p>`;
                                                }
                                        $html += `<p>
                                                    <strong>Velocidade de Upload: </strong><span class='upload-contrato'></span>
                                                </p>
                                                <p>
                                                    <strong>Velocidade de Download: </strong><span class='download-contrato'></span>
                                                </p>
                                                <p>
                                                    <strong>Mac: </strong><span class='mac_equipamento'></span>
                                                </p>
                                                <p>
                                                    <strong>IP do equipamento: </strong><span class='ip_equipamento'></span>
                                                </p>
                                                <p>
                                                    <strong>Login PPPoE: </strong><span class="login_pppoe"></span>
                                                </p>
                                                <p>
                                                    <strong>Senha PPPoE: </strong><span class="senha_pppoe"></span>
                                                </p>
                                                <div class="modal fade modal-diagnostico" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" style="width: 1400px;" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Diagnóstico</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <table class="table table-bordered table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>IP</th>
                                                                            <th>Mac</th>
                                                                            <th>Conexão Inicial</th>
                                                                            <th>Conexão Final</th>
                                                                            <th>Desconexão</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody class="corpo-diagnostico">
                                                                    
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px !important;">
                                            <div class="col-md-6" style="padding-left: 0; padding-right: 2px !important;">
                                                <button class="btn btn-primary `+desconectar_disabled+` desconectar-login form-control">Reiniciar conexão</button>
                                            </div>
                                            <div class="col-md-6" style="padding-left: 0; padding-right: 2px !important;">
                                                <button class="btn btn-primary `+desbloquear_vel_reduzida_disabled+` desbloquear_vel_reduzida form-control">Desbloquear vel. reduzida</button>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px !important;">
                                            <div class="col-md-6" style="padding-left: 0; padding-right: 2px !important;">
                                                <button class="btn btn-primary `+zerar_mac_disabled+` zerar-mac form-control">Zerar Mac</button>
                                            </div>
                                            <div class="col-md-6" style="padding-left: 2px; padding-right: 0;">
                                                <button class="btn btn-primary diagnostico-conexao form-control" `+diagnosticar_disabled+` data-toggle="modal" data-target=".modal-diagnostico">Diagnosticar conexão</button>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px !important;">
                                            <div class="col-md-6" style="padding-left: 0; padding-right: 2px !important;">
                                                <button type="button" class="btn btn-primary btn-contrato form-control" `+desbloquear_disabled+` id="desbloqueio-manual" contrato_id='`+data.registros[0].id+`'>Desbloquear contrato</button>
                                            </div>
                                            <div class="col-md-6" style="padding-left: 2px; padding-right: 0 !important;">
                                                <button type="button" class="btn btn-primary form-control" `+boleto_disabled+` data-toggle="modal" data-target="#modal-2-boleto" id="envio-segunda-via-boleto">Enviar 2ª via do boleto</button>
                                            </div>
                                        </div>
                                        `;

                                $(".dados-assinante").html($html);

                                    //Bloco que faz o desbloqueio de confiança
                                    $(".btn-contrato").on("click", function(){
                                        
                                        valor_contrato = $(this).attr("contrato_id");

                                        var r2 = confirm("Desbloquear contrato?");
                                        if(r2 == true){
                                            $.ajax({
                                                url: "class/IntegracaoTipoSistemaAjax.php",
                                                method: "GET",
                                                dataType: "json",
                                                data: {
                                                    acao: "desbloqueio_manual",
                                                    id_contrato: valor_contrato,
                                                    id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>
                                                },
                                                success: function(data){
                                                    $("#modal-desbloqueio").modal('hide');
                                                    if(data){
                                                        modalAguarde(false);
                                                        $(".alert-utilitarios").fadeIn().addClass("alert-success");
                                                        if(data.mensagem){
                                                            $(".conteudo-alerta").html(data.mensagem);
                                                        }else{
                                                            $(".conteudo-alerta").html(data.message);
                                                        }
                                                        
                                                    }else if(!data){
                                                        modalAguarde(false);
                                                        $(".alert-utilitarios").fadeIn().addClass("alert-danger");
                                                        if(data.mensagem){
                                                            $(".conteudo-alerta").html(data.mensagem);
                                                        }else{
                                                            $(".conteudo-alerta").html(data.message);
                                                        }
                                                        
                                                    }
                                                },
                                                beforeSend: function(){
                                                    modalAguarde();
                                                },
                                                error: function(){
                                                    console.log("Erro ao efetuar o desbloqueio manual!")
                                                }
                                            });
                                        }
                                    });


                            }
                        },
                        beforeSend: function(){
                            $("#escolha-contrato").on("change", function(){
                                if($(this).val() != "0"){
                                    $('.dados-assinante').html(' <span class="text-primary"><i class="fa fa-spinner faa-spin animated"></i> carregando...</span>');
                                }else if($(this).val() == "0"){
                                    $('.dados-assinante').html('');
                                    $("#opcoes-logins").hide();
                                }
                            });
					    },

                        //Funções de alteração de login
                        complete: function(){
                            if(registros){
                                //Busca o login para a execução do utilitário de reinicialização remota
                                $.ajax({
                                    url: "class/IntegracaoTipoSistemaAjax.php",
                                    method: "GET",
                                    dataType: "json",
                                    data: {
                                        acao: "busca_login",
                                        id_contrato: id_contrato,
                                        id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>
                                    },
                                    success: function(data){

                                        //console.log(data);

                                        sessionStorage.removeItem("login");
                                        sessionStorage.removeItem("id_login");
                                        //Suporte para a verificação de status do login - (provisório)
                                        sessionStorage.removeItem("online_login");
                                        sessionStorage.removeItem("senha_login");
                                        sessionStorage.removeItem("mac_login");
                                        sessionStorage.removeItem("ip_login");
                                        sessionStorage.removeItem("ipv6_login");
                                        sessionStorage.removeItem("obs_login");
                                        sessionStorage.removeItem("mac_login");
                                        sessionStorage.removeItem("ssid_router");
                                        sessionStorage.removeItem("senha_router");
                                        sessionStorage.removeItem("tecnologia");

                                        if (!sessionStorage.getItem("login") && data.registros) {
                                            sessionStorage.setItem("login", data.registros[0].login);
                                            sessionStorage.setItem("id_login", data.registros[0].id);
                                            //Suporte para a verificação de status do login - (provisório)
                                            sessionStorage.setItem("online_login", data.registros[0].online);
                                            sessionStorage.setItem("senha_login", data.registros[0].senha);
                                            sessionStorage.setItem("mac_login", data.registros[0].mac);
                                            sessionStorage.setItem("ip_login", data.registros[0].ip);
                                            sessionStorage.setItem("ipv6_login", data.registros[0].pd_ipv6);
                                            sessionStorage.setItem("obs_login", data.registros[0].obs);
                                            sessionStorage.setItem("ssid_router", data.registros[0].ssid_router_wifi);
                                            sessionStorage.setItem("senha_router", data.registros[0].senha_rede_sem_fio);

                                            if (data.registros[0].tipo_conexao_mapa == '58') {
                                                tecnologia = '5.8';

                                            } else if (data.registros[0].tipo_conexao_mapa == '2.4') {
                                                tecnologia = '5.8';

                                            } else if (data.registros[0].tipo_conexao_mapa == 'F') {
                                                tecnologia = 'Fibra';
                                                
                                            } else if (data.registros[0].tipo_conexao_mapa == 'L') {
                                                tecnologia = 'Cabo';
                                                
                                            } else if (data.registros[0].tipo_conexao_mapa == 'A') {
                                                tecnologia = 'ADSL';
                                                
                                            } else if (data.registros[0].tipo_conexao_mapa == 'LTE') {
                                                tecnologia = 'LTE';
                                            }

                                            sessionStorage.setItem("tecnologia", tecnologia);

                                        } else if (!sessionStorage.getItem("mac_login") && data.registros){
                                            sessionStorage.setItem("mac_login", data.registros[0].mac);
                                        }

                                        var ssid_router = sessionStorage.getItem('ssid_router') ? sessionStorage.getItem('ssid_router') : "";         
                                        var senha_router = sessionStorage.getItem('senha_router') ? sessionStorage.getItem('senha_router') : "";

                                        $(".ssid-router").html(ssid_router);
                                        $(".senha-router").html(senha_router);

                                        if(data.registros){
                                            select_login = `<div id="opcoes-logins">
                                                                <label>Login:</label>
                                                                <select class="form-control" id="escolha-login">`;
                                                                $.each(data.registros, function(i){
                                                                    var login_selected = sessionStorage.getItem("login");
                                                                    var selected = data.registros[i].login == login_selected ? "selected" : "";
                                                                    select_login += `<option `+selected+` value="`+data.registros[i].id+`">`+data.registros[i].login+`</option>`;
                                                                });
                                                                select_login += `</select>
                                                            </div>`;
                                            $('#seleciona-login').html(select_login);
                                            $("#opcoes-logins").show();
                                        }else if(!data.registros){
                                            $("#opcoes-logins").hide();
                                        }

                                        id = "";
                                        if(data.registros){
                                            //Faz a seleção do login que se deseja pesquisar - (provisório)
                                            $("#escolha-login").on("change", function(){
                                                escolha_login = $(this);
                                                $.each(data.registros, function(i){
                                                    if(escolha_login.val() == data.registros[i].id){
                                                        //username = data.registros[i].login ? data.registros[i].login : "";
                                                        //Campos gravados em sessionStorage para dar suporte a atualização de dados de login - (provisório)
                                                        
                                                        sessionStorage.setItem("login", data.registros[i].login);
                                                        sessionStorage.setItem("id_login", data.registros[i].id);
                                                        sessionStorage.setItem("online_login", data.registros[i].online);
                                                        sessionStorage.setItem("senha_login", data.registros[i].senha);
                                                        sessionStorage.setItem("mac_login", data.registros[i].mac);
                                                        sessionStorage.setItem("ip_login", data.registros[i].ip);
                                                        sessionStorage.setItem("ipv6_login", data.registros[i].pd_ipv6);
                                                        sessionStorage.setItem("obs_login", data.registros[i].obs);
                                                    }
                                                });
                                                
                                                busca_dados_contrato($("#escolha-contrato option:selected").val());

                                                

                                            });
                                            //username = data.registros[0].login ? data.registros[0].login : "";
                                            username = sessionStorage.getItem("login");
                                            //id = data.registros[0].id ? data.registros[0].id : "";
                                            id = sessionStorage.getItem("id_login");
                                        }

                                        //Requisição para verifica o sinal rx do login do cliente//
                                        $.ajax({
                                            url: "class/IntegracaoTipoSistemaAjax.php",
                                            dataType: "json",
                                            data: {
                                                acao: 'busca_sinal',
                                                id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
                                                id_login: sessionStorage.getItem("id_login")
                                            },
                                            success: function(data){
                                                //console.log(data);
                                                if(data.registros){
                                                    $(".sinal-rx").html(data.registros[0].sinal_rx);
                                                }else{
                                                    $(".sinal-rx").html('');
                                                }
                                                
                                            }
                                        });

                                        //$(".desconectar-login").attr("id", id);

                                        $("button.desconectar-login").on("click", function(){
                                            var r = confirm("Reiniciar conexão?");
                                            if(r == true){
                                                desconectar_login = $(this);
                                                $.ajax({
                                                    url: "class/IntegracaoTipoSistemaAjax.php",
                                                    dataType: "json",
                                                    data: {
                                                        acao: 'desconectar_login',
                                                        id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
                                                        id_login: id
                                                    },
                                                    success: function(data){
                                                        if(data){
                                                            modalAguarde(false);
                                                            $(".alert-desconecta-login").fadeIn().addClass("alert-success").css("display", "block").html(data.message);
                                                            $(".alert-desconecta-login").delay(1400).fadeOut();
                                                        }else if(!data){
                                                            modalAguarde(false);
                                                            $(".alert-desconecta-login").fadeIn().addClass("alert-danger").css("display", "block").html(data.message);
                                                            $(".alert-desconecta-login").delay(1400).fadeOut();
                                                        }
                                                        //alert(data.message);
                                                    },
                                                    beforeSend: function(){
                                                        modalAguarde();
                                                    },
                                                });
                                            }
                                        });

                                        $("button.desbloquear_vel_reduzida").on("click", function(){
                                            var r = confirm("Desbloquear velocidade reduzida?");
                                            if(r == true){
                                                desconectar_login = $(this);
                                                $.ajax({
                                                    url: "class/IntegracaoTipoSistemaAjax.php",
                                                    dataType: "json",
                                                    data: {
                                                        acao: 'desbloquear_vel_reduzida',
                                                        id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
                                                        id_contrato: id_contrato
                                                    },
                                                    success: function(data){
                                                        if(data){

                                                            modalAguarde(false);
                                                            $(".alert-desbloqueio-vel-reduzida").fadeIn().addClass("alert-success").css("display", "block").html(data.message);
                                                            //$(".alert-desbloqueio-vel-reduzida").delay(200).fadeOut();
                                                        }else if(!data){
                                                            modalAguarde(false);
                                                            $(".alert-desbloqueio-vel-reduzida").fadeIn().addClass("alert-danger").css("display", "block").html(data.message);
                                                            //$(".alert-desbloqueio-vel-reduzida").delay(1600).fadeOut();
                                                        }
                                                        //alert(data.message);
                                                    },
                                                    beforeSend: function(){
                                                        modalAguarde();
                                                    },
                                                });
                                            }
                                        });

                                        //Utilitário responsável por zerar o mac do equipamento do cliente
                                        $(".zerar-mac").on("click", function(){
                                            var z = confirm("Zerar o Mac no sistema de gestão?");
                                            if(z == true){
                                                $.ajax({
                                                    url: "class/IntegracaoTipoSistemaAjax.php",
                                                    dataType: "json",
                                                    method: "GET",
                                                    data: {
                                                        acao: "zerar_mac",
                                                        id_login: id,
                                                        id_contrato_plano_pessoa: <?php echo $id_contrato_plano_pessoa ?>
                                                    },
                                                    success: function(data){
                                                        if(data){
                                                            sessionStorage.setItem('mac_login', '');
                                                            modalAguarde(false);
                                                            $(".alert-utilitarios").fadeIn().addClass("alert-success").css("display", "block");
                                                            $(".conteudo-alerta").html(data.message);
                                                            //$(".alert-utilitarios").delay(1200).fadeOut();
                                                        }else if(!data){
                                                            modalAguarde(false);
                                                            $(".alert-utilitarios").fadeIn().addClass("alert-danger").css("display", "block");
                                                            $(".conteudo-alerta").html(data.message);
                                                            //$(".alert-utilitarios").delay(1200).fadeOut();
                                                        }
                                                    }
                                                });
                                            }
                                        });

                                        //Utilitário que imprime um modal com a tabela de diagnóstico de conexão do cliente
                                        $(".diagnostico-conexao").on("click", function(){
                                            //// Gera diagnóstico
                                            $.ajax({
                                                url: "class/IntegracaoTipoSistemaAjax.php",
                                                dataType: "json",
                                                method: "GET",
                                                data: {
                                                    acao: "gerar_diagnostico",
                                                    //username: sessionStorage.getItem('id_login'),
                                                    username: sessionStorage.getItem('login'),
                                                    id_contrato_plano_pessoa: <?php echo $id_contrato_plano_pessoa ?>
                                                },
                                                success: function(data){
                                                    console.log(data);
                                                    html = '';
                                                    $desconexao = {
                                                        "": "",
                                                        "NAS-Request": "Solicitação do Concentrador",
                                                        "User-Request": "Solicitação Usuário",
                                                        "Lost-Carrier": "Conexão Perdida",
                                                        "NAS-Reboot": "Concentrador Reiniciou",
                                                        "NAS-Error": "Erro no Concentrador",
                                                        "User-Error": "Erro no Usuário",
                                                        "Admin-Reboot": "Admin-Reboot"
                                                    }
                                                    $.each(data.registros, function(i){
                                                        html += `
                                                        <tr>
                                                            <td>`+data.registros[i].framedipaddress+`</td>
                                                            <td>`+data.registros[i].callingstationid+`</td>
                                                            <td>`+data.registros[i].acctstarttime+`</td>
                                                            <td>`+data.registros[i].acctstoptime+`</td>
                                                            <td>`+$desconexao[data.registros[i].acctterminatecause]+`</td>
                                                        <tr>
                                                        `;
                                                    });
                                                    $(".corpo-diagnostico").html(html);
                                                }
                                            });
                                            /////////////////////////
                                        });                                       

                                        data_online = "";
                                        data_login = "";
                                        data_senha = "";
                                        data_mac = "";
                                        data_ip = "";
                                        data_ipv6 = "";
                                        data_obs = "";
                                        tecnologia = "";

                                        if (data.registros) {
                                            data_online = sessionStorage.getItem("online_login");
                                            data_login = sessionStorage.getItem("login");
                                            data_senha = sessionStorage.getItem("senha_login");
                                            data_mac = sessionStorage.getItem("mac_login");
                                            data_ip = sessionStorage.getItem("ip_login");
                                            data_ipv6 = sessionStorage.getItem("ipv6_login");
                                            data_obs = sessionStorage.getItem("obs_login");
                                            tecnologia = sessionStorage.getItem("tecnologia");
                                        }

                                        //seta os status de velocidade e login

                                        $('.status-logado').parent().addClass(status_online[data_online]['cor']);
                                        $('.status-logado').html(status_online[data_online]['nome']+' <i class="fa '+status_online[data_online]['icone']+'" aria-hidden="true"></i>');
                                        
                                        
                                        acesso_login = "<?=$acesso_login?>";
                                        if(acesso_login == 1){
                                            $('.login_pppoe').html(data_login);
                                            $('.senha_pppoe').html(data_senha);
                                        }
                                        
                                        $('.ip_equipamento').html(data_ip + " " + " " + data_ipv6);
                                        $('.mac_equipamento').html(data_mac);
                                        $('.obs_login').html(data_obs);
                                        $('.tecnologia').html(tecnologia);

                                    },
                                    error: function(){
                                        console.log("Erro ao buscar login!");
                                    }
                                });
                                $.ajax({
                                    url: "class/IntegracaoTipoSistemaAjax.php",
                                    method: "GET",
                                    dataType: "json",
                                    data: {
                                        acao: "busca_planos_velocidade",
                                        plano_venda: plano_venda,
                                        id_contrato: id_contrato,
                                        id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>
                                    },
                                    success: function(data){
                                        if(data.registros){
                                            download = data.registros[0].download ? data.registros[0].download : "";
                                            upload = data.registros[0].upload ? data.registros[0].upload : "";
                                            $('.download-contrato').html(download);
                                            $('.upload-contrato').html(upload);
                                        }
                                        
                                    },
                                    error: function(){
                                        console.log("Erro ao buscar o plano de velocidade!");
                                    }
                                });
                            }
                        },
                        error: function(){
                            console.log("Erro ao buscar o contrato do cliente!");
                        }
                    });
                }
                // Fim das funções relacionadas a login

                    //Seleciona se serão listados os atendimentos e ordens de serviços abertos ou fechados no sistema ixc
                    $("#escolha-status").ready(function(){
                        atendimentoIxc("abertos");
                        osIxc("abertos");
                        escolha_status = $(this);
                        escolha_status.on("change", function(){
                            if($("#escolha-status").val() == "abertos"){
                                atendimentoIxc("abertos");
                                osIxc("abertos");
                            }else if($("#escolha-status").val() == "fechados"){
                                atendimentoIxc("fechados");
                                osIxc("fechados");
                            }
                        });
                    });

                    //ATENDIMENTOS IXC
                    function atendimentoIxc(status_atendimento){
                        descricao_status = { "N": "Novo", "P": "Pendente", "EP": "Em progresso", "S": "Solucionado", "C": "Cancelado" };
                        html = '';
                        $.ajax({
                            url: "class/IntegracaoTipoSistemaAjax.php",
                            method: "GET",
                            dataType: "json",
                            data: {
                                acao: "busca_atendimentos",
                                status_atendimento: status_atendimento,
                                id_assinante: id_assinante,
                                id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>
                            },
                            success: function(data){

                                //console.log(data['registros'].reverse());

                                item = '';
                                html = '<div>Registros em atendimento (classificação):</div>';
                                html_detalhes = '';
                                todos_atendimentos = data;

                                if(data['registros']){
                                    $.each(data['registros'].reverse(), function(index){
                                        if(index < 5){
                                            departamento = '';
                                            id_atendimento = data.registros[index].id;
                                            if(departamentos.registros){
                                                $.each(departamentos.registros, function(i){
                                                    if(departamentos.registros[i].id == data.registros[index].id_ticket_setor){
                                                        departamento = departamentos.registros[i].setor;
                                                    }
                                                });
                                            }
                                            html += `
                                                <div class='row' style='border: 1px solid #dddddd;padding:7px 5px 5px 0;margin: 0 0px 5px 0px; background-image: linear-gradient(to bottom,#f5f5f5 0,#e8e8e8 100%); background-repeat: repeat-x; border-radius: 3px;'>
                                                    <div class='col-md-6'>
                                                        <p style="margin-bottom: 0"><strong>Protocolo: </strong>`+data.registros[index].protocolo+`</p>
                                                    </div>
                                                    <div class='col-md-6'>
                                                        <p style="margin-bottom: 0"><strong>Classificação: </strong>Atendimento</p>
                                                    </div>
                                                    <div class='col-md-6'>
                                                        <p style="margin-bottom: 0"><strong>Data criação: </strong>`+converteDataHora(data.registros[index].data_criacao)+`</p>
                                                    </div>
                                                    <div class='col-md-6'>
                                                        <p style="margin-bottom: 0"><strong>Departamento: </strong>`+departamento+`</p>
                                                    </div>
                                                    <div class='col-md-6'>
                                                        <p style="margin-bottom: 0"><strong>Status: </strong>`+descricao_status[data.registros[index].su_status]+`</p>
                                                    </div>
                                                    <div class='col-md-6'>
                                                        <button type="button" id="`+id_atendimento+`" class="btn btn-info btn-xs btn-atendimento pull-right" data-toggle="modal" data-assunto="`+data.registros[index].id_assunto+`" data-target=".detalhes-`+data.registros[index].protocolo+`"><i class='fa fa-plus'></i> Detalhes</button>
                                                    </div>
                                                </div>`;
                                                html_detalhes += `<div class="modal modal-atendimento fade detalhes-`+data.registros[index].protocolo+`" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title"><label>Protocolo:</label> `+data.registros[index].protocolo+`</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class='row'>
                                                                    <div class="carousel-atendimento-generic carousel slide" id="carousel" data-interval="false">

                                                                    
                                                                        <div class="carousel-inner conteudo-atendimento" id="item-principal" role="listbox">
                                                                            
                                                                        </div>

                                                                        <hr>
                                                                        <div class="controles-carousel" style="width: 194px;margin: 20px auto 0 auto;">
                                                                            <a class="left btn btn-primary btn-atendimento-anterior" href=".carousel-atendimento-generic" role="button" data-slide="prev">
                                                                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                                                                Anterior
                                                                                <span class="sr-only">Previous</span>
                                                                            </a>
                                                                            <a class="right btn btn-primary btn-atendimento-proximo" href=".carousel-atendimento-generic" role="button" data-slide="next">
                                                                                Próximo
                                                                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                                                <span class="sr-only">Next</span>
                                                                            </a>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            `;
                                        }
                                    });
                                }
                                
                                    ///////////////////////////////////////////////////////////////////////////////////////////////
                                if(data.registros){
                                    $(".painel-informacoes-atendimento").html(html + html_detalhes);
                                }else{
                                    $(".painel-informacoes-atendimento").html("<div>Registros em atendimento (classificação):</div><div class='alert alert-warning text-center' role='alert'>Não há atendimentos!</div>");
                                }
                                $(".btn-atendimento").on("click", function(){
                                        html_detalhes2 = "";
                                        id_atendimento = $(this).attr("id");
                                        $.ajax({
                                            url: "class/IntegracaoTipoSistemaAjax.php",
                                            method: "GET",
                                            dataType: "json",
                                            data: {
                                                acao: "busca_interacao_atendimento",
                                                id_atendimento: id_atendimento,
                                                id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>
                                            },
                                            success: function(data){

                                                descricao_origem_endereco = { "C": "Cliente", "L": "Login", "CC": "Contrato", "M": "Manual" };
                                                descricao_prioridade = { "B": "Baixa", "M": "Manual", "A": "Alta", "C": "Crítica" };

                                                //Insere o atendimento
                                                $(".conteudo-atendimento").html(
                                                    `<div class='item'>
                                                        <input type="hidden" class="data_contador_itens-`+id_atendimento+` data_contador_itens-0" value="0" />
                                                        <table class="table table-striped" style="width: 866px; margin: 0 auto;">
                                                            <tr>
                                                                <td><p><label>Título:</label> <span class="titulo-`+id_atendimento+`"></span></p></td>
                                                                <td><p><label>Classificação:</label> Atendimento</p></td>
                                                            </tr>
                                                            <tr>
                                                                <td><p><label>Origem do endereço:</label> <span class="origem_endereco-`+id_atendimento+`"></span></p></td>
                                                                <td><p><label>Departamento:</label> <span class="departamento-`+id_atendimento+`"></span></p></td>
                                                            </tr>
                                                            <tr>
                                                                <td><p><label>Prioridade:</label> <span class="prioridade-`+id_atendimento+`"></span></p></td>
                                                                <td><p><label>Status:</label> <span class="status-`+id_atendimento+`"></span></p></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"><p><label>Endereço:</label> <span class="endereco-`+id_atendimento+`"></span></p></td>
                                                            </tr>
                                                            <tr class="mensagem-atendimento">
                                                                <td colspan="2"><label>Mensagem:</label><br><span class="mensagem-`+id_atendimento+`"></span></td>
                                                            </tr>
                                                        </table>
                                                    </div>`
                                                );

                                                if(data.registros){
                                                    
                                                    var tamanho_slides = data.registros.length;
                                                    //contador inicializado com o número total de slides para o slide começar com a última interação da ordem de serviço
                                                    var contador = tamanho_slides;

                                                    //Se a ordem de serviço só tiver a ação de abertura, ambos os botões são configurados como 'disabled'
                                                    if(tamanho_slides == 0){
                                                        $(".btn-atendimento-anterior").addClass("disabled");
                                                        $(".btn-atendimento-proximo").addClass("disabled");
                                                    }
                                                    $(".btn-atendimento-proximo").addClass("disabled");
                                                    $(".btn-atendimento-anterior").on("click", function(){
                                                        btn_anterior = $(this);
                                                        contador -= 1;
                                                        $(".data_contador_itens-"+id_atendimento).each(function(){
                                                            if(contador == 0){
                                                                btn_anterior.addClass("disabled");
                                                                $(".btn-atendimento-proximo").removeClass("disabled");
                                                            }
                                                            if(contador != 0){
                                                                $(".btn-atendimento-proximo").addClass("disabled");
                                                                btn_anterior.removeClass("disabled");
                                                            }
                                                            if(contador != 0 && contador != tamanho_slides){
                                                                btn_anterior.removeClass("disabled");
                                                                $(".btn-atendimento-proximo").removeClass("disabled");
                                                            }
                                                        });
                                                    });
                                                    $(".btn-atendimento-proximo").on("click", function(){
                                                        btn_proximo = $(this);
                                                        contador += 1;
                                                        $(".data_contador_itens-"+id_atendimento).each(function(){
                                                            
                                                            if(contador == tamanho_slides){
                                                                btn_proximo.addClass("disabled");
                                                                btn_anterior.removeClass("disabled");
                                                            }
                                                            if(contador != tamanho_slides){
                                                                btn_anterior.addClass("disabled");
                                                                btn_proximo.removeClass("disabled");
                                                            }
                                                            if(contador != 0 && contador != tamanho_slides){
                                                                btn_anterior.removeClass("disabled");
                                                                btn_proximo.removeClass("disabled");
                                                            }
                                                        });
                                                    });
                                                    active = "";
                                                    //Itera sobre todas as interações de determinado atendimento
                                                    $.each(data.registros, function(i){
                                                        //Itera sobre todos os atendimentos de determinado cliente
                                                        $.each(todos_atendimentos.registros, function(index){

                                                            if(departamentos.registros){
                                                                $.each(departamentos.registros, function(indice){
                                                                    if(departamentos.registros[indice].id == todos_atendimentos.registros[index].id_ticket_setor && data.registros[i].id_ticket == todos_atendimentos.registros[index].id){
                                                                        departamento = departamentos.registros[indice].setor;
                                                                    }
                                                                });
                                                            }

                                                            //Verifica se id do atendimento é igual a id_ticket(chave estrangeira da interação para o atendimento)
                                                            if(data.registros[i].id_ticket == todos_atendimentos.registros[index].id){
                                                                //cria o item da interação no carousel
                                                                html_detalhes2 += `
                                                                    <div class='item'>
                                                                        <input type="hidden" class="data_contador_itens-`+id_atendimento+` data_contador_itens-`+(i+1)+`" value="`+(i+1)+`" />
                                                                        <table class="table table-striped" style="width: 866px; margin: 0 auto;">
                                                                            <tr>
                                                                                <td><p><label>Título:</label> <span class="titulo-`+id_atendimento+`">`+todos_atendimentos.registros[index].titulo+`</span></p></td>
                                                                                <td><p><label>Classificação:</label> Atendimento</p></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><p><label>Origem do endereço:</label> <span class="origem_endereco-`+id_atendimento+`">`+descricao_origem_endereco[todos_atendimentos.registros[index].origem_endereco]+`</span></p></td>
                                                                                <td><p><label>Departamento:</label> <span class="departamento-`+id_atendimento+`">`+departamento+`</span></p></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><p><label>Prioridade:</label> <span class="prioridade-`+id_atendimento+`">`+descricao_prioridade[todos_atendimentos.registros[index].prioridade]+`</span></p></td>
                                                                                <td><p><label>Status:</label> <span class="status-">`+descricao_status[todos_atendimentos.registros[index].su_status]+`</span></p></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="2"><p><label>Endereço:</label> <span class="endereco-`+id_atendimento+`">`+todos_atendimentos.registros[index].endereco+`</span></p></td>
                                                                            </tr>
                                                                            <tr class="mensagem-atendimento">
                                                                                <td colspan="2"><label>Mensagem:</label><br><span class="mensagem-`+id_atendimento+`">`+nl2br(data.registros[i].mensagem)+`</span></td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                `;

                                                                $(".titulo-"+id_atendimento).html(todos_atendimentos.registros[index].titulo);
                                                                $(".origem_endereco-"+id_atendimento).html(descricao_origem_endereco[todos_atendimentos.registros[index].origem_endereco]);
                                                                $(".departamento-"+id_atendimento).html(departamento);
                                                                $(".prioridade-"+id_atendimento).html(descricao_prioridade[todos_atendimentos.registros[index].prioridade]);
                                                                $(".status-"+id_atendimento).html(descricao_status[todos_atendimentos.registros[index].su_status]);
                                                                $(".endereco-"+id_atendimento).html(todos_atendimentos.registros[index].endereco);
                                                                
                                                                $(".mensagem-"+id_atendimento).html(nl2br(todos_atendimentos.registros[index].menssagem));
                                                            }
                                                        });

                                                    });

                                                    conteudo_principal = $(".conteudo-atendimento").html();
                                                    
                                                    $(".conteudo-atendimento").html(html_detalhes2 + conteudo_principal);

                                                    //Adiciona a classe active para o item do carousel da última interação com o atendimento
                                                    $(".data_contador_itens-"+(data.registros.length)).parent().addClass("active");
                                                    

                                                }else{

                                                    //Bloco para exibição de atendimentos sem interações
                                                    $(".item").addClass("active");
                                                    $(".btn-atendimento-anterior").addClass("disabled");
                                                    $(".btn-atendimento-proximo").addClass("disabled");
                                                    $.each(todos_atendimentos.registros, function(index){

                                                        if(departamentos.registros){
                                                            $.each(departamentos.registros, function(indice){
                                                                if(departamentos.registros[indice].id == todos_atendimentos.registros[index].id_ticket_setor){
                                                                    departamento = departamentos.registros[indice].setor;
                                                                }
                                                            });
                                                        }

                                                        if(todos_atendimentos.registros[index].id == id_atendimento){
                                                            $(".titulo-"+id_atendimento).html(todos_atendimentos.registros[index].titulo);
                                                            $(".origem_endereco-"+id_atendimento).html(descricao_origem_endereco[todos_atendimentos.registros[index].origem_endereco]);
                                                            $(".departamento-"+id_atendimento).html(departamento);
                                                            $(".prioridade-"+id_atendimento).html(descricao_prioridade[todos_atendimentos.registros[index].prioridade]);
                                                            $(".status-"+id_atendimento).html(descricao_status[todos_atendimentos.registros[index].su_status]);
                                                            $(".endereco-"+id_atendimento).html(todos_atendimentos.registros[index].endereco);
                                                            $(".mensagem-"+id_atendimento).html(nl2br(todos_atendimentos.registros[index].menssagem));
                                                        }
                                                    });
                                                }
                                            },
                                            error: function(){
                                                console.log("Erro ao buscar as interações do atendimento!");
                                            }
                                        });
                                    });


                            },
                            complete: function(){

                                $('.modal-atendimento').on('hidden.bs.modal', function(e){
                                    $(".btn-atendimento-anterior").removeClass("disabled");
                                    $(".btn-atendimento-proximo").removeClass("disabled");
                                    $(".conteudo-atendimento").html("");
                                });
                            },
                            error: function(error){
                                console.log("Erro ao buscar os atendimentos");
                            }
                        });
                    }
                    
                    //OS IXC
                    function osIxc(status_atendimento){
                        $.ajax({
                            url: "class/IntegracaoTipoSistemaAjax.php",
                            method: "GET",
                            dataType: "json",
                            data: {
                                acao: "busca_os",
                                status_atendimento: status_atendimento,
                                id_assinante: id_assinante,
                                id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>
                            },
                            success: function(data){

                                setores_os = [];

                                $html = '<div>Registros em ordem de serviço (classificação):</div>';
                                $status_os = {
                                    "A": "Aberto",
                                    "AN": "Análise",
                                    "EN": "Encaminhada",
                                    "AS": "Assumida",
                                    "AG": "Agendado",
                                    "EX": "Execução",
                                    "F": "Finalizado"
                                };
                                $status_prioridade = {
                                    "B": "Baixa",
                                    "N": "Normal",
                                    "A": "Alta",
                                    "C": "Crítica"
                                }

                                $.each(data.registros, function(index){

                                    id_os = data.registros[index].id;
                                    id_setor = data.registros[index].setor;

                                    console.log('id_setor: ' +id_setor);

                                    if(assuntos.registros){
                                        $.each(assuntos.registros, function(i){
                                            if(assuntos.registros[i].id == data.registros[index].id_assunto){
                                                assunto = assuntos.registros[i].assunto;
                                            }
                                        });
                                    }

                                    if(setores.registros){

                                        setor = '';

                                        if (id_setor == 0) {
                                                setor = 'N/D';
                                        } else {

                                            $.each(setores.registros, function(i){

                                                if(setores.registros[i].id == id_setor){
                                                    setor = setores.registros[i].setor;
                                                }
                                            });
                                        }

                                        setores_os[id_os] = setor;
                                    }

                                    protocolo = data.registros[index].protocolo;
                                    data_abertura = converteDataHora(data.registros[index].data_abertura);
                                    status = $status_os[data.registros[index].status];
                                    id_assunto = data.registros[index].id_assunto;
                                    prioridade_os = $status_prioridade[data.registros[index].prioridade];
                                    //setor = data.registros[index].setor;
                                    
                                    $html += `
                                    <div class='row' style='border: 1px solid #dddddd;padding:5px;margin: 0 0px 5px 0px;background-image: linear-gradient(to bottom,#f5f5f5 0,#e8e8e8 100%); background-repeat: repeat-x; border-radius: 3px;'>
                                        <input type="hidden" id="prioridade-`+id_os+`" value="`+prioridade+`" />
                                        <input type="hidden" id="setor-`+id_os+`" value="0" />
                                        <div class='col-md-6' style="padding-left: 10px;">
                                            <p style="margin-bottom: 0;"><strong>Protocolo: </strong>`+protocolo+`</p>
                                        </div>
                                        <div class='col-md-6' style="padding-left: 10px;">
                                            <p style="margin-bottom: 0;"><strong>Classificação: </strong>O.S.</p>
                                        </div>
                                        <div class='col-md-6' style="padding-left: 10px;">
                                            <p style="margin-bottom: 0;"><strong>Data criação: </strong>`+data_abertura+`</p>
                                        </div>
                                        <div class='col-md-6' style="padding-left: 10px;">
                                            <p style="margin-bottom: 0;"><strong>Assunto: </strong><span id="assunto-`+id_os+`">`+assunto+`</span></p>
                                        </div>
                                        <div class='col-md-6' style="padding-left: 10px;">
                                            <p style="margin-bottom: 0;"><strong>Status: </strong>`+status+`</p>
                                        </div>
                                        <div class='col-md-6' style="padding-left: 10px;">
                                            <button type="button" data_os="`+id_os+`" class="btn btn-info btn-xs btn-os pull-right" data-toggle="modal" data-assunto="`+assunto+`" data-target=".detalhes-`+protocolo+`"><i class='fa fa-plus'></i> Detalhes</button>
                                        </div>
                                    </div>
                                    `;

                                    $html += `<div class="modal fade modal-os detalhes-`+protocolo+`" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title"><label>Protocolo:</label> `+protocolo+`</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class='row'>

                                                        <div class="carousel-os-generic carousel slide" id="carousel-`+id_os+`" data-interval="false">
                                                            <div class="carousel-inner conteudo-os" role="listbox">

                                                            </div>
                                                            <hr>
                                                            <div class="controles-carousel" style="width: 194px;margin: 20px auto 0 auto;">
                                                                <a class="left btn btn-primary btn-os-anterior" href=".carousel-os-generic" role="button" data-slide="prev">
                                                                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                                                    Anterior
                                                                    <span class="sr-only">Previous</span>
                                                                </a>
                                                                <a class="right btn btn-primary btn-os-proximo" href=".carousel-os-generic" role="button" data-slide="next">
                                                                    Próximo
                                                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                                    <span class="sr-only">Next</span>
                                                                </a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                                    
                                });

                                if(data.registros){
                                    $(".painel-informacoes-os").html($html);
                                }else{
                                    $(".painel-informacoes-os").html("<div>Registros em ordem de serviço (classificação):</div><div class='alert alert-warning text-center' role='alert'>Não há ordens de serviço!</div>");
                                }

                                $(".btn-os").on("click", function(){
                                    id_os = $(this).attr("data_os");
                                    $.ajax({
                                        url: "class/IntegracaoTipoSistemaAjax.php",
                                        method: "GET",
                                        dataType: "json",
                                        data: {
                                            acao: "busca_evento_analisar",
                                            id_os: id_os,
                                            id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>
                                        },
                                        success: function(data){

                                            var html_acao = "";
                                            //tamanho total de todas interações com a ordem de serviço para determinar quantos itens terá o slide
                                            var tamanho_slides = data.registros.length;
                                            //contador inicializado com o número total de slides para o slide começar com a última interação da ordem de serviço
                                            var contador = tamanho_slides;
                                            
                                            //Se a ordem de serviço só tiver a ação de abertura, ambos os botões são configurados como 'disabled'
                                            if(tamanho_slides == 1){
                                                $(".btn-os-anterior").addClass("disabled");
                                                $(".btn-os-proximo").addClass("disabled");
                                            }

                                            $(".btn-os-proximo").addClass("disabled");

                                            $(".btn-os-anterior").on("click", function(){

                                                btn_anterior = $(this);

                                                contador -= 1;

                                                $("#data_contador_itens-"+contador).each(function(){
                                                    if(contador == 1){
                                                        btn_anterior.addClass("disabled");
                                                        $(".btn-os-proximo").removeClass("disabled");
                                                    }
                                                    if(contador != 1){
                                                        $(".btn-os-proximo").addClass("disabled");
                                                        btn_anterior.removeClass("disabled");
                                                    }
                                                    if(contador != 1 && contador != tamanho_slides){
                                                        btn_anterior.removeClass("disabled");
                                                        $(".btn-os-proximo").removeClass("disabled");
                                                    }
                                                });
                                            });
                                            
                                            $(".btn-os-proximo").on("click", function(){

                                                btn_proximo = $(this);

                                                contador += 1;
                                                
                                                $("#data_contador_itens-"+contador).each(function(){
                                                    if(contador == tamanho_slides){
                                                        btn_proximo.addClass("disabled");
                                                        btn_anterior.removeClass("disabled");
                                                    }
                                                    if(contador != tamanho_slides){
                                                        btn_anterior.addClass("disabled");
                                                        btn_proximo.removeClass("disabled");
                                                    }
                                                    if(contador != 1 && contador != tamanho_slides){
                                                        btn_anterior.removeClass("disabled");
                                                        btn_proximo.removeClass("disabled");
                                                    }
                                                });
                                            });

                                            contador_itens = 0;
                                            $.each(data.registros, function(i){
                                                
                                                var my_obj_str = JSON.stringify(data);
                                                //console.log('aqui: ' + my_obj_str);

                                                contador_itens += 1;

                                                assunto = $("#assunto-"+data.registros[i].id_chamado).text();
                                                prioridade = $("#prioridade-"+data.registros[i].id_chamado).val();

                                                //setor = $("#setor-"+data.registros[i].id_chamado).val();

                                                if(i == data.registros.length - 1){
                                                    active = " active";
                                                }else{
                                                    active = "";
                                                }

                                                if (tecnicos.registros) {
                                                    $.each (tecnicos.registros, function(indice) {
                                                        if (tecnicos.registros[indice].id == data.registros[i].id_tecnico) {
                                                            tecnico = tecnicos.registros[indice].funcionario;
                                                        } 
                                                        
                                                        if (data.registros[i].id_tecnico == '0' || data.registros[i].id_tecnico == '') {
                                                            tecnico = "";
                                                        }
                                                    });
                                                } else {
                                                    tecnico = "";
                                                }

                                                console.log(setores_os);

                                                setor = setores_os[data.registros[i].id_chamado];

                                                html_acao += `<div class='item`+active+`'>
                                                                <input type="hidden" id="data_contador_itens-`+contador_itens+`" value="`+contador_itens+`" />
                                                                <table class="table table-striped" style="width: 866px; margin: 0 auto;">
                                                                    <tr>
                                                                        <td><strong>Status: </strong>`+$status_os[data.registros[i].status]+`</td>
                                                                        <td><strong>Classificação: </strong>O.S.</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Data: </strong>`+converteDataHora(data.registros[i].data)+`</td>
                                                                        <td><strong>Assunto: </strong>`+assunto+`</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Assinante: </strong>`+assunto+`</td>
                                                                        <td><strong>Prioridade: </strong>`+prioridade_os+`</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Setor responsável: </strong>`+setor+`</td>
                                                                        <td><strong>Técnico responsável: </strong>`+tecnico+`</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2"><strong>Mensagem:</strong><br>`+nl2br(data.registros[i].mensagem)+`</td>
                                                                    </tr>
                                                                </table>
                                                               </div>`;
                                            });
                                            $(".conteudo-os").html(html_acao);
                                        },
                                        complete: function(){

                                            //Remove a classe disabled de ambos os botões sempre que o modal é fechado
                                            $('.modal-os').on('hidden.bs.modal', function (e) {
                                                $(".btn-os-anterior").removeClass("disabled");
                                                $(".btn-os-proximo").removeClass("disabled");
                                            });
                                        },
                                        error: function(){
                                            console.log("Erro ao buscar eventos de ordem de serviço!")
                                        }
                                    });
                                });
                            },
                            error: function(){
                                console.log("Erro ao buscar ordem de serviço!");
                            }
                        });
                    }
                    
            });

            </script>