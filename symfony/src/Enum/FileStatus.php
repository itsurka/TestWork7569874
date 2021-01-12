<?php

declare(strict_types=1);

namespace App\Enum;

class FileStatus extends BaseEnum
{
    public const PARTIAL = 0;
    public const UPLOADED = 1;
    public const LINKED = 2;
    public const PUBLISHED = 3;
    public const DELETED = 9;

    public static function getList(): array
    {
        return [
            self::PARTIAL => 'Partial',
            self::LINKED => 'New',
            self::PUBLISHED => 'Published',
            self::DELETED => 'Deleted',
        ];
    }
}