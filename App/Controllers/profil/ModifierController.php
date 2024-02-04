<?php

require_once __DIR__ . '/../../Autoloader/autoloader.php';
require_once __DIR__ .'/../../../Database/DatabaseConnection/ConnexionBDD.php';
require_once __DIR__ .'/../../Models/EntityOperations/CrudUser.php';
require_once __DIR__ .'/../../Models/User.php';

use \App\Autoloader\Autoloader;
use \Database\DatabaseConnection\ConnexionBDD;
use \App\Models\EntityOperations\CrudUser;
use \App\Models\User;

Autoloader::register();

$instance = new ConnexionBDD();

