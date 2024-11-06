<?php

namespace App\Trait;

use DateTimeImmutable;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait BaseEntityTrait
{
    // Properties
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank(message: 'Le nom ne peut pas être vide.')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Le nom doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZÀ-ÖØ-öø-ÿ\s]+$/',
        message: 'Le nom ne peut contenir que des lettres et des espaces.'
    )]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank(message: 'Le slug ne peut pas être vide.')]
    #[Assert\Regex(
        pattern: '/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
        message: 'Le slug ne doit contenir que des lettres minuscules, des chiffres et des tirets.'
    )]
    private string $slug;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(
        max: 500,
        maxMessage: 'La description ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Type(type: 'string', message: 'La description doit être une chaîne de caractères.')]
    private ?string $description;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull(message: 'La date de création doit être définie.')]
    #[Assert\Type(type: DateTimeImmutable::class, message: 'La date de création doit être de type DateTimeImmutable.')]
    private DateTimeImmutable $createdAt; 

    // Constructor
    private function traitConstructor()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    // Méthodes evenementielles
    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->slug = (new Slugify())->slugify($this->name);
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

    public function getType(): string
    {
        // Retourne le nom de la classe sans le namespace
        return (new \ReflectionClass($this))->getShortName();
    }

    // Méthode magique __toString
    public function __toString()
    {
        return $this->name;
    }   
}
