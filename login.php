<?php

session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
  header("Location: index.php");
  exit;
}

if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  try {
    $db = new PDO('sqlite:cinema.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT mdp FROM Utilisateur WHERE login_ = :username";
    $statement = $db->prepare($query);
    $statement->execute(array('username' => $username));
    $hashed_password = $statement->fetchColumn();

    // utiliser password_verify()
    if ($password == $hashed_password) {
      $_SESSION['logged_in'] = true;
      header("Location: index.php");
      exit;
    } else {
      $error = "Nom d'utilisateur ou mot de passe incorrect";
    }
  } catch (PDOException $e) {
    //$error = "Erreur lors de la connexion avec la base de données";
    $error = $e->getMessage();
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
    <link rel="stylesheet" href="login.css">
    <link rel="icon" type="image/png" href="logo.png"/>
    <title>Connexion</title>
</head>
  <body>
    <header>
      <nav>
        <a href="index.php">Accueil</a>
        <a href="login.php">Connexion</a>
      </nav>
    </header>
    <main>
      <h1>Connexion</h1>
      <?php if (isset($error)) { echo "<p style='color: red;'>" . $error . "</p>"; } ?>
      <form action="login.php" method="post">
        <input type="text" name="username" placeholder="Nom d'utilisateur">
        <input type="password" name="password" placeholder="Mot de passe">
        <button type="submit" name="submit">Se connecter</button>
      </form>
    </main>
  </body>
</html>