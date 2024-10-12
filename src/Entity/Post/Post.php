<?php

namespace App\Entity\Post;

use DateTimeImmutable;
use Cocur\Slugify\Slugify;
use App\Entity\Post\Thumbnail;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\Post\PostRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(
    'titre',
    message: 'Ce titre est déjà utilisé.'
)]
class Post
{
    // Déclarations
    const STATES = ['STATE_DRAFT','STATE_PUBLISHED'];

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

    #[ORM\Column(type:'string', length:255)]
    #[Assert\NotBlank()]
    private string $state = Post::STATES[0];

    #[ORM\Column(type:'datetime_immutable', length:255)]
    #[Assert\NotNull()]
    private DateTimeImmutable $updatedAt; 

    #[ORM\Column(type:'datetime_immutable', length:255)]
    #[Assert\NotNull()]
    private DateTimeImmutable $createdAt;  

    // Relations
    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'posts')]
    private Collection $categories;

    // Constructeur
    public function __construct(){
        $this->updatedAt = new DateTimeImmutable();
        $this->createdAt = new DateTimeImmutable();
        $this->categories = new ArrayCollection();
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

    // Méthodes relationnelles
    public function getCategories():Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if(!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addPost($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removePost($this);
        }

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

    public function setTitle(string $title):self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug():string
    {
        return $this->slug;
    }

    public function setSlug($slug):self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent():string
    {
        return $this->content;
    }

    public function setContent(string $content):self
    {
        $this->content = $content;

        return $this;
    }

    public function getThumbnail() : ?Thumbnail
    {
        return $this->thumbnail;
    }

    public function setThumbnail($thumbnail) : self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }    

    public function getState():string
    {
        return $this->state;
    }

    public function setState(string $state):self
    {
        $this->state = $state;

        return $this;
    }

    public function getUpdatedAt():DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt):self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedAt():DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt):self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    // Méthode magique
    public function __toString()
    {
        return $this->title;
    }

}
