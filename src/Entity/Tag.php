<?php

namespace App\Entity;

use App\Entity\Post;
use App\Trait\CommonMethodsEntityTrait;
use App\Trait\SlugTrait;
use App\Trait\DateTrait;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TagRepository;
use App\Trait\BidirectionalRelationTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[HasLifecycleCallbacks]
#[UniqueEntity(
    'slug',
    message: 'Ce slug est déjà utilisé.'
)]
class Tag
{
    // Trait
    use CommonMethodsEntityTrait;
    use SlugTrait;
    use DateTrait;
    use BidirectionalRelationTrait;
   
    // Properties
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(
        max: 500,
        maxMessage: 'tag.description.length.error {{ limit }}',
    )]
    #[Assert\Type(type: 'string', message: 'tag.description.string.error')]
    #[Assert\NotBlank(message: 'La description ne peut pas être vide.')]
    private string $description;

    // Relations
    #[ORM\ManyToMany(targetEntity: Post::class, inversedBy: 'tags')]
    #[ORM\JoinTable(name: 'tags_posts')]
    private ?Collection $posts;

    // Constructor
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    // Méthodes relationnelles
    public function getPosts():Collection
    {
        return $this->posts;
    }

    /**
     * @param Post $post
     * @return static
     */
    public function addPost(Post $post): static
    {
        $this->addRelation(
            $this->posts,
            $post,
            fn(Post $p) => $p->addTag($this)
        );

        return $this;
    }

    /**
     * @param Post $post
     * @return static
     */
    public function removePost(Post $post): static
    {
        $this->removeRelation(
            $this->posts,
            $post,
            fn(Post $p) => $p->removeTag($this)
        );

        return $this;
    }    

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription() : string
    {
        return $this->description;
    }

    public function setDescription(string $description) : static
    {
        $this->description = $description;

        return $this;
    } 
}