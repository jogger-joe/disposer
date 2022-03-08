<?php

namespace App\Service;

class HousingStatusResolver
{
    const HOUSING_STATUS_MAP = [
        0 => 'frei',
        1 => 'teilweise belegt',
        2 => 'vollständig belegt',
        3 => 'bezugsbereit'
    ];

    public static function getHousingStatusLabel(int $housingStatus): string
    {
        if (array_key_exists($housingStatus, self::HOUSING_STATUS_MAP)) {
            return self::HOUSING_STATUS_MAP[$housingStatus];
        }
        return 'unbekannt';
    }

    public static function getHousingStatusChoices(): array
    {
        return array_flip(self::HOUSING_STATUS_MAP);
    }
}
