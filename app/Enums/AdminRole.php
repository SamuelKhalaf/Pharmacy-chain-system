<?php
namespace App\Enums;

enum AdminRole: int
{
    case SuperAdmin = 1;
    case BranchAdmin = 2;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getRoleName(int $roleId): ?string
    {
        return match ($roleId) {
            self::SuperAdmin->value => 'Super Admin',
            self::BranchAdmin->value => 'Branch Admin',
            default => null,
        };
    }
}
