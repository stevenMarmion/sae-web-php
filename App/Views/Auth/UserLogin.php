<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Public/Css/Auth/login-style.css">
    <title>Formulaire de Connexion</title>
</head>
<body>
    <?php 
        include __DIR__ . '/../Layout/Auth/NavBar.php';
    ?>

    <section>
        <h2>Connexion</h2>

        <form action="/App/Controllers/Auth/AuthLoginController.php" method="post">
            <label for="pseudo">Pseudo :</label>
            <input type="text" id="pseudo" name="pseudo" required>
            <br>

            <label for="mdp">Mot de passe :</label>
            <input type="password" id="mdp" name="mdp" required>
            <br>

            <input type="submit" value="Se Connecter">

            <p id="no-account"> Vous n'avez pas de compte ? 
                <a id="register-link" href="/App/Views/Auth/UserRegister.php">S'inscrire gratuitement</a>
            </p>
        </form>
    </section>

</body>
</html>
