<?php

require_once("../include/fcs_api.php");
require_once("../include/fcs_bd.php");
require_once("../include/formulaire.php");
require_once("../include/res_recherche.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/liste_film.css">
    <title>Document</title>
</head>

<body>
    <?php

    echo afficher_form();

    //afficher_liste($tableau);
    echo afficher_un_film(315162);
    echo afficher_un_film(12201);
    echo afficher_un_film(424775);

    ?>

</body>

</html>