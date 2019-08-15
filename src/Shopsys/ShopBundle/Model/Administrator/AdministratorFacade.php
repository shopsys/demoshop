<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Administrator;

use Shopsys\FrameworkBundle\Model\Administrator\Administrator as BaseAdministrator;
use Shopsys\FrameworkBundle\Model\Administrator\AdministratorFacade as BaseAdministratorFacade;

class AdministratorFacade extends BaseAdministratorFacade
{
    /**
     * @param \Shopsys\ShopBundle\Model\Administrator\Administrator $administrator
     */
    protected function checkForDelete(BaseAdministrator $administrator)
    {
        parent::checkForDelete($administrator);

        if ($administrator->isDefaultAdmin()) {
            throw new \Shopsys\ShopBundle\Model\Administrator\Exception\DeletingDefaultAdminException();
        }
    }
}
