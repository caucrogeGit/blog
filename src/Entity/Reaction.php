<?php

namespace App\Entity;

use App\Trait\CommonMethodsEntityTrait;
use App\Trait\DateTrait;
use App\enum\DecisionEnum;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReactionRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReactionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Reaction
{
    use DateTrait;
    use CommonMethodsEntityTrait;

    // Properties
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', enumType: DecisionEnum::class)]
    #[Assert\Choice(callback: [DecisionEnum::class, 'getAllDecisions'], message: 'reaction.avis.error' .'{{ value }}')]
    #[Assert\All([
        new Assert\Choice(
            choices: DecisionEnum::CHOICES,
            message: 'decision.error : ' .'{{ value }}'
        )
    ])]
    private DecisionEnum $avis;
    
    #[ORM\Column(type: 'string', enumType: DecisionEnum::class)]
    #[Assert\Choice(callback: [DecisionEnum::class, 'getAllDecisions'], message: 'reaction.moderation.error' .'{{ value }}')]
    #[Assert\All([
        new Assert\Choice(
            choices: DecisionEnum::CHOICES,
            message: 'decision.error : ' .'{{ value }}'
        )
    ])]
    private DecisionEnum $moderation;

    #[ORM\Column(type: 'string', length: 45, nullable: false)]
    #[Assert\Ip(message: 'reaction.ip_address.error')]
    private string $ipAddress;

    // Relationships ManyToOne
    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'reactions', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private Post $post;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reactions', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'user_uuid', referencedColumnName: 'uuid', nullable: false)]
    private User $user;

    // Constructor
    public function __construct()
    {
        $this->avis = DecisionEnum::getDefaultDecision();
        $this->moderation = DecisionEnum::getDefaultDecision();
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

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): static
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

    // Autres méthodes
    public function isAvis(DecisionEnum $avis): bool
    {
        return $this->avis === $avis;
    }
}
