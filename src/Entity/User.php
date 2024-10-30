<?php

namespace App\Entity;

use DateTimeImmutable;
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
    private ?string $firstName = null; 

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $lastName = null; 

    #[ORM\Column(type: 'json')]
    #[Assert\NotBlank()]
    private array $roles = [];

    private ?string $plainPassword = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank()]
    private string $password;

    #[ORM\Column(type: 'string', length: 512, nullable: true)]
    #[Assert\NotBlank()]
    private ?string $avatar = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull()]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull()]
    private DateTimeImmutable $updatedAt;

    #[ORM\Column(type: 'json')]
    #[Assert\NotNull()]
    private array $logs = [];

    #[Assert\NotNull()]
    private ?string $message = null;

    // Relationships
    #[ORM\OneToMany(targetEntity: Reaction::class, mappedBy: 'user')]
    private Collection $reactions;

    // Constructeur
    public function __construct(){
        $this->roles = ['ROLE_USER'];
        $this->updatedAt = new DateTimeImmutable();
        $this->createdAt = new DateTimeImmutable();
        $this->message = 'Création du compte';
        $this->reactions = new ArrayCollection();
    }

    // Méthodes evenementielles
    #[ORM\PrePersist]
    public function prePersist()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->logs = [['date' => $this->createdAt->format('Y-m-d H:i:s'), 'message' => 'Création du compte']];
        $this->avatar = 'https://api.dicebear.com/9.x/' .$this->getAvatarStyle() .'/svg?seed='.$this->firstName.' '.$this->lastName;
    }

    #[ORM\PreUpdate]
    public function preUpdate()
    {
        $this->updatedAt = new DateTimeImmutable();
        if($this->message)
            $this->logs[] = ['date' => $this->updatedAt->format('Y-m-d H:i:s'), 'message' => $this->message];
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

    // Implémentation de UserInterface
    public function getRoles(): array
    {
        // Garantir que chaque utilisateur a au moins le rôle ROLE_USER
        $roles = $this->roles;
        if (!in_array('ROLE_USER', $roles, true)) {
            $roles[] = 'ROLE_USER';
        }
        return array_unique($roles);
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
 
    public function setMessage($message): static
    {
        $this->message = $message;

        return $this;
    }

    // Implémentation de UserInterface
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

    /**
     * Get the value of reactions
     *
     * @return Collection
     */
    public function getReactions(): Collection
    {
        return $this->reactions;
    }

    /**
     * Set the value of reactions
     *
     * @param Collection $reactions
     *
     * @return self
     */
    public function setReactions(Collection $reactions): self
    {
        $this->reactions = $reactions;

        return $this;
    }
}
