<?php

include_once("../include/fcs_api.php");
include_once("../include/formulaire.php");
include_once("../include/res_recherche.php");
include_once("../include/base_html.php");
include_once("../include/base_html.php");
include_once("../include/nav.php");
$nav = new Navbar("pages");

echo afficher_entete("../css/liste_film.css");


?>
<header>
    <?php $nav->afficheNavbar(); ?>
</header>
<main>
    <div id="formDB">
        <h1>Recherche API</h1>
        <?php echo afficher_form_api(); ?>
    </div>
    <div id="res"></div>
</main>

<script>
    var trier = "";
    var query = "";
    var genre = "";
    var annee = "";

    var delay = (function() {
        var timer = 0;
        return function(callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();

        $(document).ready(function() {
            jQuery.ajax({
                type: "POST",
                url: "../include/requeteAjaxJs.php",
                data: {
                    functionname: 'rechercheAvanceeAPI',
                    arguments: []
                }
            }).done(function(reponse) {
                $("#res").html(reponse);
            });
        });

        $('select').on('change', function() {
            var optionSelected = $("option:selected", this);
            var valueSelected = optionSelected.attr("value");
            var nameSelected = $(this).attr("name");
            switch (nameSelected) {
                case "trier":
                    trier = valueSelected;
                    break;
                case "genre":
                    genre = valueSelected;
                    break;
                case "year":
                    annee = valueSelected;
                    break;
            }
            if(query != ""){
                jQuery.ajax({
                    type: "POST",
                    url: "../include/requeteAjaxJs.php",
                    data: {
                        functionname: 'rechercheAvanceeAPI',
                        arguments: [query, trier, genre, annee]
                    }
                }).done(function(reponse) {
                    $("#res").html(reponse);
                });
            }

        });

        $("input").on("input", function() {
            var valueSelected = $(this).val();
            var nameSelected = $(this).attr("name");
            delay(function() {

                if(nameSelected == "titre"){
                    query = valueSelected
                }
                jQuery.ajax({
                    type: "POST",
                    url: "../include/requeteAjaxJs.php",
                    data: {
                        functionname: 'rechercheAvanceeAPI',
                        arguments: [query, trier, genre, annee]
                    }
                }).done(function(reponse) {
                    $("#res").html(reponse);
                });
            }, 600);
        });
</script>
<?php afficher_pied() ?>