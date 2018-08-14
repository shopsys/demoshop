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
        $advertPositions['homepagePrimary'] = t('on homepage above popular categories (full width)');
        $advertPositions['homepageSecondaryLeft'] = t('on homepage above popular categories (half width left)');
        $advertPositions['homepageSecondaryRight'] = t('on homepage above popular categories (half width right)');

        return $advertPositions;
    }
}
