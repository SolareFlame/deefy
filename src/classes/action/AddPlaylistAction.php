<?php

namespace iutnc\deefy\action;

require_once 'vendor/autoload.php';
use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\audio\tracks as tracks;
use iutnc\deefy\exception as exception;
use iutnc\deefy\renderer as renderer;
use iutnc\deefy\repository\DeefyRepository;


class AddPlaylistAction extends Action {

    //CREER UNE PLAYLIST
    public function executePost(): string
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        if(!isset($_POST['name']) || $_POST['name'] === '') {
            return "Vous devez donner un nom à votre playlist";
        }

        $playlist = new lists\Playlist($_POST['name'], array());

        $instance = DeefyRepository::getInstance();
        $playlist = $instance->saveEmptyPlaylist($playlist);

        if(!isset($_SESSION['user'])) {
            return "Vous devez être connecté pour créer une playlist";
        }

        $instance->setPlaylistOwner($playlist->uuid, $_SESSION['user']);

        $_SESSION['playlist'] = $playlist;

        $renderer = new renderer\AudioListRenderer($playlist);
        return $renderer->render(2);
    }

    //AFFICHER LE FORMULAIRE POUR CREER UNE PLAYLIST
    public function executeGet(): string
    {
        return <<<FORM
            <form action='index.php?action=new-playlist' method='POST'>
            <input type='text' name='name' placeholder='Nom de la playlist' required>
            <input type='submit' value='Créer une playlist'>
            </form>
        FORM;
    }
}