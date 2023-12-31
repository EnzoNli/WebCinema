<?php

include_once("../include/disallowGuest.php");
include_once("../include/nav.php");
include_once("../include/fcs_api.php");
include_once("../include/fcs_bd.php");
include_once("../include/base_html.php");
include_once("../include/res_recherche.php");

$nav = new Navbar("pages");
$infos_film = json_decode(getMovie($_GET['id_movie']), true);
//var_dump($infos_film);
afficher_entete("../css/film.css");
$nav->afficheNavbar(); ?>

<main>
    <img src="<?php echo getCheminVersAfficheOuBackdrop(6, $infos_film['backdrop_path'], basename(__DIR__)) ?>" alt="" id="bg-film">
    <img src="<?php echo getCheminVersAfficheOuBackdrop(4, $infos_film['poster_path'], basename(__DIR__)) ?>" alt="" id="poster-film">
    <h2 id="title-film"><?php echo $infos_film['title'] ?> <span id="date"><?php echo substr($infos_film['release_date'], 0, 4) ?></span></h2>
    <p id="genre-film"><?php echo genereStringGenres($infos_film['genres'], true) ?></p>
    <?php
    echo "<div id=\"note_bd\">";
    if (boolFilmExiste($infos_film['id'])) {
        echo afficher_note(round(floatval(getMoyenne($infos_film['id'])), 2), "la BD");
    } else {
        echo afficher_pas_note("--", "la BD");
    }
    echo "</div>";
    echo "<div id=\"note_api\">";
    if ($infos_film['vote_count']) {
        echo afficher_note(round(floatval($infos_film['vote_average']) / 2, 2), "l'API");
    } else {
        echo afficher_pas_note("--", "l'API");
    }
    echo "</div>";
    ?>
    <p id="synopsis"><?php echo $infos_film['overview'] ?></p>
    <hr id="sep_com">
    <h2 id="titre_com">Commentaires :<br></h2>
    <form id="sub" method="post">
        Note :
        <button type="button" name="1"><i class="rating__star far fa-star"></i></button>
        <button type="button" name="2"><i class="rating__star far fa-star"></i></button>
        <button type="button" name="3"><i class="rating__star far fa-star"></i></button>
        <button type="button" name="4"><i class="rating__star far fa-star"></i></button>
        <button type="button" name="5"><i class="rating__star far fa-star"></i></button>
        <br><br>
        <textarea name="com" id="com" cols="100" rows="10" maxlength="5000" placeholder="Veuillez écrire votre commentaire ici (5000 caractères max)" style="resize: none;" required></textarea>
        <input type="submit" id="sub_btn" value="Envoyer">
    </form>

    <div id="popup-container"></div>


    <div id="all_coms">
        <?php
        $tab_coms = getCommentaires($_GET['id_movie']);
        foreach ($tab_coms as $com) {
            echo "<div class=\"container_com\" >";
            echo "<p>" . $com['login_'] . " (" . $com['note'] . " <span><img class=\"etoile\" src=\"../images/etoile.png\" alt=\"\">) : </span></p>";
            echo "<p>" . $com['commentaire'] . "</p>";
            echo "</div><br>";
        }
        ?>
    </div>

    <div class="overlay"></div>

    <script>
        // passage en parametre pour requete ajax (noter un film) dans film.js
        var nom_utilisateur = "<?php echo $_SESSION['username']; ?>";
        var id_movie = <?php echo $_GET['id_movie']; ?>;
    </script>
    <script src="../js/film.js"></script>
</main>

<?php afficher_pied(); ?>