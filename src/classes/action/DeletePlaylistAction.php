<?php

namespace iutnc\deefy\action;

require_once 'vendor/autoload.php';
use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\audio\tracks as tracks;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception as exception;
use iutnc\deefy\renderer as renderer;
use iutnc\deefy\repository\DeefyRepository;


class DeletePlaylistAction extends Action {
    public function executePost(): string
    {
        if (isset($_POST['pl_id']) && !empty($_POST['pl_id'])) {
            $pl_id = $_POST['pl_id'];

            $instance = DeefyRepository::getInstance();

            try {
                $playlist = $instance->findPlaylistById($pl_id);
            } catch (\Exception $e) {
                return "<p>Erreur lors de la récupération de la playlist.</p>";
            }

            if ($playlist) {

                $instance->deletePlaylist($playlist->uuid);
                unset($_SESSION['playlist']);

                return "<p>Playlist supprimée.</p>";

            } else {
                return "<p>Playlist non trouvée.</p>";
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