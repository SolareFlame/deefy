<?php

namespace iutnc\deefy\action;

require_once 'vendor/autoload.php';
use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\audio\tracks as tracks;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception as exception;
use iutnc\deefy\renderer as renderer;
use iutnc\deefy\repository\DeefyRepository;

class SavePlaylistAction extends Action {
    public function executePost(): string
    {
        if (isset($_POST['pl_id']) && !empty($_POST['pl_id']) && isset($_POST['track_id']) && !empty($_POST['track_id'])) {
            $pl_id = $_POST['pl_id'];
            $track_id = $_POST['track_id'];

            $instance = DeefyRepository::getInstance();
            $track = $instance->findTrackById($track_id);

            try {
                $playlist = $instance->findPlaylistById($pl_id);
            } catch (\Exception $e) {
                return "<p>Erreur lors de l'ajout de la track à la playlist.</p>";
            }

            if ($track && $playlist) {
                $res = $instance->addTrackToPlaylist($playlist->uuid, $track->uuid);

                if($res !== "OK") {
                    return $res;
                } else {
                    $playlist->addTrack($track);
                    $_SESSION['playlist'] = $playlist;

                    return "<p>Track ajoutée à la playlist.</p>";
                }

            } else {
                return "<p>Track ou playlist non trouvée.</p>";
            }
        } else {
            return "<p>Données manquantes.</p>";
        }
    }

    public function executeGet(): string
    {
        $html = '';

        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $trackId = $_GET['id'];

            $instance = DeefyRepository::getInstance();
            $track = $instance->findTrackById($trackId);

            if ($track) {
                $renderer = new renderer\AlbumTrackRenderer($track);
                $html .= $renderer->render(2);
            } else {
                return "<p>Track non trouvée.</p>";
            }
        } else {
            return "<p>Aucune track spécifiée.</p>";
        }

        try {
            $playlists = $instance->findUserPlaylists($_SESSION['user']);
        } catch (\Exception $e) {
            return "<p>Aucune playlist trouvée.</p>";
        }

        if (!empty($playlists)) {
            $playlistOptions = '';

            foreach ($playlists as $playlist) {
                $playlistOptions .= <<<HTML
                    <input type="radio" name="pl_id" value="$playlist->uuid" required>
                    <label>Ajouter <b> $track->title </b> à la playlist <b> $playlist->name </b></label>
                HTML;
            }

            $html .= <<<HTML
        <h2>Choisissez une playlist :</h2>
            <form action="?action=save" method="POST">
                $playlistOptions
                <input type="hidden" name="track_id" value="$trackId">
                <br>
                <br>
                <button type="submit">Ajouter à la playlist sélectionnée</button>
            </form>
        HTML;
        } else {
            $html .= "<p>Aucune playlist disponible.</p>";
        }

        return $html;
    }
}
