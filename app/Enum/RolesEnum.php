<?php
namespace App\Enum;

enum RolesEnum: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';

    public function label(): string
    {
        return match ($this) {
            RolesEnum::ADMIN => 'Administrator',
            RolesEnum::MANAGER => 'Manager',
        };
    }
}
