<?php

if (isset($_SERVER["REQUEST_METHOD"]) && isset($_GET["delete"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tableToUpdate = $_GET["delete"]; // UTILISATEUR ou ALBUMS
        if ($tableToUpdate === "UTILISATEUR") {
            $userId = $_POST["user_id"];
            echo $userId;
        }
        if ($tableToUpdate === "ALBUMS") {
            $albumId = $_POST["album_id"];
            echo $albumId;
        }
    }
}

?>