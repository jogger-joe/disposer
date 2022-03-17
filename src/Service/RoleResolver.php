<?php

namespace App\Service;

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
        'ROLE_USER' => 'Read-Only Benutzer',
        'ROLE_HELPER' => 'Zugeordnete Wohnungen pflegen',
        'ROLE_GUEST' => 'registrierter User',
    ];

    public static function getRoleLabel(int $role): string
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
}
