<?php

declare(strict_types=1);

namespace App\Enum;


class UserStatus extends BaseEnum
{
    const WAIT_EMAIL_CONFIRM = 0;
    const EMAIL_CONFIRMED = 1;

    public static function getList(): array
    {
        return [
            self::WAIT_EMAIL_CONFIRM => 'Wait email confirmation',
            self::EMAIL_CONFIRMED => 'Email confirmed',
        ];
    }
}
