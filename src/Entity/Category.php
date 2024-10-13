<?php

namespace App\Entity;

use DateTimeImmutable;
use App\Entity\Post;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[HasLifecycleCallbacks]
#[UniqueEntity(
    'slug   ',
    message: 'Ce slug est déjà utilisé.'
)]
class Category
{
    // Properties
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank()]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank()]
    private string $slug;

    #[ORM\Column(type: 'text', length: 255, nullable: true)]
    private ?string $description;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull()]
    private DateTimeImmutable $createdAt;

    // Relations
    #[ORM\ManyToMany(targetEntity: Post::class, inversedBy: 'categories')]
    #[ORM\JoinTable(name: 'categories_posts')]
    private Collection $posts;

    // Constructor
    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->posts = new ArrayCollection();
    }   

    // Méthodes evenementielles
    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->slug = (new Slugify())->slugify($this->name);
    }

    // Méthodes relationnelles
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->addCategory($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            $post->removeCategory($this);
        }

        return $this;
    }

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName() : string
    {
        return $this->name;
    }


    public function setName($name) : self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug() : string
    {
        return $this->slug;
    }

    public function setSlug(String $slug) : self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription() : string
    {
        return $this->description;
    }

    public function setDescription(?string $description) : self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt() : DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    // Méthode magique __toString
    public function __toString()
    {
        return $this->name;
    }   
}