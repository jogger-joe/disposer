<?php

namespace App\Service;

class FurnitureTypeResolver
{
    const FURNITURE_TYPE_MAP = [
        0 => 'Sonstiges',
        1 => 'Küche',
        2 => 'Badezimmer',
        3 => 'Wohnzimmer',
        4 => 'Schlafzimmer',
        5 => 'Zimmer 3',
        6 => 'Zimmer 4',
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
