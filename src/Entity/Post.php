<?php

namespace App\Entity;

use App\Entity\Tag;
use App\Trait\DateTrait;
use App\Trait\SlugTrait;
use App\Trait\CommonMethodsEntityTrait;
use App\enum\EtatEnum;

use App\Entity\Thumbnail;
use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity('title', message: 'category.titre.already_use')]
class Post
{
    // Traits
    use DateTrait;
    use SlugTrait;
    use CommonMethodsEntityTrait;

    // Propriétés
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type:'string', length: 2024)]
    #[Assert\Length(
        min: 3,
        max: 512,
        message: "post.content.length.error {{ min }} et {{ max }}",
    )]
    private string $content;
    
    private ?Thumbnail $thumbnail = null; 

    #[ORM\Column(type:'string', enumType: EtatEnum::class)]
    #[Assert\NotBlank()]
    private EtatEnum $etat;

    // Relationships ManyToMany
    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'posts')]
    private Collection $categories;

    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'posts')]
    private Collection $tags;

    // Relationships ManyToOne
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(name: 'user_uuid', referencedColumnName: 'uuid', nullable: false)]
    private ?User $user = null;

    // Relationships OneToMany
    #[ORM\OneToMany(targetEntity: Reaction::class, mappedBy: 'post', cascade: ['persist', 'remove'])]
    private ?Collection $reactions;

    // Constructeur
    public function __construct(){
        $this->categories = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->reactions = new ArrayCollection();
        $this->etat = EtatEnum::getDefaultEtat();
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
        if (!$this->reactions->contains($reaction)) {
            $this->reactions->add($reaction);
            if ($reaction->getPost() !== $this) {
                $reaction->setPost($this);
            }
        } else {
            throw new \LogicException('categorie.post.already_associated');
        }
    
        return $this;
    }

    public function removeReaction(Reaction $reaction): static
    {
        if ($this->reactions->removeElement($reaction)) {
            if ($reaction->getPost() === $this) {
                $reaction->setPost(null);
            }
        } else {
            throw new \LogicException('categorie.post.not_associated');
        }
    
        return $this;
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

    public function getEtat():EtatEnum
    {
        return $this->etat;
    }

    public function setEtat(EtatEnum $etat):static
    {
        $this->etat = $etat;

        return $this;
    }

    // Autres méthodes
    public function isEtat(EtatEnum $etat): bool
    {
        return $this->etat === $etat;
    }
}
