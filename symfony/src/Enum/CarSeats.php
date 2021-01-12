<?php


namespace App\Enum;


class CarSeats extends BaseEnum
{
    public static function getList(): array
    {
        $list = [];
        for ($i = 1; $i <= 8; $i++) {
            $list[$i] = $i;
        }
        return $list;
    }
}