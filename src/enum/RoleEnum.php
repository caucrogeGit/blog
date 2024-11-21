<?php

namespace App\enum;

enum RoleEnum: string
{
    case ROLE_USER = 'ROLE_USER';
    case ROLE_ADMIN = 'ROLE_ADMIN';
    case ROLE_MANAGER = 'ROLE_MANAGER';
    case ROLE_EDITOR = 'ROLE_EDITOR';

    // Constantes contenant tous les rôles.
    public const DEFAULT = self::ROLE_USER->value;
    public const CHOICES = [
        self::ROLE_USER->value,
        self::ROLE_ADMIN->value,
        self::ROLE_MANAGER->value,
        self::ROLE_EDITOR->value,
    ];

    /**
     * Récupère tous les rôles disponibles à partir des cas de l'enum.
     */
    public static function getAllRoles(): array
    {
        return self::CHOICES;
    }

    /**
     * Récupère le rôle par défaut.
     */
    public static function getDefaultRole(): array
    {
        return [self::ROLE_USER->value]; 
    }

    /**
     * Récupère la valeur d'un cas de l'enum.
     */
    private static function getValue(self $role): string
    {
        return $role->value;
    }
}
