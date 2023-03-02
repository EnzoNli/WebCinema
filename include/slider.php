<?php


$api_key = "99d8800f8d0f8aea34740a64e8617a2a";

function requeteCurl($url){
    if(extension_loaded("curl")){
        $ch = curl_init();
        try{
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);   
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);         
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                echo curl_error($ch);
                die();
            }
    
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($http_code != intval(200)){
                curl_close($ch);
                throw new Exception("Mauvaise requete");
            }else{
                curl_close($ch);
                return $response;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }else{
        throw new Exception("cURL désactivé");
    }
}

function getConfig(){
    global $api_key;
    return requeteCurl("http://api.themoviedb.org/3/configuration?api_key=" . $api_key);
}

function getPopular(){
    global $api_key;
    return requeteCurl("https://api.themoviedb.org/3/movie/popular?api_key=" . $api_key . "&language=fr-FR&page=1");

}



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
        <h2 class="slider-title">Populaire</h2>
        <button class="pre-btn"><img src="images/arrow.png" alt=""></button>
        <button class="nxt-btn"><img src="images/arrow.png" alt=""></button>
        <div class="slider-container">
            <?php genererSliderCard(); ?>
        </div>
</section>

<script src="js/slider.js"></script>