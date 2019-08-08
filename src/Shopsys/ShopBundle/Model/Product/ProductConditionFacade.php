<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Product;

class ProductConditionFacade
{
    public const CONDITION_NEW = 'new';
    public const CONDITION_REFURBISHED = 'refurbished';
    public const CONDITION_USED = 'used';

    /**
     * @return array
     */
    public function getAll()
    {
        return [
            t('New') => self::CONDITION_NEW,
            t('Refurbished') => self::CONDITION_REFURBISHED,
            t('Used') => self::CONDITION_USED,
        ];
    }
}
