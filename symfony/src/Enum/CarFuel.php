<?php


namespace App\Enum;


class CarFuel extends BaseEnum
{
    public static function getList(): array
    {
        return [
            'Бензин',
            'Дизель',
            'Бензин-Газ (пропан)',
            'Бензин-Газ (метан)',
            'Гибрид',
            'Электро',
        ];
    }
}
