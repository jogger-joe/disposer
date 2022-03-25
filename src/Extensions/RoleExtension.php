<?php


namespace App\Extensions;


use App\Service\RoleResolver;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class RoleExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('roleLabel', [$this, 'getLabel']),
        ];
    }

    public function getLabel(string $role): string
    {
        return RoleResolver::getRoleLabel($role);
    }
}
