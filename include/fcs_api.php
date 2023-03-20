<?php

$api_key = "99d8800f8d0f8aea34740a64e8617a2a";

function genereStringGenres($tab_genre) {
    $count = count($tab_genre);
    $res = "";
    foreach ($tab_genre as $genre) {
        if (--$count <= 0) {
            $res .= $genre['name'];
            break;
        }
        $res .= $genre['name'] . ", ";
    }

    return $res;
}

function getCheminVersAfficheOuBackdrop($size, $poster_path, $chemin) {
    if ($poster_path == null) {
        if ($chemin == "pages") {
            if ($size != 6) {
                return "../images/noimageaffiche.png";
            } else {
                return "../images/noimagebackdrop.png";
            }
        } else {
            if ($size != 6) {
                return "images/noimageaffiche.png";
            } else {
                return "images/noimagebackdrop.png";
            }
        }
    }
    $conf = json_decode(getConfig(), true);
    return $conf['images']['base_url'] . $conf['images']['poster_sizes'][$size] . $poster_path;
}


function requeteCurl($url) {
    if (extension_loaded("curl")) {
        $ch = curl_init();
        try {
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
            if ($http_code != intval(200)) {
                curl_close($ch);
                throw new Exception("Mauvaise requete");
            } else {
                curl_close($ch);
                return $response;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    } else {
        throw new Exception("cURL désactivé");
    }
}

function getConfig() {
    global $api_key;
    return requeteCurl("http://api.themoviedb.org/3/configuration?api_key=" . $api_key);
}

function getPopular() {
    global $api_key;
    return requeteCurl("https://api.themoviedb.org/3/movie/popular?api_key=" . $api_key . "&language=fr-FR&page=1");
}

function getRecherche($recherche) {
    global $api_key;
    return requeteCurl("https://api.themoviedb.org/3/search/movie?api_key=" . $api_key . "&language=fr-FR&query=" . urlencode($recherche) . "&page=1&include_adult=false");
}

function getRechercheAvancee($res_build_query) {
    global $api_key;
    return "https://api.themoviedb.org/3/search/movie?api_key=" . $api_key . "&" . $res_build_query;
}

function getMovie($id_movie) {
    global $api_key;
    return requeteCurl("https://api.themoviedb.org/3/movie/" . $id_movie . "?api_key=" . $api_key . "&language=fr-FR");
}

function getTopRatedMovies() {
    global $api_key;
    return requeteCurl("https://api.themoviedb.org/3/movie/top_rated?api_key=" . $api_key . "&language=fr-FR");
}

function getActorsName($id_movie) {
    global $api_key;
    $req = json_decode(requeteCurl("https://api.themoviedb.org/3/movie/" . $id_movie . "/credits?api_key=" . $api_key . "&language=fr-FR"), true);
    $res = array();
    foreach ($req['cast'] as $actor) {
        array_push($res, array("id" => $actor['id'], "name" => $actor['original_name']));
    }
    return $res;
}

function getGenreListe(){
    global $api_key;
    return json_decode(requeteCurl("https://api.themoviedb.org/3/genre/movie/list?api_key=" . $api_key . "&language=fr-FR"), true)['genres'];
}
