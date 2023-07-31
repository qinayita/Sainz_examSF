<?php
// src/Form/DataTransformer/StringToDateTimeTransformer.php

namespace App\Form\DataTransformer;

use DateTime;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class StringToDateTimeTransformer implements DataTransformerInterface
{
    private $format;

    public function __construct(string $format = 'Y-m-d')
    {
        $this->format = $format;
    }

    public function transform($dateTime): ?string
    {
        if ($dateTime === null) {
            return null;
        }

        if (!$dateTime instanceof \DateTimeInterface) {
            throw new \InvalidArgumentException('DateTime instance expected.');
        }

        return $dateTime->format($this->format);
    }

    public function reverseTransform($stringDateTime): ?\DateTimeInterface
    {
        if ($stringDateTime === null || $stringDateTime === '') {
            return null;
        }

        if ($stringDateTime instanceof \DateTimeInterface) {
            return $stringDateTime;
        }

        try {
            $dateTime = DateTime::createFromFormat($this->format, $stringDateTime);
            if (!$dateTime) {
                throw new TransformationFailedException('Invalid date format.');
            }
        } catch (\Exception $e) {
            throw new TransformationFailedException($e->getMessage());
        }

        return $dateTime;
    }
}







