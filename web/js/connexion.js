$(document).ready(function () {

    // on récupére les valeur quand on submit
    $(".connexion").submit(function () {
        var login = $('.login').val();
        var mdp = $('.mdp').val();

        $.post
        (
            $('.connexion').attr('action'),
            {
                login : login,
                mdp : mdp
            },
            function(reponse){
                if(!reponse.succes){
                    $('.alert-danger').html('Vos idantifiants sont incorrects, Veuillez Réessayer');
                }
            },
            'json'
        );
        return false;
    });

});

