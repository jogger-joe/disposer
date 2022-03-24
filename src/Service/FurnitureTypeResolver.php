<?php

namespace App\Service;

class FurnitureTypeResolver
{
    const FURNITURE_TYPE_MAP = [
        0 => 'Sonstiges Einrichtungsgegenstände',
        1 => 'Kücheeinrichtung',
        2 => 'Badezimmereinrichtung',
        3 => 'Wohnzimmereinrichtung',
        4 => 'Schlafzimmereinrichtung',
        5 => 'Gäste-/Kinderzimmereinrichtung 1',
        6 => 'Gäste-/Kinderzimmereinrichtung 2',
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

    public static function getFurnitureTypeColor(float $statusId)
    {
        return "bg-$statusId";
    }
}
