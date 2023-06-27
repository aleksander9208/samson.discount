<?php

declare(strict_types=1);

namespace Samson\Discount\Entity;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\ORM\Fields;
use Bitrix\Main\SystemException;
use Bitrix\Main\UserTable;

/**
 * Таблица хранения скидок
 */
class DiscountUser extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'discount_user';
    }

    /**
     * @param Query $query
     * @return Query
     */
    public static function withDefaultFields(Query $query): Query
    {
        return $query->setSelect(static::getDefaultFieldsSelection());
    }

    /**
     * @return string[]
     */
    public static function getDefaultFieldsSelection(): array
    {
        return ['ID', 'USER_ID', 'COUPON', 'DISCOUNT'];
    }

    /**
     * @return array
     * @throws ArgumentException
     * @throws SystemException
     */
    public static function getMap(): array
    {
        return [
            (new Fields\IntegerField('ID'))
                ->configurePrimary()
                ->configureAutocomplete(),
            (new Fields\IntegerField('USER_ID'))
                ->configureRequired(),
            (new Fields\StringField('COUPON'))
                ->configureRequired(),
            (new Fields\IntegerField('DISCOUNT'))
                ->configureRequired(),
            (new Fields\Relations\Reference(
                'USER',
                UserTable::class,
                Join::on('this.USER_ID', 'ref.ID')
            ))->configureJoinType(Join::TYPE_INNER),
        ];
    }
}