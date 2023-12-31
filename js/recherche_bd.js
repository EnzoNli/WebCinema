var trier = "sans";
var debut = 'no';
var fin = 'no';
var titre = '';
var genre = 'sans';
var acteur = '';
var noteSup = 'no';
var noteInf = 'no';
var delay = (function() {
    var timer = 0;
    return function(callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
})();

$(document).ready(function() {
    $('#db_form').append("<div class=\"loader\"></div>");
    jQuery.ajax({
        type: "POST",
        url: "../include/requeteAjaxJs.php",
        data: {
            functionname: 'rechercheDB',
            arguments: [trier, debut, fin, titre, genre, acteur, noteSup, noteInf]
        }
    }).done(function(reponse) {
        $('.loader').remove();
        $("#res").html(reponse);
    });
});

$('select').on('change', function() {
    $('#db_form').append("<div class=\"loader\"></div>");
    var optionSelected = $("option:selected", this);
    var valueSelected = optionSelected.attr("value");
    var nameSelected = $(this).attr("name");
    switch (nameSelected) {
        case "trier":
            trier = valueSelected;
            break;
        case "debut":
            debut = valueSelected;
            break;
        case "fin":
            fin = valueSelected;
            break;
        case "genre":
            genre = valueSelected;
            break;
        case "inf":
            noteInf = valueSelected;
            break;
        case "sup":
            noteSup = valueSelected;
            break;
    }
    jQuery.ajax({
        type: "POST",
        url: "../include/requeteAjaxJs.php",
        data: {
            functionname: 'rechercheDB',
            arguments: [trier, debut, fin, titre, genre, acteur, noteSup, noteInf]
        }
    }).done(function(reponse) {
        $('.loader').remove();
        $("#res").html(reponse);
    });

});

$("input").on("input", function() {
    $('#db_form').append("<div class=\"loader\"></div>");
    var valueSelected = $(this).val();
    var nameSelected = $(this).attr("name");
    delay(function() {

        switch (nameSelected) {
            case "titre":
                titre = valueSelected;
                break;
            case "acteur":
                acteur = valueSelected;
                break;
        }
        jQuery.ajax({
            type: "POST",
            url: "../include/requeteAjaxJs.php",
            data: {
                functionname: 'rechercheDB',
                arguments: [trier, debut, fin, titre, genre, acteur, noteSup, noteInf]
            }
        }).done(function(reponse) {
            $('.loader').remove();
            $("#res").html(reponse);
        });
    }, 600);
});