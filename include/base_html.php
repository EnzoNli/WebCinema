<?php

function afficher_entete($css) {
    $ch = '<!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="co-authored by enzo nulli, zoé marquis">
        <title>Le Cinéma des Zous</title>
        <link rel="icon" type="image/png" href="../images/logo.png">
        <link rel="stylesheet" href="' . $css . '">
        <script src="../js/jquery.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    </head>

    <body>
    ';
    echo $ch;
}

function afficher_pied() {
    $ch = '
    </body>
    </html>
    ';
    echo $ch;
}
