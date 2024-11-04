<?php

namespace iutnc\deefy\action;

require_once 'vendor/autoload.php';
use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\audio\tracks as tracks;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception as exception;
use iutnc\deefy\renderer as renderer;
use iutnc\deefy\repository\DeefyRepository;


class SignOutAction extends Action {

    public function executePost(): string
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);

            $message = "Vous avez été déconnecté avec succès.";
        } else {
            $message = "Aucune session utilisateur active.";
        }

        return "<p>$message</p>";
    }

    public function executeGet(): string
    {
        return $this->executePost();
    }
}