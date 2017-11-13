$(document).ready( function() {

    //MASK INPUT
    $('.cep').mask('00000-000');
    $('.numero').mask("#.##0,00", {reverse: true});

    //SHOW AND HIDE INFORMACOES ESPECIFICACOES
    $( ".especificacoes-toggle" ).click(function() {
        $(".especificacoes-box").slideToggle();
    });

    //inicia um datatables padrao
    $('#example').DataTable({});

    //QUANDO INSERE ALGUM DIGITO
    $('.cep').on("input", function() {
        var cep = $(this).val().replace(/\D/g, '');

        //VERIFICA SE CEP TEM 8 DIGITOS
        if(cep.length === 8){
            var url = 'https://viacep.com.br/ws/'+cep+'/json/';
            var elemento = $(this);

            $.ajax({
                type: "GET",
                dataType: 'json',
                url: url,
                success: function(data){
                    if(data.erro === true){
                        //DADOS INCORRETOS
                        console.log('CEP não existe');
                        $("#btn-calcula").prop( "disabled", true );
                    }
                    else{
                        //SEM ERRO
                        elemento.parent().find(".data-estado").val(data.uf);
                        elemento.parent().find(".data-codigo-ibge").val(data.ibge);

                        if($("#origem").val() != '' && $("#destino").val() != ''){
                            $("#btn-calcula").prop( "disabled", false );
                        }


                    }
                },
                error: function(data) {
                    console.log('Erro tente novamente mais tarde!');
                    $("#btn-calcula").prop( "disabled", true );
                }
            });
        }

    });

    $("#btn-calcula").click(function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            dataType: 'json',
            url: baseUrl+'/calcula',
            data: {
                _token:$("meta[name='csrf-token']").attr('content'),
                origem:$("input[name='origem']").val().replace(/\D/g, ''),
                origem_estado:$("input[name='origem_estado']").val(),
                origem_codigo_ibge:$("input[name='origem_codigo_ibge']").val(),
                destino:$("input[name='destino']").val().replace(/\D/g, ''),
                destino_estado:$("input[name='destino_estado']").val(),
                destino_codigo_ibge:$("input[name='destino_codigo_ibge']").val(),
                adicionar:($("input[name='adicionar']").is(':checked'))?1:0,
                altura:$("input[name='altura']").val().replace(/\D/g, ''),
                largura:$("input[name='largura']").val().replace(/\D/g, ''),
                comprimento:$("input[name='comprimento']").val().replace(/\D/g, ''),
                peso:$("input[name='peso']").val().replace(/\D/g, ''),
                valor_objeto:$("input[name='valor_objeto']").val().replace(/\D/g, ''),
                aviso_recebimento:($("input[name='aviso_recebimento']").is(':checked'))?1:0,
                mao_propria:($("input[name='mao_propria']").is(':checked'))?1:0,
            },
            success: function(data){
                if(data.erro === true){
                    //DADOS INCORRETOS
                    console.log(data.mensagem);
                }
                else{
                    //SEM ERRO
                    console.log('Dados Completos');
                    var html = '';

                    //map resultado
                    $.map( data.resultado, function( val, i ) {
                        html += '<tr>';
                        html += ' <td>'+val.nome+'</td>';
                        html += ' <td>'+val.tipo+'</td>';
                        html += ' <td>'+val.valor_total+'</td>';
                        html += ' <td>'+val.prazo+'</td>';
                        html += '</tr>';
                    });

                    //Destroi datatables antigo
                    $('#example').DataTable().destroy();

                    //Insere resultado na tabela
                    $('#resultado_body').html(html);

                    //Inicia novo Datatables
                    $('#example').DataTable({
                        "searching": false, // <- remove barra de pesquisa
                        "paging": false, // <-- remove paginacao
                        "bInfo" : false, // <-- remove informacao de rodape
                        "order": [[ 2, "asc" ]], // <-- ordena do menor ao maior preco
                    });

                    //Esconde calculadora e mostra resultados
                    $("#calculadora").fadeOut(function(){
                        $("#resultados").fadeIn();
                    });
                }
            },
            error: function(data) {
                console.log('Erro tente novamente mais tarde!');
            }
        });

    });

    //Calcular de novo
    $("#btn-calcula-denovo").click(function(){
        //Esconde resultados e mostra calculadora
        $("#resultados").fadeOut(function(){
            $("#calculadora").fadeIn();
        });
    });

});

