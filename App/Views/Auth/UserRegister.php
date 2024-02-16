<?php

session_start();

$errorDetected = null;

if (isset($_GET['error'])) {
    $errorDetected = true;
    $error = $_GET['error'];
    switch ($error) {
        case 'AlreadyExists':
            $alreadyExists = true;
            $badPwd = false;
            break;
        
        case 'BadPassword':
            $badPwd = true;
            $alreadyExists = false;
            break;
        default:
            $badPwd = false;
            $alreadyExists = false;
            break;
    }
}
else {
    $errorDetected = false;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Public/Css/Auth/register-style.css">
    <title>Formulaire d'Inscription</title>
</head>
<body>
    <?php 
        include __DIR__ . '/../Layout/Auth/NavBar.php';
    ?>

    <h2>Inscription</h2>

    <form action="/App/Controllers/Auth/AuthRegisterController.php" method="post">
        <label for="pseudo">Pseudo :</label>
        <input type="text" id="pseudo" name="pseudo" required>
        <br>

        <label for="email">Adresse E-mail :</label>
        <input type="email" id="email" name="email" required>
        <br>

        <label for="mdp">Mot de passe :</label>
        <input type="password" id="mdp" name="mdp" required>
        <br>

        <label for="confirmer_mdp">Confirmer le mot de passe :</label>
        <input type="password" id="confirmer_mdp" name="confirmer_mdp" required>
        <br>

        <input type="submit" value="S'inscrire">

        <p id="account"> Déjà inscrit ? 
            <a id="login-link" href="/App/Views/Auth/UserLogin.php">Se connecter</a>
        </p>
        <?php if ($errorDetected) : ?>
            <p class="erreur-register">
                <?php if ($alreadyExists) : ?>
                    Le pseudo de cet utilisateur existe déjà, veuillez en choisir un autre...
                <?php endif; ?>
                <?php if ($badPwd) : ?>
                    Les deux mots de passe sont différents...
                <?php endif; ?>
            </p>
        <?php endif; ?>
    </form>

</body>
</html>
