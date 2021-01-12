<?php


namespace App\Enum;


abstract class BaseEnum
{
    /**
     * @return array
     */
    abstract public static function getList(): array;
}