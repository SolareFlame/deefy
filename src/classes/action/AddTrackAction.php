<?php

namespace iutnc\deefy\action;

require_once 'vendor/autoload.php';
use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\audio\tracks as tracks;
use iutnc\deefy\exception as exception;
use iutnc\deefy\renderer as renderer;
use iutnc\deefy\repository\DeefyRepository;
use Ramsey\Uuid\Uuid;
use getID3;


class AddTrackAction extends Action {

    //AJOUTER UN TRACK A LA PLAYLIST
    public function executePost(): string
    {
            if (substr($_FILES['userfile']['name'], -4) === '.mp3') {
                if ($_FILES['userfile']['type'] === 'audio/mpeg') {

                    if ($_FILES['userfile']['error'] === UPLOAD_ERR_NO_FILE) {
                        return "Aucun fichier uploadé";
                    }

                    $dir = 'audio/';

                    $getid3 = new getID3();
                    $fileinfo = $getid3->analyze($_FILES['userfile']['tmp_name']);

                    //Titre en meta data ou nom du fichier si inexistant
                    $title = $fileinfo['tags']['id3v2']['title'][0] ?? substr($_FILES['userfile']['name'], 0, -4);
                    $genre = $fileinfo['tags']['id3v2']['genre'][0] ?? 'Inconnu';
                    $duration = $fileinfo['playtime_seconds'] ?? 0;
                    $artists = $fileinfo['tags']['id3v2']['artist'] ?? ['Inconnu'];
                    $date = $fileinfo['tags']['id3v2']['year'][0] ?? 'Inconnu';

                    $uuid = Uuid::uuid4();
                    $filename = $dir . $uuid . '.mp3';
                    $track = new tracks\AlbumTrack($uuid, $title, $genre, $duration, $filename, $artists, $date);

                    //ajouter la track à la DB
                    $instance = DeefyRepository::getInstance();
                    $instance->addTrack($track);

                    if (!move_uploaded_file($_FILES['userfile']['tmp_name'], $filename)) {
                        return "Erreur lors de l'upload du fichier";
                    }
                    return '<a href="?action=add-track">Ajouter encore une piste</a>';
                } else {
                    return "Le fichier n'est pas un fichier audio";
                }
            } else {
                return "Le fichier n'est pas un fichier mp3";
            }
    }

    //ENVVOIE UN FORMULAIRE POUR AJOUTER UN TRACK
    public function executeGet(): string
    {
            return <<<FORM
                <form action='index.php?action=add-track' method='POST' enctype='multipart/form-data'>
                <input type="file" name="userfile" accept="audio/*" required>
                <input type='submit' value='Ajouter un track'>
                </form>
            FORM;
    }
}