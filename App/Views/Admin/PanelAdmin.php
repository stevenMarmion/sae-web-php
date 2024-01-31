<?php

namespace App\Views\Admin;

require_once __DIR__ . '/../../Autoloader/autoloader.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Public/Css/Admin/panel-admin-style.css">
    <title>Panel - Admin</title>
</head>
<body>
    <h1>Bienvenue dans le panel admin</h1>
    <a id="panel-admin-link" href="Details/PanelDetails.php?table=UTILISATEUR">Gérer les utilisateurs</a>
    <a id="panel-admin-link" href="Details/PanelDetails.php?table=ALBUMS">Gérer les albums</a>
</body>
</html>