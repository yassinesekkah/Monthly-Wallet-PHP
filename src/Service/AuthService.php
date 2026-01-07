<?php
namespace App\Service;

use Exception;
use App\Repository\UserRepository;
use App\Service\WalletService;

class AuthService
{   private UserRepository $userRepository;
    private WalletService $walletService;

    public function __construct()
    {
        $this -> userRepository = new UserRepository;
        $this -> walletService = new WalletService;
    }
    ///Register
    public function register(string $name, string $email, string $password): void
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
        $userId = $this -> userRepository -> create($name, $email, $hashedPassword);
        //// create wallet pour ce utilisateur
        $this -> walletService -> createCurrentWalletForUser ($userId);
    }
    
    ///login
    public function login(string $email, string $password): array
    {   ///empty input no valide
        if(empty($email) || empty($password)){
            throw new Exception ("ous les champs sont obligatoires");
        }
        ///search user par email
        $user = $this -> userRepository -> findByEmail($email);

        if(!$user){
            throw new Exception ("Email ou mot de passe incorrect");
        }

        if(!password_verify($password, $user['password'])){
            throw new Exception ("Email ou mot de passe incorrect");
        }

        return $user;
    }
}
