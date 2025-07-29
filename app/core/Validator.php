<?php
namespace App\Core;

use App\Core\Session;

class Validator
{
    private static array $errors = [];
    private static ?Validator $instance = null;

    public function __construct()
    {
    }

    public static function getInstance(): Validator
    {
        if (self::$instance === null) {
            self::$instance = new Validator();
        }
        self::$errors = [];
        return self::$instance;
    }

    public function validateLogin(array $data): bool
    {
        $login = trim($data['login'] ?? '');
        $password = trim($data['password'] ?? '');

        if (empty($login)) {
            self::$errors['login'] = 'Le numéro de téléphone est obligatoire';
        } elseif (!preg_match('/^(77|78|70|76|75)[0-9]{7}$/', $login)) {
            self::$errors['login'] = 'Format de numéro invalide';
        }

        if (empty($password)) {
            self::$errors['password'] = 'Le mot de passe est obligatoire';
        }

        if (!empty(self::$errors)) {
            Session::getInstance()->set('errors', self::$errors);
            return false;
        }

        return true;
    }

    public function addError(string $field, string $message): void
    {
        self::$errors[$field] = $message;
        Session::getInstance()->set('errors', self::$errors);
    }

    public function getErrors(): array
    {
        return self::$errors;
    }
    public function setSuccess(string $message)
    {
        Session::getInstance()->set('success', $message);
    }

    public function validateRegister(array $data, array $files): bool
    {
        // Validation nom et prénom
        if (empty($data['nom'])) {
            self::$errors['nom'] = "Le nom est obligatoire";
        }
        if (empty($data['prenom'])) {
            self::$errors['prenom'] = "Le prénom est obligatoire";
        }

        // Validation CNI
        if (empty($data['cni'])) {
            self::$errors['cni'] = "Le numéro CNI est obligatoire";
        } elseif (!preg_match('/^[0-9]{13}$/', $data['cni'])) {
            self::$errors['cni'] = "Le numéro CNI doit contenir exactement 13 chiffres";
        }

        // Validation téléphone
        if (empty($data['login'])) {
            self::$errors['login'] = "Le numéro de téléphone est obligatoire";
        } elseif (!preg_match('/^(77|78|70|76|75)[0-9]{7}$/', $data['login'])) {
            self::$errors['login'] = "Format de numéro invalide";
        }

        // Validation adresse
        if (empty($data['adresse'])) {
            self::$errors['adresse'] = "L'adresse est obligatoire";
        }

        // Validation mot de passe
        if (empty($data['password'])) {
            self::$errors['password'] = "Le mot de passe est obligatoire";
        } elseif (strlen($data['password']) < 6) {
            self::$errors['password'] = "Le mot de passe doit contenir au moins 6 caractères";
        } elseif ($data['password'] !== $data['password_confirm']) {
            self::$errors['password_confirm'] = "Les mots de passe ne correspondent pas";
        }

        // Validation des fichiers
        $this->validateFiles($files);

        if (!empty(self::$errors)) {
            Session::getInstance()->set('errors', self::$errors);
            return false;
        }

        return true;
    }

    private function validateFiles(array $files): void
    {
        // Validation photo de profil
        if (!empty($files['photo']['name'])) {
            $this->validateImage($files['photo'], 'photo', 5);
        }

        // Validation photos CNI
        if (!empty($files['photorecto']['name'])) {
            $this->validateImage($files['photorecto'], 'photorecto', 5);
        }
        if (!empty($files['photoverso']['name'])) {
            $this->validateImage($files['photoverso'], 'photoverso', 5);
        }
    }

    private function validateImage(array $file, string $field, int $maxSize): void
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        
        if (!in_array($file['type'], $allowedTypes)) {
            self::$errors[$field] = "Format d'image non supporté";
        }
        
        if ($file['size'] > $maxSize * 1024 * 1024) {
            self::$errors[$field] = "L'image ne doit pas dépasser {$maxSize}MB";
        }
    }
}