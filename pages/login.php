<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/db_connexion.php");

session_start();
$connexion = new ConnexionDB();

if ($connexion->userIsConnected($_SESSION)) {
  header("Location: ../index.php");
  exit;
}

if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if ($connexion->tryConnection($username, $password)) {
    $_SESSION['logged_in'] = true;
    $_SESSION['username'] = $username;
    header("Location: ../index.php");
    exit;
  } else {
    $error = "Nom d'utilisateur ou mot de passe incorrect";
  }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="co-authored by enzo nulli, zoé marquis">
  <link rel="stylesheet" href="../css/login.css">
  <link rel="icon" type="image/png" href="../images/logo.png" />
  <title>Connexion</title>
</head>

<body>
  <header>
  </header>
  <main>
    <h1>Connexion</h1>
    <?php if (isset($error)) {
      echo "<p style='color: red;'>" . $error . "</p>";
    } ?>
    <form action="login.php" method="post">
      <input type="text" name="username" placeholder="Nom d'utilisateur">
      <input type="password" name="password" placeholder="Mot de passe" required>
      <button type="submit" name="submit">Se connecter</button>
    </form>
  </main>
</body>

</html>