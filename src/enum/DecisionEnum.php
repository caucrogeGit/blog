<?php

namespace App\enum;

enum DecisionEnum: string
{
    case APPROUVE = 'APPROUVE';
    case REJETE = 'REJETE';
    case AUCUNE = 'AUCUNE';

    
    // Constantes contenant tous les rôles.
    public const DEFAULT = self::AUCUNE->value;
    public const CHOICES = [
        self::APPROUVE->value,
        self::REJETE->value,
        self::AUCUNE->value
    ];


    /**
     * Récupère tous les rôles disponibles à partir des cas de l'enum.
     */
    public static function getAllDecisions(): array
    {
        return self::CHOICES;
    }

    /**
     * Récupère la décision par défaut.
     */
    public static function getDefaultDecision(): static
    {
        return self::AUCUNE; 
    }

    /**
     * Récupère la valeur d'un cas de l'enum.
     */
    private static function getValue(self $decision): string
    {
        return $decision->value;
    }
}