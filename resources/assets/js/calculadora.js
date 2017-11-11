var teste;
$(document).ready( function() {

    //MASK INPUT
    $('.cep').mask('00000-000');

    //SHOW AND HIDE INFORMACOES ESPECIFICACOES
    $( ".especificacoes-toggle" ).click(function() {
        $(".especificacoes-box").slideToggle();
    });


    //QUANDO INSERE ALGUM DIGITO
    $('#origem').on("input", function() {
        var cep = $(this).val().replace(/\D/g, '');

        //VERIFICA SE CEP TEM 8 DIGITOS
        if(cep.length === 8){
            var url = 'https://viacep.com.br/ws/'+cep+'/json/';

            $.ajax({
                type: "GET",
                dataType: 'json',
                url: url,
                success: function(data){
                    if(data.erro === true){
                        //DADOS INCORRETOS
                        console.log('CEP não existe');
                    }
                    else{
                        //SEM ERRO
                        console.log(data);
                        console.log(data.bairro);
                        console.log(data.cidade);
                    }
                },
                error: function(data) {
                    console.log('Erro tente novamente mais tarde!');
                }
            });
        }

    });

    $("#form").submit(function(e){
        e.preventDefault();
        console.log('click');

        $.ajax({
            type: "POST",
            dataType: 'json',
            url: baseUrl+'/calcula',
            data: {
                _token:$("meta[name='csrf-token']").attr('content'),
                origem:$("input[name='origem']").val().replace(/\D/g, ''),
                destino:$("input[name='destino']").val().replace(/\D/g, ''),
                adicionar:($("input[name='adicionar']").is(':checked'))?1:0,
                altura:$("input[name='altura']").val().replace(/\D/g, ''),
                largura:$("input[name='largura']").val().replace(/\D/g, ''),
                comprimento:$("input[name='comprimento']").val().replace(/\D/g, ''),
                peso:$("input[name='peso']").val().replace(/\D/g, ''),
                seguro:$("input[name='seguro']").val().replace(/\D/g, '')
            },
            success: function(data){
                if(data.erro === true){
                    //DADOS INCORRETOS
                    console.log('Dados Incompletos');
                }
                else{
                    //SEM ERRO
                    console.log('Dados Completos');
                }
            },
            error: function(data) {
                console.log('Erro tente novamente mais tarde!');
            }
        });

    });

});

