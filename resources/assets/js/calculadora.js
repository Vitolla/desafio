var teste;
$(document).ready( function() {
    $('#origem').on("input", function() {
        var cep = $(this).val();
        var url = 'https://viacep.com.br/ws/'+cep+'/json/';

        $.ajax({
            type: "GET",
            dataType: 'json',
            url: url,
            success: function(data){
                if(data.erro == true){
                    console.log('cep nao existe');
                }
                else{
                    console.log(data);
                    console.log(data.bairro);
                    console.log(data.cidade);
                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    });
});

