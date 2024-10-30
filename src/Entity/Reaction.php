<?php

namespace App\Entity;

use App\enum\DecisionEnum;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReactionRepository;
use DateTimeImmutable;

#[ORM\Entity(repositoryClass: ReactionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Reaction
{
    // Properties
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: 'string', enumType: DecisionEnum::class, options: ['default' => DecisionEnum::AUCUNE])]
    private DecisionEnum $avis;
    
    #[ORM\Column(type: 'string', enumType: DecisionEnum::class, options: ['default' => DecisionEnum::APPROUVE])]
    private DecisionEnum $moderation;

    #[ORM\Column(type: 'string', length: 45, nullable: true)]
    private ?string $ipAddress;

    // Relationships
    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'reactions')]
    private Post $post;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reactions')]
    #[ORM\JoinColumn(name: 'user_uuid', referencedColumnName: 'uuid', nullable: false)]
    private User $user;
    
    // Lifecycle Callbacks
    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): DecisionEnum
    {
        return $this->avis;
    }

    public function setType(DecisionEnum $avis): static
    {
        $this->avis = $avis;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getAvis(): DecisionEnum
    {
        return $this->avis;
    }

    public function setAvis(DecisionEnum $avis): static
    {
        $this->avis = $avis;

        return $this;
    }

    public function getModeration(): DecisionEnum
    {
        return $this->moderation;
    }

    public function setModeration(DecisionEnum $moderation): static
    {
        $this->moderation = $moderation;

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(?string $ipAddress): static
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function setPost(Post $post): static
    {
        $this->post = $post;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
