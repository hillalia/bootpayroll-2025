<?php

namespace App\Enums;

/**
 * Enum Position
 *
 * Represents employee positions within the organization.
 */
enum Position: string
{
    case MANAGER = 'manager';
    case LEAD = 'lead';
    case STAFF = 'staff';

    /**
     * Get the human-readable label for the enum case.
     */
    public function label(): string
    {
        return match ($this) {
            self::MANAGER => 'Manager',
            self::LEAD => 'Lead',
            self::STAFF => 'Staff',
        };
    }

    /**
     * Get all enum options as a value => label array.
     * Useful for dropdowns in Filament/Laravel forms.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return array_column(
            array_map(
                fn(self $case) => [$case->value, $case->label()],
                self::cases()
            ),
            1,
            0
        );
    }
}
