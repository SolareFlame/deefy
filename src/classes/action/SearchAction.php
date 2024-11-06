<?php

namespace iutnc\deefy\action;

require_once 'vendor/autoload.php';
use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\audio\tracks as tracks;
use iutnc\deefy\exception as exception;
use iutnc\deefy\renderer as renderer;
use iutnc\deefy\repository\DeefyRepository;

class SearchAction extends Action {
    public function executeGet(): string
    {
        if (isset($_GET['query']) && $_GET['query'] !== '') {

            $connected = isset($_SESSION['user']);

            $instance = DeefyRepository::getInstance();
            $tracks = $instance->searchTracks($_GET['query']);
            $podcasts = $instance->searchPodcast($_GET['query']);

            if ($tracks === [] && $podcasts === []) {
                return "Aucun track ou podcaast trouvé avec la recherche fournie";
            }

            $result = '<div class="search">';
            $result .= 'Résultats de la recherche pour : ' . htmlspecialchars($_GET['query']) . '<br>';
            $result .= 'Pistes trouvées : ' . (count($tracks) + count($podcasts)) . '<br>';

            foreach ($tracks as $track) {
                if ($track instanceof tracks\AlbumTrack) {
                    $result .= '<div class="track">';

                    $renderer = new renderer\AlbumTrackRenderer($track);
                    $result .= $renderer->render(2);

                    if ($connected) {
                        $result .= '<a href="?action=save&id=' . $track->uuid . '">Ajouter à une playlist</a>';
                    }

                    $result .= '</div>';
                }
            }

            foreach ($podcasts as $podcast) {
                if($podcast instanceof tracks\PodcastTrack) {
                    $result .= '<div class="track">';

                    $renderer = new renderer\PodcastRenderer($podcast);
                    $result .= $renderer->render(2);

                    $result .= '</div>';
                }
            }

            $result .= '</div>';

            return $result;
        }
        return "Aucune recherche fournie";
    }

    public function executePost(): string
    {
        return $this->executeGet();
    }

}