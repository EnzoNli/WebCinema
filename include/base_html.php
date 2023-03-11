<?php

function afficher_entete($js, $css) {
    $ch = '<!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="co-authored by enzo nulli, zoé marquis">
        <title>Le Cinéma des Zous</title>
        <link rel="icon" type="../image/png" href="../images/logo.png" />
        <link rel="stylesheet" href="' . $css . '">
        <script src="' . $js . '"></script>
    </head>

    <body>
    ';
    return $ch;
}