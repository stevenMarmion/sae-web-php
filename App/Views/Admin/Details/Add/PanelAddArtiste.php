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
    <title>Add - Admin - Artiste</title>
</head>
<body>
    <?php 
        include __DIR__ . '/../../../Layout/Admin/AdminNavBar.php';
    ?>
    <section>
        <h1>Ajout d'un nouvel artiste</h1>

        <form action="/App/Controllers/Admin/AdminAddController.php?add=ARTISTES" method="post">
            <input type="hidden" name="artiste_id" value=0>

            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" required>
            </div>

            <input type="submit" value="Ajout">
        </form>
        <?php if ($errorDetected) : ?>
            <p class="erreur-add">
                Le nom de cet artiste existe déjà, veuillez en choisir un autre...
            </p>
        <?php endif; ?>
    </section>
</body>
</html>