<?php


namespace App\Enum;


class CarBody extends BaseEnum
{
    public static function getList(): array
    {
        return [
            'Седан',
            'Хэтчбек',
            'Универсал',
            'Кроссовер',
            'Внедоржник',
            'Компактвэн',
            'Минивэн',
            'Купе',
            'Кабриолет',
            'Лимузин',
            'Пикап',
            'Родстер',
            'Спорт купе',
        ];
    }
}