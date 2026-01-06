<?php
namespace App\Service;

use Exception;
use App\Repository\UserRepository;

class AuthService
{   private UserRepository $userRepository;

    public function __construct()
    {
        $this -> userRepository = new UserRepository;
    }
    ///Register
    public function register($name, $email, $password)
    {   ///not empty input valide
        if(empty($name) || empty($email)|| empty($password)){
            throw new Exception ("Tous les champs sont obligatoires");
        }
        ///email correct form
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new Exception ("Email invalide");
        }
        ///password minium char
        if(strlen($password) < 8 ){
            throw new Exception("Le mot de passe doit contenir au moins 8 caractères");
        }
        /// miniscule wel majuscule obligatoire
        if(!preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password)){
            throw new Exception("Le mot de passe doit contenir une majuscule et une minuscule");
        }
        ///check wach email deja f database
        if($this -> userRepository -> emailExists($email)){
            throw new Exception ("Cet email est déjà utilisé");
        }
        /// hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        /// create new utilisateur f repository
        $this -> userRepository -> create($name, $email, $hashedPassword);

    }
}
