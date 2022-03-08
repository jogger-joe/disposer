<?php

namespace App\Service;

class FurnitureTypeResolver
{
    const FURNITURE_TYPE_MAP = [
        0 => 'Sonstiges',
        1 => 'Möbel',
        2 => 'Elektrogeräte',
        3 => 'Hygieneartikel'
    ];

    public static function getFurnitureTypeLabel(int $furnitureType): string
    {
        if (array_key_exists($furnitureType, self::FURNITURE_TYPE_MAP)) {
            return self::FURNITURE_TYPE_MAP[$furnitureType];
        }
        return 'unbekannt';
    }

    public static function getFurnitureTypeChoices(): array
    {
        return array_flip(self::FURNITURE_TYPE_MAP);
    }
}
