<?php

namespace App\Entity;

use App\Entity\Post;
use App\Trait\BaseEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[HasLifecycleCallbacks]
#[UniqueEntity(
    'slug',
    message: 'Ce slug est déjà utilisé.'
)]
class Category
{
    use BaseEntityTrait;

    // Constructor
    public function __construct()
    {
        $this->traitConstructor();
        $this->posts = new ArrayCollection();
    }
    
    // Relations
    #[ORM\ManyToMany(targetEntity: Post::class, inversedBy: 'categories')]
    #[ORM\JoinTable(name: 'categories_posts')]
    private ?Collection $posts = null;

    // Méthodes relationnelles
    public function getPosts(): ?Collection
    {
        return $this->posts;
    }

    /**
     * @param Post $post
     * @return static
     */
    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->addCategory($this);
        }

        return $this;
    }

    /**
     * @param Post $post
     * @return static
     */
    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            $post->removeCategory($this);
        }

        return $this;
    }
}