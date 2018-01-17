$(document).ready(function(){

    $('.suppProduit').click(function(e){
        e.preventDefault();
        var id = $(this).attr('id');
        $.ajax({
            type : "get",
            url : "http://127.0.0.1:8000/supprimer/"+id,
            success : function (data) {
               $('#'+data.idLigne).parent().parent().css('display','none');
               alert("vous avez suppprimé le produit "+ data.idLigne);
            }
        });
    })


});