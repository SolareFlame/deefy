<?php

namespace iutnc\deefy\action;

require_once 'vendor/autoload.php';
use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\audio\tracks as tracks;
use iutnc\deefy\exception as exception;
use iutnc\deefy\renderer as renderer;
use iutnc\deefy\repository\DeefyRepository;

class DisplayLibraryAction extends Action {
    public function executeGet(): string
    {

        $instance = DeefyRepository::getInstance();

        try {
            $library = $instance->findUserPlaylists($_SESSION['user']);
        } catch (\Exception $e) {
            return "Bibliothèque vide";
        }

        $res = "<h1>Ma bibliothèque</h1><ul>";

        foreach ($library as $playlist) {
            $res .= "<li><a href='index.php?action=playlist&id=" . $playlist->uuid . "'>" . $playlist->name . "</a></li>";
        }
        return $res . "</ul>";
    }

    public function executePost(): string
    {
        return $this->executeGet();
    }
}