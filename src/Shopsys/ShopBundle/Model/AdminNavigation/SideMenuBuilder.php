<?php

namespace Shopsys\ShopBundle\Model\AdminNavigation;

use Knp\Menu\ItemInterface;
use Shopsys\FrameworkBundle\Model\AdminNavigation\SideMenuBuilder as BaseSideMenuBuilder;

class SideMenuBuilder extends BaseSideMenuBuilder
{
    protected function createSettingsMenu(): ItemInterface
    {
        $menu = parent::createSettingsMenu();

        $menu->getChild('lists')->removeChild('flags');

        return $menu;
    }
}
