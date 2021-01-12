<?php


namespace App\Enum;


class CarWheelDrive extends BaseEnum
{
    public static function getList(): array
    {
        return [
            'Задний',
            'Передний',
            'Полный',
        ];
    }
}