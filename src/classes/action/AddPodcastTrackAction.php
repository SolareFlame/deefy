<?php

namespace iutnc\deefy\action;

require_once 'vendor/autoload.php';
use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\audio\tracks as tracks;
use iutnc\deefy\exception as exception;
use iutnc\deefy\renderer as renderer;
use getID3;


class AddPodcastTrackAction extends Action {

    //AJOUTER UN TRACK A LA PLAYLIST
    public function executePost(): string
    {
        if (isset($_SESSION['playlist'])) {
            if (substr($_FILES['userfile']['name'], -4) === '.mp3') {
                if ($_FILES['userfile']['type'] === 'audio/mpeg') {

                    // Vérifier l'existance du fichier
                    if ($_FILES['userfile']['error'] === UPLOAD_ERR_NO_FILE) {
                        return "Aucun fichier uploadé";
                    }

                    $dir = 'audio/';
                    $fileExtension = pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);
                    $uniqueId = uniqid();
                    $newFileName = $uniqueId . '.' . $fileExtension;
                    $filePath = $dir . $newFileName;

                    // Get les méta données du fichier
                    $getid3 = new getID3();
                    $fileinfo = $getid3->analyze($_FILES['userfile']['tmp_name']);

                    $originalTitle = substr($_FILES['userfile']['name'], 0, -4);
                    $duration = $fileinfo['playtime_seconds'] ?? 0;
                    $creator = $fileinfo['tags']['id3v2']['artist'][0] ?? 'Inconnu';
                    $date = $fileinfo['tags']['id3v2']['year'][0] ?? 'Inconnu';
                    $album = $fileinfo['tags']['id3v2']['album'][0] ?? 'Inconnu';
                    $genre = $fileinfo['tags']['id3v2']['genre'][0] ?? 'Inconnu';
                    $trackNumber = $fileinfo['tags']['id3v2']['track_number'][0] ?? 'Inconnu';
                    $bitrate = $fileinfo['bitrate'] ?? 'Inconnu';;

                    if (!move_uploaded_file($_FILES['userfile']['tmp_name'], $filePath)) {
                        return "Erreur lors de l'upload du fichier";
                    }

                    $playlist = $_SESSION['playlist'];
                    $track = new tracks\PodcastTrack(
                        $originalTitle,
                        $filePath,
                        $duration,
                        $creator,
                        $date
                    );

                    // Ajouter le track à la playlist
                    $playlist->addTrack($track);

                    return '<a href="?action=add-track">Ajouter encore une piste</a>' . var_dump($fileinfo);
                } else {
                    return "Le fichier n'est pas un fichier audio";
                }
            } else {
                return "Le fichier n'est pas un fichier mp3";
            }
        } else {
            return "Aucune playlist en session";
        }
    }

    //ENVVOIE UN FORMULAIRE POUR AJOUTER UN TRACK
    public function executeGet(): string
    {
        if (isset($_SESSION['playlist'])) {
            return <<<FORM
                <form action='index.php?action=add-track' method='POST' enctype='multipart/form-data'>
                <input type="file" name="userfile" accept="audio/*" required>
                <input type='submit' value='Ajouter un track'>
                </form>
            FORM;

        } else {
            return "Aucune playlist en session";
        }
    }
}