<?php

namespace App\Entity;

class Users
{
    private ?int $id = null;
    private string $nom;
    private string $prenom;
    private string $login;
    private string $password;
    private string $numeroCarteIdentite;
    private ?string $photoRecto = null;
    private ?string $photoVerso = null;
    private string $adresse;
    private TypeUser $typeUser;
    private array $comptes = [];

    public function __construct(
        string $nom,
        string $prenom,
        string $login,
        string $password,
        string $numeroCarteIdentite,
        string $adresse,
        TypeUser $typeUser,
        ?string $photoRecto = null,
        ?string $photoVerso = null
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->login = $login;
        $this->password = $password;
        $this->numeroCarteIdentite = $numeroCarteIdentite;
        $this->adresse = $adresse;
        $this->typeUser = $typeUser;
        $this->photoRecto = $photoRecto;
        $this->photoVerso = $photoVerso;
        $this->comptes = [];
    }



    public function getId(): ?int
    {
        return $this->id;
    }
    public function getNom(): string
    {
        return $this->nom;
    }
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }
    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function getLogin(): string
    {
        return $this->login;
    }
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getNumeroCarteIdentite(): string
    {
        return $this->numeroCarteIdentite;
    }
    public function setNumeroCarteIdentite(string $numeroCarteIdentite): void
    {
        $this->numeroCarteIdentite = $numeroCarteIdentite;
    }

    public function getPhotoRecto(): ?string
    {
        return $this->photoRecto;
    }
    public function setPhotoRecto(?string $photoRecto): void
    {
        $this->photoRecto = $photoRecto;
    }

    public function getPhotoVerso(): ?string
    {
        return $this->photoVerso;
    }
    public function setPhotoVerso(?string $photoVerso): void
    {
        $this->photoVerso = $photoVerso;
    }

    public function getAdresse(): string
    {
        return $this->adresse;
    }
    public function setAdresse(string $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function getTypeUser(): TypeUser
    {
        return $this->typeUser;
    }
    public function setTypeUser(TypeUser $typeUser): void
    {
        $this->typeUser = $typeUser;
    }
    public function getComptes(): array
    {
        return $this->comptes;
    }
    public function addCompte(Compte $compte): void
    {
        $this->comptes[] = $compte;
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'login' => $this->login,
            'password' => $this->password,
            'numeroCarteIdentite' => $this->numeroCarteIdentite,
            'photoRecto' => $this->photoRecto,
            'photoVerso' => $this->photoVerso,
            'adresse' => $this->adresse,
            'typeUser' => $this->typeUser->value,
            'comptes' => array_map(fn($compte) => $compte->toArray(), $this->comptes),
        ];
    }

    public static function toObject(array $data): self
    {
        $user = new self(
            $data['nom'] ?? '',
            $data['prenom'] ?? '',
            $data['login'] ?? '',
            $data['password'] ?? '',
            $data['numeroCarteIdentite'] ?? '',
            $data['adresse'] ?? '',
            TypeUser::from($data['typeUser'] ?? 'client'),
            $data['photoRecto'] ?? null,
            $data['photoVerso'] ?? null
        );

        if (isset($data['id'])) {
            $reflection = new \ReflectionClass(self::class);
            $property = $reflection->getProperty('id');
            $property->setAccessible(true);
            $property->setValue($user, (int)$data['id']);
        }

        return $user;
    }
}
