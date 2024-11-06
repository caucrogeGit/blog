<?php

namespace App\Entity;

use DateTimeImmutable;
use App\enum\DecisionEnum;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Cette email est déjà utilisé.')]
#[ORM\HasLifecycleCallbacks]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // Constantes
    public const MESSAGE_CREATION = 'Création du compte';
    public const MESSAGE_UPDATE = 'Mise à jour du compte';
    public const MESSAGE_PASSWORD_RESET = 'Réinitialisation du mot de passe';

    // Properties
    #[ORM\Id]
    #[ORM\GeneratedValue('CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private ?Uuid $uuid = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank()]
    #[Assert\Email()]
    private string $email;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(
        min: 2,
        max: 25,
        minMessage: 'Le prénom doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le prénom ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+$/',
        message: 'Le prénom ne peut contenir que des lettres.'
    )]
    private ?string $firstName = null; 

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(
        min: 2,
        max: 25,
        minMessage: 'Le nom de famille doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le nom de famille ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+$/',
        message: 'Le nom de famille ne peut contenir que des lettres.'
    )]
    private ?string $lastName = null; 

    #[ORM\Column(type: 'json')]
    #[Assert\NotBlank()]
    #[Assert\All([
        new Assert\Choice(
            choices: ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_MANAGER', 'ROLE_EDITOR'],
            message: 'Le rôle {{ value }} n’est pas valide.'
        )
    ])]
    private array $roles = [];

    #[Assert\Length(
        min: 8,
        minMessage: 'Le mot de passe doit contenir au moins {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/',
        message: 'Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.'
    )]
    private ?string $plainPassword = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank()]
    private string $password;

    #[ORM\Column(type: 'string', length: 512, nullable: true)]
    #[Assert\NotBlank()]
    #[Assert\Url(message: 'L’URL de l’avatar n’est pas valide.')]
    #[Assert\NotBlank(message: 'L’URL de l’avatar ne peut pas être vide.')]
    private ?string $avatar = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull()]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull()]
    private DateTimeImmutable $updatedAt;

    #[ORM\Column(type: 'json')]
    #[Assert\NotNull()]
    #[Assert\All([
        new Assert\Collection([
            'date' => new Assert\DateTime(['message' => 'La date du log n’est pas valide.']),
            'message' => new Assert\NotBlank(['message' => 'Le message du log ne peut pas être vide.']),
        ])
    ])]
    private array $logs = [];

    #[ORM\Column(type: 'string', enumType: DecisionEnum::class, options: ['default' => DecisionEnum::APPROUVE])]
    #[Assert\NotNull()]
    #[Assert\Type(type: DecisionEnum::class, message: 'L’état doit être une instance de DecisionEnum.')]
    private DecisionEnum $state;

    #[Assert\NotNull()]
    private ?string $message = null;

    // Relationships
    #[ORM\OneToMany(targetEntity: Reaction::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Collection $reactions;

    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'user',cascade: ['persist', 'remove'])]
    private ?Collection $posts;

    // Constructeur
    public function __construct(){
        $this->roles = ['ROLE_USER'];
        $this->updatedAt = new DateTimeImmutable();
        $this->createdAt = new DateTimeImmutable();
        $this->message = $this->setCreationMessage();
        $this->reactions = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->state = DecisionEnum::APPROUVE;
    }

    // Méthodes evenementielles
    #[ORM\PrePersist]
    public function prePersist()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->logs = [['date' => $this->createdAt->format('Y-m-d H:i:s'), 'message' => $this->message]];
        $this->avatar = 'https://api.dicebear.com/9.x/' .$this->getAvatarStyle() .'/svg?seed='.$this->firstName.' '.$this->lastName;
    }

    #[ORM\PreUpdate]
    public function preUpdate()
    {
        $this->updatedAt = new DateTimeImmutable();
        if($this->message)
            $this->logs[] = ['date' => $this->updatedAt->format('Y-m-d H:i:s'), 'message' => $this->message];
    }

    /** Méthodes relationnelles **/

    // (User->Reactions) OneToMany
    public function getReactions(): ?Collection
    {
        return $this->reactions;
    }

    public function addReactions(Reaction $reaction): static
    {
        if (!$this->reactions->contains($reaction)) {
            $this->reactions[] = $reaction;
            $reaction->setUser($this);
        }

        return $this;
    }

    public function removeReaction(Reaction $reaction): static
    {
        if ($this->reactions->removeElement($reaction)) {
            if ($reaction->getUser() === $this) {
                $reaction->setUser(null);
            }
        }

        return $this;
    }

    // (User->Posts) OneToMany
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    // Getters and Setters
    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function generateUuid()
    {
        if ($this->uuid === null) {
            $this->uuid = Uuid::v4();
        }
    }
    
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }
 
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static    
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }
 
    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getState(): DecisionEnum
    {
        return $this->state;
    }

    public function setAvis(DecisionEnum $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function setRoles(array $roles): static    
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(string $role): static
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword): static
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword($password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function getAvatarStyle(): ?string
    {
        return "initials";
    }

    public function setAvatar($avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getLogs(): array
    {
        return $this->logs;
    }

    public function setLogs($logs): static
    {
        $this->logs = $logs;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
 
    public function setCreationMessage(): void
    {
        $this->message = self::MESSAGE_CREATION;
    }
    
    public function setUpdateMessage(): void
    {
        $this->message = self::MESSAGE_UPDATE;
    }
    
    public function setPasswordResetMessage(): void
    {
        $this->message = self::MESSAGE_PASSWORD_RESET;
    }

    // Implémentation
    public function getRoles(): array
    {
        // Garantir que chaque utilisateur a au moins le rôle ROLE_USER
        $roles = $this->roles;
        if (!in_array('ROLE_USER', $roles, true)) {
            $roles[] = 'ROLE_USER';
        }
        return array_unique($roles);
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    // Méthodes magiques
    public function __toString(): string
    {
        return $this->email;
    }
}
