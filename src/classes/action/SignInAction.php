<?php

namespace iutnc\deefy\action;

require_once 'vendor/autoload.php';
use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\audio\tracks as tracks;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception as exception;
use iutnc\deefy\renderer as renderer;
use iutnc\deefy\repository\DeefyRepository;


class SignInAction extends Action {

    public function executePost(): string
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $email = $_POST['email'];
        $mdp = $_POST['mdp'];

        return AuthnProvider::signin($email, $mdp);
    }

    public function executeGet(): string
    {
        if(isset($_SESSION['user'])) {
            return "Vous êtes déjà connecté";
        }

        return <<<FORM
            <form action='index.php?action=signin' method='POST'>
            <input type='text' name='email' placeholder='Votre adresse mail' required>
            <input type='text' name='mdp' placeholder='Votre mot de passe' required>
            <input type='submit' value='Se connecter'>
            </form>
        FORM;
    }
}