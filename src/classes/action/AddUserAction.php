<?php

namespace iutnc\deefy\action;

require_once 'vendor/autoload.php';
use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\audio\tracks as tracks;
use iutnc\deefy\exception as exception;
use iutnc\deefy\renderer as renderer;


class AddUserAction extends Action {

    //CREER UN USER
    public function executePost(): string
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $nom = $_POST['name'];
        $email = $_POST['email'];
        $age = $_POST['age'];

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return "Email invalide";
        }

        if($age < 0 || $age > 150){
            return "Age invalide";
        }

        return "User $nom créé avec succès (email : $email, age : $age)";
    }

    //AFFICHER LE FORMULAIRE POUR CREER UN USER
    public function executeGet(): string
    {
        return <<<FORM
            <form action='index.php?action=add-user' method='POST'>
            <input type='text' name='name ' placeholder='username' required>
            <input type='text' name='email' placeholder='email' required>
            <input type='number' name='age' placeholder='age' required>
            <input type='submit' value='Créer un user'>
            </form>
        FORM;
    }
}