<?php

namespace App\enum;

enum EtatEnum: string
{
    case BROUILLON = 'BROUILLON';
    case PUBLIE = 'PUBLIE';

    // Constantes contenant tous les rôles.
    public const DEFAULT = self::BROUILLON->value;
    public const CHOICES = [
        self::BROUILLON->value,
        self::PUBLIE->value,
    ];

    /**
     * Récupère tous les rôles disponibles à partir des cas de l'enum.
     */
    public static function getAllEtats(): array
    {
        return self::CHOICES;
    }    

    /**
     * Récupère l'état par défaut.
     */
    public static function getDefaultEtat(): static
    {
        return self::BROUILLON; 
    }

    /**
     * Récupère la valeur d'un cas de l'enum.
     */
    private static function getValue(self $etat): string
    {
        return $etat->value;
    }
}