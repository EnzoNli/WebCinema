<?php
include_once("include/nav.php");
include_once("include/base_html.php");
$nav = new Navbar("");
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="co-authored by enzo nulli, zoé marquis">
    <title>Le Cinéma des Zous</title>
    <link rel="icon" type="image/png" href="images/logo.png" />
    <link rel="stylesheet" href="css/index.css">
    <script src="js/jquery.js"></script>
</head>

<body>

    <header>
        <?php $nav->afficheNavbar(); ?>
    </header>
    <main>
        <?php include_once("include/slider.php"); ?>
    </main>

<?php afficher_pied() ?>