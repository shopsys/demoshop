<?php

namespace Shopsys\ShopBundle\Model\Order;

use Shopsys\FrameworkBundle\Model\Order\OrderFacade as BaseOrderFacade;

class OrderFacade extends BaseOrderFacade
{
    /**
     * @param \Shopsys\ShopBundle\Model\Customer\User[] $users
     * @return \Shopsys\FrameworkBundle\Model\Order\Order[]
     */
    public function getOrderListByCustomers(array $users)
    {
        return $this->orderRepository->getOrderListByCustomers($users);
    }
}
