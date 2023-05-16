<?php
?>
    <style>
        body{
            padding-top: 0 !important; 
        }
        .navbar{
            display: none !important;
        }    
        td,th{
            padding: 0 !important;
        }
        #div-parametros {
            overflow-x: scroll;
            overflow-y: hidden;
            white-space: nowrap;
        }
    </style>

    <div class="row" id="">
        <div class="col-lg-12" id="alertas"></div>
    </div>
    
    <script>
        var alertas = function(){
            $.ajax({
                cache: false,
                type: "POST",
                data: 
                { 
                    tipo:'proximos_operadores',
                    token: '<?= $request->token ?>'
                },
                url:'/api/ajax?class=TelaoAlerta.php',
                success: function(data){
                    $('#alertas').html(data);
                }
            });
            setTimeout(function(){ alertas(); },50000);
        };
        alertas();
    </script>