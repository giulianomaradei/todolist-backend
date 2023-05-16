<?php 
    require_once(__DIR__."/System.php");
    
    
    $descricao_setor = (!empty($_POST['descricao_setor'])) ? $_POST['descricao_setor'] : '';
    $descricao_horario = (!empty($_POST['descricao_horario'])) ? $_POST['descricao_horario'] : '';

    if ( !empty($_POST['inserir']) ) {

        if ($descricao_setor != '' && $descricao_horario != '') {
            inserir($descricao_setor, $descricao_horario);

        } else {
            $alert = ('Não foi possível cadastrar!','d');
            header("location: /api/iframe?token=$request->token&view=painel-cliente-horarios-form");
        }
    } else if ( !empty($_POST['alterar']) ) {

        if ($descricao_setor != '' && $descricao_horario != '') {
            alterar($descricao_setor, $descricao_horario);

        } else {
            $alert = ('Não foi possível alterar!','d');
            header("location: /api/iframe?token=$request->token&view=painel-cliente-horarios-form");
        }
    
    }

    function inserir ($descricao_setor, $descricao_horario) {

        $cont = 0;
        foreach($descricao_setor as $conteudo){
            $dados_horario[$cont]['descricao_setor'] = $conteudo;
            $cont++;
        }

        $cont = 0;
        foreach($descricao_horario as $conteudo){
            $dados_horario[$cont]['descricao_horarios'] = $conteudo;
            $cont++;
        }

        $data = getdatahora();

        foreach ($dados_horario as $conteudo) {
            
            $descricao_setor = $conteudo['descricao_setor'];
            $descricao_horarios = $conteudo['descricao_horarios'];

            $dados = array(
                'descricao_setor' => $descricao_setor,
                'descricao_horarios' => $descricao_horarios,
                'data_cadastro' => $data
            );
    
            $insertID = DBCreate('', 'tb_painel_horarios', $dados, true);
            registraLog('Inserção de horarios para o painel do cliente.','i','tb_painel_horarios',$insertID,"decricao_setor: $descricao_setor | descricao_horarios: $descricao_horarios | data_cadastro: $data");
        }

        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=painel-cliente-horarios-form");

    }

    function alterar ($descricao_setor, $descricao_horario) {

        $cont = 0;
        foreach($descricao_setor as $conteudo){
            $dados_horario[$cont]['descricao_setor'] = $conteudo;
            $cont++;
        }

        $cont = 0;
        foreach($descricao_horario as $conteudo){
            $dados_horario[$cont]['descricao_horarios'] = $conteudo;
            $cont++;
        }

        $data = getdatahora();

        DBDelete('', 'tb_painel_horarios');

        foreach ($dados_horario as $conteudo) {
            
            $descricao_setor = $conteudo['descricao_setor'];
            $descricao_horarios = $conteudo['descricao_horarios'];

            $dados = array(
                'descricao_setor' => $descricao_setor,
                'descricao_horarios' => $descricao_horarios,
                'data_cadastro' => $data
            );
    
            $insertID = DBCreate('', 'tb_painel_horarios', $dados, true);
            registraLog('Inserção de horarios para o painel do cliente.','i','tb_painel_horarios',$insertID,"decricao_setor: $descricao_setor | descricao_horarios: $descricao_horarios | data_cadastro: $data");
        }

        $alert = ('Item alterado com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=painel-cliente-horarios-form");

    }