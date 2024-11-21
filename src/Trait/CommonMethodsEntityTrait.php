<?php
namespace App\Trait;

use ReflectionClass;
use Doctrine\Common\Collections\ArrayCollection;

trait CommonMethodsEntityTrait
{
    public function getType(): string
    {
        return (new ReflectionClass($this))->getShortName();
    }

    public function __toString(): string
    {
        if (property_exists($this, 'name') && isset($this->name)) {
            return $this->name;
        }

        return 'Entity-' . ($this->id ?? 'unknown');
    }

    public function toArray(): array
    {
        $vars = get_object_vars($this);

        // Liste des propriétés sensibles ou inutiles à exclure
        $excludedProperties = ['password', 'privateKey'];
    
        foreach ($excludedProperties as $property) {
            // Vérifie si la propriété existe avant de tenter de la supprimer
            if (array_key_exists($property, $vars)) {
                unset($vars[$property]);
            }
        }
    
        return $vars;
    }

    public function initializeCollection(): ArrayCollection
    {
        return new ArrayCollection();
    }

    public function equals(self $entity): bool
    {
        return isset($this->id, $entity->id) && $this->id === $entity->id;
    }
}
