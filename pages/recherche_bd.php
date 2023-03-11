<?php

include_once("../include/fcs_api.php");
include_once("../include/fcs_bd.php");
include_once("../include/formulaire.php");
include_once("../include/res_recherche.php");
include_once("../include/base_html.php");

echo afficher_entete("../css/liste_film.css");

noter_un_film("Zoze", 315162, 2, "a fait peur à Kaloo");
noter_un_film("Enzo", 315162, 3, "Olak a adoré");
noter_un_film("TotorLeCastor", 315162, 5, "j'aime bien pcq y avait un chat");

echo afficher_form();

?>
<div id="res"></div>

<script>
    var trier = "sans";
    var debut = 'no';
    var fin = 'no';
    var titre = '';
    var genre = 'sans';
    var acteur = '';
    var noteSup = 'no';
    var noteInf = 'no';
    var delay = (function () {
        var timer = 0;
        return function (callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();

    $( document ).ready(function() {
        jQuery.ajax({
            type: "POST",
            url: "../include/requeteAjaxJs.php",
            data: {
                functionname: 'rechercheDB',
                arguments: [trier, debut, fin, titre, genre, acteur, noteSup, noteInf]
            }
        }).done(function(reponse) {
            $("#res").html(reponse);
        });
    });

    $('select').on('change', function () {
        var optionSelected = $("option:selected", this);
        var valueSelected = optionSelected.attr("value");
        var nameSelected = optionSelected.attr("name");
        switch(nameSelected){
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
                inf = valueSelected;
                break;
            case "sup":
                sup = valueSelected;
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
            $("#res").html(reponse);
        });
        
    });

    $("input").on("input", function() {
        var valueSelected = $(this).val();
        var nameSelected = $(this).attr("name");
        delay(function (){
    
            switch(nameSelected){
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
                $("#res").html(reponse);
            });
        }, 300);
    });
</script>


<?php
echo afficher_pied();

