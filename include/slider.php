<?php

include_once("fcs_api.php");


function genererSliderCard(){
    $resp = getPopular();
    $infos = json_decode($resp, true);
    $conf = json_decode(getConfig(), true);
    foreach($infos['results'] as $movie){
        echo "<div class=\"slider-card\">";
        echo "<div class=\"slider-image\">";
        echo "<span class=\"api_note\">" . $movie['vote_average'] . "<img src=\"images/etoile.png\" class=\"etoile\" alt=\"\"></span>";
        echo "<img src=\"" .  $conf['images']['base_url'] . $conf['images']['poster_sizes'][3] . $movie['poster_path'] . "\" class=\"affiche\" alt=\"\">";
        echo "<button class=\"slider-btn\">voir</button>";
        echo "</div>";
        echo "<div class=\"slider-info\">";
        echo "<h2 class=\"titre_film\">" . $movie['title'] . "</h2>";
        echo "<p class=\"genre_film\">Thriller</p>";
        echo "</div>";
        echo "</div>";
    }
}

?>



<link rel="stylesheet" href="css/slider.css">

<section class="slider">
        <div id="background">
            <h2 class="slider-title">Populaire</h2>
            <div class="slider-container">
                <button class="pre-btn"><img src="images/arrow.png" alt=""></button>
                <button class="nxt-btn"><img src="images/arrow.png" alt=""></button>
                <?php genererSliderCard(); ?>
            </div>
        </div>
</section>

<script src="js/slider.js"></script>