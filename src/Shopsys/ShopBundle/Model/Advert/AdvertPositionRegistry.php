<?php

namespace Shopsys\ShopBundle\Model\Advert;

use Shopsys\FrameworkBundle\Model\Advert\AdvertPositionRegistry as BaseAdvertPositionRegistry;

class AdvertPositionRegistry extends BaseAdvertPositionRegistry
{
    /**
     * @return string[]
     */
    public function getAllLabelsIndexedByNames(): array
    {
        $advertPositions = parent::getAllLabelsIndexedByNames();
        $advertPositions['homepagePrimary'] = t('on homepage above popular categories (Primary)');
        $advertPositions['homepageSecondaryFirst'] = t('on homepage above popular categories (Secondary First)');
        $advertPositions['homepageSecondarySecond'] = t('on homepage above popular categories (Secondary Second)');

        return $advertPositions;
    }
}
