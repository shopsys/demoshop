<?php

namespace Shopsys\ShopBundle\Model\Customer;

use Shopsys\FrameworkBundle\Model\Customer\CurrentCustomer as BaseCurrentCustomer;

class CurrentCustomer extends BaseCurrentCustomer
{
    /**
     * @return float
     */
    public function getDiscountCoeficient()
    {
        $user = $this->findCurrentUser();
        if ($user === null) {
            return 1;
        } else {
            return (100 - $user->getDiscount()) / 100;
        }
    }
}
