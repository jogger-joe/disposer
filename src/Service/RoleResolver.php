<?php

namespace App\Service;

use Symfony\Component\Security\Core\User\UserInterface;

class RoleResolver
{
    const ROLE_MAP = [
        'ROLE_SUPER_ADMIN' => 'Superadministrator',
        'ROLE_ADMIN' => 'Administrator',
        'ROLE_ADMIN_USER' => 'User verwalten',
        'ROLE_EDIT_USER' => 'User editieren',
        'ROLE_CREATE_USER' => 'User anlegen',
        'ROLE_ADMIN_FURNITURE' => 'Einrichtungen verwalten',
        'ROLE_ADMIN_SERVICE' => 'Dienstleistungen verwalten',
        'ROLE_ADMIN_SUPPORTER' => 'Helfer verwalten',
        'ROLE_ADMIN_HOUSING' => 'Unterkünfte verwalten',
        'ROLE_ADMIN_TAG' => 'Tags verwalten',
        'ROLE_USER' => 'Read-Only Benutzer',
        'ROLE_SUPPORTER' => 'Zugeordnete Wohnungen pflegen',
        'ROLE_GUEST' => 'registrierter User',
    ];

    public static function getRoleLabel(string $role): string
    {
        if (array_key_exists($role, self::ROLE_MAP)) {
            return self::ROLE_MAP[$role];
        }
        return 'unbekannt';
    }

    public static function getRoleChoices(): array
    {
        return array_flip(self::ROLE_MAP);
    }

    public static function buildRoleChoices($roles): array
    {
        $roleChoices = [];
        foreach ($roles as $role) {
            $roleChoices[self::getRoleLabel($role)] = $role;
        }
        return $roleChoices;
    }

    public static function getAvailableRoleChoices($roleHierarchy, ?UserInterface $user): array
    {
        $availableRoles = [];
        $userRoles = $user->getRoles();
        foreach ($userRoles as $userRole) {
            $availableRoles = array_merge($availableRoles, self::collectRoles($userRole, $roleHierarchy));
        }
        // filter duplicates
        $availableRoles = array_unique(array_values($availableRoles));
        return self::buildRoleChoices($availableRoles);
    }

    public static function collectRoles($role, array $roleHierarchy): array
    {
        $roles = [];
        $roles[] = $role;
        foreach ($roleHierarchy[$role] as $hierarchyRole) {
            $roles[] = $hierarchyRole;
            $roles = array_merge($roles, self::collectRoles($hierarchyRole, $roleHierarchy));
        }
        return $roles;
    }
}
