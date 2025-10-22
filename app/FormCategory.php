<?php

namespace App;

enum FormCategory: string
{
    case MINOR_EQUIPMENT_SERVICING = 'Minor Equipment & Servicing';
    case GROCERY_FOOD_CLEANING = 'Grocery, Food & Cleaning Supplies';
    case REAGENTS_LAB_CONSUMABLES = 'Reagents & Lab Consumables';
    case STATIONERY_TONERS = 'Stationery & Toners';
    case ALL_OTHER_ITEMS = 'All Other Items';

    /**
     * Get all enum values as an array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all enum cases as an associative array (value => label)
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->value;
        }
        return $options;
    }

    /**
     * Get the label for display
     */
    public function label(): string
    {
        return $this->value;
    }
}
