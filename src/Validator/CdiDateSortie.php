<?php
// src/Validator/Constraints/CdiDateSortie.php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CdiDateSortie extends Constraint
{
    public $message = 'Vous ne pouvez pas mettre de date de sortie pour un CDI.';
    public $isCdi; // Ajout du paramètre isCdi

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
