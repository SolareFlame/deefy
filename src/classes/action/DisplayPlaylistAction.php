<?php

namespace iutnc\deefy\action;

require_once 'vendor/autoload.php';
use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\audio\tracks as tracks;
use iutnc\deefy\exception as exception;
use iutnc\deefy\renderer as renderer;

class DisplayPlaylistAction extends Action {

    //AFFICHER LA PLAYLIST EN SESSION
    public function executePost(): string
    {
        if(isset($_SESSION['playlist'])){
            $playlist = $_SESSION['playlist'];
            $renderer = new renderer\AudioListRenderer($playlist);
            return $renderer->render(1);

        } else {
            return "Aucune playlist en session";
        }
    }

    //AFFICHER LA PLAYLIST EN SESSION
    public function executeGet(): string
    {
        if(isset($_SESSION['playlist'])){
            $playlist = $_SESSION['playlist'];
            $renderer = new renderer\AudioListRenderer($playlist);
            return $renderer->render(2);

        } else {
            return "Aucune playlist en session";
        }
    }
}