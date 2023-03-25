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
    var genre = "";
    var annee = "";
    var delay = (function() {
        var timer = 0;
        return function(callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();



        $('#api_form').submit(function(event){
            $('#api_form').append("<div class=\"loader\"></div>");

            console.log($("#api_form :input[name='genre']")[0].value);
            event.preventDefault();
            jQuery.ajax({
                type: "POST",
                url: "../include/requeteAjaxJs.php",
                data: {
                    functionname: 'rechercheAvanceeAPI',
                    arguments: [
                        $("#api_form :input[name='trier']")[0].value,
                        $("#api_form :input[name='keywords']")[0].value,
                        $("#api_form :input[name='genre']")[0].value,
                        $("#api_form :input[name='debut']")[0].value,
                        $("#api_form :input[name='fin']")[0].value
                    ]
                }
            }).done(function(reponse) {
                $('.loader').remove();
                $("#res").html(reponse);
            });
        });
</script>
<?php afficher_pied() ?>