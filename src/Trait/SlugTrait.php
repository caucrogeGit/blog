<?php
namespace App\Trait;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

trait SlugTrait
{
    // Properties
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[UniqueEntity(
        'label',
        message: 'Ce label est déjà utilisé.'
    )]
    #[Assert\NotBlank(message: 'Le label ne peut pas être vide.')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Le label doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le label ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZÀ-ÖØ-öø-ÿ\s]+$/',
        message: 'Le label ne peut contenir que des lettres et des espaces.'
    )]
    private string $label = "";

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank(message: 'Le slug ne peut pas être vide.')]
    #[Assert\Regex(
        pattern: '/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
        message: 'Le slug ne doit contenir que des lettres minuscules, des chiffres et des tirets.'
    )]
    private string $slug;

    // Méthodes evenementielles
    #[ORM\PrePersist]
    public function slugPrePersist(): void
    {
        $this->validateLabel();
        $this->generateSlug($this->label);
    }

    #[ORM\PreUpdate]
    public function slugPreUpdate(): void
    {
        $this->generateSlug($this->label);
    }

    // Méthodes
    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        if ($this->label !== $label) {
            $this->label = $label;
            $this->validateLabel();
            $this->generateSlug($label);
        }

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    private function generateSlug(string $input): void
    {
        $this->slug = (new Slugify())->slugify($input);
    }

    private function validateLabel(): void
    {
        if (empty($this->label)) {
            throw new \InvalidArgumentException('Le label ne peut pas être vide.');
        }
    }
}
