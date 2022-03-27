<?php


namespace App\Extensions;


use App\Service\FurnitureTypeResolver;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FurnitureTypeExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('furnitureTypeLabel', [$this, 'getLabel']),
            new TwigFilter('furnitureTypeColor', [$this, 'getColor']),
        ];
    }

    public function getLabel(float $statusId): string
    {
        return FurnitureTypeResolver::getFurnitureTypeLabel($statusId);
    }

    public function getColor(float $statusId): string
    {
        return FurnitureTypeResolver::getFurnitureTypeColor($statusId);
    }
}
