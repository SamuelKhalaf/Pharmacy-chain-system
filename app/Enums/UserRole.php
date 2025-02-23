<?php
namespace App\Enums;

enum UserRole: int
{
    case SuperAdmin = 1;
    case BranchAdmin = 2;
    case Customer = 3;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getRoleName(int $roleId): ?string
    {
        return match ($roleId) {
            self::SuperAdmin->value => 'Super Admin',
            self::BranchAdmin->value => 'Branch Admin',
            self::Customer->value => 'Customer',
            default => null,
        };
    }
}
