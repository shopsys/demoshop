<?php

declare(strict_types=1);

namespace App\Model\Customer\User;

use Shopsys\FrameworkBundle\Model\Customer\User\CurrentCustomerUser as BaseCurrentCustomerUser;

/**
 * @method \App\Model\Customer\User\CustomerUser|null findCurrentCustomerUser()
 */
class CurrentCustomerUser extends BaseCurrentCustomerUser
{
    /**
     * @return int|string
     */
    public function getDiscountCoeficient()
    {
        $customerUser = $this->findCurrentCustomerUser();
        if ($customerUser === null) {
            return 1;
        } else {
            return json_encode((100 - $customerUser->getDiscount()) / 100);
        }
    }
}
