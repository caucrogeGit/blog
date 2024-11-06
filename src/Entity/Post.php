<?php

namespace App\Entity;

use App\Entity\Tag;
use App\enum\EtatEnum;
use DateTimeImmutable;
use App\Entity\Thumbnail;
use App\enum\DecisionEnum;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PostRepository;
use App\Repository\ReactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity('title', message: 'Ce titre est déjà utilisé.')]
class Post
{
    // Propriétés
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: 'Le titre doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'Le titre ne peut pas dépasser {{ limit }} caractères.'
    )]
    private string $title;  
    
    #[ORM\Column(type:'string', length:255, unique:true)]
    #[Assert\NotBlank()]
    private string $slug;  

    #[Assert\Length(
        min: 20,
        max: 2000,
        minMessage: 'Le contenu doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'Le contenu ne peut pas dépasser {{ limit }} caractères.'
    )]
    private string $content;
    
    private ?Thumbnail $thumbnail = null; 

    #[ORM\Column(type:'string', enumType: EtatEnum::class, options: ['default' => EtatEnum::BROUILLON])]
    #[Assert\NotBlank()]
    private EtatEnum $state;

    #[ORM\Column(type:'datetime_immutable', length:255)]
    #[Assert\NotNull()]
    private DateTimeImmutable $updatedAt; 

    #[ORM\Column(type:'datetime_immutable', length:255)]
    #[Assert\NotNull()]
    private DateTimeImmutable $createdAt;  

    // Relationships
    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'posts')]
    private ?Collection $categories = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'posts')]
    private ?Collection $tags = null;

    #[ORM\OneToMany(targetEntity: Reaction::class, mappedBy: 'post', cascade: ['persist', 'remove'])]
    private ?Collection $reactions = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(name: 'user_uuid', referencedColumnName: 'uuid', nullable: false)]
    private ?User $user = null;

    // Constructeur
    public function __construct(){
        $this->updatedAt = new DateTimeImmutable();
        $this->createdAt = new DateTimeImmutable();
        $this->categories = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->reactions = new ArrayCollection();
        $this->state = EtatEnum::BROUILLON;
    }

    // Méthodes evenementielles
    #[ORM\PrePersist]
    public function prePersist()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->slug = (new Slugify())->slugify($this->title);
    }

    #[ORM\PreUpdate]
    public function preUpdate()
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    /* Méthodes relationnelles */

    // (Posts->Categories) ManyToOne
    public function getCategories():Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addPost($this);
        }
        return $this;
    }

    public function removeCategory(Category $category): static
    {
        if ($this->categories->removeElement($category)) {
            $category->removePost($this);
        }

        return $this;
    }    

    // (Tags<->Posts) ManyToMany
    public function getTags(): ?Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addPost($this);
        }
        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removePost($this);
        }

        return $this;
    }    

    // (Post->Reactions) ManyToOne
    public function getReactions(): ?Collection
    {
        return $this->reactions;
    }

    public function addReaction(Reaction $reaction): static
    {
        if(!$this->reactions->contains($reaction)) {
            $this->reactions[] = $reaction;
            $reaction->setPost($this);
        }

        return $this;
    }

    public function removeReaction(Reaction $reaction, EntityManagerInterface $entityManager): static
    {
        if ($this->reactions->contains($reaction)) {
            $this->reactions->removeElement($reaction);
            $entityManager->remove($reaction); // Supprime l'entité Reaction de la base de données
            $entityManager->flush(); // Applique la suppression en base de données
        }

        return $this;
    }

    public function isReactionAucune(Reaction $reaction):bool
    {
        return $reaction->getAvis() ==  DecisionEnum::AUCUNE;
    }

    public function isReactionApprouve(Reaction $reaction):bool
    {
        return $reaction->getAvis() ==  DecisionEnum::APPROUVE;
    }

    public function isReactionRejete(Reaction $reaction):bool
    {
        return $reaction->getAvis() ==  DecisionEnum::REJETE;
    }

    // (Posts->User) ManyToOne
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    // Gettters et Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle():string
    {
        return $this->title;
    }

    public function setTitle(string $title):static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug():string
    {
        return $this->slug;
    }

    public function setSlug($slug):static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent():string
    {
        return $this->content;
    }

    public function setContent(string $content):static
    {
        $this->content = $content;

        return $this;
    }

    public function getThumbnail() : ?Thumbnail
    {
        return $this->thumbnail;
    }

    public function setThumbnail($thumbnail) : static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }    

    public function getState():EtatEnum
    {
        return $this->state;
    }

    public function setState(EtatEnum $state):static
    {
        $this->state = $state;

        return $this;
    }

    public function getUpdatedAt():DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt):static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedAt():DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt):static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    // Autres méthodes
    public function getType(): string
    {
        // Retourne le nom de la classe sans le namespace
        return (new \ReflectionClass($this))->getShortName();
    }

    public function __toString()
    {
        return $this->title;
    }
}
