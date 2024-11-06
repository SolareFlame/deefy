<?php

namespace iutnc\deefy\dispatch;

require_once 'vendor/autoload.php';

use iutnc\deefy\action as action;
use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\audio\tracks as tracks;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception as exception;
use iutnc\deefy\renderer as renderer;
use iutnc\deefy\repository\DeefyRepository;


class Dispatcher {

    private ?string $action;

    function __construct(string $a) {
        $this->action = $a;
    }

    function run(): void {
        switch ($this->action) {
            //ACCUEIL
            case 'new-playlist': //CREER UNE PLAYLIST
                $this->renderPage((new action\AddPlaylistAction())());
                break;
            case 'library': //AFFICHER LA BIBLIOTHEQUE DE PLAYLIST
                $this->renderPage((new action\DisplayLibraryAction())());
                break;
            case 'playlist': //AFFICHER LA PLAYLIST EN SESSION
                $this->renderPage((new action\DisplayPlaylistAction())());
                break;

            //ADMIN
            case 'add-track':
                $this->renderPage((new action\addTrackAction())());
                break;
            case 'add-podcast':
                $this->renderPage((new action\AddPodcastAction())());
                break;

            //USER
            case 'signin': //SE CONNECTER
                $this->renderPage((new action\SignInAction())());
                break;
            case 'signout': //SE DECONNECTER
                $this->renderPage((new action\SignOutAction())());
                break;
            case 'new-user': //CREER UN COMPTE
                $this->renderPage((new action\AddUserAction())());
                break;

            case 'search': //RECHERCHER
                $this->renderPage((new action\SearchAction())());
                break;
            case 'save':
                $this->renderPage((new action\SavePlaylistAction())());
                break;
            case 'delete':
                $this->renderPage((new action\DeleteTrackAction())());
                break;
            case 'delete-playlist':
                $this->renderPage((new action\DeletePlaylistAction())());
                break;

            default:
                $this->renderPage((new action\DefaultAction())());
                break;
        }
    }

    function renderPage(string $html): void {
        $connected = isset($_SESSION['user']);
        $admin = isset($_SESSION['user']) && AuthnProvider::asPermission($_SESSION['user'], 100);

        $res = <<<HTML
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Deefy</title>
        <link rel="stylesheet" href="style.css">
        <link rel="icon" type="image/png" href="images/logo2.png">
    </head>
    <body>
        <header>
            <div class="title">
                <img src="images/logo2.png" alt="Logo de Deefy">
                <h1>Deefy</h1>   
            </div>
            <nav>
                <form action="index.php" method="GET">
                    <input type="hidden" name="action" value="search">
                    <input type="text" name="query" placeholder="Rechercher..." required>
                    <button type="submit">Rechercher</button>
                </form>
                    
                <ul>
                    <li><a href="?action=default">Accueil</a></li>
                    
                    
                    
HTML;

        if ($connected) {
            $res .= <<<HTML
            <li><a href="?action=new-playlist">Creer une playlist</a></li>
            <li><a href="?action=library">Bibliothèque</a></li>
            <li><a href="?action=playlist">Playlist en session</a></li>
            <li><a href="?action=signout">Se deconnecter</a></li>
HTML;
        } else {
            $res .= '<li><a href="?action=signin">Se connecter</a></li>';
            $res .= '<li><a href="?action=new-user">Creer un compte</a></li>';
        }

        if ($admin) {
            $res .= '<li><a href="?action=add-track">Ajouter un track</a></li>';
            $res .= '<li><a href="?action=add-podcast">Ajouter un podcast</a></li>';
        }

        $res .= <<<HTML
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
    </html>
HTML;
        echo $res;
    }

}