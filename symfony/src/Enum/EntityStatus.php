<?php


namespace App\Enum;


class EntityStatus extends BaseEnum
{
    const STATUS_NEW = 0;
    const STATUS_MODERATION = 1;
    const STATUS_HIDDEN = 2;
    const STATUS_PUBLISHED = 3;
    const STATUS_DELETED = 9;

    public static function getList(): array
    {
        return [
            self::STATUS_NEW => 'Новый',
            self::STATUS_MODERATION => 'Модерация',
            self::STATUS_HIDDEN => 'Скрыт',
            self::STATUS_PUBLISHED => 'Публикуется',
            self::STATUS_DELETED => 'Удалён',
        ];
    }
}
