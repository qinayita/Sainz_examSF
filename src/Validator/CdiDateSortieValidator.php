<?php
// src/Validator/CdiDateSortieValidator.php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CdiDateSortieValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        // Vérifiez si l'option CDI est choisie
        if ($constraint->isCdi && $value !== null) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}

