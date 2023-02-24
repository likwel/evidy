<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Ce mail est déjà utilisé par un autre utilisateur')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255)]
    private ?string $tablemessage = null;

    #[ORM\Column(length: 255)]
    private ?string $tablenotification = null;

    #[ORM\Column(length: 255)]
    private ?string $tablecarte = null;

    #[ORM\Column(length: 255)]
    private ?string $tableactivity= null;

    #[ORM\Column(length: 255)]
    private ?string $tablefriends= null;

    #[ORM\Column(length: 255)]
    private ?string $profil= null;

    #[ORM\Column(length: 255)]
    private ?string $couverture= null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(length: 50)]
    private ?string $type= null;

    #[ORM\Column(length: 50)]
    private ?string $telephone= null;

    #[ORM\Column(length: 255)]
    private ?string $tablecommentaire= null;

    #[ORM\Column(length: 255)]
    private ?string $tablepartage= null;

    #[ORM\Column(length: 255)]
    private ?string $tablereaction= null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }


    /**
     * Get the value of lastname
     */ 
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @return  self
     */ 
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get the value of firstname
     */ 
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @return  self
     */ 
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get the value of pseudo
     */ 
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set the value of pseudo
     *
     * @return  self
     */ 
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get the value of tablecarte
     */ 
    public function getTablecarte()
    {
        return $this->tablecarte;
    }

    /**
     * Set the value of tablecarte
     *
     * @return  self
     */ 
    public function setTablecarte($tablecarte)
    {
        $this->tablecarte = $tablecarte;

        return $this;
    }

    /**
     * Get the value of tablemessage
     */ 
    public function getTablemessage()
    {
        return $this->tablemessage;
    }

    /**
     * Set the value of tablemessage
     *
     * @return  self
     */ 
    public function setTablemessage($tablemessage)
    {
        $this->tablemessage = $tablemessage;

        return $this;
    }

    /**
     * Get the value of tablenotification
     */ 
    public function getTablenotification()
    {
        return $this->tablenotification;
    }

    /**
     * Set the value of tablenotification
     *
     * @return  self
     */ 
    public function setTablenotification($tablenotification)
    {
        $this->tablenotification = $tablenotification;

        return $this;
    }


    /**
     * Get the value of tableactivity
     */ 
    public function getTableactivity()
    {
        return $this->tableactivity;
    }

    /**
     * Set the value of tableactivity
     *
     * @return  self
     */ 
    public function setTableactivity($tableactivity)
    {
        $this->tableactivity = $tableactivity;

        return $this;
    }

    /**
     * Get the value of tableafriends
     */ 
    public function getTablefriends()
    {
        return $this->tablefriends;
    }

    /**
     * Set the value of tableafriends
     *
     * @return  self
     */ 
    public function setTablefriends($tablefriends)
    {
        $this->tablefriends = $tablefriends;

        return $this;
    }

    /**
     * Get the value of couverture
     */ 
    public function getCouverture()
    {
        return $this->couverture;
    }

    /**
     * Set the value of couverture
     *
     * @return  self
     */ 
    public function setCouverture($couverture)
    {
        $this->couverture = $couverture;

        return $this;
    }

    /**
     * Get the value of profil
     */ 
    public function getProfil()
    {
        return $this->profil;
    }

    /**
     * Set the value of profil
     *
     * @return  self
     */ 
    public function setProfil($profil)
    {
        $this->profil = $profil;

        return $this;
    }


    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of telephone
     */ 
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set the value of telephone
     *
     * @return  self
     */ 
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get the value of tablecommentaire
     */ 
    public function getTablecommentaire()
    {
        return $this->tablecommentaire;
    }

    /**
     * Set the value of tablecommentaire
     *
     * @return  self
     */ 
    public function setTablecommentaire($tablecommentaire)
    {
        $this->tablecommentaire = $tablecommentaire;

        return $this;
    }

    /**
     * Get the value of tablepartage
     */ 
    public function getTablepartage()
    {
        return $this->tablepartage;
    }

    /**
     * Set the value of tablepartage
     *
     * @return  self
     */ 
    public function setTablepartage($tablepartage)
    {
        $this->tablepartage = $tablepartage;

        return $this;
    }

    /**
     * Get the value of tablereaction
     */ 
    public function getTablereaction()
    {
        return $this->tablereaction;
    }

    /**
     * Set the value of tablereaction
     *
     * @return  self
     */ 
    public function setTablereaction($tablereaction)
    {
        $this->tablereaction = $tablereaction;

        return $this;
    }
}
