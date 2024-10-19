<?php

//require_once 'autoloader.php';
require_once 'vendor/autoload.php';



//$autoloader = new autoloader("iutnc\\deefy\\", "./src/classes/");
//$autoloader->register();


use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\audio\tracks as tracks;
use iutnc\deefy\exception as exception;
use iutnc\deefy\renderer as renderer;
use iutnc\deefy\action as action;
use iutnc\deefy\dispatch as dispatch;

session_start();

$dispatcher = new dispatch\Dispatcher($_GET['action'] ?? 'default');
$dispatcher->run();













