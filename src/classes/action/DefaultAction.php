<?php

namespace iutnc\deefy\action;

require_once 'vendor/autoload.php';
use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\audio\tracks as tracks;
use iutnc\deefy\exception as exception;
use iutnc\deefy\renderer as renderer;



class DefaultAction extends Action {

    // PAGE D'ACCUEIL
    public function executePost(): string
    {
        return "Bienvenue !";
    }

    public function executeGet(): string
    {
        return "Bienvenue !";
    }
}