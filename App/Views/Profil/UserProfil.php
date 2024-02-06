<?php

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: /login.php"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

$user = $_SESSION['user'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Public/Css/Profile/profile-style.css">
    <title>Profil Utilisateur</title>
</head>
<body>
    <?php 
        include __DIR__ . '/../Layout/NavMenu.php'; // Inclure le menu de navigation
    ?>

    <h2>Profil Utilisateur</h2>

    <div class="profile-info">
        <p><strong>Pseudo :</strong> <?php echo $user['pseudo']; ?></p>
        <p><strong>Email :</strong> <?php echo $user['adresseMail']; ?></p>
        <p><strong>Mot de passe :</strong> ********</p>

    </div>

    <a href="/logout.php">Se déconnecter</a>

</body>
</html>
