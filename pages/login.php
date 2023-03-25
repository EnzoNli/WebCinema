<?php

include_once(getcwd() . "/../include/db_connexion.php");
include_once("../include/base_html.php");

session_start();
$connexion = new ConnexionDB("../database");

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

echo afficher_entete("../css/login.css");

?>
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