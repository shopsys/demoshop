<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Customer;

use Shopsys\FrameworkBundle\Model\Customer\CurrentCustomer as BaseCurrentCustomer;

/**
 * @method \Shopsys\ShopBundle\Model\Customer\User|null findCurrentUser()
 */
class CurrentCustomer extends BaseCurrentCustomer
{
    /**
     * @return int|string
     */
    public function getDiscountCoeficient()
    {
        $user = $this->findCurrentUser();
        if ($user === null) {
            return 1;
        } else {
            return json_encode((100 - $user->getDiscount()) / 100);
        }
    }
}
