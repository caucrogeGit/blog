<?php

namespace App\Entity;

use App\Entity\Post;
use App\Trait\DateTrait;
use App\Trait\CommonMethodsEntityTrait;
use App\enum\RoleEnum;
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
#[UniqueEntity(fields: ['email'], message: 'user.email.unique.error')]
#[ORM\HasLifecycleCallbacks]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // Traits
    use DateTrait;
    use CommonMethodsEntityTrait;

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
        message: 'user.first_name.length.error' . '{{ min }} et {{ max }}',
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+$/',
        message: 'user.first_name.content.error'
    )]
    private ?string $firstName = null; 

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(
        min: 2,
        max: 25,
        message: 'user.last_name.length.error' . '{{ min }} et {{ max }}',
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+$/',
        message: 'user.first_name.content.error'
    )]
    private ?string $lastName = null; 

    #[ORM\Column(type: 'json')]
    #[Assert\NotBlank()]
    #[Assert\All([
        new Assert\Choice(
            choices: RoleEnum::CHOICES,
            message: 'role.error : ' .'{{ value }}'
        )
    ])]
    private array $roles = [];

    #[Assert\Length(
        min: 8,
        max: 255,
        minMessage: 'user.password.length.error' .'{{ min }} et {{ max }}',
    )]
    #[Assert\Regex(
        pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/',
        message: 'user.password.content.error'
    )]
    private ?string $plainPassword = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank()]
    private string $password;

    #[ORM\Column(type: 'string', length: 512, nullable: true)]
    #[Assert\NotBlank()]
    #[Assert\Url(message: 'user.avatar.url.error')]
    #[Assert\NotBlank(message: 'user.avatar.empty.error')]
    private ?string $avatar = null;

    #[ORM\Column(type: 'string', enumType: DecisionEnum::class, options: ['default' => DecisionEnum::DEFAULT])]
    #[Assert\NotNull()]
    #[Assert\Type(type: DecisionEnum::class, message: 'user.etat.error')]
    private DecisionEnum $etat;

    // Relationships
    #[ORM\OneToMany(targetEntity: Reaction::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Collection $reactions;

    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'user',cascade: ['persist', 'remove'])]
    private ?Collection $posts;

    // Constructeur
    public function __construct(){
        $this->roles = RoleEnum::getDefaultRole();
        $this->etat = DecisionEnum::getDefaultDecision();
        $this->reactions = new ArrayCollection();
        $this->posts = new ArrayCollection();

    }

    // Méthodes evenementielles
    #[ORM\PrePersist]
    public function prePersist()
    {
        $this->avatar = 'https://api.dicebear.com/9.x/' .$this->getAvatarStyle() .'/svg?seed='.$this->firstName.' '.$this->lastName;
    }

    /** Méthodes relationnelles **/

    // (User->Reactions) OneToMany
    public function getReactions(): ?Collection
    {
        return $this->reactions;
    }

    public function addReactions(Reaction $reaction): static
    {
        try {
            // Vérifie si la réaction est déjà associée à l'utilisateur
            if (!$this->reactions->contains($reaction)) {
                $this->reactions[] = $reaction;
    
                // Associe l'utilisateur à la réaction dans la relation bidirectionnelle
                if ($reaction->getUser() !== $this) {
                    $reaction->setUser($this);
                }
            } else {
                throw new \LogicException('user.reaction.already_associated' . $this->email);
            }
        } catch (\Exception $e) {
            // Gestion de l'erreur
            throw new \RuntimeException('user.reaction.add.error' . $e->getMessage(), 0, $e);
        }
    
        return $this;
    }

    public function removeReaction(Reaction $reaction): static
    {
        try {
            // Vérifie si la réaction est bien associée à l'utilisateur
            if ($this->reactions->removeElement($reaction)) {
                // Vérifie si l'utilisateur est toujours lié à la réaction et annule cette association
                if ($reaction->getUser() === $this) {
                    $reaction->setUser(null);
                }
            } else {
                throw new \LogicException('user.reaction.not_associated' .$this->email);
            }
        } catch (\Exception $e) {
            // Gestion de l'erreur
            throw new \RuntimeException('user.reaction.remove.error' . $e->getMessage(), 0, $e);
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
        try {
            // Vérifie si le post est déjà associé à l'utilisateur
            if (!$this->posts->contains($post)) {
                $this->posts[] = $post;
    
                // Associe l'utilisateur au post dans la relation bidirectionnelle
                if ($post->getUser() !== $this) {
                    $post->setUser($this);
                }
            } else {
                throw new \LogicException('user.post.already_associated' .$this->email);
            }
        } catch (\Exception $e) {
            // Gestion de l'erreur
            throw new \RuntimeException('user.post.add.error' . $e->getMessage(), 0, $e);
        }
    
        return $this;
    }

    public function removePost(Post $post): static
    {
        try {
            // Vérifie si le post est bien associé à l'utilisateur
            if ($this->posts->removeElement($post)) {
                // Vérifie si l'utilisateur est toujours lié au post et annule cette association
                if ($post->getUser() === $this) {
                    $post->setUser(null);
                }
            } else {
                throw new \LogicException('user.post.not_associated' .$this->email);
            }
        } catch (\Exception $e) {
            // Gestion de l'erreur
            throw new \RuntimeException('user.post.remove.error' . $e->getMessage(), 0, $e);
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

    public function getEtat(): DecisionEnum
    {
        return $this->etat;
    }

    public function setEtat(DecisionEnum $etat): static
    {
        $this->etat = $etat;

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
}
