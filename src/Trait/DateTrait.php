<?php

namespace App\Trait;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait DateTrait
{
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull(message: 'La date de création doit être définie.')]
    #[Assert\Type(type: DateTimeImmutable::class, message: 'La date de création doit être de type DateTimeImmutable.')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Assert\Type(type: DateTimeImmutable::class, message: 'La date de mise à jour doit être de type DateTimeImmutable.')]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\PrePersist]
    public function timestampablePrePersist(): void
    {
        $this->initializeTimestamps();
    }

    #[ORM\PreUpdate]
    public function timestampablePreUpdate(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    // Getters and Setters
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt ?? $this->createdAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    // Other methods
    private function initializeTimestamps(): void
{
    if (!isset($this->createdAt)) {
        $this->createdAt = new DateTimeImmutable();
    }
    $this->updatedAt = new DateTimeImmutable();
}
}

