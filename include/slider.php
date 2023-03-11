<?php

include_once("fcs_api.php");


function genererSliderCard($chemin){
    $resp = getPopular();
    $infos = json_decode($resp, true);
    $conf = json_decode(getConfig(), true);
    foreach($infos['results'] as $movie){
        echo "<div class=\"slider-card\">";
        echo "<div class=\"slider-image\">";
        echo "<span class=\"api_note\">" . round(floatval($movie['vote_average'])/2, 2) . "<img src=\"images/etoile.png\" class=\"etoile\" alt=\"\"></span>";
        echo "<img src=\"" . getCheminVersAfficheOuBackdrop(3, $movie['poster_path'], $chemin) . "\" class=\"affiche\" alt=\"\">";
        echo "<a href=\"pages/film.php?id_movie=" . $movie['id'] . "\"><button class=\"slider-btn\">voir</button></a>";
        echo "</div>";
        echo "<div class=\"slider-info\">";
        echo "<h2 class=\"titre_film\">" . $movie['title'] . "</h2>";
        echo "</div>";
        echo "</div>";
    }
}

?>



<link rel="stylesheet" href="css/slider.css">

<section class="slider">
        <div id="background">
            <h2 class="slider-title">Populaire (API)</h2>
            <div class="slider-container">
                <button class="pre-btn"><img src="images/arrow.png" alt=""></button>
                <button class="nxt-btn"><img src="images/arrow.png" alt=""></button>
                <?php genererSliderCard(basename(__DIR__)); ?>
            </div>
        </div>
</section>

<script src="js/slider.js"></script>