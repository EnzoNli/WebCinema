<?php 

include_once("../include/nav.php");
include_once("../include/fcs_api.php");

$nav = new Navbar("pages");
$infos_film = json_decode(getMovie($_GET['id_movie']), true);


?>


<!DOCTYPE html>
<html lang="fr">


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="co-authored by enzo nulli, zoé marquis">
    <title>Le Cinéma des Zous</title>
    <link rel="icon" type="../image/png" href="../images/logo.png" />
    <link rel="stylesheet" href="../css/film.css">
    <script src="../js/jquery.js"></script>
</head>

<body>
    <header>
        <?php $nav->afficheNavbar(); ?>
    </header>

    <main>
        <img src="<?php echo getCheminVersAfficheOuBackdrop(6, $infos_film['backdrop_path'], basename(__DIR__)) ?>" alt="" id="bg-film">
        <img src="<?php echo getCheminVersAfficheOuBackdrop(4, $infos_film['poster_path'], basename(__DIR__)) ?>" alt="" id="poster-film">
        <h2 id="title-film"><?php echo $infos_film['title'] ?> <span id="date"><?php echo substr($infos_film['release_date'], 0, 4)?><span></h2>
        <p id="genre-film"><?php echo genereStringGenres($infos_film['genres']) ?></p>
    </main>

</body>

</html>