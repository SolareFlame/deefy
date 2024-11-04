<?php

namespace iutnc\deefy\action;

require_once 'vendor/autoload.php';
use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\audio\tracks as tracks;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception as exception;
use iutnc\deefy\renderer as renderer;
use iutnc\deefy\repository\DeefyRepository;


class DeleteTrackAction extends Action {
    public function executePost(): string
    {
        // track et pl
        if (isset($_POST['track_id']) && isset($_POST['pl_id'])) {
            $track_id = $_POST['track_id'];
            $pl_id = $_POST['pl_id'];

            $instance = DeefyRepository::getInstance();
            $track = $instance->findTrackById($track_id);

            if ($track) {
                $instance->deleteTrackFromPlaylist($pl_id, $track_id);

                try {
                    $_SESSION['playlist'] = $instance->findPlaylistById($pl_id);
                } catch (\Exception $e) {
                    return "<p>Erreur lors de la suppression de la track.</p>";
                }

                return "<p>Track supprimée.</p>";

            } else {
                return "<p>Track non trouvée.</p>";
            }
        } else {
            return "<p>Données manquantes.</p>";
        }
    }

    public function executeGet(): string
    {
        return $this->executePost();
    }

}