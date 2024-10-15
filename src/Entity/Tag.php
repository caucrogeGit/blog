<?php

namespace App\Entity;

use App\Entity\Post;
use App\Trait\CategoryTagEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[HasLifecycleCallbacks]
#[UniqueEntity(
    'slug',
    message: 'Ce slug est déjà utilisé.'
)]

class Tag
{
    use CategoryTagEntityTrait;
    
    // Relations
    #[ORM\ManyToMany(targetEntity: Post::class, inversedBy: 'tags')]
    #[ORM\JoinTable(name: 'tags_posts')]
    private Collection $posts;


    // Méthodes relationnelles
    public function getPosts():Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if(!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->addTag($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            $post->removeTag($this);
        }

        return $this;
    }  
    
    public function getType(): string
    {
        return 'Tag';
    }  
}