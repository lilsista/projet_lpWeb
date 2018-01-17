$("document").ready(function () {

    $(".trier").click(function (e) {
        e.preventDefault();
        console.log("ok ok ");
        var item = $(this).text();
        console.log(item);
        $.ajax({
            type: 'get',
            url: "/trier/" + item,
            beforeSend: function () {
                console.log("ça charge");

            },
            success: function (data) {
                var produit = jQuery.parseJSON(data);
                console.log(produit);
                // mettre a jours les données de la page
                var div = $('.content-figure');
                div.html(" ");
                $.each(produit, function (key, prod) {
                    console.log(key + ":" + prod.prix);
                    var prix = prod.prix;
                    // language=HTML
                    var figure = $('<figure class="figure"></figure>')
                    var image = $('<img class="rounded img-acceuil-produit img-fluid figure-img" src="/uploads/image/' + prod.image + '">');
                    var figcaption = $('<figcaption class="figure-caption">');
                    var designation = $('<p><span class="designation">' + prod.designation + '</span> <span class="prix">' + prod.prix + '</span>€</p>');
                    var description = $('<p class="description">' + prod.description + '</p>');
                    var quantite = $('<p> il en reste <span class="quantite">' + prod.quantite + '</span></p>');
                    var ajouter = $('<p class="text-right"><button class="btn btn-success">Ajouter</button></p>');
                    figure.appendTo(div);
                    image.appendTo(figure);
                    figcaption.appendTo(figure);
                    designation.appendTo(figcaption);
                    description.appendTo(figcaption);
                    quantite.appendTo(figcaption);
                    ajouter.appendTo(figcaption);
                })
            }
        });
    });

});


