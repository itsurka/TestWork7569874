<?php

declare(strict_types=1);

namespace App\Enum;

class FileType extends BaseEnum
{
    public const IMAGE = 0;

    public static function getList(): array
    {
        return [
            self::IMAGE => 'Image',
        ];
    }
}