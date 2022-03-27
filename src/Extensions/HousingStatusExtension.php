<?php


namespace App\Extensions;


use App\Service\HousingStatusResolver;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class HousingStatusExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('housingStatusLabel', [$this, 'getLabel']),
            new TwigFilter('housingStatusColor', [$this, 'getColor']),
        ];
    }

    public function getLabel(float $statusId): string
    {
        return HousingStatusResolver::getHousingStatusLabel($statusId);
    }

    public function getColor(float $statusId): string
    {
        return HousingStatusResolver::getHousingStatusColor($statusId);
    }
}
