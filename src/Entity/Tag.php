<?php

namespace App\Entity;

use App\Entity\Post;
use App\Trait\BaseEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[HasLifecycleCallbacks]
#[UniqueEntity(
    'slug',
    message: 'Ce slug est déjà utilisé.'
)]
class Tag
{
    use BaseEntityTrait;

    // Constructor
    public function __construct()
    {
        $this->traitConstructor();
        $this->posts = new ArrayCollection();
    }
    
    // Relations
    #[ORM\ManyToMany(targetEntity: Post::class, inversedBy: 'tags')]
    #[ORM\JoinTable(name: 'tags_posts')]
    private ?Collection $posts = null;


    // Méthodes relationnelles
    public function getPosts():Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            if (!$post->getTags()->contains($this)) {
                $post->addTag($this);
            }
        }
        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            if ($post->getTags()->contains($this)) {
                $post->removeTag($this);
            }
        }
        return $this;
    }  
}