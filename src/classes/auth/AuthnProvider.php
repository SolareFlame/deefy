<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\exception\AuthnException;
use iutnc\deefy\repository\DeefyRepository;

class AuthnProvider {

    public static function signin(string $email,
                                  string $passwd2check): void {

        $instance = DeefyRepository::getInstance();

        try {
            $instance->getUserInfoByEmail($email);
        } catch (\Exception $e) {
            throw new AuthnException("Auth error : invalid credentials");
        }

        $hash = $instance->getUserInfoByEmail($email)['passwd'];

        if (!password_verify($passwd2check, $hash))
            throw new AuthnException("Auth error : invalid credentials");
        return;
    }
    public static function register(string $email,
                                     string $pass): string {

        $instance = DeefyRepository::getInstance();

        /* ACCOUNT VALIDATION */
        if($instance->getUserInfoByEmail($email) != null)
            throw new AuthnException(" error : user already exists");

        /* MAIL VALIDATION */
        if (! filter_var($email, FILTER_VALIDATE_EMAIL))
            throw new AuthnException(" error : invalid user email");

        /* PASSWORD VALIDATION */
        if (strlen($pass) < 10)
            throw new AuthnException(" error : password too short");

        if (! preg_match('/[A-Z]/', $pass))
            throw new AuthnException(" error : password must contain at least one uppercase letter");

        if (! preg_match('/[a-z]/', $pass))
            throw new AuthnException(" error : password must contain at least one lowercase letter");

        if (! preg_match('/[0-9]/', $pass))
            throw new AuthnException(" error : password must contain at least one number");

        if (! preg_match('/[^a-zA-Z0-9]/', $pass))
            throw new AuthnException(" error : password must contain at least one special character");

        $hash = password_hash($pass, PASSWORD_DEFAULT, ['cost'=>12]);
        $instance->addUser($email, $hash, 1);

        return "User registered";
    }

    public static function signout(): void {
        session_destroy();
    }
}