<?php

namespace iutnc\deefy\action;

require_once 'vendor/autoload.php';
use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\audio\tracks as tracks;
use iutnc\deefy\exception as exception;
use iutnc\deefy\renderer as renderer;


class AddPlaylistAction extends Action {

    //CREER UNE PLAYLIST
    public function executePost(): string
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $playlist = new lists\Playlist($_POST['name'], array());

        $_SESSION['playlist'] = $playlist;

        $renderer = new renderer\AudioListRenderer($playlist);
        return $renderer->render(1) . '<a href="?action=add-track">Ajouter une piste</a>';
    }

    //AFFICHER LE FORMULAIRE POUR CREER UNE PLAYLIST
    public function executeGet(): string
    {
        return <<<FORM
            <form action='index.php?action=add-playlist' method='POST'>
            <input type='text' name='name' placeholder='Nom de la playlist'>
            <input type='submit' value='Créer une playlist'>
            </form>
        FORM;
    }
}