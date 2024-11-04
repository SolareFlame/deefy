<?php

namespace iutnc\deefy\action;

require_once 'vendor/autoload.php';
use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\audio\tracks as tracks;
use iutnc\deefy\exception as exception;
use iutnc\deefy\renderer as renderer;
use iutnc\deefy\repository\DeefyRepository;

class DisplayPlaylistAction extends Action {
    public function executeGet(): string
    {
        $instance = DeefyRepository::getInstance();

        if(!isset($_SESSION['user'])){
            return "Vous devez être connecté pour accéder à cette page";
        }

        if (isset($_GET['id']) && $_GET['id'] !== '') {
            $id = $_GET['id'];

            //si il n'est pas propriétaire ou pas admin
            if(!$instance->isPlaylistOwner($_SESSION['user'], $id) && !$instance->asPermission($_SESSION['user'], 100)){
                return "Vous n'avez pas les droits pour accéder à cette page";
            }

            try {
                $playlist = $instance->findPlaylistById($id);
            } catch (\Exception $e) {
                return "Aucune playlist trouvée avec l'ID fourni";
            }

            if ($playlist === null) {
                return "Aucune playlist trouvée avec l'ID fourni";
            }

            $_SESSION['playlist'] = $playlist;
        } else {
            $playlist = $_SESSION['playlist'] ?? null;

            if ($playlist === null) {
                return "Aucune playlist en session";
            }
        }

        $renderer = new renderer\AudioListRenderer($playlist);

        return $renderer->render(2);
    }

    public function executePost(): string
    {
        return $this->executeGet();
    }

}