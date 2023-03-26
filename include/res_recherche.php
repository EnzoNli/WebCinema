<?php

include_once("fcs_api.php");
include_once("fcs_bd.php");

setlocale(LC_TIME, 'fr_FR.UTF-8', 'fra');

function afficher_note($note, $bdOuApi) {
    $nb_etoiles = floor($note);
    $ch = '<div class="boite_note">
    ';
    $ch .= '<p class="texte_note">Note de ' . $bdOuApi . ' : <span>
    ';
    for ($i = 0; $i < $nb_etoiles; $i++) {
        $ch .= '<img class="etoile" src="../images/etoile.png" alt="étoile jaune">
        ';
    }
    for ($i = 0; $i < 5 - $nb_etoiles; $i++) {
        $ch .= '<img class="etoile" src="../images/etoilevide.png" alt="étoile grise">
        ';
    }
    $ch .= ' ' . $note;
    $ch .= '</span></p></div>
    ';
    return $ch;
}

function afficher_pas_note($note, $bdOuApi) {
    $ch = '<div class="boite_note">
    ';
    $ch .= '<p class="texte_note">Note de ' . $bdOuApi . ' : <span>
    ';
    $ch .= ' ' . $note;
    $ch .= '</span></p></div>
    ';
    return $ch;
}

function afficher_un_film($info_film, $isApiRequest) {
    if($isApiRequest){
        $film = $info_film;
        $movie_key = $film['id'];
    }else{
        $film = json_decode(getMovie($info_film), true);
        $movie_key = $info_film;
    }
    // image
    $img = getCheminVersAfficheOuBackdrop(4, $film['poster_path'], "include");
    // titre_fr
    $titre = $film['title'];
    // date de sortie -> en mode fr
    $date = $film['release_date'];
    // Avec :
    $acteurs = getActorsName($movie_key);
    // Genres : 
    $genres = $isApiRequest ? $film['genre_ids'] : $film['genres'];
    // synopsis
    $synopsis = $film['overview'];

    $ch = '<div class="unFilm"><a class="affichageFilm" href="film.php?id_movie=' . $movie_key . '">
    ';
    $ch .= '<div class="row">
    ';

    // IMAGE
    $ch .= '<div class="gauche">
    ';
    $ch .= '<img class="image" src="' . $img . '" alt="' . $titre . '" width="310" height="420">
    ';
    $ch .= '</div>
    '; // gauche

    $ch .= '<div class="droite">
    ';

    // DESCRIPTION : TITRE + DATE + DE + AVEC + GENRES
    $ch .= '<div class="description">
    ';
    $ch .= '<h2>' . $titre . '</h2>
    ';
    $ch .= '<div class="precision">
    ';
    if ($date != null)
        $ch .= '<span class="date">' . strftime("%e %B %Y ", strtotime($date)) . '</span>
        ';
    $ch .= '<div class="avec">Avec :
    ';
    $i = 0;
    $act = '';
    while ($i < 3 && $i < count($acteurs)) {
        $act .= '<span class="avec">' . $acteurs[$i]["name"] . ',</span>
        ';
        $i++;
    }
    $ch .= $act;
    $ch .= '</div>
    '; // avec
    $ch .= '<div class="genres">
    ';
    $ch .= $isApiRequest ? genereStringGenres($genres, false) : genereStringGenres($genres, true);

    $ch .= '</div>
    '; // genres
    $ch .= '</div>
    '; // precision
    $ch .= '</div>
    '; // description

    // SYNOPSIS
    $ch .= '<div class="synopsis">
    ';
    $ch .= $synopsis;
    $ch .= '</div>
    '; // synopsis

    // NOTES
    $ch .= '<div class="notes">
    ';

    // NOTE API
    if ($film['vote_count']) {
        $note_api = $film['vote_average'];
        $ch .= afficher_note(round(floatval($note_api / 2), 2), "l'API");
    } else {
        $note_api = "--";
        $ch .= afficher_pas_note($note_api, "l'API");
    }
    // NOTE BD
    if (boolFilmExiste($movie_key)) {
        $note_db = getMoyenne($movie_key);
        $ch .= afficher_note(round($note_db, 2), "la db des Zous");
    } else {
        $note_db = "--";
        $ch .= afficher_pas_note($note_db, "la db des Zous");
    }


    $ch .= '</div>
            </div>
            </div>
            </a>
            </div>
    ';

    return $ch;
}

function afficher_liste_assoc($tableau) {
    $ch = '<ul>
    ';
    foreach ($tableau as $key => $value) {
        $ch .= '<li>
        ';
        $ch .= afficher_un_film($value['api_movie_id'], false);
        $ch .= '</li>
        ';
    }
    $ch .= '</ul>';
    return $ch;
}

function afficher_liste($tableau, $isApiRequest) {
    $ch = '<ul>
    ';
    foreach ($tableau as $value) {
        $ch .= '<li>
        ';
        $ch .= afficher_un_film($value, $isApiRequest);
        $ch .= '</li>
        ';
    }
    $ch .= '</ul>';
    return $ch;
}