<?php

namespace App\Service;

class HousingStatusResolver
{
    const HOUSING_STATUS_MAP = [
        0 => 'benötigt Erfassung',
        1 => 'teilweise belegt',
        2 => 'vollständig belegt',
        3 => 'bezugsbereit'
    ];

    const HOUSING_STATUS_COLOR_MAP = [
        0 => 'bg-housing-status-0',
        1 => 'bg-housing-status-1',
        2 => 'bg-housing-status-2',
        3 => 'bg-housing-status-3'
    ];

    public static function getHousingStatusLabel(int $housingStatus): string
    {
        if (array_key_exists($housingStatus, self::HOUSING_STATUS_MAP)) {
            return self::HOUSING_STATUS_MAP[$housingStatus];
        }
        return 'unbekannt';
    }

    public static function getHousingStatusColor(int $housingStatus): string
    {
        if (array_key_exists($housingStatus, self::HOUSING_STATUS_COLOR_MAP)) {
            return self::HOUSING_STATUS_COLOR_MAP[$housingStatus];
        }
        return 'bg-primary';
    }

    public static function getHousingStatusChoices(): array
    {
        return array_flip(self::HOUSING_STATUS_MAP);
    }
}
