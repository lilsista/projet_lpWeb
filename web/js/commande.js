$(document).ready(function(){

    var erreur = false;
    $('.required').parent().find('.input').on('blur',function () {
       var divErreur = $(this).parent().find('.erreur');
       if(verifVide($(this).val())){
           divErreur.html("Veuillez renseigner ce champ");
           divErreur.css('display', 'block');
           erreur = true;
           console.log('erreur');
       }else {
           divErreur.html('');
           divErreur.css('display', 'none');
           erreur = false;
       }
    });

    $('#email').on('blur',function () {
        var divErreur = $(this).parent().find('.erreur');
        if(!verifEmail($(this).val())){
            divErreur.html("email invalide !");
            divErreur.css('display', 'block');
            erreur = true;
            console.log('erreur');
        }else{
            divErreur.html('');
            divErreur.css('display', 'none');
            erreur = false;
        }
    });

    $('.required').parent().find('.text').on('blur',function () {
       var divErreur = $(this).parent().find('.erreur');
       if(!veriftext($(this).val())){
           divErreur.html('champ invalid');
           divErreur.css('display', 'block');
           erreur = true;
           console.log('erreur');
       }else{
           divErreur.html('');
           divErreur.css('display', 'none');
           erreur = false;
       }
    });

    $("#formClient").submit(function(e){
       e.preventDefault();
       $('.required').parent().find('input').trigger('blur');
       if(!erreur){
           var url = "http://127.0.0.1:8000/gestionCommande";
           $.ajax({
               type: 'post',
               url: url,
               data: {
                   email: $('#email').val(),
                   nom: $('#nom').val(),
                   prenom: $('#prenom').val(),
                   idpanier: $('#idpanier').val(),
                   montantTotal : $('.totalGobal').text()
               },
               success  : function(obj){
                   console.log(obj);
               },
               error : function(err){
                   console.log("erreur" + err);
               }
           });
           $('.apres_envoyer').html('');
           $('.apres_envoyer').css('display','none');
           $('#email').val('');
           $('#nom').val('');
           $('#prenom').val('');

       }else{
           $('.apres_envoyer').html("vous n'avez pas correctement remplie le formulaire, Réessayer");
           $('.apres_envoyer').css('display','block');

       }
    });

    function verifVide(champs) {
        return champs === "";
    }

    function verifEmail(email){
        var regex = /^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return regex.test(email);
    }

    function veriftext(text){
        var regex = /^[a-zA-Záàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ-]+$/;
        return regex.test(text);
    }

});