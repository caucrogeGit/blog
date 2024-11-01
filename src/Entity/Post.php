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
#[UniqueEntity('titre', message: 'Ce titre est déjà utilisé.')]
class Post
{
    // Propriétés
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type:'string', length:255, unique:true)]
    #[Assert\NotBlank()]
    private string $title;   
    
    #[ORM\Column(type:'string', length:255, unique:true)]
    #[Assert\NotBlank()]
    private string $slug;  

    #[ORM\Column(type:'text', length:255, unique:true)]
    #[Assert\NotBlank()]
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
    private Collection $categories;

    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'posts')]
    private Collection $tags;

    #[ORM\OneToMany(targetEntity: Reaction::class, mappedBy: 'post', cascade: ['persist', 'remove'])]
    private Collection $reactions;

    // Constructeur
    public function __construct(){
        $this->updatedAt = new DateTimeImmutable();
        $this->createdAt = new DateTimeImmutable();
        $this->categories = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->reactions = new ArrayCollection();
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

    // Categories/Post ManyToMany
    public function getCategories():Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if(!$this->categories->contains($category)) {
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

    // Tags/Post ManyToMany
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if(!$this->tags->contains($tag)) {
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

    // Reactions/Reaction //OneToMany
    public function getReactions(): Collection
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
        return 'Post';
    }

    public function __toString()
    {
        return $this->title;
    }
}
