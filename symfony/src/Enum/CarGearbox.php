<?php


namespace App\Enum;


class CarGearbox extends BaseEnum
{
    const AUTOMATIC = 0;
    const MANUAL = 1;

    public static function getList(): array
    {
        return [
            self::AUTOMATIC => 'Auto',
            self::MANUAL => 'Manual',
//            'Автоматическая' => 0,
//            'Механическая' => 1,
//            'Вариатор' => 2,
//            'Роботизированная' => 3,
        ];
    }
}
