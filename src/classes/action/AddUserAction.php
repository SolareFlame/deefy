<?php

namespace iutnc\deefy\action;

require_once 'vendor/autoload.php';
use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\audio\tracks as tracks;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception as exception;
use iutnc\deefy\renderer as renderer;
use iutnc\deefy\repository\DeefyRepository;


class AddUserAction extends Action {

    //CREER UN USER
    public function executePost(): string
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $email = $_POST['username'];
        $mdp = $_POST['password'];

        return AuthnProvider::register($email, $mdp);
    }

    public function executeGet(): string
    {
        return <<<FORM
            <form action='index.php?action=new-user' method='POST'>
            <input type='text' name='username' placeholder='Votre adresse mail' required>
            <input type='password' name='password' placeholder='Votre mot de passe' required>
            <input type='submit' value='Enregistrement'>
            </form>
        FORM;
    }
}