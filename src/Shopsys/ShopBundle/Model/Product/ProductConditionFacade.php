<?php

namespace Shopsys\ShopBundle\Model\Product;

class ProductConditionFacade
{
    const CONDITION_NEW = 'new';
    const CONDITION_REFURBISHED = 'refurbished';
    const CONDITION_USED = 'used';

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
