<?php

include_once("../include/nav.php");
include_once("../include/fcs_api.php");
include_once("../include/fcs_bd.php");
include_once("../include/base_html.php");
include_once("../include/res_recherche.php");

$nav = new Navbar("pages");
$infos_film = json_decode(getMovie($_GET['id_movie']), true);

echo afficher_entete("../css/film.css");

noter_un_film("Zoze", 315162, 2, "a fait peur à Kaloo");
noter_un_film("Enzo", 315162, 3, "Olak a adoré");

?>
<header>
    <?php $nav->afficheNavbar(); ?>
</header>

<main>
    <img src="<?php echo getCheminVersAfficheOuBackdrop(6, $infos_film['backdrop_path'], basename(__DIR__)) ?>" alt="" id="bg-film">
    <img src="<?php echo getCheminVersAfficheOuBackdrop(4, $infos_film['poster_path'], basename(__DIR__)) ?>" alt="" id="poster-film">
    <h2 id="title-film"><?php echo $infos_film['title'] ?> <span id="date"><?php echo substr($infos_film['release_date'], 0, 4) ?><span></h2>
    <p id="genre-film"><?php echo genereStringGenres($infos_film['genres']) ?></p>
    <?php
    echo "<div id=\"note_bd\">";
    if (boolFilmExiste($infos_film['id'])) {
        echo afficher_note(round(floatval(getMoyenne($infos_film['id'])), 2), "la BD");
    } else {
        echo afficher_note(0, "la BD");
    }
    echo "</div>";
    echo "<div id=\"note_api\">";
    echo afficher_note(round(floatval($infos_film['vote_average']) / 2, 2), "l'API");
    echo "</div>";
    ?>
    <p id="synopsis"><?php echo $infos_film['overview'] ?></p>
    <hr id="sep_com">
    <h2 id="titre_com">Commentaires :<br></h2>
    <form id="sub" action="" method="post">
        Note :
        <button type="button" name="1"><i class="rating__star far fa-star"></i></button>
        <button type="button" name="2"><i class="rating__star far fa-star"></i></button>
        <button type="button" name="3"><i class="rating__star far fa-star"></i></button>
        <button type="button" name="4"><i class="rating__star far fa-star"></i></button>
        <button type="button" name="5"><i class="rating__star far fa-star"></i></button>
        <br><br>
        <textarea name="com" id="com" cols="100" rows="10" maxlength="5000" placeholder="Veuillez écrire votre commentaire ici (5000 caractères max)" style="resize: none;" required></textarea>
        <input type="submit" id="sub_btn"></button>
    </form>


    <div id="all_coms">
        <?php
            $tab_coms = getCommentaires($_GET['id_movie']);
            foreach($tab_coms as $com){
                echo "<div class=\"container_com\"";
                echo "<p>" . $com['login_'] . " (" . $com['note'] . " <span><img class=\"etoile\" src=\"../images/etoile.png\" alt=\"\"></img>) : <span></p>";
                echo "<p>" . $com['commentaire'] . "</p>";
                echo "</div><br>";
            }
        ?>
    </div>

    <script>
        const ratingStars = [...document.getElementsByClassName("rating__star")];

        function executeRating(stars) {
            const starClassActive = "rating__star fas fa-star";
            const starClassInactive = "rating__star far fa-star";
            const starsLength = stars.length;
            let i;
            stars.map((star) => {
                star.onclick = () => {
                    i = stars.indexOf(star);

                    if (star.className === starClassInactive) {
                        for (i; i >= 0; --i) stars[i].className = starClassActive;
                    } else {
                        for (i; i < starsLength; ++i) stars[i].className = starClassInactive;
                    }
                };
            });
        }
        executeRating(ratingStars);


        $("#sub").submit(function(event) {
            var numItems = $('.fas').length
            var comment = $('textarea#com').val();
            event.preventDefault();
            jQuery.ajax({
                type: "POST",
                url: "../include/requeteAjaxJs.php",
                data: {
                    functionname: 'noteFilm',
                    arguments: ["<?php echo $_SESSION['username'] ?>", <?php echo $_GET['id_movie'] ?>, numItems, comment]
                }
            }).done(function(reponse) {
                alert(reponse);
            });
        });
    </script>
</main>

<?php echo afficher_pied(); ?>