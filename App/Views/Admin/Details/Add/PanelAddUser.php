<?php

$errorDetected = null;

if (isset($_GET['error'])) {
    if ($_GET["error"] == "AlreadyExists") {
        $errorDetected = true;
    }
    else {
        $errorDetected = false;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Public/Css/Admin/Details/panel-admin-update-user-style.css">
    <title>Add - Admin - User</title>
</head>
<body>
    <?php 
        include __DIR__ . '/../../../Layout/Admin/AdminNavBar.php';
    ?>
    <section>
        <h1>Ajout d'un nouvel utilisateur</h1>

        <form action="/App/Controllers/Admin/AdminAddController.php?add=UTILISATEUR" method="post">
            <input type="hidden" name="user_id" value=0>

            <div class="form-group">
                <label for="pseudo">Pseudo :</label>
                <input type="text" id="pseudo" name="pseudo" required>
            </div>

            <div class="form-group">
                <label for="mdp">Mot de passe :</label>
                <input type="password" name="mdp" required>
            </div>

            <div class="form-group">
                <label for="adresseMail">Adresse Mail :</label>
                <input type="email" id="adresseMail" name="adresseMail" required>
            </div>

            <div class="form-group">
                <label for="isAdmin">Admin :</label>
                <div>
                    <input type="radio" id="isAdmin" name="isAdmin" value="true">
                    <label for="adminTrue">True</label>

                    <input type="radio" id="isAdmin" name="isAdmin" value="false">
                    <label for="adminFalse">False</label>
                </div>
            </div>

            <input type="submit" value="Ajouter">
        </form>
        <?php if ($errorDetected) : ?>
            <p class="erreur-add">
                Le pseudo de cet utilisateur existe déjà, veuillez en choisir un autre...
            </p>
        <?php endif; ?>
    </section>
</body>
</html>