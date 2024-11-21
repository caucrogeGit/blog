<?php

namespace App\Trait;

trait BidirectionalRelationTrait
{
    public function addRelation($relation, $relatedEntity, callable $bidirectionalCallback): void
    {
        if (!$relation->contains($relatedEntity)) {
            $relation->add($relatedEntity);
            $bidirectionalCallback($relatedEntity);
        }
    }

    public function removeRelation($relation, $relatedEntity, callable $bidirectionalCallback): void
    {
        if ($relation->removeElement($relatedEntity)) {
            $bidirectionalCallback($relatedEntity);
        }
    }
}

