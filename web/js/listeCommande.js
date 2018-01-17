$(document).ready(function () {
    $('#trierCommande').on('click',function(){
        var element = $(this).val();
        $.ajax({
            type : "post",
            url : "http://127.0.0.1:8000/commercant/commandes/trier",
            data : {
                element : element
            },
            success : function (data) {
                var content = $('tbody');
                var commandes = jQuery.parseJSON(data);
                console.log(commandes);
                content.html('');
                if(commandes == null){
                    var tr = $('<tr><td>pas de commandes</td></tr>');
                    tr.appendTo(content);
                }else{
                    $.each(commandes, function (key,commande){
                        console.log(commande.id);
                       var ligne = $('<tr class="tbody">' +
                           '<td>'+commande.id+'</td>' +
                           '<td>' +
                           '<p>Nom : '+commande.client.nom+'</p>' +
                           '<p>Prénom : '+commande.client.prenom+'</p>'+
                           '<p>Email : '+commande.client.email+'</p>'+
                           '</td>' +
                           '<td>'+commande.date+'</td>'+
                           '<td>'+commande.montantTotal+'</td>'+
                           '</tr>');
                        ligne.appendTo(content);
                        var bool = '';
                        if(commande.estLivre){
                            bool = "oui";
                        }
                        else{
                            bool = "non";
                        }
                        var livrer = $('<td>'+bool+'</td>');
                        livrer.appendTo('.tbody');

                    });
                }
            }
        })
    })
});