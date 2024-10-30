<?php

namespace App\enum;

enum DecisionEnum: string
{
    case AUCUNE = 'AUCUNE';
    case APPROUVE = 'APPROUVE';
    case REJETE = 'REJETE';
}
