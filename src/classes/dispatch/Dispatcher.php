<?php

namespace iutnc\deefy\dispatch;

require_once 'vendor/autoload.php';

use iutnc\deefy\action as action;
use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\audio\tracks as tracks;
use iutnc\deefy\exception as exception;
use iutnc\deefy\renderer as renderer;


class Dispatcher {

    private ?string $action;

    function __construct(string $a) {
        $this->action = $a;
    }

    function run(): void {
        switch ($this->action) {
            case 'add-playlist':
                $this->renderPage((new action\AddPlaylistAction())());
                break;
            case 'add-track':
                $this->renderPage((new action\AddPodcastTrackAction())());
                break;
            case 'playlist':
                $this->renderPage((new action\DisplayPlaylistAction())());
                break;
            case 'add-user':
                $this->renderPage((new action\AddUserAction())());
                break;
            default:
                $this->renderPage((new action\DefaultAction())());
                break;
        }
    }

    function renderPage(string $html): void {
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Deefy</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <header>
                <h1>Deefy</h1>
                <nav>
                    <ul>
                        <li><a href="?action=default">Accueil</a></li>
                        <li><a href="?action=add-playlist">Ajouter une playlist</a></li>
                        <li><a href="?action=add-track">Ajouter un podcast</a></li>
                        <li><a href="?action=playlist">Afficher la playlist</a></li>
                        <li><a href="?action=add-user">Ajouter un utilisateur</a></li>
                    </ul>
                </nav>
            </header>
            <main>
                $html
            </main>
            <footer>
                <p>&copy; 2024 - Deefy</p>
            </footer>
        </body>
        HTML;
    }
}