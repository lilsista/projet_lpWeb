$(document).ready(function () {

    // quantite panier

    // on récupére la valeur de la selection
    $('select').click(function () {
        var quantite = $(this).val();
        var id = $(this).attr('id');

        $.ajax({
            type : 'get',
            url : 'http://127.0.0.1:8000/quantite/'+id+'/'+quantite,
            beforeSend : function(){
                console.log('ça charge');
            },
            success : function(data) {
                $('.totalProduit'+id).text(data.prixTotal);
                var totalGlobal = 0;
                $('.totalProduit').each(function () {
                    totalGlobal = totalGlobal + parseInt($(this).text());
                });
                console.log(totalGlobal);
                $('.totalGobal').text(totalGlobal);
            }
        });
    });
});